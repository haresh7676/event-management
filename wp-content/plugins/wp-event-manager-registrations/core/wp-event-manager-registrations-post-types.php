<?php
/**
 * WP_Event_Manager_Registrations_Post_Types class.
 */
class WP_Event_Manager_Registrations_Post_Types {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'event_already_registered_title', array( $this, 'already_registered_title' ));
		add_action( 'init', array( $this, 'register_post_types' ), 20 );
		if ( get_option( 'event_registration_delete_with_event', 0 ) ) {
			add_action( 'delete_post', array( $this, 'delete_post' ), 10 );
		}
		add_action( 'event_registrations_purge', array( $this, 'event_registrations_purge' ) );
		add_action( 'transition_post_status', array( $this, 'transition_post_status' ), 10, 3 );
	}

	public function already_registered_title() {
	    global $post;
	   $post_id = $post->ID;
		if ( $post_id && 'event_listing' === get_post_type( $post_id ) && ! is_single() && empty( $_GET['download-csv'] ) && user_has_registered_for_event( get_current_user_id(), $post_id ) ) {
		    echo '<div class="boxes-view-registered">'.__( 'Registered', 'wp-event-manager-registrations' ).'</div>';   
		}
	}

	/**
	 * register_post_types function.
	 */
	public function register_post_types() {
		if ( post_type_exists( "event_registration" ) ) {
			return;
		}

		$plural   = __( 'Event Registrations', 'wp-event-manager-registrations' );
		$singular = __( 'Registration', 'wp-event-manager-registrations' );

		register_post_type( "event_registration",
			apply_filters( "register_post_type_event_registration", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => $plural,
					'all_items'             => sprintf( __( 'All %s', 'wp-event-manager-registrations' ), $plural ),
					'add_new' 				=> __( 'Add New', 'wp-event-manager-registrations' ),
					'add_new_item' 			=> sprintf( __( 'Add %s', 'wp-event-manager-registrations' ), $singular ),
					'edit' 					=> __( 'Edit', 'wp-event-manager-registrations' ),
					'edit_item' 			=> sprintf( __( 'Edit %s', 'wp-event-manager-registrations' ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', 'wp-event-manager-registrations' ), $singular ),
					'view' 					=> sprintf( __( 'View %s', 'wp-event-manager-registrations' ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', 'wp-event-manager-registrations' ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', 'wp-event-manager-registrations' ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', 'wp-event-manager-registrations' ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'wp-event-manager-registrations' ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', 'wp-event-manager-registrations' ), $singular )
				),
				'description'         => __( 'This is where you can edit and view registrations.', 'wp-event-manager-registrations' ),
				'public'              => false,
				'show_ui'             => true,
				'capability_type'     => 'event_registration',
				'map_meta_cap'        => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'hierarchical'        => false,
				'rewrite'             => false,
				'query_var'           => false,
				'supports'            => array( 'title', 'custom-fields', 'editor' ),
				'has_archive'         => false,
				'show_in_nav_menus'   => false
			) )
		);

		$applicaton_statuses = get_event_registration_statuses();

		foreach ( $applicaton_statuses as $name => $label ) {
			register_post_status( $name, apply_filters( 'register_event_registration_status', array(
				'label'                     => $label,
				'public'                    => true,
				'exclude_from_search'       => 'archived' === $name ? true : false,
				'show_in_admin_all_list'    => 'archived' === $name ? false : true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( $label . ' <span class="count">(%s)</span>', $label . ' <span class="count">(%s)</span>', 'wp-event-manager' )
			), $name ) );
		}
	}

	/**
	 * Delete registrations when deleting a event
	 */
	public function delete_post( $id ) {
		global $wpdb;

		if ( $id > 0 ) {

			$post_type = get_post_type( $id );

			if ( 'event_listing' === $post_type ) {
				$registrations = get_children( 'post_parent=' . $id . '&post_type=event_registration' );

				if ( $registrations ) {
					foreach ( $registrations as $registration ) {
						wp_delete_post( $registration->ID, true );
					}
				}
			}
		}
	}

	/**
	 * recursive_rmdir function
	 */
	public function recursive_rmdir( $directory ) {
	    foreach( glob( "{$directory}/*" ) as $file ) {
	        if ( is_dir( $file ) ) {
	            $this->recursive_rmdir( $file );
	        } else {
	            unlink( $file );
	        }
	    }
	    rmdir( $directory );
	}

	/**
	 * Purge registrations after x days
	 */
	public function event_registrations_purge() {
		$days = absint( get_option( 'event_registration_purge_days' ) );

		if ( ! $days ) {
			return;
		}

		global $wpdb;

		$registration_ids = $wpdb->get_col( $wpdb->prepare( "
			SELECT ID FROM {$wpdb->posts} as posts
			WHERE posts.post_type = 'event_registration'
			AND DATEDIFF( NOW(), posts.post_date ) > %d
		", $days ) );

		if ( $registration_ids ) {
			foreach ( $registration_ids as $registration_id ) {
				$upload_dir = wp_upload_dir();
				$secret_dir = get_post_meta( $registration_id, '_secret_dir', true );
				$dir_path   = trailingslashit( $upload_dir['basedir'] ) . 'event_registrations/' . $secret_dir;

				if ( $secret_dir && is_dir( $dir_path ) ) {
					$this->recursive_rmdir( $dir_path );
				}

				wp_delete_post( $registration_id, true );
			}
		}
	}

	/**
	 * When the status changes
	 */
	public function transition_post_status( $new_status, $old_status, $post ) {
		if ( 'event_registration' !== $post->post_type ) {
			return;
		}	

        	$registration_id = isset($_POST['registration_id']) ?absint( $_POST['registration_id'] ) : '';
		$statuses = get_event_registration_statuses();

		// Add a note
		if ( $old_status !== $new_status && array_key_exists( $old_status, $statuses ) && array_key_exists( $new_status, $statuses ) ) {
			$user                 = get_user_by( 'id', get_current_user_id() );
			$comment_author       = $user->display_name;
			$comment_author_email = $user->user_email;
			$comment_post_ID      = $post->ID;
			$comment_author_url   = '';
			$comment_content      = sprintf( __( 'Registration status changed from "%s" to "%s"', 'wp-event-manager-registrations' ), $statuses[ $old_status ], $statuses[ $new_status ] );
			$comment_agent        = 'WP Event Manager';
			$comment_type         = 'event_registration';
			$comment_parent       = 0;
			$comment_approved     = 1;
			$commentdata          = apply_filters( 'event_registration_note_data', compact( 'comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_agent', 'comment_type', 'comment_parent', 'comment_approved' ), $registration_id );
			$comment_id           = wp_insert_comment( $commentdata );
		}

	}
	
}