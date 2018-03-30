<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       N/A
 * @since      1.0.0
 *
 * @package    Atpropertylistings
 * @subpackage Atpropertylistings/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Atpropertylistings
 * @subpackage Atpropertylistings/admin
 * @author     Austin Turnage <propertyListing@boilerplate>
 */
class Atpropertylistings_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Atpropertylistings_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Atpropertylistings_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/atpropertylistings-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Atpropertylistings_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Atpropertylistings_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/atpropertylistings-admin.js', array( 'jquery' ), $this->version, false );

	}

    public function create_properties_post_type() {
        $slug = "properties";
        $supports = array(
            "title", 
            "editor", 
            "thumbnail", 
            "custom-fields"
        );

        $labels = array(
            "name" => __( 'Properties', 'atpropertylistings'),
            "singular_name" => __( 'Property', 'atpropertylistings'),
            "menu_name" => __( 'Properties', 'atpropertylistings'),
            "all_items" => __( 'Properties', 'atpropertylistings'),
            "add_new" => __( 'Add New Property', 'atpropertylistings'),
            "add_new_item" => __( 'Add New Property', 'atpropertylistings'),
            "edit_item" => __( 'Edit Property', 'atpropertylistings'),
            "new_item" => __( 'New Property', 'atpropertylistings'),
            "view_item" => __( 'View Property', 'atpropertylistings'),
            "view_items" => __( 'View Properties', 'atpropertylistings'),
            "search_items" => __( 'Search Properties', 'atpropertylistings'),
        );
        $args = array(
            "label" => __( 'Properties'),
            "labels" => $labels,
            "description" => "",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => false,
            "rest_base" => "",
            "has_archive" => false,
            "show_in_menu" => true,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "rewrite" => array( "slug" => $slug, "with_front" => true ),
            "query_var" => true,
            "menu_position" => 5,
            "supports" => $supports,
            'menu_icon' => 'dashicons-pressthis',
        );
        register_post_type( $slug, $args );
    }

    public function set_custom_columns($columns) {
	    $columns['price'] = __( 'Price', 'atpropertylistings' );
	    $columns['construction_date'] = __( 'Construction Date', 'atpropertylistings' );
	    $columns['location'] = __( 'Location', 'atpropertylistings' );
	    return $columns;
	}
	
	public function get_custom_columns( $column, $post_id ) {
	    switch ( $column ) {
	        case 'price':
	        	echo get_post_meta( $post_id , 'price_41422' , true ); 
	        break;
	        case 'location':
	        	echo get_post_meta( $post_id , 'location_48861' , true ); 
	        break;
	        case 'construction_date':
	        	echo get_post_meta( $post_id , 'dateofconstruct_99575' , true ); 
	        break;
	    }
	}

	public function create_properties_taxonomy() {

		$labels = array(
			'name'                       => _x( 'Property Status', 'Property Status', 'PropertyStatus' ),
			'singular_name'              => _x( 'PropertyStatus', 'Property Status', 'PropertyStatus' ),
			'menu_name'                  => __( 'Property Status', 'PropertyStatus' ),
			'all_items'                  => __( 'All Items', 'PropertyStatus' ),
			'parent_item'                => __( 'Parent Item', 'PropertyStatus' ),
			'parent_item_colon'          => __( 'Parent Item:', 'PropertyStatus' ),
			'new_item_name'              => __( 'New Item Name', 'PropertyStatus' ),
			'add_new_item'               => __( 'Add New Item', 'PropertyStatus' ),
			'edit_item'                  => __( 'Edit Item', 'PropertyStatus' ),
			'update_item'                => __( 'Update Item', 'PropertyStatus' ),
			'view_item'                  => __( 'View Item', 'PropertyStatus' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'PropertyStatus' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'PropertyStatus' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'PropertyStatus' ),
			'popular_items'              => __( 'Popular Items', 'PropertyStatus' ),
			'search_items'               => __( 'Search Items', 'PropertyStatus' ),
			'not_found'                  => __( 'Not Found', 'PropertyStatus' ),
			'no_terms'                   => __( 'No items', 'PropertyStatus' ),
			'items_list'                 => __( 'Items list', 'PropertyStatus' ),
			'items_list_navigation'      => __( 'Items list navigation', 'PropertyStatus' ),
		);

		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => false,
			'show_in_rest'               => false,
			'meta_box_cb' => 'post_categories_meta_box',
		);

		register_taxonomy( 'PropertyStatus', "properties", $args );
		wp_insert_term('sale', 'PropertyStatus', array('slug' => 'sale'));
		wp_insert_term('rent', 'PropertyStatus', array('slug' => 'rent'));
	}

}
