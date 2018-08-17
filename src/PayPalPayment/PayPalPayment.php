<?php

namespace IQnection\PayPalPayment;

use IQnection\Payment\Payment;
use SilverStripe\Forms;
use SilverStripe\ORM;

class PayPalPayment extends Payment
{
	private static $table_name = 'PayPalPayment';
	private static $PaymentMethod = 'PayPal';
	
	private static $db = [
		"TransactionID" => "Varchar(255)",
		"GatewayResponse" => "Text",
		"Date" => "Datetime",
		"Status" => "Varchar(255)",
		"Name" => "Varchar(255)",
		"Street" => "Varchar(255)",
		"City" => "Varchar(255)",
		"State" => "Varchar(255)",
		"Country" => "Varchar(255)",
		"Zip" => "Varchar(255)",
		"Email" => "Varchar(255)",
		"PayerID" => "Varchar(255)"
	];
	
	private static $summary_fields = [
		'Date.Nice' => 'Date',
		'Name' => 'Name',
		'Email' => 'Email',
		'Amount' => 'Amount'
	];
	
	public function getTitle()
	{
		return $this->TransactionID;
	}
	
	public function getCMSFields()
	{
		$fields = parent::getCMSFields();
		$fields->addFieldToTab('Root.GatewayResponse', Forms\TextareaField::create('GatewayResponse','Gateway Response') );

		$this->extend('updateCMSFields',$fields);
		
		return $fields;
	}
		
	public function canCreate($member = null, $context = []) { return false; }
	public function canDelete($member = null, $context = []) { return true; }
	public function canEdit($member = null, $context = [])   { return false; }
	public function canView($member = null, $context = [])   { return true; }
}




