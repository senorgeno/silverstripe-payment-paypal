<?php

class PayPal_Express extends Payment { 
	
	private static $db = array(
		'Token' => 'Varchar',
		'PayerID' => 'Varchar'
	);
}
