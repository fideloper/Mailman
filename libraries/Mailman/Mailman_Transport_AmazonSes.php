<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'libraries/Mailman/Mailman_Transport_Abstract.php');

class Mailman_Transport_AmazonSes extends Mailman_Transport_Abstract {

	private $CI;

	//This transport is a wrapper for Amazon SES library
	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->library('amazon_ses');

		$this->_core =& $this->CI->amazon_ses;

		$this->CI->amazon_ses->debug(TRUE); //We want API response for logging
	}

	public function send() {
		$result = $this->CI->amazon_ses
			->to( $this->get('to') )
			->subject(  $this->get('subject') )
			->message( $this->get('message') )
			->message_alt( $this->get('message_alt') )
			->send();

		$parsedResult = simplexml_load_string($result);

		if($parsedResult !== FALSE) {
			if(isset($parsedResult->Error)) {
				log_message('error', 'AmazonSes Email Error :: Type: '.$parsedResult->Error->Type.' :: Code: '.$parsedResult->Error->Code.' :: Message: '.$parsedResult->Error->Message);
				return FALSE;
			}
			if(isset($parsedResult->SendEmailResult->MessageId)) {
				log_message('info', 'AmazonSes Email Sent :: MessageID: '.$parsedResult->SendEmailResult->MessageId);
				return TRUE;
			}
		}

		return $result;
	}

}