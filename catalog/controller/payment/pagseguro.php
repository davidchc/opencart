<?php
/**
 * ControllerPaymentPagseguro
 *
 * Classe que controla o comportamento do módulo no lado do cliente
 * Responsável por capturar os dados do carrinho de compras, exibir o formulário
 * na página de checkout e enviar estes dados para o getway de pagamento.
 * @package pagseguro_opencart
 * <code>
 * \@include pgs/pgs.php
 * \@include pgs/tratadados.php
 * </code>
 * @author ldmotta - ldmotta@gmail.com
 * @link motanet.com.br
 */
require_once ('pgs/pgs.php');
require_once ('pgs/tratadados.php');

class ControllerPaymentPagseguro extends Controller
{
    /**
     * index - Incluido à ultima tela do processo de compra
     * 
     * @access protected
     * @return void
     */
	protected function index() {
		$this->language->load('payment/pagseguro');
		$this->load->model('payment/pagseguro');
		$this->load->model('total/reward');
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back']    = $this->language->get('button_back');
		
		$this->session->data['token'] = isset($this->session->data['token']) ? $this->session->data['token'] : '';
		$this->data['continue']       = HTTPS_SERVER . 'index.php?route=checkout/success&token=' . $this->session->data['token'];
		$this->data['back']           = HTTPS_SERVER . 'index.php?route=checkout/payment&token=' . $this->session->data['token'];

        /* Aplicando a biblioteca PagSeguro */
        list($order, $cart) = $this->model_payment_pagseguro->getCart();
        $produtos = array();
        foreach ($cart as $item) {
            $produtos[] = array(
                'id'         => $item['product_id'],
                'descricao'  => $item['name'],
                'quantidade' => $item['quantity'],
                'valor'      => $item['total'] / $item['quantity'],
                'frete'      => 0,
            );
        }
        list($ddd, $telefone) = trataTelefone($order['telephone']);
        
        $street = explode(',',$order['shipping_address_1']);            
        $street = array_slice(array_merge($street, array("","","")),0,3); 
        list($endereco, $numero, $complemento) = $street;      
        
        $cliente = array (
          'nome'   => $order['payment_firstname'].' '.$order['payment_lastname'],
          'cep'    => $order['payment_postcode'],
          'end'    => $endereco,
          'num'    => $numero,
          'compl'  => $complemento,
          'cidade' => $order['payment_city'],
          'uf'     => $order['payment_zone'],
          'pais'   => $order['payment_country'],
          'ddd'    => $ddd,
          'tel'    => $telefone,
          'email'  => $order['email'],
        );

		/*Pega cupom e calcula o desconto*/
		if(isset($this->session->data['coupon']) && $this->session->data['coupon']){	
			$coupon =  $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);
			$extras = 0;
			if(count($coupon['product']) > 0){
				
				foreach ($this->cart->getProducts() as $products) {
				
					if(in_array($products['product_id'],$coupon['product'])){
					
						if($coupon['type'] == 'F'){
							$extra = $coupon['discount'] > $products['total'] ? $products['total'] : $coupon['discount'];
							
						}elseif($coupon['type'] == 'P'){		
						
							$extra = ($products['total'] * $coupon['discount']) / 100;
							$extra = $extra > $products['total'] ? $products['total'] : $extra;
							
						}
						$extras += $extra;
					}
				}
			}else{
			
				if($coupon['type'] == 'F'){
					$extras = $coupon['discount'] > $this->cart->getTotal() ? $this->cart->getTotal() : $coupon['discount'];
					
				}elseif($coupon['type'] == 'P'){			
					$extras = ($this->cart->getTotal() * $coupon['discount']) / 100;
					$extras = $extras > $this->cart->getTotal() ? $this->cart->getTotal() : $extras;
				}
			}
			$extras = $this->cart->getTotal() - $extras == 0 ? $extras - 0.01 : $extras;
			$extras = sprintf("%01.2f", $extras);
			$extras = '-' . str_replace('.','',$extras);
			
		}else{
			$extras = 0;
		}
		
		if(isset($this->session->data['reward']) && $this->session->data['reward'] > 0){
			$total_data = array();
			$total = 0;
			$taxes = $this->cart->getTaxes(); 
			$results = $this->model_total_reward->getTotal($total_data, $total, $taxes);		
			$reward =  ($total_data[0]['value'] * 100)*(-1);
		}else{
			$reward = 0;	
		}	
		
		
        $pgs = new Pgs(array(
            'email_cobranca' => $this->config->get("pagseguro_mail"), 
            'extras'          => $extras-$reward,
            'ref_transacao'  => $order['order_id'],
            'encoding'=>'utf-8',
        ));
        $pgs->cliente($cliente);

		if (isset($this->session->data['shipping_method'])) {
		    $produtos[0]['frete'] = str_replace('.','',sprintf("%01.2f", $this->session->data['shipping_method']['cost']));
		}
		
        $pgs->adicionar($produtos);
        $this->form = $pgs->mostra(array('print'=>false));

		$this->id           = 'payment';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pagseguro.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/pagseguro.tpl';
		} else {
			$this->template = 'default/template/payment/pagseguro.tpl';
		}		
		$this->render(); 
	}

    /**
     * confirm - é executado quando se clica no botão de confirm
     * 
     * @access public
     * @return void
     */
	public function confirm() {
		$this->load->model('checkout/order');
		
		$this->model_checkout_order->confirm( $this->session->data['order_id'], $this->config->get('pagseguro_order_status_id') );
	}
}
