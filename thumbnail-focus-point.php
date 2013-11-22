<?php
/*
 Plugin Name: Thumbnail Focus Point
 Plugin URI: http://sideways-nyc.com
 Description: Add a focus point position to thumbnails.
 Version: 1.0.0-RC1
 Author: William Garcia http://sideways-nyc.com
 Author URI: http://sideways-nyc.com
 */

if(!class_exists('Thumbnail_Focus_Point')) {
	class Thumbnail_Focus_Point {
		
		//// Plugin Version
		const VERSION = '1.0';

		public static function init() {
			self::add_actions();
		}

		private static function add_actions() {
			// Common actions
			add_action('init', array(__CLASS__, 'register_resources'), 0);

			if(is_admin()) {
				add_action('admin_enqueue_scripts', array(__CLASS__, 'load_resources'));
			} else {
				// Frontend only actions
			}
		}

		//// Generic operation

		public static function register_resources() {

			wp_register_script('wp-thumbnail-focus-point', plugins_url('js/scripts.js', __FILE__));
			wp_register_style('wp-thumbnail-focus-point', plugins_url('css/styles.css', __FILE__));
		}

		public static function load_resources() {
			
			wp_enqueue_script('wp-thumbnail-focus-point');
			wp_enqueue_style('wp-thumbnail-focus-point');
		}

		
		public static function load_settings_page() {
			
			wp_enqueue_script('wp-thumbnail-focus-point');
			wp_enqueue_style('wp-thumbnail-focus-point');
		}

		/// Template tags

		public static function get_template_tag() {
			return '';
		}
	}
	/* Update fields */
	function add_focus_fields_save( $post, $attachment ) {
		update_post_meta( $post['ID'], 'thumb_focus_position',  $attachment['thumb_focus_position']  );
		return $post;
	}
	add_filter( 'attachment_fields_to_save', 'add_focus_fields_save', null, 2 );

	
	/* Add edit fields */
	function add_focus_fields_edit( $form_fields, $post ) {
		
		$pos = get_focus_point($post->ID);
		
		$n = 8; 
		$html = '<div class="thumbnail-focus-position">
			<p><?php _e( "Select thumbnail focus point position:", "thumb_focus_position" );?></p>
			<div class="pos-controls">';


		for($i=0; $i<=$n;$i++){
			$html .= '<div class="button button-hero'. ( $pos == $i ? ' button-primary' : '' ).'">';
			$html .= '<input type="radio" class="screen-reader-text" id="attachments-'.$post->ID.'-thumb_focus_position" name="attachments['.$post->ID.'][thumb_focus_position]" value="'.$i.'" '.($pos == $i ? 'checked' : '').' />';
			$html .= '<label for="attachments['.$post->ID.'][thumb_focus_position]" class="pi-'.$i.'">'.$i.'</label>'; 
			$html .= '</div>';
		}
		
		$html .= '</div>
		</div>';

		$form_fields['thumb_focus_position'] = array(
			'label' => __('Focus Point'),
			'value' => '',
			'html' => $html,
			'input' =>'html'
			);
		
		return $form_fields;
	}
	add_filter( 'attachment_fields_to_edit', 'add_focus_fields_edit', null, 2 );

	/* front end function will return the quadrant of where the focal point should be.. */
	function get_focus_point($attachment_id=false){
		if(!isset($attachment_id)) return false; 
		$pos = get_post_meta( $attachment_id, 'thumb_focus_position', true);
		
		if(!isset($pos) || $pos=='') $pos=4;

		return $pos;
	}

	Thumbnail_Focus_Point::init();
}
