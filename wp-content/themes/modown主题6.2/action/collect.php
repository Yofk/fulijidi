<?php 
	if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
		header('Allow: POST');
		header('HTTP/1.1 405 Method Not Allowed');
		header('Content-Type: text/plain');
		exit;
	}

	require( dirname(__FILE__) . '/../../../../wp-load.php' ); 
	date_default_timezone_set('Asia/Shanghai');

	if(is_user_logged_in()){
		global $wpdb;
		$userdata = wp_get_current_user();
		if($_POST['id']){
			if(MBThemes_check_collect($wpdb->escape($_POST['id']))){
				$sql="delete from ".$wpdb->prefix ."collects where user_id = '".$userdata->ID."' and post_id = '".$wpdb->escape($_POST['id'])."'";
				$wpdb->query($sql);
				$printr["result"] = "2";
			}else{
				$sql="INSERT INTO ".$wpdb->prefix ."collects(user_id,post_id,create_time) VALUES('".$userdata->ID."','".$wpdb->escape($_POST['id'])."','".date("Y-m-d H:i:s")."')";
				$wpdb->query($sql);
				$printr["result"] = "1";
			}	
		}
	}
	
	echo json_encode($printr);