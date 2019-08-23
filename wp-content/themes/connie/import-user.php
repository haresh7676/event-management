<?php /* Template Name: Import Users */ ?>
<?php get_header('blank'); ?>
<div class="importwpr">
<?php
$pageid = (isset($_GET['page_no']) && !empty($_GET['page_no'])) ? $_GET['page_no']:1;
$curlurl = 'https://app.quirktastic.co/beta/ws/get_all_users?page_no='.$pageid;
$method = 'GET';
//$query_data = (!empty($data)) ? http_build_query($data) : '';
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $curlurl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $method,
    //CURLOPT_POSTFIELDS => $query_data,
    CURLOPT_HTTPHEADER => array(
        "authorization: Basic YWRtaW46MTIzNA=="
    ),
));
$response = curl_exec($curl);
$finalres = json_decode($response);
if($finalres->MESSAGE == "Success"){
    $userlist = $finalres->USER_LIST;
    if(!empty($userlist)){
        foreach ($userlist as $user){
            $username = $user->phone_number;
            if ( username_exists( $username ) ){

            }else{
                $userdata = array(
                    'user_login'  =>  !empty($user->phone_number)?$user->phone_number:'',
                    'user_email'  =>  !empty($user->email_id)?$user->email_id:'',
                    'first_name'  =>  !empty($user->first_name)?$user->first_name:'',
                    'last_name'  =>  !empty($user->last_name)?$user->last_name:'',
                    'user_pass'   =>  NULL  // When creating a new user, `user_pass` is expected.
                );
                $user_id = wp_insert_user( $userdata ) ;
                if ( ! is_wp_error( $user_id ) ) {
                    update_user_meta($user_id,'app_user_id',$user->id);
                    update_user_meta($user_id,'country_code',$user->country_code);
                    update_user_meta($user_id,'phone_number',$user->phone_number);
                    update_user_meta($user_id,'zipcode',$user->zipcode);
                    update_user_meta($user_id,'date_of_birth',$user->date_of_birth);
                    update_user_meta($user_id,'gender',$user->gender);
                    update_user_meta($user_id,'is_verify_phone_number',$user->is_verify_phone_number);
                    update_user_meta($user_id,'latitude',$user->latitude);
                    update_user_meta($user_id,'longitude',$user->longitude);
                    update_user_meta($user_id,'city_name',$user->city_name);
                    update_user_meta($user_id,'state_name',$user->state_name);
                    update_user_meta($user_id,'state_short_name',$user->state_short_name);
                    update_user_meta($user_id,'app_status',$user->status);
                }
            }
        }
    }
}
pr($finalres);
//exit;

/*START CURL DATA STORE IN TABLE*/
/*if (!curl_errno($curl)) {
      $info = curl_getinfo($curl);
    global $wpdb;
    $table_name = $wpdb->prefix."curl_info";
    $insert = $wpdb->insert($table_name, array(
                'ws_name' => $ws_name,
                'ws_url' => $curlurl,
                'ws_data' => http_build_query($data),
                'response_time' => $info['total_time'],
            ),array('%s','%s','%s','%s')
        );
}*/
/*END CURL DATA STORE IN TABLE*/
$err = curl_error($curl);
curl_close($curl);
if ($err) {
    return "cURL Error #:" . $err;
} else {
    return $response;
}
?>
</div>
<?php get_footer('blank'); ?>
