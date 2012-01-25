<?php if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) die();

// Configuration
require_once('config.php');  
 
// Startup
require_once (DIR_SYSTEM . 'startup.php');

// Library PagSeguro
require_once (DIR_APPLICATION . 'controller/payment/PagSeguroLibrary/PagSeguroLibrary.php');

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

class NotificationListener extends Controller {

    public static function main() {
        
    	$code = self::verifyData($_POST['notificationCode']);
    	$type = self::verifyData($_POST['notificationtype']);
    	
    	if ( $code && $type ) {
			
    		$notificationType = new PagSeguroNotificationType($type);
    		$strType = $notificationType->getTypeFromValue();

			switch($strType) {
				
				case 'TRANSACTION':
					self::TransactionNotification($code);
					break;
				
				default:
					LogPagSeguro::error("Tipo de notificação não reconhecido [".$notificationType->getValue()."]");
					
			}

			self::saveLog($strType);
			
		} else {
			
			LogPagSeguro::error("Os parâmetros de notificação (notificationCode e notificationType) não foram recebidos.");
			
			self::saveLog();
			
		}
		
    }
    
    
    private static function TransactionNotification($notificationCode) {
		
    	/*
    	* #### Crendenciais #####
    	* Se desejar, utilize as credenciais pré-definidas no arquivo de configurações
    	* $credentials = PagSeguroConfig::getAccountCredentials();
    	*/
    	
        // Pegando as configurações definidas no admin do módulo
        $config = self::getConfig();
        
    	$credentials = new PagSeguroAccountCredentials($config['email'], $config['token']);
    	
    	try {
    		
    		$transaction = PagSeguroNotificationService::checkTransaction($credentials, $notificationCode);
    		
    		self::validateTransaction($transaction);
    		
    	} catch (PagSeguroServiceException $e) {

    		die($e->getMessage());

    	}
    	
    }
  
    private static function validateTransaction(Transaction $transaction) {
    
	    global $db;

        $order = $db->query('SELECT * FROM `' . DB_PREFIX . 'order` WHERE order_id = ' . $transaction->getReference());

        $StatusTransacao = $transaction->getStatus();

	    switch($StatusTransacao){
		    case 1: //'Aguardando Pagto'
			    $order_status_id = 10200;
			    break;
			
		    case 2: //'Em Analise'
			    $order_status_id = 10201;
			    break;
			
		    case 3: //'Paga' :
			    $order_status_id = 10202;
			    break;
			
		    case 4: //'Disponivel' :
			    $order_status_id = 10203;
			    break;
			
		    case 5: //'Em Disputa' :
			    $order_status_id = 10204;
			    break;
			
		    case 6: //'Devolvida' :
			    $order_status_id = 10205;
			    break;
			
		    case 7: //'Cancelada' :
			    $order_status_id = 10206;
			    break;
			
		    default:
			    $order_status_id = 10207;
	    }
	
	    LogPagSeguro::info("Alteração de status para [" . $transaction->getStatus()->getTypeFromValue() . "]");
	    
	    self::saveLog();
	    
	    $db->query('UPDATE `' . DB_PREFIX . 'order` SET `order_status_id` = ' . $order_status_id . ' WHERE `order_id` = ' . $Referencia);
	    
	    $db->query("INSERT INTO `" . DB_PREFIX . "order_history` VALUES (NULL , '" . $Referencia . "', '" . $order_status_id . "', '0', '', NOW());");	
            
    }
  
    /**
     * verifyData - Corrige os dados enviados via post
     * @data string Dados enviados via post
     */
    private static function verifyData($data){
    
        return isset($data) && trim($data) !== "" ? trim($data) : null;
    
    }  
    
    /**
     * getConfig - Retorna as configurações definidas para as credenciais
     * @return array Array contendo as credenciais do usuário
     */
    private static function getConfig() {
        global $db;
        
        $config = array();
                
        // Settings
        $query = $db->query("SELECT value FROM " . DB_PREFIX . "setting s where s.key='pagseguro_mail'");
        $config['email'] = $query->row['value'];

        $query = $db->query("SELECT value FROM " . DB_PREFIX . "setting s where s.key='pagseguro_token'");
        $config['token'] = $query->row['value'];
    
        return $config;
    
    }
    
    private static function saveLog($strType = null) {
        #LogPagSeguro::getHtml();
    }
	
}
NotificationListener::main();
?>
