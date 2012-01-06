<?php
/**
 * ControllerPaymentPagseguro
 *
 * Classe controle da administração do módulo de pagamento
 * @package pagseguro_opencart
 * @author ldmotta - ldmotta@gmail.com
 * @link motanet.com.br
 */
class ControllerPaymentPagseguro extends Controller {
    private $error;
    /**
     * index
     * Executado na página de edição do módulo na administração, implementa
     * os botões de salvar e cancelar
     */
    function index() {

        $this->load->language('payment/pagseguro');
		
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        
        $this->session->data['token'] = isset($this->session->data['token']) ? $this->session->data['token'] : '';
            
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

            $this->load->model('setting/setting');

            $this->model_setting_setting->editSetting('pagseguro', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect(HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token']);
        }

        $this->data['breadcrumbs'] = array(
            array(
                    'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
                    'text'      => $this->language->get('text_home'),
                    'separator' => FALSE
            ),
            array(
                    'href'      => HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'],
                    'text'      => $this->language->get('text_payment'),
                    'separator' => ' :: '
            ),
            array(
                    'href'      => HTTPS_SERVER . 'index.php?route=payment/pagseguro&token=' . $this->session->data['token'],
                    'text'      => $this->language->get('heading_title'),
                    'separator' => ' :: '
            )
        );
        
        $langs = array(
                'heading_title', 'text_payment', 'text_success',
                'text_enabled', 'text_disabled', 'button_cancel',
                'button_save', 'lb_mail', 'lb_token', 'lb_sort_order',
                'lb_status', 'instructions_title', 'instructions_info',
                'entry_order_status'
        );

        foreach ($langs as $item) {
            $this->data[$item] = $this->language->get($item);
        }

        foreach (array('mail', 'token', 'sort_order', 'status', 'order_status_id') as $item) {
            if (isset($this->request->post['pagseguro_'.$item])) {
                $this->data["pagseguro_$item"] = $this->request->post["pagseguro_$item"];
            } else {
                $this->data["pagseguro_$item"] = $this->config->get("pagseguro_$item");
            }
        }

		$this->load->model('localisation/order_status');		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/pagseguro&token=' . $this->session->data['token'];

        $this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];

        $this->data['error_warning'] = @$this->error['warning'];
        $this->data['error_pagseguro_mail'] = @$this->error['pagseguro_mail'];

        $this->id       = 'content';
        $this->template = 'payment/pagseguro.tpl';
        $this->children = array(
                'common/header',
                'common/footer'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    /**
     * validate - Valida os dados submetidos na página de edição do módulo
     * @access public
     * @return boolean True ou False dependendo do retorno da validação
     */
    public function validate() {
        if (!$this->user->hasPermission('modify', 'payment/pagseguro')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * formatText - Utilizado para formatar o texto da documentação do módulo
     * @access static
     * @param string Texto a ser formatado
     * @return string Retorna o texto formatado com html
     */
    static function formatText($text) {
        $text = trim($text);
        // Trocando os titulos
        $text = preg_replace('/== (.+) ==/', '<h3>\1</h3>', $text);
        // Trocando os paragrados
        $text = preg_replace('@[\n\r]{3,}@', "</p>\n\n<p>", $text);
        // Trocando os negritos
        $text = preg_replace("@\*([^\*]+)\*@", '<strong>\1</strong>', $text);
        // Troca as imagens
        $text = preg_replace('@\[img:([^\]]+)\]@', '<img src="view/image/payment/\1" />', $text);
        // Trocando as urls
        $text = preg_replace('@\[([^ ]+) ([^\]]+)\]@', '<a href="\1" title="\2" target="_blank">\2</a>', $text);
        // Aplicando o primeiro e ultimo paragrafos
        $text = "\n\n<p>$text</p>\n\n";
        // Removendo as duplicatas
        $text = preg_replace('@<p>(<h\d>.+</h\d>)</p>@', '\1', $text);
        return $text;
    }
}
