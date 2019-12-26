<?php

	defined("DS") || define("DS", DIRECTORY_SEPARATOR);
	defined("BASE") || define("BASE", __DIR__ . DS . '..' . DS . '..');
	if(!class_exists("Configuration"))
		require_once BASE . DS . 'Configuration.php';

	class JamesFactory
	{
		public static $logger = null;

		public static $php_ews = null;

		public static $config = null;

		public static function getConfig() {
			if(!self::$config)
				self::$config = new Configuration();

			return self::$config;
		}

		public static function getLogger($log_file = "default.log", $log_dir = "", $log_level = "ERROR")
		{
			if(!class_exists("Logger"))
				require_once __DIR__ . DS . '..' . DS . 'logging' . DS . 'logger.php';

			if(!$log_dir || empty($log_dir))
				$log_dir = self::getConfig()->log_dir;


			if(!self::$logger) {
				self::$logger = new Logger($log_dir, $log_file, $log_level);
			}

			return self::$logger;
		}

		public static function getPhpEws()
		{
			if(!class_exists("Mailbox"))
				require_once LIBRARIES . DS . 'php-ews' . DS . 'Mailbox.php';

			if(!self::$php_ews)
			{
				self::$php_ews = new Mailbox(
					JamesFactory::getConfig()->php_ews_username,
					JamesFactory::getConfig()->php_ews_password
				);
				self::$php_ews->temp_dir = JamesFactory::getConfig()->temp_dir;
				self::$php_ews->mailServer = JamesFactory::getConfig()->php_ews_url;
				self::$php_ews->client_version = JamesFactory::getConfig()->php_ews_version;
				self::$php_ews->connect();
			}

			return self::$php_ews;
		}

		public static function getPayloadInstance(): Payload
		{
			if(!class_exists("Payload"))
				require_once BASE . DS . 'libraries' . DS . 'self' . DS . 'Payload.php';

			return new Payload();
		}
	}