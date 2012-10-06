<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
*	Send email using various third-parties
*/

class Mailman {

	protected $CI;					// CodeIgniter instance
	private static $_instance; 		// Singleton Instance
	private $_transport;			// Transport, implements Mailman_Transport_Interface.php

	const MAILMAN_TRANSPORT_DEFAULT = 'AmazonSes';
	const MAILMAN_TRANSPORT_AMAZONSES = 'AmazonSes';
	const MAILMAN_TRANSPORT_MANDRILL = 'Mandrill';

	public function __construct($transport=NULL) {
		$this->CI = & get_instance();

		//Load dependencies
		$this->CI->load->library('parser');

		if($transport !== NULL) {
			$this->setTransport($transport);
		}
	}

	/**
	*	Send an email. Wrapper for use by static
	*	functions and to call private _email workhorse
	*
	*	@param array 	Data needed to send email - See _email()
	*	@return bool 	Success or Failure boolean
	*/
	public function sendmail($data) {
		return $this->_email($data);
	}

	/**
	*	Send an email. Wrapper for use by static
	*	functions and to call private _queue workhorse
	*
	*	@param array 	Data needed to send email - See _email()
	*	@return bool 	Success or Failure boolean on addition to queue
	*/
	public function queueup() {
		return $this->_queue($data);
	}

	/**
	*	Hook callback
	*	Used to send email via Hook
	*	rather than direct call
	*/
	public function hookSend($data) {
		if( isset($data['transport']) ) {
			$this->setTransport( $data['transport'] );
		}
		return $this->sendmail( $data );
	}

	/**
	*	Allow setting of different email systems
	*	For instance Amazon SES or Mandrill
	*	DEFAULTS to AmazonSes transport
	*/
	public function setTransport($transport) {
		$transFile = APPPATH.'libraries/Mailman/Mailman_Transport_'.$transport.'.php';
		if(file_exists($transFile)) {
			require_once($transFile);
			$transportClass = 'Mailman_Transport_'.$transport;
		} else {
			require_once(APPPATH.'libraries/Mailman/Mailman_Transport_'.self::MAILMAN_TRANSPORT_DEFAULT.'.php');
			$transportClass = 'Mailman_Transport_'.self::MAILMAN_TRANSPORT_DEFAULT;
		}

		$this->_transport = new $transportClass();
		return $this;
	}

	/**
	*	Get Transport to send email with
	*/
	public function getTransport() {
		if($this->_transport === NULL) {
			$this->setTransport(self::MAILMAN_TRANSPORT_DEFAULT);
		}
		return $this->_transport;
	}



	//Private Functions

	/**
	*	Send an email
	*
	*	@param array 	Data needed to send email:
	*					$data[to] 				#array || string
	*					$data[subject] 			#string
	*					$data[template_html] 	#string
	*					$data[template_alt] 	#string
	*					$data[template_data]	#array (See _template function)
	*
	*					Optional:
	*					$data[from]				#string
	*					$data[from_name]		#string
	*					$data[cc]				#array || string
	*					$data[bss]				#array || string
	*
	*/
	private function _email($data) {
		$this->getTransport()->to( $data['to'] )
							 ->subject( $data['subject'] )
							 ->message( $this->_template( $data['template_html'], $data['template_data'] ) )
							 ->message_alt( $this->_template( $data['template_alt'], $data['template_data'] ) );

		// Optionally FROM - if not taken automatically via config (a là Amazon SES)
		if( isset($data['from']) ) {

			$this->getTransport()->from( $data['from'] );
			$this->getTransport()->from_name( isset($data['from_name']) ? $data['from_name'] : FALSE );

		}

		// Optionally CC
		if( isset($data['cc']) ) {

			$this->getTransport()->cc( $data['cc'] );

		}

		// Optionally BCC
		if( isset($data['bcc']) ) {

			$this->getTransport()->bcc( $data['bcc'] );

		}

		return $this->_send();
	}

	/**
	*	Perform Send, Error Checking and Logging
	*
	*	@return bool 	If sent successfully
	*/
	private function _send() {
		return $this->getTransport()->send();
	}

	/**
	*	Load email content from template
	*	Replace variables with Template Parser class
	*
	*	@link http://codeigniter.com/user_guide/libraries/parser.html
	*	@return string 	Parsed template
	*/
	private function _template($view, $data) {
		return $this->CI->parser->parse($view, $data, TRUE);
	}


	//Static Accessors

	/**
	*	Retrieve class instance
	*	For use of static functions
	*/
	public static function instance() {
		if(self::$_instance === NULL) {
			self::$_instance = new Mailman();
		}
		return self::$_instance;
	}

	/**
	*	Send an email. Proxy to sendmail()
	*/
	public static function email($data) {
		$inst = self::instance();
		return $inst->sendmail($data);
	}

	/**
	*	Add email to queue
	*/
	public static function queue($data) {
		$inst = self::instance();
		return $inst->queueup($data);
	}



}