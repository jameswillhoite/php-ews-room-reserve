<?php

	if(!class_exists("JamesFactory"))
		require_once BASE . DS . 'libraries' . DS . 'self' . DS . 'JamesFactory.php';

    use jamesiarmes\PhpEws\ArrayType\ArrayOfRecipientsType;
    use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfAllItemsType;
    use jamesiarmes\PhpEws\Client;
    use jamesiarmes\PhpEws\SoapClient;
    use jamesiarmes\PhpEws\Enumeration\BodyTypeType;
    use jamesiarmes\PhpEws\Enumeration\MessageDispositionType;
    use jamesiarmes\PhpEws\Enumeration\ResponseClassType;
    use jamesiarmes\PhpEws\Request\CreateItemType;
    use jamesiarmes\PhpEws\Type\BodyType;
    use jamesiarmes\PhpEws\Type\EmailAddressType;
    use jamesiarmes\PhpEws\Type\MessageType;
    use jamesiarmes\PhpEws\Type\SingleRecipientType;

    spl_autoload_register(function ($class) {
    	$dir = __DIR__;
    	$exp = explode("\\", $class);
    	$class = end($exp);
        if(file_exists($dir ."/" . $class . ".php")) {
            require_once $dir. "/" . $class . ".php";
        }
        elseif (file_exists($dir . "/ArrayType/" . $class . ".php")) {
            require_once $dir . "/ArrayType/" . $class . ".php";
        }
        elseif (file_exists($dir . "/Enumeration/" . $class . ".php")) {
            require_once $dir . "/Enumeration/" . $class . ".php";
        }
        elseif (file_exists($dir . "/Request/" . $class . ".php")) {
            require_once $dir . "/Request/" . $class . ".php";
        }
        elseif (file_exists($dir . "/Response/" . $class . ".php")) {
            require_once $dir . "/Response/" . $class . ".php";
        }
        elseif (file_exists($dir . "/Type/" . $class . ".php")) {
            require_once $dir . "/Type/" . $class . ".php";
        }
        else {
            return null;
        }
 });
 

     class ExchangeMaster {
         public $mailServer = "change me";
         public $fromUsername = "change me";
         public $fromPassword = "change me";
         public $temp_dir = 'path/to/temp/directory/for/attachments';
         public $client_version = null;
         protected $theClient = null;



         public function __construct($username = null, $password = null)
         {
            if($username != null) {
                $this->fromUsername = $username;
                $this->fromPassword = $password;
            }

            if(!$this->client_version) {
            	$this->client_version = Client::VERSION_2007;
            }

         }

         public function connect(): void
         {
         	if(!$this->theClient)
            {
	            $this->theClient = new Client($this->mailServer, $this->fromUsername, $this->fromPassword, $this->client_version);
	            $this->theClient->setTimezone('Eastern Standard Time');
	            $this->theClient->setCurlOptions(array(CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false));
            }

         }

         public function getConnection()
         {
         	return $this->theClient;
         }

     }
