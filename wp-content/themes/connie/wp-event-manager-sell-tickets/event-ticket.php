<div></div>
<!DOCTYPE html>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Event Ticket</title>
</head>
<body>
 <?php 
 global $woocommerce;
$event = get_post($event_id);
$order = wc_get_order($order_id);
$items = $order->get_items();
foreach ( $items as $item ) {
   $product = $item->get_product();
    $product_name = $item->get_name();
    $product_id = $item->get_product_id();
    $product_variation_id = $item->get_variation_id();
}
$registration_id = $registration->ID;

 ?>
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" style="border:3px solid #000;">
   <tr>
      <td width="75%">
         <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
               <td colspan="2" style="padding:15px 15px 0 15px;font-family:Arial, Helvetica, sans-serif; font-weight:bold; font-size:13px; text-align: center;color:#000;"><?php echo get_bloginfo( 'name' );?></td> 
            </tr>
            <tr>
               <td colspan="2" style="padding:5px 15px 10px 15px;font-family:Arial, Helvetica, sans-serif; font-weight:bold;  font-size:20px; text-align: center;color:#000;"><?php echo $event->post_title;?></td>
            </tr>
            <tr>
               <td width="50%" style="padding:0 30px;border-right:1px solid #000;">
                  <table width="100%" style="text-align:center;" border="0" cellspacing="0" cellpadding="0">
                     <!-- <tr>
                        <td><img src="banner.jpg" style="width:100px;"></td>
                     </tr> -->
                     <tr>
                        <td style="font-family:Arial, Helvetica, sans-serif;  font-size:12px; padding:10px; color:#000;"><strong><?php _e('Venue:', 'wp-event-manager-sell-tickets');?></strong><br><?php display_event_location( false, $event );?></td>
                     </tr>
                      <tr>
                        <td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; padding:10px 0; color:#000;"><?php  display_event_start_date( '', '',  true, $event);?> / <?php  display_event_start_time( '', '',  true, $event);?></td>
                     </tr>   
                  </table>
               </td>
               <td width="50%" style="padding:10px 0;">
                  <table style="text-align: center;" width="100%" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                        <td ><img src="<?php echo $qrcode_directory.'/'.$event_id.'-'.$order_id.'-'.$registration_id;?>.png" title="Link to Google.com" />
                     </tr>
                     <tr>
                      <td style="font-family:Arial, Helvetica, sans-serif; font-size:12px;   color:#000;"><strong><?php _e('Ticket Price:', 'wp-event-manager-sell-tickets'); ?></strong><br><?php echo $product->get_price_html(); ?> PAID</td>
                     </tr>
                  </table>                 
               </td>
            </tr>
            <tr>
               <td style="padding:15px 30px;font-family:Arial, Helvetica, sans-serif; font-size:12px;   color:#000;"><strong><?php _e('Order By:', 'wp-event-manager-sell-tickets');?></strong> <?php echo $registration->post_title;?></td>
               <td style="padding:15px 30px;font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000;text-align: right;"><strong><?php _e('Order ID:', 'wp-event-manager-sell-tickets');?></strong> #<?php echo $order_id;?></td>
            </tr>
         </table>
      </td>
      <td style="border-left:1px dashed #000;padding: 20px;" width="25%" >
         <table border="0" style="text-align:center;" cellpadding="0" cellspacing="0">
            <tr>
               <td style="font-family:Arial, Helvetica, sans-serif;  font-size:18px; text-align: center;color:#000;font-weight: bold;"><?php echo $event->post_title;?></td>
            </tr>
            <tr>
               <td style="padding-top:5px;font-family:Arial, Helvetica, sans-serif; color:#000;font-size:12px; "><strong><?php _e('Order ID:', 'wp-event-manager-sell-tickets');?></strong> #<?php echo $order_id;?></td>
            </tr>
            <tr>
               <td style="padding-top:5px;font-family:Arial, Helvetica, sans-serif; color:#000;font-size:12px; "><strong><?php _e('Order By:', 'wp-event-manager-sell-tickets');?></strong> <?php echo $registration->post_title;?></td>
            </tr>
            <tr>
               <td style="padding-top:8px;font-family:Arial, Helvetica, sans-serif;color:#000;font-size:12px; "><?php  display_event_start_date( '', '',  true, $event);?> / <?php  display_event_start_time( '', '',  true, $event);?></td>
            </tr>
         </table>
      </td>
   </tr>
</table>
</body>
</html>
<?php 
if($page_break_count == 1){ ?>
<div style="page-break-before: always;" class="page_break"></div>
<?php $page_break_count++;
 }else{ 
   $page_break_count==0;
   echo '<br>';
  } ?>

