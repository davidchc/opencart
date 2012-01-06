<?php
/**
 * ControllerPaymentPagseguro
 *
 * Classe que controla o comportamento do módulo no lado do cliente
 * Responsável por capturar os dados do carrinho de compras, exibir o formulário
 * na página de checkout e enviar estes dados para o getway de pagamento.
 * @package pagseguro_opencart
 * <code>
 * \@include PagSeguroLibrary/PagSeguroLibrary.php
 * </code>
 * @author ldmotta - ldmotta@gmail.com
 * @link motanet.com.br
 */

require_once (DIR_APPLICATION . "controller/payment/PagSeguroLibrary/PagSeguroLibrary.php");


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
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back']    = $this->language->get('button_back');
		
		$this->session->data['token'] = isset($this->session->data['token']) ? $this->session->data['token'] : '';
		
		$this->data['continue']       = HTTPS_SERVER . 'index.php?route=checkout/success&token=' . $this->session->data['token'];
		$this->data['back']           = HTTPS_SERVER . 'index.php?route=checkout/payment&token=' . $this->session->data['token'];

        list($order, $cart) = $this->model_payment_pagseguro->getCart();
        list($prefix, $phone) = $this->splitPhone($order['telephone']);

        $frete = 0;
		
		if (count($this->session->data['shipping_method'])) {
		
		    $frete = sprintf("%01.2f", $this->session->data['shipping_method']['cost']);
		
		}

        /* Aplicando a biblioteca PagSeguro */
        $paymentRequest = new PaymentRequest();
        
        $paymentRequest->setCurrency("BRL");

        foreach ($cart as $item) {
        
            $paymentRequest->addItem(
                $item['product_id'], 
                $item['model'], 
                $item['quantity'], 
                $item['total'] / $item['quantity'],
                $item['weight'],
                $frete
            );
        
        }

        $paymentRequest->setReference($order['order_id']);
        
        $paymentRequest->setSender(
        
            $order['payment_firstname'].' '.$order['payment_lastname'], 
        
            $order['email'], $prefix, $phone
        
        );

        $street = explode(',', $order['shipping_address_1']);            
        
        $street = array_slice(array_merge($street, array("", "", "")), 0, 3); 
        
        list($address, $number, $complement) = $street;      

        $freight_type = $this->config->get("pagseguro_frete");
        
        if ($freight_type=='1'){
		    
		    $FREIGHT_CODE = ShippingType::getCodeByType('PAC');
		    
		}elseif($freight_type=='2'){
		
		    $FREIGHT_CODE = ShippingType::getCodeByType('SEDEX');
		    
		}

		if( $freight_type > 0 ){ 
		
		    $paymentRequest->setShippingType($FREIGHT_CODE);
		    
		    $paymentRequest->setShippingAddress(
		        $order['shipping_postcode'], 
		        $address, $number, $complement,
		        $order['shipping_address_2'],
		        $order['shipping_city'],
		        $order['shipping_zone_code'],
		        $order['shipping_iso_code_3']
		    );
		    
		}

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
				
		$paymentRequest->setExtraAmount($extras);
		
		$paymentRequest->setRedirectUrl("http://homologacao.visie.com.br/bibliotecas/pagseguro/opencart1505/notification.php");

        // Pegando as configurações definidas no admin do módulo
		$email = $this->config->get("pagseguro_mail");
		$token = $this->config->get("pagseguro_token");		

		/**
		 * Você pode utilizar o método getData para capturar as credenciais
		 * do usuário (email e token)
         * $email = PagSeguroConfig::getData('credentials', 'email');
         * $token = PagSeguroConfig::getData('credentials', 'token');
		 */
		try {
    		/**
             * #### Crendenciais ##### 
             * Se desejar, utilize as credenciais pré-definidas no arquivo de configurações
             * $credentials = PagSeguroConfig::getAccountCredentials();
			 */		
		    $credentials = new AccountCredentials($email, $token);
		    
			if ($url = $paymentRequest->register($credentials)) {
				// Payment URL
				$_form = array();
				$_form[] = sprintf('<form action="%s" target="pagseguro" name="pagseguro" id="pagseguro" method="post">', $url);
				$_form[] = '<td align="right"><a class="button" onclick="checkout()"><span>Pague com o PagSeguro</span></a></td>';
				$_form[] = '</form>';
                $this->form = implode("\n", $_form);
			}
		} catch (PagSeguroServiceException $e) {
			die($e->getMessage());
		}
        


		$this->id = 'payment';
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
		$this->language->load('payment/pagseguro');
		$this->load->model('checkout/order');

		$comment  = $this->language->get('text_payable') . "\n";
		$comment .= $this->config->get('pagseguro_payable') . "\n\n";
		$comment .= $this->language->get('text_address') . "\n";
		$comment .= $this->config->get('config_address') . "\n\n";
		$comment .= $this->language->get('text_payment') . "\n";
		
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('pagseguro_order_status_id'), $comment);
	}
	
	/**
	 * install - É executada quando o plugin é instalado, este método prepara o banco
	 * para conter os status do pagseguro.
	 */
	function install() {        

        $query = $this->db->query("SELECT COUNT( * ) AS `Registros` , `language_id` FROM `" . DB_PREFIX. "order_status` GROUP BY `language_id` ORDER BY `language_id`");
        
        foreach ($query->rows as $reg) {
            $this->db->query("REPLACE INTO `" . DB_PREFIX . "order_status` (`order_status_id`, `language_id`, `name`) VALUES
            (10200, " . $reg['language_id'] . ", 'Aguardando Pagto'),
            (10201, " . $reg['language_id'] . ", 'Em Analise'),
            (10202, " . $reg['language_id'] . ", 'Paga'),
            (10203, " . $reg['language_id'] . ", 'Disponivel'),
            (10204, " . $reg['language_id'] . ", 'Em Disputa'),
            (10205, " . $reg['language_id'] . ", 'Devolvida'),
            (10206, " . $reg['language_id'] . ", 'Cancelada'),
            (10207, " . $reg['language_id'] . ", 'Desconhecido');");
        }
	
	}
	
    function splitPhone($phone){
        $phone = preg_replace('/[a-w]+.*/', '', $phone);
        $numbers = preg_replace('/\D/', '', $phone);
        $telephone = substr($numbers, sizeof($numbers) - 9);
        $prefix = substr($numbers, sizeof($numbers) - 11, 2);
        return array($prefix, $telephone);
    }	
}
