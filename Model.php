<?php

	use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfBaseFolderIdsType;
	use jamesiarmes\PhpEws\Enumeration\DefaultShapeNamesType;
	use jamesiarmes\PhpEws\Enumeration\DistinguishedFolderIdNameType;
	use jamesiarmes\PhpEws\Enumeration\ResponseClassType;
	use jamesiarmes\PhpEws\Request\FindItemType;
	use jamesiarmes\PhpEws\Type\CalendarViewType;
	use jamesiarmes\PhpEws\Type\DistinguishedFolderIdType;
	use jamesiarmes\PhpEws\Type\ItemResponseShapeType;

	if(!class_exists("JamesFactory"))
		require_once BASE . DS . 'libraries' . DS . 'self' . DS . 'JamesFactory.php';

	class Model
	{
		/**
		 * @var Mailbox
		 */
		public $php_ews = null;

		/**
		 * @var Payload
		 */
		public $payload = null;

		public $logger = null;

		public function __construct()
		{
			$this->payload = JamesFactory::getPayloadInstance();
			$this->logger = JamesFactory::getLogger();

			$this->php_ews = JamesFactory::getPhpEws();
			//Default to today
			$this->php_ews->start_date = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d 00:00:00"));
			$this->php_ews->stop_date = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d 23:59:59"));
		}

		/**
		 * @param DateTime $start
		 * @param DateTime $stop
		 */
		public function setPhpEwsDateRange(DateTime $start, DateTime $stop): void
		{
			$this->php_ews->start_date = $start;
			$this->php_ews->stop_date = $stop;
		}

		public function getCalendar()
		{
			$request = new FindItemType();
			$request->ParentFolderIds = new NonEmptyArrayOfBaseFolderIdsType();

// Return all event properties.
			$request->ItemShape = new ItemResponseShapeType();
			$request->ItemShape->BaseShape = DefaultShapeNamesType::ALL_PROPERTIES;

			$folder_id = new DistinguishedFolderIdType();
			$folder_id->Id = DistinguishedFolderIdNameType::CALENDAR;
			$request->ParentFolderIds->DistinguishedFolderId[] = $folder_id;

			$request->CalendarView = new CalendarViewType();
			$request->CalendarView->StartDate = $this->php_ews->start_date->format('c');
			$request->CalendarView->EndDate = $this->php_ews->stop_date->format('c');

			$response = $this->php_ews->getConnection()->FindItem($request);

			//Make sure the Response is valid
			if($response->ResponseMessages->FindItemResponseMessage[0]->ResponseClass != ResponseClassType::SUCCESS) {
				$this->logger->error(__FUNCTION__, "Could not get the Calendar. " . $response->ResponseMessages->FindFolderResponseMessage[0]->MessageText);
				return $this->error("Could not get a response");
			}

			$this->payload->data = $response->ResponseMessages->FindItemResponseMessage[0]->RootFolder->Items->CalendarItem;

			return $this->payload;

		}

		protected function error($message = ""): Payload
		{
			$this->payload->error = true;
			$this->payload->error_msg = $message;
			return $this->payload;
		}
	}