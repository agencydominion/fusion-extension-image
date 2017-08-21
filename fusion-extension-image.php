<?php
/**
 * @package Fusion_Extension_Image
 */
 
/**
 * Plugin Name: Fusion : Extension - Image
 * Plugin URI: http://www.agencydominion.com/fusion/
 * Description: Image Extension Package for Fusion.
 * Version: 1.1.7
 * Author: Agency Dominion
 * Author URI: http://agencydominion.com
 * Text Domain: fusion-extension-image
 * Domain Path: /languages/
 * License: GPL2
 */
 
/**
 * FusionExtensionImage class.
 *
 * Class for initializing an instance of the Fusion Image Extension.
 *
 * @since 1.0.0
 */

class FusionExtensionImage	{ 
	public function __construct() {
						
		// Initialize the language files
		add_action('plugins_loaded', array($this, 'load_textdomain'));
		
		// Enqueue front end scripts and styles
		add_action('wp_enqueue_scripts', array($this, 'front_enqueue_scripts_styles'));
		
	}
	
	/**
	 * Load Textdomain
	 *
	 * @since 1.1.7
	 *
	 */
	 
	public function load_textdomain() {
		load_plugin_textdomain( 'fusion-extension-image', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Enqueue JavaScript and CSS on Front End pages.
	 *
	 * @since 1.0.0
	 *
	 */
	 
	public function front_enqueue_scripts_styles() {
		//plugin
		wp_enqueue_style( 'fsn_image', plugin_dir_url( __FILE__ ) . 'includes/css/fusion-extension-image.css', false, '1.0.0' );
	}

}

$fsn_extension_image = new FusionExtensionImage();

//EXTENSIONS

//Image
require_once('includes/extensions/image.php');

?>