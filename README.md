Mailman
=======

A CodeIgniter library for using various third-party email systems.

## Why is this useful?
Many web-apps are using email platform services instead of their own server or SMTP solutions. Mailman provides a way for you to use any of these third-party libraries interchangeably.

For instance, if you reach a daily limit on AmazonSES, you can quickly and effortessly switch over to Mandrill by simply changing a configuration, instead of editing your code everywhere emails are sent.

## The nerdier reason why
This library has a generic Interface and a implementing Abstract class. Mail transport classes such as AmazonSES or Mandrill extend this abstract class and compose their related third-party libraries (amazon_ses, mandrill PHP sdk).

**Why is this cool?** This means that any code you write in your application using the Mailman library will always use the same methods no matter which email transport you use.

> If you change from Amazon to Mandrill or another third-party email system, you only need to change a configuration, rather than changes in your code.

OOP is good!

## Dependencies
This library will depend on any third-party email libraries you use.

Out of the box, Mailman uses [Amazon SES](http://aws.amazon.com/ses/), which depends on [this SES library](https://github.com/joelcox/codeigniter-amazon-ses) by Joël Cox (Not included).

### Update!
Mailman now also supports [Mandrill](http://mandrill.com/) for use as a transport! Download the [Mandrill library](https://github.com/fideloper/CI-Mandrill).

## Documentation

**Note:**

This library uses [views](http://codeigniter.com/user_guide/general/views.html) for the email bodies, in conjunction with the [CI template parser library](http://codeigniter.com/user_guide/libraries/parser.html) to populate those templates with your data.

Parameters needed for sending email:

 1. *transport* - Which email service are you using (default: AmazonSes)
 2. *to* - A valid email address, or array or email addresses
 3. *from* - A valid email address
 4. *subject* - A subject line
 5. *template_html* - Path to the view file for the HTML email
 6. *template_alt* - Path to the view file for the plain-text version of the email
 7. *template_data* - An array of data to be parsed by the [CI template parser library](http://codeigniter.com/user_guide/libraries/parser.html) and injected into the html and alternate plain text view files.

Optional Parameters, depending on transport.

1. *from* - A valid email address.
2. *cc* - A valid email address
3. *bcc* - A valid email address

Class Constants:

1. Mailmail::MAILMAN_TRANSPORT_DEFAULT		= 'AmazonSes'
2. Mailmail::MAILMAN_TRANSPORT_AMAZONSES	= 'AmazonSes'
3. Mailmail::MAILMAN_TRANSPORT_MANDRILL	= 'Mandrill'

## Usage Examples:
Methods are accessible via Static methods, or via classic CI library calls.
Here's some sample usage.

```php
//Load Library
$this->load->library('mailman');



//Classic CI implementations
$this->mailman->setTransport(Mailman::MAILMAN_TRANSPORT_AMAZONSES);

$this->mailman->sendmail(array(
	'to' => 'example@example.com',
	'from' => 'system@example.com',
	'from_name' => 'Do Not Reply',
	'cc' => 'another@example.com',
	'bcc' => 'sneaky@example.com',
	'subject' => 'Test Email',
	'template_html' => 'email/example',
	'template_alt' => 'email/example_alt',
	'template_data' => array(
	 		'headline' => 'Example Headline',
	 		'body' => 'Example Message.',
	 		'example list' => array(
	 			'List Item 1',
	 			'List Item 2',
	 			'List Item 3'
	 		)
	 	)
));

## Alternatively, chain the methods
$this->mailman->setTransport(Mailman::MAILMAN_TRANSPORT_AMAZONSES)->sendmail(array(
	'to' => 'example@example.com',
	'subject' => 'Test Email',
	'template_html' => 'email/example',
	'template_alt' => 'email/example_alt',
	'template_data' => array(
	 		'headline' => 'Example Headline',
	 		'body' => 'Example Message.',
	 		'example list' => array(
	 			'List Item 1',
	 			'List Item 2',
	 			'List Item 3'
	 		)
	 	)
));

## OR just let mailman default to AmazonSes (Current set default)
$this->mailman->sendmail(array(
	'to' => 'example@example.com',
	'subject' => 'Test Email',
	'template_html' => 'email/example',
	'template_alt' => 'email/example_alt',
	'template_data' => array(
	 		'headline' => 'Example Headline',
	 		'body' => 'Example Message.',
	 		'example list' => array(
	 			'List Item 1',
	 			'List Item 2',
	 			'List Item 3'
	 		)
	 	)
));

## OR change the default yourself and set transport to it:
$this->mailman->setTransport(Mailman::MAILMAN_TRANSPORT_DEFAULT)->sendmail(array(
	'to' => 'example@example.com',
	'subject' => 'Test Email',
	'template_html' => 'email/example',
	'template_alt' => 'email/example_alt',
	'template_data' => array(
	 		'headline' => 'Example Headline',
	 		'body' => 'Example Message.',
	 		'example list' => array(
	 			'List Item 1',
	 			'List Item 2',
	 			'List Item 3'
	 		)
	 	)
));


//Super-cool static method way
$this->mailman::instance()->setTransport(Mailman::MAILMAN_TRANSPORT_AMAZONSES);

## OR
$this->mailman::instance()->setTransport(Mailman::MAILMAN_TRANSPORT_AMAZONSES)->sendmail( ... );

## OR default to AmazonSes
$this->mailman::email(array(
	'to' => 'example@example.com',
	'subject' => 'Test Email',
	'template_html' => 'email/example',
	'template_alt' => 'email/example_alt',
	'template_data' => array(
	 		'headline' => 'Example Headline',
	 		'body' => 'Example Message.',
	 		'example list' => array(
	 			'List Item 1',
	 			'List Item 2',
	 			'List Item 3'
	 		)
	 	)
));
```

## My library does more, how do I get to it?
If the email trainsport does more, or you need extra options, there is an available function getCore() in each transport, which will give you direct access to that library. Here's an example.

```php
$this->load->library('mailman');

$this->mailman->setTransport(Mailman::MAILMAN_TRANSPORT_MANDRILL);

$mandrill = $this->mailman->getTransport()->getCore();

// Lets get a list of Mandrill webhooks we have set up, for instance
$webhooks = $mandrill->webhooks_list();

print_r($webhooks);

```

## To Do:
1. Add more libraries (Sendgrid, Postmark)
2. Support arrays for multiple to, cc, bcc fields [as email systems allow]
3. Wiki for platform-specific and extra information

## License

	The MIT License

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.