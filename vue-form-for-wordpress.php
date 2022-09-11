<?php
/*
Plugin Name: Vue Form For Wordpress
Plugin URI:		http://wordpress.org/
Description: 	Vue 3 Form with VeeValidate and Axios for Wordpress.
Version: 		1.0.0
Author: 	 Author Name
Author URI: 	http://wordpress.org/
Text Domain: 	vue-form-for-wp
License: 		GPLv2 or later
License URI:	http://www.gnu.org/licenses/gpl-2.0.html

*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class vueFormForWP(){

public function contactFormApiEndpoint(){
	register_rest_route( 'send-contact-form/v1', '/contact/', [
		'methods' => 'POST',
		'callback' => 'send_contact_form',
		//'nonce' => check_ajax_referer( 'contact-form-nonce' , 'security' ),
	]);
}
public function __constract(){
  add_action( 'wp_enqueue_scripts', array($this, 'enqueue_vueCF_scripts'));
 add_action('rest_api_init',array( $this, 'contactFormApiEndpoint'));

}

public function enqueue_vueCF_scripts() {
 wp_enqueue_script( 'vue-main', get_stylesheet_directory_uri() . '/dist/js/app.js', array(), _S_VERSION, true );
 wp_enqueue_script( 'vue-chunk', get_stylesheet_directory_uri() . '/dist/js/chunk-vendors.js', array(), _S_VERSION, true );
    wp_localize_script( 'vue-main', 'vue_main_object',
        array(
		   'siteurl' => esc_url_raw( rest_url()),
           'ajaxurl' => admin_url( 'admin-ajax.php' ),
	        'nonce' => wp_create_nonce( 'vue-nonce'),
       )
   );
add_action( 'wp_ajax_send_contact_form', 'send_contact_form' );
add_action( 'wp_ajax_nopriv_send_contact_form', 'send_contact_form' );
}
  
public function send_contact_form(WP_REST_Request $request){
if ( ! wp_verify_nonce( $request['nonce'], 'vue-nonce' ) ) {
return  false;
}

	$full_name = sanitize_text_field(trim($request['full_name']));
	$email = sanitize_email(trim($request['email']));
	$body = wp_kses_post(trim($request['body']));
	$site_url = esc_url(site_url());
	$errors = [];
	if(empty($full_name)){
		$errors[] = 'Name is required';
	}
	if(empty($email) || ! filter_var ($email, FILTER_VALIDATE_EMAIL)){
		$errors[] = 'Valid Email is required';
	}
	/*if(empty($body)){
		$errors[] = 'Message is required';
	}*/
	if(count($errors)){
		return new WP_Error('contact_form_errors', $errors, [ 'status' => 422]);
	}
	
	$message = "Full name: {$full_name}, <br> From: {$email}, <br> Message: $body";
	$customerMessage = '<p style="direction:rtl;">היי, '.$full_name.'!  תודה שפנית אלינו :)<br> אנו נדאג לחזור אליך בהקדם האפשרי..ועד אז שיהיה המשך יום מדהים. <br><a href="'.esc_url(site_url()).'">SPITE</a></p>';
	$headers = array("Content-Type: text/html; charset=UTF-8");
	wp_mail('meidanmuzrafi@gmail.com', 'contact form',$message,$headers);
	wp_mail($email, 'פנייתך התקבלה',$customerMessage,$headers);
	return "success";
}
  
}
