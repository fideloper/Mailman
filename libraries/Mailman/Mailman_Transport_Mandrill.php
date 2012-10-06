<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'libraries/Mailman/Mailman_Transport_Abstract.php');

/**
*	Note that Mandrill does not support CC or BCC at this time
*/

class Mailman_Transport_Mandrill extends Mailman_Transport_Abstract {

	private $CI;
	private $_can_send_mandrill;

	//This transport is a wrapper for Mandrill library
	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->library('mandrill');

		$this->CI->config->load('mandrill');

		$this->_core =& $this->CI->mandrill;

		try {
			$this->_core->init($this->CI->config->item('mandrill_api_key'));
			$this->_can_send_mandrill = TRUE;
		} catch(Mandrill_Exception $e) {
			$this->_can_send_mandrill = FALSE;
		}
	}

	public function send() {

		if( $this->_can_send_mandrill === FALSE ) {

			log_message('error', 'Mandrill Email Error :: Type: API Key Invalid');
			return FALSE;

		}

		$data = array(
			'html' => $this->get('message'),
			'text' => $this->get('message_alt'),
			'subject' => $this->get('subject'),
			'from_email' => $this->get('from'),
			'from_name' => $this->get('from_name'),
			'to' => array(array('email' => $this->get('to')))
		);

		$result = $this->_core->messages_send($data);

		//Need to get status of each to address in future
		if( is_array($result) ) {

			if( isset($result[0]['status']) && $result[0]['status'] == 'sent' ) {

				return TRUE;

			}

		}

		return FALSE;

	}

}