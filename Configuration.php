<?php


	defined("DS") || define("DS", DIRECTORY_SEPARATOR);
	defined("BASE") || define("BASE", __DIR__);
	defined("LIBRARIES") || define("LIBRARIES", BASE . DS . 'libraries');

	class Configuration
	{
		public $log_dir = "";
		public $php_ews_version = "Exchange2007";
		public $php_ews_url = "";
		public $php_ews_username = "";
		public $php_ews_password = "";
		public $temp_dir = "";
	}