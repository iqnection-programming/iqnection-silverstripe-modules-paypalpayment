<?php

namespace IQnection\PayPalPayment\PayPalPage;

use SilverStripe\ORM\DataExtension;
use Silverstripe\Forms;

class PageExtension extends DataExension
{
	private static $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
	private static $paypal_sandbox_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	
	private static $db = [
		"RedirectText" => "HTMLText",
		"PayPalAccount" => "Varchar(255)",
		"UsePayPalSandbox" => "Boolean"
	];
	
	private static $has_many = [
		"PayPalPayments" => \IQnection\PayPalPayment\PayPalPayment::class
	];
	
	public function updateCMSFields(Forms\FieldList &$fields)
	{
		$fields->addFieldToTab("Root.PayPalSettings", Forms\HTMLEditor\HTMLEditorField::create("RedirectText", "Text Explaining PayPal Redirect")
			->addExtraClass('stacked') );

		$fields->addFieldToTab('Root.PayPalSettings', Forms\CheckboxField::create('UsePayPalSandbox','Enable Test Mode') );
		$fields->addFieldToTab("Root.PayPalSettings", Forms\TextField::create("PayPalAccount", "PayPal Account (email address)"));
		
		// Paypal Payments
		$fields->addFieldToTab('Root.Payments', Forms\GridField\GridField::create(
			'PayPalPayments',
			'PayPal Payments',
			$this->owner->PayPalPayments(),
			Forms\GridField\GridFieldConfig_RecordEditor::create()
		));
	}
	
	public function PayPalUrl()
	{
		if ($this->owner->UsePayPalSandbox)	
		{
			return $this->owner->config()->get('paypal_sandbox_url');
		}
		return $this->owner->config()->get('paypal_url');
	}
	
	public function IPNLink()
	{
		$Link = $this->owner->AbsoluteLink('process_ipn_response');
		$this->extend('updateIPNLink',$Link);
		return $Link;
	}
}