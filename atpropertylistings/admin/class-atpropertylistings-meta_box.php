<?php

/**
 * The metabox functionality of the plugin.
 *
 * @link       N/A
 * @since      1.0.0
 *
 * @package    Atpropertylistings
 * @subpackage Atpropertylistings/admin
 * @author     Austin Turnage <propertyListing@boilerplate>

 */

class atpropertylistings_Metabox {
	private $screen = array(
		'properties',
	);
	
	private $meta_fields = array(
		array(
			'label' => 'Property Status',
			'id' => 'propertystatus_49310',
			'type' => 'radio',
			'options' => array(
				'sale',
				'rent',
			),
		),
		array(
			'label' => 'Price',
			'id' => 'price_41422',
			'type' => 'text',
		),
		array(
			'label' => 'Location',
			'id' => 'location_48861',
			'type' => 'text',
		),
		array(
			'label' => 'Date of Construction',
			'id' => 'dateofconstruct_99575',
			'type' => 'text',
		),
	);
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_fields' ) );
	}
	public function add_meta_boxes() {
		foreach ( $this->screen as $single_screen ) {
			add_meta_box(
				'atpropertylist',
				__( 'Property Listing Data', 'atpropertylist_meta' ),
				array( $this, 'meta_box_callback' ),
				$single_screen,
				'advanced',
				'high'
			);
		}
		
		// hide custom fields
		remove_meta_box( 'postcustom' , 'properties' , 'normal' );

		// Remove from edit page
		remove_meta_box('tagsdiv-PropertyStatus', 'properties','normal');
	}
	public function meta_box_callback( $post ) {
		wp_nonce_field( 'atpropertylist_data', 'atpropertylist_nonce' );
		$this->field_generator( $post );
	}
	public function field_generator( $post ) {
		$output = '';
		foreach ( $this->meta_fields as $meta_field ) {
			$label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
			$meta_value = get_post_meta( $post->ID, $meta_field['id'], true );
			if ( empty( $meta_value ) ) {
				$meta_value = $meta_field['default']; }
			switch ( $meta_field['type'] ) {
				case 'radio':
					$input = '<fieldset>';
					$input .= '<legend class="screen-reader-text">' . $meta_field['label'] . '</legend>';
					$i = 0;
					foreach ( $meta_field['options'] as $key => $value ) {
						$meta_field_value = !is_numeric( $key ) ? $key : $value;
						$input .= sprintf(
							'<label><input %s id=" % s" name="% s" type="radio" value="% s"> %s</label>%s',
							$meta_value === $meta_field_value ? 'checked' : '',
							$meta_field['id'],
							$meta_field['id'],
							$meta_field_value,
							$value,
							$i < count( $meta_field['options'] ) - 1 ? '<br>' : ''
						);
						$i++;
					}
					$input .= '</fieldset>';
					break;
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$meta_field['type'] !== 'color' ? 'style="width: 100%"' : '',
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['type'],
						$meta_value
					);
			}
			$output .= $this->format_rows( $label, $input );
		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
	}
	public function format_rows( $label, $input ) {
		return '<tr><th>'.$label.'</th><td>'.$input.'</td></tr>';
	}
	public function save_fields( $post_id ) {
		if ( ! isset( $_POST['atpropertylist_nonce'] ) )
			return $post_id;
		$nonce = $_POST['atpropertylist_nonce'];
		if ( !wp_verify_nonce( $nonce, 'atpropertylist_data' ) )
			return $post_id;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;
		foreach ( $this->meta_fields as $meta_field ) {
			if ( isset( $_POST[ $meta_field['id'] ] ) ) {
				switch ( $meta_field['type'] ) {
					case 'email':
						$_POST[ $meta_field['id'] ] = sanitize_email( $_POST[ $meta_field['id'] ] );
						break;
					case 'text':
						$_POST[ $meta_field['id'] ] = sanitize_text_field( $_POST[ $meta_field['id'] ] );
						break;
				}
				// if radio/category, update post, otherwise update meta
				if ( $meta_field['type'] === 'radio') {
					wp_set_post_terms($post_id, $_POST[ $meta_field['id'] ], 'PropertyStatus');
				} else {
					update_post_meta( $post_id, $meta_field['id'], $_POST[ $meta_field['id'] ] );
				}

			} else if ( $meta_field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, $meta_field['id'], '0' );
			} 
		}
	}

}