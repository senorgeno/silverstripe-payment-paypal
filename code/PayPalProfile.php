<?php

/**
 * Represents a paypal recurring payments profile
 *
 * @author Gene
 */
class PayPalPaymentProfile extends DataObject{
    
	private static $db = array(
	    'ProfileID' => 'Varchar'
	);
	
	private static $has_one = array(
	    'Member' => 'Member'
	);
	
	private static $has_many = array();
	
}

