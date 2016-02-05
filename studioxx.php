<?php
/*
Plugin Name: StudioXX Site Plugin
Description: 
Version: 00.1
Text Domain: studioxx
Domain Path: 
Author: H. Kurth Bemis for StudioXX
Author URI: https://kurthbemis.com
License: GPL3
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class StudioXXCustom {
    public function __construct() {
		add_action( 'init', array($this, 'sxx_post_types_init') );
		add_action( 'init', array($this, 'sxx_taxonomy_init') );
		add_action( 'save_post', array($this, 'sxx_save_post'), 10, 3 );

		if ( is_admin() ) {
			add_action( 'admin_menu', array($this, 'sxx_add_matricule_meta_box') );
			add_action( 'admin_menu', array($this, 'sxx_remove_meta_boxes') );
		}

    }

	public function sxx_post_types_init() {
		register_post_type( 'event',
			array(
				'labels' => array(
					'name' => __( 'Events' ),
					'singular_name' => __( 'Event' ),
					'add_new' => __( 'Add Event' ),
					'add_new_item' => __( 'Add New Event' ),
					'edit_item' => __( 'Edit Event' ),
					'new_item' => __( 'Add New Event' ),
					'view_item' => __( 'View Event' ),
					'search_items' => __( 'Search Events' ),
					'not_found' => __( 'No events found' ),
					'not_found_in_trash' => __( 'No events found in trash' )
				),
				'public' => true,
				'supports' => array( 'title', 'editor', 'thumbnail', 'comments' ),
				'capability_type' => 'post',
				'rewrite' => array("slug" => "event"), // Permalinks format
				'menu_position' => 10,
				'register_meta_box_cb' => 'add_events_metaboxes',
				'taxonomies' => array('category', 'post_tag', 'years', 'matricules'),
				// 'show_ui' => TRUE,
				// 'show_in_menu' => TRUE,
				// 'show_in_nav_menus' => TRUE,
			)
		);
		register_post_type( 'workshop',
			array(
				'labels' => array(
					'name' => __( 'Workshops' ),
					'singular_name' => __( 'Workshops' ),
					'add_new' => __( 'Add Workshop' ),
					'add_new_item' => __( 'Add New Workshop' ),
					'edit_item' => __( 'Edit Workshop' ),
					'new_item' => __( 'Add New Workshop' ),
					'view_item' => __( 'View Workshop' ),
					'search_items' => __( 'Search Workshops' ),
					'not_found' => __( 'No items found' ),
					'not_found_in_trash' => __( 'No workshops found in trash' )
				),
				'public' => true,
				'supports' => array( 'title', 'editor', 'thumbnail', 'comments' ),
				'capability_type' => 'post',
				'rewrite' => array("slug" => "workshops"), // Permalinks format
				'menu_position' => 11,
				'taxonomies' => array('category', 'post_tag', 'years')
				// 'register_meta_box_cb' => 'add_events_metaboxes'
			)
		);
		register_post_type( 'education',
			array(
				'labels' => array(
					'name' => __( 'Education' ),
					'singular_name' => __( 'Education' ),
					'add_new' => __( 'Add Education' ),
					'add_new_item' => __( 'Add New Education' ),
					'edit_item' => __( 'Edit Education' ),
					'new_item' => __( 'Add New Education' ),
					'view_item' => __( 'View Education' ),
					'search_items' => __( 'Search Education' ),
					'not_found' => __( 'No education found' ),
					'not_found_in_trash' => __( 'No education found in trash' )
				),
				'public' => true,
				'supports' => array( 'title', 'editor', 'thumbnail', 'comments' ),
				'capability_type' => 'post',
				'rewrite' => array("slug" => "education"), // Permalinks format
				'menu_position' => 12,
				'taxonomies' => array('category', 'post_tag', 'years')
				// 'register_meta_box_cb' => 'add_events_metaboxes'
			)
		);
		register_post_type( 'residency',
			array(
				'labels' => array(
					'name' => __( 'Residencies' ),
					'singular_name' => __( 'Residency' ),
					'add_new' => __( 'Add Residency' ),
					'add_new_item' => __( 'Add New Residency' ),
					'edit_item' => __( 'Edit Residency' ),
					'new_item' => __( 'Add New Residency' ),
					'view_item' => __( 'View Residency' ),
					'search_items' => __( 'Search Residencies' ),
					'not_found' => __( 'No residencies found' ),
					'not_found_in_trash' => __( 'No residencies found in trash' )
				),
				'public' => true,
				'supports' => array( 'title', 'editor', 'thumbnail', 'comments' ),
				'capability_type' => 'post',
				'rewrite' => array("slug" => "residencies"), // Permalinks format
				'menu_position' => 13,
				'taxonomies' => array('category', 'post_tag', 'years', 'matricules')
				// 'register_meta_box_cb' => 'add_events_metaboxes'
			)
		);
		register_post_type( 'participant',
			array(
				'labels' => array(
					'name' => __( 'Participants' ),
					'singular_name' => __( 'Participants' ),
					'add_new' => __( 'Add Participant' ),
					'add_new_item' => __( 'Add New Participant' ),
					'edit_item' => __( 'Edit Participant' ),
					'new_item' => __( 'Add New Participant' ),
					'view_item' => __( 'View Participant' ),
					'search_items' => __( 'Search Participants' ),
					'not_found' => __( 'No participants found' ),
					'not_found_in_trash' => __( 'No participants found in trash' )
				),
				'public' => true,
				'supports' => array( 'title', 'editor', 'thumbnail', 'comments' ),
				'capability_type' => 'post',
				'rewrite' => array("slug" => "participants"),
				'menu_position' => 14,
				'taxonomies' => array('category', 'post_tag', 'years', 'matricules')
				// 'register_meta_box_cb' => 'add_events_metaboxes'
			)
		);
	}

	public function sxx_taxonomy_init() {
		register_taxonomy( 'year', array( 'event', 'workshop', 'residency' ), array(
				'labels' => array(
					'name' => _x( 'Years', 'Years', 'studioxx' ),
					'singular_name' => _x( 'Year', 'Taxonomy Singular Name', 'studioxx' ),
					'menu_name' => __( 'Years', 'studioxx' ),
					'all_items' => __( 'All Years', 'studioxx' ),
					'new_item_name' => __( 'New Year', 'studioxx' ),
					'add_new_item' => __( 'Add New Year', 'studioxx' ),
					'edit_item' => __( 'Edit Year', 'studioxx' ),
					'update_item' => __( 'Update Year', 'studioxx' ),
					'view_item' => __( 'View Year', 'studioxx' ),
				),
				'hierarchical'               => false,
				'public'                     => true,
				'show_ui'                    => true,
				'show_admin_column'          => true,
				'show_in_nav_menus'          => false,
				'show_tagcloud'              => false,
			)
		);
		register_taxonomy( 'matricules', array( 'post', 'event', 'workshop' ), array(
				'labels' => array(
					'name' => _x( 'Matricules', 'Matricules', 'studioxx' ),
					'singular_name' => _x( 'Matricule', 'Taxonomy Singular Name', 'studioxx' ),
					'menu_name' => __( 'Matricules', 'studioxx' ),
					'all_items' => __( 'All Matricules', 'studioxx' ),
					'new_item_name' => __( 'New Matricule', 'studioxx' ),
					'add_new_item' => __( 'Add New Matricule', 'studioxx' ),
					'edit_item' => __( 'Edit Matricule', 'studioxx' ),
					'update_item' => __( 'Update Matricule', 'studioxx' ),
					'view_item' => __( 'View Matricule', 'studioxx' ),
				),
				'hierarchical'               => false,
				'public'                     => true,
				'show_ui'                    => true,
				'show_admin_column'          => false,
				'show_in_nav_menus'          => true,
				'show_tagcloud'              => false,
			)
		);
		register_taxonomy( 'participants', array( 'post', 'event', 'workshop', 'residency','participant' ), array(
				'labels' => array(
					'name' => _x( 'Participants', 'Participants', 'studioxx' ),
					'singular_name' => _x( 'Participant', 'Taxonomy Singular Name', 'studioxx' ),
					'menu_name' => __( 'Participants', 'studioxx' ),
					'all_items' => __( 'All Participant', 'studioxx' ),
					'new_item_name' => __( 'New Participant', 'studioxx' ),
					'add_new_item' => __( 'Add New Participant', 'studioxx' ),
					'edit_item' => __( 'Edit Participant', 'studioxx' ),
					'update_item' => __( 'Update Participant', 'studioxx' ),
					'view_item' => __( 'View Participant', 'studioxx' ),
				),
				'hierarchical'               => false,
				'public'                     => true,
				'show_ui'                    => true,
				'show_admin_column'          => false,
				'show_in_nav_menus'          => true,
				'show_tagcloud'              => false,
			)
		);
	}

	public function sxx_add_matricule_meta_box() {
	    add_meta_box('matricule_meta', __('Matricule'), array($this, 'sxx_matricule_meta_box'), 'event', 'side', 'core');
	    add_meta_box('years_meta', __('Year'), array($this, 'sxx_year_meta_box'), 'event', 'side', 'core');
	}   

	public function sxx_year_meta_box($post) {
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-datepicker');
		echo '<div class="wrap">';
	    echo '<input type="text" width="100%" class="datepicker" name="datepicker" value=""/>';
	    echo '</div>';
	    echo '<script>
	    jQuery(function() {
	        jQuery(".datepicker").datepicker({
	            dateFormat : "dd-mm-yy"
	        });
	    });
	    </script>'; 
	}

	public function sxx_matricule_meta_box($post) {

	}

	public function sxx_remove_meta_boxes() {
		remove_meta_box('tagsdiv-year', 'event', 'side' );
		remove_meta_box('tagsdiv-matricules', 'event', 'side' );
	}

	public function sxx_save_post( $postId, $post, $update ) {
		if ( $post->post_type == 'participant') {
			wp_insert_term(
				$post->post_title,
				'participants',
				array(
					'description'=> $post->post_excerpt,
					'slug'=> $post->post_name,
				)
			);
		}
	}
}
 
$sxxCustom = new StudioXXCustom();