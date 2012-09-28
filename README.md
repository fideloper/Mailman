Mailman
=======

A CodeIgniter library for using various third-party email systems.

## Dependencies
This library will depend on any third-party email libraries you use. Out of the box, this uses [Amazon SES](http://aws.amazon.com/ses/), which depends on [this SES library](https://github.com/joelcox/codeigniter-amazon-ses) by Joël Cox (Not included).

An upcoming future addition to provide support for [Mandrill](http://mandrill.com/) will depend on the Mandrill library.

##Documentation

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

##Usage Examples:
Methods are accessible via Static methods, or via classic CI library calls.
Here's some sample usage.

```php
//Load Library
$this->load->library('clabMail');



//Classic CI implementations
$this->clabmail->setTransport('AmazonSes');

$this->clabmail->sendmail(array(
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

## Alternatively, chain the methods
$this->setTransport('AmazonSes')->clabmail->sendmail(array(
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

## OR just let ClabMail default to AmazonSes (Current set default)
$this->clabmail->sendmail(array(
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
ClabMail::instance()->setTransport('AmazonSes');

## OR
ClabMail::instance()->setTransport('AmazonSes')->sendmail( ... );

## OR default to AmazonSes
ClabMail::email(array(
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