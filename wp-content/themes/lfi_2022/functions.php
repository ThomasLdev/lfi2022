<?php

// ASSETS
function theme_register_assets() {

	wp_enqueue_script(
		'lfi2022_main_js',
		get_template_directory_uri() . '/assets/js/custom.js',
		array(),
		'1.0',
		true
	);

	wp_enqueue_style(
		'lfi2022_main_css',
		get_stylesheet_uri(),
		array(),
		'1.0'
	);
}

add_action( 'wp_enqueue_scripts', 'theme_register_assets' );

// FEATURES
add_theme_support( 'post-thumbnails' );
add_theme_support( 'custom-logo' );
add_post_type_support( 'page', 'excerpt' );

// ADD WOOCOMMERCE SUPPORT
function lfi_2022_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

add_action( 'after_setup_theme', 'lfi_2022_add_woocommerce_support' );

// REMOVE UNECESSARY FIELDS
function wc_remove_checkout_fields( $fields ) {
// Billing fields
	unset( $fields['billing']['billing_company'] );
// Shipping fields
	unset( $fields['shipping']['shipping_company'] );
	unset( $fields['shipping']['shipping_phone'] );
	unset( $fields['shipping']['shipping_state'] );
	unset( $fields['shipping']['shipping_first_name'] );
	unset( $fields['shipping']['shipping_last_name'] );
	unset( $fields['shipping']['shipping_address_1'] );
	unset( $fields['shipping']['shipping_address_2'] );
	unset( $fields['shipping']['shipping_city'] );
	unset( $fields['shipping']['shipping_postcode'] );
// Order fields
	unset( $fields['order']['order_comments'] );
	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'wc_remove_checkout_fields' );

// CLEAR CART BEFORE ADDING NEW PRODUCT
add_filter( 'woocommerce_add_cart_item_data', 'wdm_empty_cart', 10,  3);

function wdm_empty_cart( $cart_item_data, $product_id, $variation_id )
{

	global $woocommerce;
	$woocommerce->cart->empty_cart();

	// Do nothing with the data and return
	return $cart_item_data;
}

// ADD MAIN MENU
function lfi2022_custom_new_menu() {
	register_nav_menu('main-menu',__( 'Menu Landing Page' ));
}
add_action( 'init', 'lfi2022_custom_new_menu' );

// CUSTOM LOGO
// CUSTOM LOGO SETUP
function theme_custom_logo_setup() {
	$defaults = array(
		'flex-height' => true,
		'flex-width'  => true,
	);
}

add_action( 'after_setup_theme', 'theme_custom_logo_setup' );

function theme_login_logo() {
	wp_enqueue_style(
		'custom-login',
		get_template_directory_uri() . '/assets/css/custom-login.css',
		array( 'login' )
	);
}

add_action( 'login_enqueue_scripts', 'theme_login_logo' );

// CHANGE PAY BUTTON



function lfi2022_change_checkout_button_text( $button_text ) {

	return 'Faire un don'; // Replace this text in quotes with your respective custom button text

}

add_filter( 'woocommerce_order_button_text', 'lfi2022_change_checkout_button_text' );

// REPLACE SOME WORDINGS

function custom_wc_translations($translated){
	$text = array(
		'Produit' => 'Votre choix',
	);
	$translated = str_ireplace(  array_keys($text),  $text,  $translated );
	return $translated;
}

add_filter( 'gettext', 'custom_wc_translations', 20 );

// ADD LEGAL CHECKBOX

add_action( 'woocommerce_review_order_before_submit', 'bt_add_checkout_checkbox', 10 );
/**
 * Add WooCommerce additional Checkbox checkout field
 */
function bt_add_checkout_checkbox() {

	woocommerce_form_field( 'checkout_checkbox', array( // CSS ID
		'type'          => 'checkbox',
		'class'         => array('form-row lega-checkbox'), // CSS Class
		'label_class'   => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
		'input_class'   => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
		'required'      => true, // Mandatory or Optional
		'label'         => "Je certifie sur l'honneur être une personne physique de nationalité française ou résidant en France, et que le règlement de mon don ne provient pas d'une personne morale (association, société, société civile...) mais de mon compte bancaire personnel.", // Label and Link
	));
}

add_action( 'woocommerce_checkout_process', 'bt_add_checkout_checkbox_warning' );
/**
 * Alert if checkbox not checked
 */
function bt_add_checkout_checkbox_warning() {
	if ( ! (int) isset( $_POST['checkout_checkbox'] ) ) {
		wc_add_notice( __( 'Veuillez accepter les conditions de don' ), 'error' );
	}
}
