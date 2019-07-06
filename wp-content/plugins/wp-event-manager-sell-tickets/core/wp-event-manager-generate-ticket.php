<?php

/**
*  WP_Event_Manager_Generate_Tickets
* @since 1.5
*/

class WP_Event_Manager_Generate_Tickets
{
	
	public function __construct()
	{
		add_action( 'admin_init', array( $this,'init_admin_hook' ) ); 
		// Add your custom order status action button (for orders with placed)
		add_filter( 'woocommerce_admin_order_actions', array($this ,'add_order_ticket_actions_button'), 100, 2 );
		add_action('event_registration_footer_action_start', array($this ,'add_download_ticket_link_registration_dashboard') );
		
		add_filter('woocommerce_my_account_my_orders_columns',array($this , 'add_ticket_download_woocommerce_dashboard_column'));
		add_action('woocommerce_my_account_my_orders_column_download-ticket',array($this,'add_ticket_download_woocommerce_dashboard_column_value'));
		
		//woocommerce email attachment
		add_filter( 'woocommerce_email_attachments', array( $this , 'attach_terms_conditions_pdf_to_email' ),10,3);
		
		add_action('wp_loaded',array( $this,'download_event_ticket_registration_dashboard') );
	}
    
    /**
     * generate_pdf_ticket
     * It will genereate pdf ticket or store it in the wp-event-manager-sell-ticket folder
     * Make directory if not exist
     * if stream is true it will be downloaded by the end user
     * If stream is false it will be stored in sell ticket upload folder
     * @since 1.5
     **/
	public function generate_pdf_ticket( $order_id ,$stream = false ){
	    require_once 'lib/phpqrcode/qrlib.php';
	      require_once 'dompdf/autoload.inc.php';
            $upload_dir   = wp_upload_dir();
            if ( ! empty( $upload_dir['basedir'] ) ) {
                $ticket_dirname = $upload_dir['basedir'].'/wp-event-manager-sell-tickets';
                if ( ! file_exists( $ticket_dirname ) ) {
                    wp_mkdir_p( $ticket_dirname );
                }
                $qrcode_dirname = $upload_dir['basedir'].'/wp-event-manager-sell-tickets/qr-code';
                if ( ! file_exists( $qrcode_dirname ) ) {
                    wp_mkdir_p( $qrcode_dirname );
                }
            }
			$registrations = $this->get_event_registration_by_order_id( $order_id );
			$event_id = get_post_meta($order_id, '_event_id',true);
			$page_break_count=0;
			ob_start();
			foreach ( $registrations as $registration ) {
			    /**
			     * Qrcode will be generated by default using Event_id, order_id and Regitration_id
			     * Using  "event_manager_sell_tickets_qrcode" filter user can change QRcode value
			     */
			    $qrcode=$event_id.'-'.$order_id.'-'.$registration->ID;
			    $qrcode=apply_filters('event_manager_sell_tickets_qrcode', $qrcode);
			    
			    /**
			     * If QRcode image is already saved previously then it will fatch that image file.
			     * Otherwise It will generate QRocde image for given code
			     */
			    if(!file_exists($qrcode_dirname.'/'.$qrcode.'.png'))
			        QRcode::png($qrcode, $qrcode_dirname.'/'.$qrcode.'.png', QR_ECLEVEL_H, 4); 
			    $product_id = get_post_meta($registration->ID,'_ticket_id',true);
			    $product = wc_get_product( $product_id );
			    get_event_manager_template('event-ticket.php',array( 'order_id' => $order_id , 'event_id' => $event_id ,'registration' => $registration ,'product' => $product, 'qrcode_directory' => $qrcode_dirname , 'page_break_count' =>$page_break_count),'wp-event-manager-sell-tickets',EVENT_MANAGER_SELL_TICKETS_PLUGIN_DIR . '/templates/' );
			}
			
			$html = ob_get_clean();
			
            $file_name = $order_id .'.pdf';
            
			$dompdf = new Dompdf\Dompdf();
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();
		
			if($stream == true)
			{
			    $dompdf->stream($file_name);
			    exit();
			}
			else{
			    $full_path = $ticket_dirname.'/'. $file_name;
			    $pdf_gen = $dompdf->output();
			    
    			if(!file_exists( $full_path )){
    			    if(!is_wp_error(file_put_contents( $full_path , $pdf_gen ))) 
    			    return $full_path;
    			}
    			else{
    			    return $full_path;    
    			}    
			}
	}
	
    /**
     * init_admin_hook 
     * It will allow admin to download ticket on the woocomerce -> orders page
     * @since 1.5
     **/
	public function init_admin_hook(){
		
		
		if(isset($_GET['action'] ) && isset( $_GET['order_id'] ) && $_GET['action'] == 'view_ticket'   ){
			// sanitize data and verify nonce.
			
			$action = sanitize_key( $_GET['action'] );
			$nonce  = sanitize_key( $_GET['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'woocommerce-order-ticket-nonce' ) ) {
				wp_die( 'Invalid request.' );
			}
		
			$this->generate_pdf_ticket( $_GET['order_id']  , true );
		}
		else{
			return;
		}
	}
	
    /**
     * add_order_ticket_actions_button 
     * It will add button on the woocommerce my account (frontend)
     * 
     * @since 1.5
     **/
	public function add_order_ticket_actions_button( $actions, $order  ){
	
		echo '<style>a.button.wc-action-button.wc-action-button-view-ticket.view-ticket::after { font-family: woocommerce; content: "\e00a" !important; }</style>';
        $event_id = get_post_meta( $order->get_id(), '_event_id', TRUE);
        if($event_id){
        	// Set the action button
	        $actions['view-ticket'] = array(
	            'url'       => wp_nonce_url( admin_url( 'admin-ajax.php?action=view_ticket&order_id=' . $order->get_id() ), 'woocommerce-order-ticket-nonce' ),
	            'name'      => __( 'View ticket', 'woocommerce' ),
	            'action'    => 'view-ticket',
	        );
        }
        return $actions;
	}
	
    /**
     * add_download_ticket_link_registration_dashboard
     * It will show download ticket link on registration dashboard
     *  @since 1.5
     **/ 
	public function add_download_ticket_link_registration_dashboard( $registration ){
	    
		$order_id = get_post_meta($registration->ID,'_order_id',true);
		if($order_id){
			get_event_manager_template('ticket-link-registration-dashboard.php',array('order_id' => $order_id),'wp-event-manager-sell-tickets', EVENT_MANAGER_SELL_TICKETS_PLUGIN_DIR . '/templates/' );
		}
		
	}
	
    /**
     * add_ticket_download_woocommerce_dashboard_column
     * Add ticket column on woocommerce my account order table
     * @since 1.5
     **/
	public function add_ticket_download_woocommerce_dashboard_column( $columns ){
		$columns['download-ticket'] = __( 'Ticket', 'wp-event-manager-sell-tickets' );

		return $columns;
	}
	
    /**
     * add_ticket_download_woocommerce_dashboard_column_value
     * Show woocommerce ticket column value 
     **/
	public function add_ticket_download_woocommerce_dashboard_column_value( $order ){
		
		if($order && $order->get_status() == 'completed' )
			echo sprintf( __( '<a href="?download_ticket=true&order_id=%s">%s</a>', 'wp-event-manager-sell-tickets' ),$order->get_id(),__( 'Download ', 'wp-event-manager-sell-tickets' ) );
		else
			echo '-';
	}
	
	/**
	 * get_event_registration_by_order_id
	 * Get event registration by order id 
	 * 
	 * @return posts
	 * @since 1.5
	 **/ 
	public function get_event_registration_by_order_id( $order_id )
	{
	     $args = array(
	        		'post_type'           => 'event_registration',
	        		'post_status'         => array_diff( array_merge( array_keys( get_event_registration_statuses() ), array( 'publish' ) ), array( 'archived' ) ),
	        		'ignore_sticky_posts' => 1,
	        	    'numberposts'   => -1, // get all posts.
	        		'meta_query' => array(
	                                        
	                                        array(
	                                            'key' => '_order_id',
	                                            'value'   => $order_id,
	                                            'compare' => '='
	                                        )
	                                      
	                                    ),
	                //'fields' => 'ids',
				);
				
			return get_posts($args);
		
	
	}
	
	/**
     *  attach_terms_conditions_pdf_to_email
     * Attach ticket pdf with order email on order completed
     * 
     * @since 1.5
     * @return $attachments
     * */
    public function attach_terms_conditions_pdf_to_email($attachments ,  $status, $order ){
    		
    		$allowed_statuses = array( 'customer_completed_order' );
    		 
    		if( isset( $status ) && in_array ( $status, $allowed_statuses ) ) {
    
    		$attachments[] = $this->generate_pdf_ticket($order->get_id(), false);
    		}
    		return $attachments;
    }
    
    /**
     * Download event ticket on registration dasbhoard
     * @since 1.5
     **/
    public function download_event_ticket_registration_dashboard(){
    
    	if(isset($_GET['download_ticket']) && isset( $_GET['order_id'] ) && $_GET['download_ticket'] == true  ){
			$this->generate_pdf_ticket( $_GET['order_id']  , true );
		}
    }
}

new WP_Event_Manager_Generate_Tickets();

?>