<?php
/**
 * Engage Forms - starter template
 *
 * @package   Engage_Forms
 * @author    Adrian <Adrian@EngageWP.com>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 David Cramer
 */

return array (
	'name' => __( 'Contact Form', 'engage-forms' ),
	'description' => __( 'Basic starter contact form with auto-responder processor.', 'engage-forms' ),
	'db_support' => '1',
	'hide_form' => '1',
	'success' => __( 'Form has been successfully submitted. Thank you.', 'engage-forms' ),
	'avatar_field' => 'fld_6009157',
	'form_ajax' => '1',
	'layout_grid' => 
	array (
		'fields' => 
		array (
			'fld_29462' => '1:1',
			'fld_8768091' => '2:1',
			'fld_9970286' => '2:2',
			'fld_6009157' => '2:3',
			'fld_2758980' => '3:1',
			'fld_7683514' => '4:1',
			'fld_7908577' => '5:1',
		),
		'structure' => '12|4:4:4|12|12|12',
	),
	'fields' => 
	array (
		'fld_29462' => 
		array (
			'ID' => 'fld_29462',
			'type' => 'html',
			'label' => 'header',
			'slug' => 'header',
			'caption' => '',
			'config' => 
			array (
				'custom_class' => '',
				'default' => '<h2>'.__( 'Your Details', 'engage-forms' ).'</h2>
<p>'.__( 'Let us know how to get back to you.', 'engage-forms' ).'</p>
<hr>',
			),
			'conditions' => 
			array (
				'type' => '',
			),
		),
		'fld_8768091' => 
		array (
			'ID' => 'fld_8768091',
			'type' => 'text',
			'label' => __( 'First Name', 'engage-forms' ),
			'slug' => 'first_name',
			'required' => '1',
			'caption' => '',
			'config' => 
			array (
				'custom_class' => '',
				'placeholder' => '',
				'default' => '',
				'mask' => '',
			),
			'conditions' => 
			array (
				'type' => '',
			),
		),
		'fld_9970286' => 
		array (
			'ID' => 'fld_9970286',
			'type' => 'text',
			'label' => __( 'Last Name', 'engage-forms' ),
			'slug' => 'last_name',
			'required' => '1',
			'caption' => '',
			'config' => 
			array (
				'custom_class' => '',
				'placeholder' => '',
				'default' => '',
				'mask' => '',
			),
			'conditions' => 
			array (
				'type' => '',
			),
		),
		'fld_6009157' => 
		array (
			'ID' => 'fld_6009157',
			'type' => 'email',
			'label' => __( 'Email Address', 'engage-forms' ),
			'slug' => 'email_address',
			'required' => '1',
			'caption' => '',
			'config' => 
			array (
				'custom_class' => '',
				'placeholder' => '',
				'default' => '',
			),
			'conditions' => 
			array (
				'type' => '',
			),
		),
		'fld_2758980' => 
		array (
			'ID' => 'fld_2758980',
			'type' => 'html',
			'label' => __( 'Message', 'engage-forms' ),
			'slug' => 'message',
			'caption' => '',
			'config' => 
			array (
				'custom_class' => '',
				'default' => '<h2>'.__( 'How can we help?', 'engage-forms' ).'</h2>
<p>'.__( 'Feel free to ask a question or simply leave a comment.', 'engage-forms' ).'</p>
<hr>',
			),
			'conditions' => 
			array (
				'type' => '',
			),
		),
		'fld_7683514' => 
		array (
			'ID' => 'fld_7683514',
			'type' => 'paragraph',
			'label' => __( 'Comments / Questions', 'engage-forms' ),
			'slug' => 'comments_questions',
			'required' => '1',
			'caption' => '',
			'config' => 
			array (
				'custom_class' => '',
				'placeholder' => '',
				'rows' => '7',
				'default' => '',
			),
			'conditions' => 
			array (
				'type' => '',
			),
		),
		'fld_7908577' => 
		array (
			'ID' => 'fld_7908577',
			'type' => 'button',
			'label' => __( 'Send Message', 'engage-forms' ),
			'slug' => 'submit',
			'caption' => '',
			'config' => 
			array (
				'custom_class' => '',
				'type' => 'submit',
				'class' => 'btn btn-default',
			),
			'conditions' => 
			array (
				'type' => '',
			),
		),
	),
	'page_names' => 
	array (
		0 => 'Page 1',
	),
	'processors' => 
	array (
		'fp_17689566' => 
		array (
			'ID' => 'fp_17689566',
			'type' => 'auto_responder',
			'config' => 
			array (
				'sender_name' => get_option( 'blogname' ),
				'sender_email' => get_option( 'admin_email' ),
				'subject' => __( 'Thank you for contacting us', 'engage-forms' ),
				'recipient_name' => '%first_name% %last_name%',
				'recipient_email' => '%email_address%',
				'message' => 'Hi %recipient_name%.
Thanks for your email.
We\'ll get back to you as soon as possible!
Here\'s a summary of your message:
------------------------
{summary}',
			),
			'conditions' => 
			array (
				'type' => '',
			),
		),
	),
	'settings' => 
	array (
		'responsive' => 
		array (
			'break_point' => 'sm',
		),
	),
	'mailer' => 
	array (
		'enable_mailer' => '1',
		'sender_name' => 'Contact Form Submission',
		'sender_email' => '%email_address',
		'email_type' => 'html',
		'recipients' => get_option( 'admin_email' ),
		'email_subject' => __( 'Contact Form', 'engage-forms' ),
		'email_message' => '{summary}',
	),
);
