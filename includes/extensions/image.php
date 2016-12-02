<?php
/**
 * @package Fusion_Extension_Image
 */

/**
 * Image Fusion Extension.
 *
 * Function for adding an Image element to the Fusion Engine
 *
 * @since 1.0.0
 */

/**
 * Map Shortcode
 */

add_action('init', 'fsn_init_image', 12);
function fsn_init_image() {
	
	if (function_exists('fsn_map')) {
						
		$image_sizes_array = fsn_get_image_sizes();
		
		$image_styles_array = array(
			'img-default' => __('Default', 'fusion-extension-image'),
			'img-rounded' => __('Rounded', 'fusion-extension-image'),
			'img-circle' => __('Circle', 'fusion-extension-image'),
			'img-thumbnail' => __('Thumbnail', 'fusion-extension-image'),
		);
		$image_styles_array = apply_filters('fsn_image_style_options', $image_styles_array);
		
		$image_params = array(				
			array(
				'type' => 'image',
				'param_name' => 'image_id',
				'label' => __('Image', 'fusion-extension-image'),
				'section' => 'general'
			),							
			array(
				'type' => 'select',
				'options' => $image_sizes_array,
				'param_name' => 'image_size',
				'label' => __('Image Size', 'fusion-extension-image'),
				'help' => __('Default is "Medium".', 'fusion-extension-image'),
				'section' => 'general'
			),
			array(
				'type' => 'button',
				'param_name' => 'image_button',
				'label' => __('Button', 'fusion-extension-image'),
				'help' => __('Link to external or internal content.', 'fusion-extension-image'),
				'section' => 'general'
			),
			array(
				'type' => 'checkbox',
				'param_name' => 'image_2x',
				'label' => __('High Resolution Image', 'fusion-extension-image'),
				'help' => __('Check to output image at 2x resolution. Use on logos and icons to make images high resolution display-ready. Dimensions will be half the size of the uploaded image.', 'fusion-extension-image'),
				'section' => 'style'
			),
			array(
				'type' => 'select',
				'options' => array(
					'align-none' => __('None', 'fusion-extension-image'),
					'align-left' => __('Left', 'fusion-extension-image'),
					'align-center' => __('Center', 'fusion-extension-image'),
					'align-right' => __('Right', 'fusion-extension-image'),
				),
				'param_name' => 'image_align',
				'label' => __('Image Alignment', 'fusion-extension-image'),
				'section' => 'style'
			),
			array(
				'type' => 'select',
				'options' => $image_styles_array,
				'param_name' => 'image_style',
				'label' => __('Image Style', 'fusion-extension-image'),
				'section' => 'style'
			),
			array(
				'type' => 'checkbox',
				'param_name' => 'enable_kenburns',
				'label' => __('Ken Burns Effect', 'fusion-extension-image'),
				'section' => 'animation'
			)		
		);
		
		//filter image params
		$image_params = apply_filters('fsn_image_params', $image_params);
		
		fsn_map(array(
			'name' => __('Image', 'fusion-extension-image'),
			'shortcode_tag' => 'fsn_image',
			'description' => __('Add image. Use the options below to upload or select an image from the Media Library and a basic image size. Among other options, choose the "Advanced" tab above to choose style, and alignment.', 'fusion-extension-image'),
			'icon' => 'insert_photo',
			'disable_style_params' => array('text_align','text_align_xs','font_size','color'),
			'params' => $image_params
		));
	}
}

/**
 * Output Shortcode
 */

function fsn_image_shortcode( $atts, $content ) {		
	extract( shortcode_atts( array(							
		'image_id' => '',				
		'image_size' => 'medium',
		'enable_kenburns' => '',
		'image_2x' => '',
		'image_style' => 'img-default',
		'image_align' => 'align-none',
		'image_button' => ''
	), $atts ) );
	
	/**
	 * Enqueue Scripts
	 */
	 
	//plugin
	wp_enqueue_script('fsn_image');
	
	$output = '';
	
	if (!empty($image_id)) {				
		//get image
		$attachment_attrs = wp_get_attachment_image_src( $image_id, $image_size );
		$attachment_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
		
		//image classes
		$image_classes_array = array();
		if (empty($image_2x)) {
			$image_classes_array[] = 'img-responsive';
		}
		if (!empty($image_style)) {
			$image_classes_array[] = $image_style;
		}
		if (!empty($image_classes_array)) {
			$image_classes = implode(' ', $image_classes_array);
		}
		
		$image = '<img src="'. esc_url($attachment_attrs[0]) .'" width="'. (!empty($image_2x) ? round(intval($attachment_attrs[1])/2, 0, PHP_ROUND_HALF_DOWN) : $attachment_attrs[1]) .'" height="'. (!empty($image_2x) ? round(intval($attachment_attrs[2])/2, 0, PHP_ROUND_HALF_DOWN) : $attachment_attrs[2]) .'" alt="'. esc_attr($attachment_alt) .'"'. (!empty($image_classes) ? ' class="'. esc_attr($image_classes) .'"' : '') .'>';
		
		//build classes
		$classes_array = array();
		
		//filter for adding classes
		$classes_array = apply_filters('fsn_image_classes', $classes_array, $atts);
		if (!empty($classes_array)) {
			$classes = implode(' ', $classes_array);
		}
		
		$output .= '<div class="fsn-image '. fsn_style_params_class($atts) . (!empty($image_align) ? ' '. esc_attr($image_align) : ''). (!empty($enable_kenburns) ? ' kenburns' : '') . (!empty($classes) ? ' '. esc_attr($classes) : '') .'">';
		
			if (!empty($image_button)) {
				//get button
				$button_object = fsn_get_button_object($image_button);
				$output .= '<a'.fsn_get_button_anchor_attributes($button_object, 'image-button') .'>';
			}
			
			//action executed before the image output
			ob_start();
			do_action('fsn_before_image', $atts);
			$output .= ob_get_clean();
			
			//output image
			$output .= $image;
			
			//action executed after the image output
			ob_start();
			do_action('fsn_after_image', $atts);
			$output .= ob_get_clean();
			
			if (!empty($image_button)) {
				$output .= '</a>';
			}	
		$output .= '</div>';				
	}

	return $output;
}
add_shortcode('fsn_image', 'fsn_image_shortcode');

?>