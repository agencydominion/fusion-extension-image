<?php
/**
 * @package Fusion_Extension_Image
 */
 
/**
 * Plugin Name: Fusion : Extension - Image
 * Plugin URI: http://fusion.1867dev.com
 * Description: Image Extension Package for Fusion.
 * Version: 1.1.1
 * Author: Agency Dominion
 * Author URI: http://agencydominion.com
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
		load_plugin_textdomain( 'fusion-extension-image', false, plugin_dir_url( __FILE__ ) . 'languages' );
		
		// Enqueue front end scripts and styles
		add_action('wp_enqueue_scripts', array($this, 'front_enqueue_scripts_styles'));
		
	}

	/**
	 * Enqueue JavaScript and CSS on Front End pages.
	 *
	 * @since 1.0.0
	 *
	 */
	 
	 public function front_enqueue_scripts_styles() {
		//plugin
		global $post;
		if (has_shortcode($post->post_content, 'fsn_image')) {
			wp_enqueue_style( 'fsn_image', plugin_dir_url( __FILE__ ) . 'includes/css/fusion-extension-image.css', false, '1.0.0' );
		}
	}

}

$fsn_extension_image = new FusionExtensionImage();

//EXTENSIONS

//Image
require_once('includes/extensions/image.php');

?>