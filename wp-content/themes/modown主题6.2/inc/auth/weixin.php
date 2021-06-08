<?php 
class WEIXIN_LOGIN{
	function __construct(){
		session_start();
	}

	function login($appid,$appkey,$code){
		if($_REQUEST ['state'] == 'MBT_weixin_login'){
		
			$token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appkey."&code=".$code."&grant_type=authorization_code";
			$response = get_url_contents ( $token_url );
			$msg = json_decode ( $response );
			if (isset ( $msg->errcode )) {
				echo "<h3>error:</h3>" . $msg->errcode;
				echo "<h3>msg  :</h3>" . $msg->errmsg;
				exit ();
			}else{
				
				$_SESSION ['weixin_access_token'] = $msg->access_token;
				$_SESSION ['weixin_open_id'] = $msg->openid;
			}
		}else{
			echo ("The state does not match. You may be a victim of CSRF.");
			exit;
		}
	}
	
	function weixin_cb(){
		date_default_timezone_set('Asia/Shanghai');
		if(is_user_logged_in()){
			exit('<meta charset="UTF-8" />您已登录，请在个人中心绑定。');
		}else{
			global $wpdb;
			if(isset($_SESSION ['weixin_open_id'])){
			    $user_uID = 0;
				$uinfo = $this->wx_oauth2_get_user_info($_SESSION ['weixin_access_token'],$_SESSION ['weixin_open_id']);
				if($uinfo->unionid){
				    $user_uID = $wpdb->get_var("SELECT ID FROM $wpdb->users WHERE weixin_unionid='".esc_sql($uinfo->unionid)."'");
				}
				
				if($user_uID){
				    wp_set_auth_cookie($user_uID,true,is_ssl());
					wp_signon( array(), is_ssl() );
					wp_redirect(get_bloginfo('url'));
					exit();
				}else{
    				$user_ID = $wpdb->get_var("SELECT ID FROM $wpdb->users WHERE weixinid='".esc_sql($_SESSION['weixin_open_id'])."'");
    				if($user_ID){
    				    if($uinfo->unionid){
    				        $wpdb->query("UPDATE $wpdb->users SET weixin_unionid = '".esc_sql($uinfo->unionid)."' WHERE ID = ".$user_ID);
    				    }
    					//$user_login = $wpdb->get_var("SELECT user_login FROM $wpdb->users WHERE weixinid='".$wpdb->escape($_SESSION['weixin_open_id'])."'");
    					wp_set_auth_cookie($user_ID,true,is_ssl());
    					wp_signon( array(), is_ssl() );
    					//do_action('wp_login', $user_login);
    					wp_redirect(get_bloginfo('url'));
    					exit();
    				}else{
    
    					$pass = wp_generate_password(16, false);
    					$login_name = "u".mt_rand(1000,9999).mt_rand(1000,9999).mt_rand(100,999).mt_rand(100,999);
    					$username = $uinfo->nickname;
    					$userdata=array(
    					  'user_login' => $login_name,
    					  'display_name' => $username,
    					  'nickname' => $username,
    					  'user_pass' => $pass,
    					  'first_name' => $username
    					);
    					$user_id = wp_insert_user( $userdata );
    					if ( is_wp_error( $user_id ) ) {
    						echo $user_id->get_error_message();
    					}else{
    		                if($uinfo->unionid){
    		                    $ff = $wpdb->query("UPDATE $wpdb->users SET weixinid = '".esc_sql($_SESSION['weixin_open_id'])."', weixin_unionid = '".esc_sql($uinfo->unionid)."' WHERE ID = ".$user_id);
    		                }else{
    						    $ff = $wpdb->query("UPDATE $wpdb->users SET weixinid = '".esc_sql($_SESSION['weixin_open_id'])."' WHERE ID = ".$user_id);
    		                }
    						if($ff){
    							update_user_meta($user_id, 'photo', $uinfo->headimgurl);
    							update_user_meta($user_id, 'weixin_name', $username);
    							wp_set_auth_cookie($user_id,true,is_ssl());
    							wp_signon( array(), is_ssl() );
    							//do_action('wp_login', $login_name);
    							wp_redirect(get_bloginfo('url'));
    						}
    					}
    					exit();
    				}
				}
				
			}
		}
	}

	function weixin_bd(){
		if(is_user_logged_in()){
			global $wpdb;
			if(isset($_SESSION ['weixin_open_id'])){
			    $uinfo = $this->wx_oauth2_get_user_info($_SESSION ['weixin_access_token'],$_SESSION ['weixin_open_id']);
				if($uinfo->unionid){
				    $hsauser_ID = $wpdb->get_var("SELECT ID FROM $wpdb->users WHERE weixinid='".esc_sql($_SESSION['weixin_open_id'])."' or weixin_unionid='".esc_sql($uinfo->unionid)."'");
				}else{
				    $hsauser_ID = $wpdb->get_var("SELECT ID FROM $wpdb->users WHERE weixinid='".esc_sql($_SESSION['weixin_open_id'])."'");
				}
				if($hsauser_ID){
					exit('<meta charset="UTF-8" />绑定失败，可能之前已有其他账号绑定，请先登录其他账户解绑。');
				}else{
					global $current_user;
					$userid = $current_user->ID;
					if($uinfo->unionid){
					    $wpdb->query("UPDATE $wpdb->users SET weixinid = '".esc_sql($_SESSION['weixin_open_id'])."', weixin_unionid = '".esc_sql($uinfo->unionid)."' WHERE ID = $userid");
					}else{
					    $wpdb->query("UPDATE $wpdb->users SET weixinid = '".esc_sql($_SESSION['weixin_open_id'])."' WHERE ID = $userid");
					}
					wp_redirect(get_permalink(MBThemes_page("template/user.php")).'?action=info');
				}

			}
		}else{
			exit('<meta charset="UTF-8" />绑定失败，请先登录。');
		}
	}
	
	
	function wx_oauth2_get_user_info($access_token, $openid){
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
		$res = get_url_contents($url);
		return json_decode($res);
	}

}
