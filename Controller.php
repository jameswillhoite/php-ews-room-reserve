<?php
	defined("BASE") || define("BASE", __DIR__);
	defined("DS") || define("DS", DIRECTORY_SEPARATOR);

	if(!class_exists("JamesFactory"))
		require_once BASE . DS . 'libraries' . DS . 'self' . DS . 'JamesFactory.php';


	class Controller
	{

		/**
		 * @var Logger
		 */
		protected $logger = null;

		public function __construct()
		{
			$this->logger = JamesFactory::getLogger();
		}
	}