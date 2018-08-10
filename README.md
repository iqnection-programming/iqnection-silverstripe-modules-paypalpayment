#IQnection PayPal Payment

## extends IQnection\Payment\Payment

When using PayPal Payment, you must implement a page and controller

in your model page file, declare the extension
```
 class MyPayPalPage extends Page
 {
 	private static $extensions = [
		IQnection\PayPalPayment\PayPalPage\PageExtension::class
	];
 }
```

in your page controller file, declare the handler as an extension
```
 class MyPayPalPageController extends PageController
 {
 	private static $extensions = [
		IQnection\PayPalPayment\Controller\PaymentHandler::class
	];
 }
 ```
 
 the handler methods are now a part of your page controller
 
 In your template, create your PayPal form and specify your IPN
 add some JavaScript to auto-submit the form
    <form id="frmPaypal" target="_self" action="$PayPalUrl" method="post">
        <input type="hidden" name="business" value="$PayPalAccount" />
        <input type="hidden" name="cmd" value="_xclick" /> 
        <input type="hidden" name="item_name" value="Payment to $SiteConfig.Title" />
        <input type="hidden" name="item_number" value="{$Submission.ID}" />
        <input type="hidden" name="amount" value="$Submission.Donation" />    
        <input type="hidden" name="return" value="{$AbsoluteLink}thanks/">
        <input type="hidden" name="cancel_return" value="$AbsoluteLink">
        <input type="hidden" name="notify_url" value="$IPNLink">
        <input type="hidden" name="currency_code" value="USD" />
        <input type="hidden" name="lc" value="US" />
    </form>
	
 when PayPal sends the IPN response, it will be send to the same page the payment was submitted from
 you can implement OnSuccessfulPayment method in your page controller to handle the payment upon success
 eg:
 ```
 public function OnSuccessfulPayment($Payment,$data)
 {
 	if ( (isset($data['item_number'])) && ($submission = MyPageSubmission()->byID($data['item_number'])) )
	{
		$submission->PaymentID = $Payment->ID;
		$submission->write();
	}
 }
 ```
 
 upon successful payment from PayPal, the user will be redirected back to your page calling action /thanks
 