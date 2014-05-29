<?php

class PayPalExpressTest extends SapphireTest {

  public $processor;
  public $data;

  function setUp() {
	  
	parent::setUp();

	Config::inst()->remove('PaymentGateway', 'environment');
	Config::inst()->update('PaymentGateway', 'environment', 'test');

	// $paymentMethods = array('test' => array('PaymentExpressPxPay'));
	Config::inst()->remove('PaymentProcessor', 'supported_methods');
	Config::inst()->update('PaymentProcessor', 'supported_methods', array(
	    'test' => array(
		  'PayPalExpress'
		  )));

//	USER=sdk-three_api1.sdk.com
//	PWD=QFZCWN5HZM8VBG7Q
//	SIGNATURE=A-IzJhZZjhg29XQ2qnhapuwxIDzyAZQ92FRP5dqBzVesOkzbdUONzmOU
	
	$gatewayConfig = array(
		'authentication' => array(
			'username' => 'sdk-three_api1.sdk.com',
			'password' => 'QFZCWN5HZM8VBG7Q',
			'signature' => 'A-IzJhZZjhg29XQ2qnhapuwxIDzyAZQ92FRP5dqBzVesOkzbdUONzmOU'
		),
		'endpoint' => 'https://api-3t.sandbox.paypal.com/nvp',
		'url' => 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='
		);
	
	
	Config::inst()->remove('PayPalGateway_Express', 'test');
	Config::inst()->update('PayPalGateway_Express', 'test', $gatewayConfig);

	$this->processor = PaymentFactory::factory('PayPalExpress');

	$this->data = array(
	    'Amount' => '10',
	    'Currency' => 'USD'
	);
}

	public function testProcessorConfig() {

		$this->assertEquals(get_class($this->processor), 'PayPalProcessor_Express');
		$this->assertEquals(get_class($this->processor->gateway), 'PayPalGateway_Express_Mock');
		$this->assertEquals(get_class($this->processor->payment), 'PayPal_Express');
	}

	
	public function testGatewayConfig() {
		
		$config = $this->processor->gateway->getConfig();


		//$PayPal_Url    = Config::inst()->get('PaymentExpressGateway_PxPay', 'url');
		$PayPal_username = $config['authentication']['username'];
		$PayPal_password = $config['authentication']['password'];
		$PayPal_signature = $config['authentication']['signature'];

	//	$this->assertEquals($PxPay_Url, 'https://sec.paymentexpress.com/pxpay/pxaccess.aspx');
		$this->assertTrue(isset($PayPal_username));
		$this->assertTrue(isset($PayPal_password));
		$this->assertTrue(isset($PayPal_signature));
		
	}
	
	public function testPaymentConnectSuccess() {
		

		$config = $this->processor->gateway->getConfig();
			
		//This should set up a redirect to the gateway for the browser in the response of the controller
		$this->data['mock'] = 'success';
		$this->processor->capture($this->data);
		
		$payment = $payment = Payment::get()->byID($this->processor->payment->ID);
		echo $payment->Token;
		$this->assertEquals($payment->Status, Payment::PENDING);
	}
	
//  function testClassConfig() {
//    $this->assertEquals(get_class($this->processor), 'PaymentProcessor_GatewayHosted');
//    $this->assertEquals(get_class($this->processor->gateway), 'PayPalGateway_Express');
//    $this->assertEquals(get_class($this->processor->payment), 'Payment');
//  }
//
//  function testGetTokenSuccess() {
//    $response = new RestfulService_Response($this->processor->gateway->generateDummyTokenResponse());
//    $this->assertEquals($this->processor->gateway->getToken($response), '2d6TB68159J8219744P');
//  }
//
//  function testGetTokenFailure() {
//    $response = new RestfulService_Response($this->processor->gateway->generateDummyFailureResponse());
//    $this->assertNull($this->processor->gateway->getToken($response));
//  }
	
}