<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
*	Extend this class to get you started
*	All Transports must at least implement Tranport_Interface
*/

require_once(APPPATH.'libraries/Mailman/Mailman_Transport_Interface.php');

abstract class Mailman_Transport_Abstract implements Mailman_Transport_Interface {

	protected $_core;
	protected $_data;

	//Mail-info functions needed (Left out: CC, BCC)
	public function to($to) {
		$this->_data['to'] = $to;
		return $this;
	}

	public function from($from) {
		$this->_data['from'] = $from;
		return $this;
	}

	public function from_name($from_name) {
		$this->_data['from_name'] = $from_name;
		return $this;
	}

	public function cc($cc) {
		$this->_data['cc'] = $cc;
		return $this;
	}

	public function bcc($bcc) {
		$this->_data['bcc'] = $bcc;
		return $this;
	}

	public function subject($subject) {
		$this->_data['subject'] = $subject;
		return $this;
	}

	public function message($message) {
		$this->_data['message'] = $message;
		return $this;
	}

	public function message_alt($message_alt) {
		$this->_data['message_alt'] = $message_alt;
		return $this;
	}

	//Send the email
	public function send() {}

	//Get the library this class will override
	public function getCore() {
		return $this->_core;
	}

	//Provide a logging mechanism
	public function log($level, $message) {
		log_message($level, $message);
	}

	//Added function to retrieve data array
	public function getData() {
		return $this->_data;
	}

	//Added function to retrieve data item
	public function get($key) {
		if(isset($this->_data[$key])) {
			return $this->_data[$key];
		}
		return FALSE;
	}

}
