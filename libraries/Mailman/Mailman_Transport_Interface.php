<?php defined('BASEPATH') OR exit('No direct script access allowed');

interface Mailman_Transport_Interface {

	//Mail-info functions needed (Left out: CC, BCC)
	public function to($to);

	public function from($from);

	public function from_name($from_name);

	public function cc($cc);

	public function bcc($bcc);

	public function subject($subject);

	public function message($message);

	public function message_alt($message_alt);

	//Send the email
	public function send();

	//Get the library this class will override
	public function getCore();

	//Provide a logging mechanism
	public function log($level, $message);
}
