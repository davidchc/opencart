<?php
/**
 * ModelPaymentPagseguro
 *
 * Recupera os dados do produto adicionado no carrino de compras
 * @package pagseguro_opencart
 * @author ldmotta - ldmotta@gmail.com
 * @link motanet.com.br
 */
class ModelPaymentPagseguro extends Model
{
    /**
     * getMethod
     *
     * @access public
     * @return array Array contendo dados de configuração do módulo
     */
    public function getMethod()
    {
		$this->load->language('payment/pagseguro');
        $method_data = array( 
                'code'       => 'pagseguro',
                'title'      => $this->language->get('text_title'),
                'sort_order' => $this->config->get('pagseguro_sort_order')
                );
    	return $method_data;
    }

    /**
     * getCart
     *
     * Recupera os dados do carrinho contidos na session e seleciona
     * os dados dos produtos adicionados a este carrinho.
     * @access public
     * @return array Array com os dados dos produtos
     */
    public function getCart()
    {
		$this->load->model('catalog/product');
		$this->load->model('checkout/order');
		$order = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		foreach ($this->cart->getProducts() as $product) {

      		$option_data = array();

      		foreach ($product['option'] as $option) {
        		$option_data[] = array(
          			'name'   => $option['name'],
          			'price'  => $option['price'],
          			'value'  => $option['option_value'],
					'prefix' => $option['price_prefix']
        		);
      		}
 
 
            // para funcionar com a versão 1.5.0.5 do opencart
            $weight_class = array_key_exists("weight_class", $product) ? $product['weight_class'] : $product['weight_class_id'];
            
       		$product_data[] = array(
                'product_id' => $product['product_id'],
                'name'       => $product['name'],
                'model'      => $product['model'],
                'option'     => $option_data,
                'download'   => $product['download'],
                'quantity'   => $product['quantity'], 
                'price'      => $product['price'],
                'total'      => $product['total'],
                'weight'     => $this->weightToGrams($product['weight'], $weight_class)
                //'tax'        => $this->tax->getRate($product['tax_class_id']),
                //'weight'     => $this->weightToGrams($product['weight'], $product['weight_class'])                
      		); 

    	}

        return array($order, $product_data);
    }
    
    public function weightToGrams($weight, $weight_class) {
        $query = "SELECT " . DB_PREFIX . "weight_class.value, " . DB_PREFIX . "weight_class_description.unit FROM " 
        . DB_PREFIX . "weight_class inner join " . DB_PREFIX . "weight_class_description on " 
        . DB_PREFIX . "weight_class.weight_class_id=" . DB_PREFIX . "weight_class_description.weight_class_id where " 
        . DB_PREFIX . "weight_class_description.unit='" . $weight_class . "'";
        $weight_query = $this->db->query($query);

        if ($weight_query->num_rows){
            $grams = $weight / $weight_query->row['value'] * 1000;
            return $grams;
        }
    }
}






























