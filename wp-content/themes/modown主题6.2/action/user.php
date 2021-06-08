<?php 
session_start();
require( dirname(__FILE__) . '/../../../../wp-load.php' );
date_default_timezone_set('Asia/Shanghai');
global $wpdb;
if ( is_user_logged_in() ) { 
	global $current_user; 
	get_currentuserinfo();
	$uid = $current_user->ID;
	
	if($_POST['action']=='user.edit'){
		
		$userdata = array();
		$userdata['ID'] = $uid;
		$userdata['nickname'] = str_replace(array('<','>','&','"','\'','#','^','*','_','+','$','?','!'), '', $wpdb->escape(trim($_POST['nickname'])) );
		$userdata['display_name'] = str_replace(array('<','>','&','"','\'','#','^','*','_','+','$','?','!'), '', $wpdb->escape(trim($_POST['nickname'])) );
		$userdata['description'] = $wpdb->escape(trim($_POST['description']));
		wp_update_user($userdata);
		update_user_meta($uid, 'qq', $wpdb->escape(trim($_POST['qq'])) );
		$error = 0;	

		$arr=array(
			"error"=>$error, 
			"msg"=>$msg
		); 
		$jarr=json_encode($arr); 
		echo $jarr;
	}elseif($_POST['action']=='user.email'){
		$user_email = apply_filters( 'user_registration_email', $wpdb->escape(trim($_POST['email'])) );
		$error = 0;$msg = '';
		if ( $user_email == '' ) {
			$error = 1;
			$msg = '邮箱不能为空';
		} elseif ( $user_email == $current_user->user_email) {
			$error = 1;
			$msg = '请输入一个新邮箱账号';
		}elseif ( email_exists( $user_email ) && $user_email != $current_user->user_email) {
			$error = 1;
			$msg = '邮箱已被使用';
		}else{
			if(empty($_POST['captcha']) || empty($_SESSION['MBT_email_captcha']) || trim(strtolower($_POST['captcha'])) != $_SESSION['MBT_email_captcha']){
				$error = 1;
				$msg .= '验证码错误 ';
			}elseif($_SESSION['MBT_email_new'] != $user_email){
				$error = 1;
				$msg = '邮箱与验证码不对应';
			}else{
				unset($_SESSION['MBT_email_captcha']);
				unset($_SESSION['MBT_email_new']);
				$userdata = array();
				$userdata['ID'] = $uid;
				$userdata['user_email'] = $user_email;
				wp_update_user($userdata);
				$error = 0;	
			}
		}
		
		$arr=array(
			"error"=>$error, 
			"msg"=>$msg
		); 
		$jarr=json_encode($arr); 
		echo $jarr;
	}elseif($_POST['action']=='user.mobile'){
		$mobile = $wpdb->escape(trim($_POST['mobile']));
		$captcha = trim($_POST['captcha']);
		$error = 0;$msg = '';
		if(MBThemes_is_phone($mobile)){
			if(empty($captcha) || empty($_SESSION['MBT_mobile_captcha']) || trim(strtolower($captcha)) != $_SESSION['MBT_mobile_captcha'] || $mobile != $_SESSION['MBT_captcha_mobile']){
				$error = 1;
			  	$msg = '验证码错误';
			}else{
				$mobile_id = $wpdb->get_var("select ID from $wpdb->users where mobile='".$mobile."'");
				
				if ( $mobile_id == $current_user->ID) {
					$error = 1;
					$msg = '请输入一个新手机号';
				} elseif ( $mobile_id && $mobile_id != $current_user->ID) {
					$error = 1;
					$msg = '手机号已被使用';
				}else{
					$ff = $wpdb->query("UPDATE $wpdb->users SET mobile = '".$mobile."' WHERE ID = '".$current_user->ID."'");
					if($ff){
						unset($_SESSION['MBT_mobile_captcha']);
						unset($_SESSION['MBT_captcha_mobile']);
					}
				}
			}
		}else{
			$error = 1;
			$msg = '手机号格式错误';
		}
		
		$arr=array(
			"error"=>$error, 
			"msg"=>$msg
		); 
		$jarr=json_encode($arr); 
		echo $jarr;
	}elseif($_POST['action']=='user.email.captcha'){
		$user_email = apply_filters( 'user_registration_email', $wpdb->escape(trim($_POST['email'])) );
		$error = 0;$msg = '';
		if ( $user_email == '' ) {
			$error = 1;
			$msg = '邮箱不能为空';
		} elseif ( $user_email == $current_user->user_email) {
			$error = 1;
			$msg = '请输入一个新邮箱账号';
		} elseif ( email_exists( $user_email ) && $user_email != $current_user->user_email) {
			$error = 1;
			$msg = '邮箱已被使用';
		}else{
			
			$originalcode = '0,1,2,3,4,5,6,7,8,9';
			$originalcode = explode(',',$originalcode);
			$countdistrub = 10;
			$_dscode = "";
			$counts=6;
			for($j=0;$j<$counts;$j++){
				$dscode = $originalcode[rand(0,$countdistrub-1)];
				$_dscode.=$dscode;
			}
			$_SESSION['MBT_email_captcha']=strtolower($_dscode);
			$_SESSION['MBT_email_new']=$user_email;
			$message .= '验证码：'.$_dscode;   
			wp_mail($user_email, '验证码-修改邮箱-'.get_bloginfo('name'), $message);    
			$error = 0;	
		}
		
		$arr=array(
			"error"=>$error, 
			"msg"=>$msg
		); 
		$jarr=json_encode($arr); 
		echo $jarr;
	}elseif($_POST['action']=='user.mobile.captcha'){
		$user_mobile = $wpdb->escape(trim($_POST['mobile']));
		$error = 0;$msg = '';
		if(MBThemes_is_phone($user_mobile)){
			$mobile_id = $wpdb->get_var("select ID from $wpdb->users where mobile='".$user_mobile."'");
			
			if ( $mobile_id == $current_user->ID) {
				$error = 1;
				$msg = '请输入一个新手机号';
			} elseif ( $mobile_id && $mobile_id != $current_user->ID) {
				$error = 1;
				$msg = '手机号已被使用';
			}else{
				$config = [
	                'accessKeyId' => _MBT('oauth_aliyun_access_id'),                
	                'accessKeySecret' => _MBT('oauth_aliyun_access_secret'),           
	                'signName' => _MBT('oauth_aliyun_sms_sign'), 
	                'templateCode' => _MBT('oauth_aliyun_sms_temp')            
	            ];
	            $code = rand(1000, 9999);    
	            $sms = new \Sms($config);
	            $status = $sms->send_verify($user_mobile, $code);  
	            if (!$status) {
	                //echo $sms->error;
	                $error = 1;
					$msg = '系统错误，请稍后重试！';
	            } else {
	                $_SESSION['MBT_mobile_captcha']=$code;
	                $_SESSION['MBT_captcha_mobile']=$user_mobile;
	            }
			}
		}else{
			$error = 1;
			$msg = '手机号格式错误';
		}
		
		$arr=array(
			"error"=>$error, 
			"msg"=>$msg
		); 
		$jarr=json_encode($arr); 
		echo $jarr;
	}elseif($_POST['action']=='user.password'){
		$error = 0;$msg = '';
		$username = $wpdb->escape(wp_get_current_user()->user_login);   
    	$password = $wpdb->escape($_POST['passwordold']); 
		$login_data = array();
		$login_data['user_login'] = $username;   
		$login_data['user_password'] = $password;   
		$user_verify = wp_signon( $login_data, false );  
		if ( is_wp_error($user_verify) ) {    
			$error = 1;$msg = '原密码错误';   
		}else{
			$userdata = array();
			$userdata['ID'] = wp_get_current_user()->ID;
			$userdata['user_pass'] = $_POST['password'];
			wp_update_user($userdata);
		}
		$arr=array(
			"error"=>$error, 
			"msg"=>$msg
		); 
		$jarr=json_encode($arr); 
		echo $jarr; 
	}elseif($_POST['action']=='user.charge.submit'){
		$error = 0;$iframe = 0;$url = '';
		$paytype = $_POST['paytype'];

		if(_MBT('recharge_iframe')){
			if($paytype=='2'){
				$iframe = 1;
				$url=constant("erphpdown")."payment/f2fpay.php?ice_money=".$_POST['ice_money'];
			}elseif($paytype=='3'){
				if(erphpdown_is_weixin() && get_option('ice_weixin_app')){
					$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.get_option('ice_weixin_appid').'&redirect_uri='.urlencode(constant("erphpdown")).'payment%2Fweixin.php%3Fice_money%3D'.$_POST['ice_money'].'&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect';
				}else{
					$iframe = 1;
					$url=constant("erphpdown")."payment/weixin.php?ice_money=".$_POST['ice_money'];
				}
			}elseif($paytype=='52'){
				$iframe = 1;
				$url=constant("erphpdown")."payment/paypy.php?ice_money=".$_POST['ice_money'];
			}elseif($paytype=='51'){
				$iframe = 1;
				$url=constant("erphpdown")."payment/paypy.php?ice_money=".$_POST['ice_money']."&type=alipay";
			}elseif($paytype=='61'){
				$iframe = 1;
				$url=constant("erphpdown")."payment/xhpay3.php?ice_money=".$_POST['ice_money']."&type=2";
			}elseif($paytype=='62'){
				$iframe = 1;
				$url=constant("erphpdown")."payment/xhpay3.php?ice_money=".$_POST['ice_money']."&type=1";
			}

			if(erphpdown_is_mobile()){
				$iframe = 0;
			}
		}

		$arr=array(
			"error"=>$error, 
			"iframe"=>$iframe,
			"url"=>$url
		); 
		
		$jarr=json_encode($arr); 
		echo $jarr;

	}elseif($_POST['action']=='user.vip.credit'){
		$error = 0;$msg = '';$link = get_permalink(MBThemes_page("template/user.php"));$payment = '';
		if(_MBT('vip_only_pay')){
			$error = 1;$msg = '抱歉，仅支持在线支付升级VIP！';
		}else{
			$userType=isset($_POST['userType']) && is_numeric($_POST['userType']) ?intval($_POST['userType']) :0;
			$oldUserType = getUsreMemberTypeById($uid);
			if($oldUserType == '10'){
				$error = 1;$msg = '您已经是终身VIP，请勿重复升级！';
			}else{
				if($userType >5 && $userType < 11){
					$okMoney=erphpGetUserOkMoney();
					$okMoney=sprintf("%.2f",$okMoney);
					$priceArr=array('6'=>'ciphp_day_price','7'=>'ciphp_month_price','8'=>'ciphp_quarter_price','9'=>'ciphp_year_price','10'=>'ciphp_life_price');
					$priceType=$priceArr[$userType];
					$price=get_option($priceType);
					if(!$price){
						$error = 1;$msg = '会员价格错误';
					}elseif($okMoney < $price){
						$error = 2;$msg = '余额不足，请先充值';
					}elseif($okMoney >=$price){
						if(erphpSetUserMoneyXiaoFei($price)){
							if(userPayMemberSetData($userType)){
								addVipLog($price, $userType);

								$ref = get_option('ice_ali_money_ref');
								$ref2 = get_option('ice_ali_money_ref2');
								if($ref){
									$RefMoney=$wpdb->get_row("select father_id from ".$wpdb->users." where ID=".$uid);
									if($RefMoney->father_id){
										addUserMoney($RefMoney->father_id,$price*$ref*0.01);
										if($ref2){
											$RefMoney2=$wpdb->get_row("select father_id from ".$wpdb->users." where ID=".$RefMoney->father_id);
											if($RefMoney2->father_id){
												addUserMoney($RefMoney2->father_id, $price*$ref2*0.01);
											}
										}
									}
								}

							}else{
								$error = 1;$msg = '升级失败';
							}
						}else{
							$error = 1;$msg = '升级失败';
						}
					}else{
						$error = 1;$msg = '升级失败';
					}
				}
			}
		}

		$arr=array(
			"error"=>$error, 
			"msg"=>$msg,
			"link"=>$link,
			"payment"=>$payment
		); 
		
		$jarr=json_encode($arr); 
		echo $jarr; 
	}elseif($_POST['action']=='user.vip'){
		$error = 0;$msg = '';$link = get_permalink(MBThemes_page("template/user.php"));$payment = '';
		$userType=isset($_POST['userType']) && is_numeric($_POST['userType']) ?intval($_POST['userType']) :0;
		
		$oldUserType = getUsreMemberTypeById($uid);
		if($oldUserType == '10'){
			$error = 1;$msg = '您已经是终身VIP，请勿重复升级！';
		}else{
			if($userType >5 && $userType < 11){
				$okMoney=erphpGetUserOkMoney();
				$okMoney=sprintf("%.2f",$okMoney);
				$priceArr=array('6'=>'ciphp_day_price','7'=>'ciphp_month_price','8'=>'ciphp_quarter_price','9'=>'ciphp_year_price','10'=>'ciphp_life_price');
				$priceType=$priceArr[$userType];
				$price=get_option($priceType);
				if(_MBT('vip_default_pay') && !_MBT('vip_only_pay')){//静默使用余额
					if(!$price){
						$error = 1;$msg = '会员价格错误';
					}elseif($okMoney < $price){
						if(_MBT('vip_just_pay')){
							$error = 3;$msg = '余额不足，请直接在线支付';
							if(get_option('ice_weixin_mchid')){
								if(erphpdown_is_weixin() && get_option('ice_weixin_app')){
									$payment .= '<a href="https://open.weixin.qq.com/connect/oauth2/authorize?appid='.get_option('ice_weixin_appid').'&redirect_uri='.urlencode(constant("erphpdown")).'payment%2Fweixin.php%3Fice_type%3D'.$userType.'&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> 微信支付</a>';
								}else{
									$payment .= '<a href="'.constant("erphpdown").'payment/weixin.php?ice_type='.$userType.'" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> 微信支付</a>';
								}
							}
							if(get_option('ice_ali_partner')){
								$payment .= '<a href="'.constant("erphpdown").'payment/alipay.php?ice_type='.$userType.'" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> 支付宝</a>';
							}
							if(get_option('erphpdown_f2fpay_id') && !get_option('erphpdown_f2fpay_alipay')){
								$payment .= '<a href="'.constant("erphpdown").'payment/f2fpay.php?ice_type='.$userType.'" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> 支付宝</a>';
							}
							if(get_option('erphpdown_xhpay_appid32')){
								$payment .= '<a href="'.constant("erphpdown").'payment/xhpay3.php?ice_type='.$userType.'&type=1" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> 支付宝</a>';
							}
							if(get_option('erphpdown_xhpay_appid31')){
								$payment .= '<a href="'.constant("erphpdown").'payment/xhpay3.php?ice_type='.$userType.'&type=2" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> 微信支付</a>';
							}
							if(get_option('erphpdown_codepay_appid')){
								if(!get_option('erphpdown_codepay_alipay')){
									$payment .= '<a href="'.constant("erphpdown").'payment/codepay.php?ice_type='.$userType.'&type=1" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> 支付宝</a>';
								}
								$payment .= '<a href="'.constant("erphpdown").'payment/codepay.php?ice_type='.$userType.'&type=3" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> 微信支付</a>';
								if(!get_option('erphpdown_codepay_qqpay')){
									$payment .= '<a href="'.constant("erphpdown").'payment/codepay.php?ice_type='.$userType.'&type=2" class="erphpdown-type-link erphpdown-type-qqpay" target="_blank"><i class="icon icon-qq"></i> QQ钱包</a>';
								}
							}
							if(get_option('erphpdown_paypy_key')){
								if(!get_option('erphpdown_paypy_wxpay')){
									$payment .= '<a href="'.constant("erphpdown").'payment/paypy.php?ice_type='.$userType.'" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> 微信支付</a>';
								}
								if(!get_option('erphpdown_paypy_alipay')){
									$payment .= '<a href="'.constant("erphpdown").'payment/paypy.php?ice_type='.$userType.'&type=alipay" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> 支付宝</a>';
								}
							}
							if(function_exists('plugin_check_epay')){
							if(plugin_check_epay() && get_option('erphpdown_epay_id')){
								if(!get_option('erphpdown_epay_wxpay')){
									$payment .= '<a href="'.ERPHPDOWN_EPAY_URL.'/epay.php?ice_type='.$userType.'&type=wxpay" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> 微信支付</a>';
								}
								if(!get_option('erphpdown_epay_alipay')){
									$payment .= '<a href="'.ERPHPDOWN_EPAY_URL.'/epay.php?ice_type='.$userType.'&type=alipay" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> 支付宝</a>';
								}
							}}
							if(get_option('ice_payapl_api_uid')){
								$payment .= '<a href="'.constant("erphpdown").'payment/paypal.php?ice_type='.$userType.'" class="erphpdown-type-link erphpdown-type-paypal" target="_blank"><i class="icon icon-paypal"></i> Paypal</a>';
							}
						}else{
							$error = 2;$msg = '余额不足，请先充值';
						}
					}elseif($okMoney >=$price){
						if(erphpSetUserMoneyXiaoFei($price)){
							if(userPayMemberSetData($userType)){
								addVipLog($price, $userType);

								$ref = get_option('ice_ali_money_ref');
								$ref2 = get_option('ice_ali_money_ref2');
								if($ref){
									$RefMoney=$wpdb->get_row("select father_id from ".$wpdb->users." where ID=".$uid);
									if($RefMoney->father_id){
										addUserMoney($RefMoney->father_id,$price*$ref*0.01);
										if($ref2){
											$RefMoney2=$wpdb->get_row("select father_id from ".$wpdb->users." where ID=".$RefMoney->father_id);
											if($RefMoney2->father_id){
												addUserMoney($RefMoney2->father_id, $price*$ref2*0.01);
											}
										}
									}
								}

							}else{
								$error = 1;$msg = '升级失败';
							}
						}else{
							$error = 1;$msg = '升级失败';
						}
					}else{
						$error = 1;$msg = '升级失败';
					}
				}else{
					if(empty($price) || $price == ''){
						$error = 1;$msg = '会员价格错误';
					}else{
						$error = 3;$msg = '请选择支付方式';
						if(_MBT('vip_just_pay') || _MBT('vip_only_pay')){
							
							if(get_option('ice_weixin_mchid')){
								if(erphpdown_is_weixin() && get_option('ice_weixin_app')){
									$payment .= '<a href="https://open.weixin.qq.com/connect/oauth2/authorize?appid='.get_option('ice_weixin_appid').'&redirect_uri='.urlencode(constant("erphpdown")).'payment%2Fweixin.php%3Fice_type%3D'.$userType.'&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> 微信支付</a>';
								}else{
									$payment .= '<a href="'.constant("erphpdown").'payment/weixin.php?ice_type='.$userType.'" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> 微信支付</a>';
								}
							}
							if(get_option('ice_ali_partner')){
								$payment .= '<a href="'.constant("erphpdown").'payment/alipay.php?ice_type='.$userType.'" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> 支付宝</a>';
							}
							if(get_option('erphpdown_f2fpay_id') && !get_option('erphpdown_f2fpay_alipay')){
								$payment .= '<a href="'.constant("erphpdown").'payment/f2fpay.php?ice_type='.$userType.'" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> 支付宝</a>';
							}
							if(get_option('erphpdown_xhpay_appid32')){
								$payment .= '<a href="'.constant("erphpdown").'payment/xhpay3.php?ice_type='.$userType.'&type=1" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> 支付宝</a>';
							}
							if(get_option('erphpdown_xhpay_appid31')){
								$payment .= '<a href="'.constant("erphpdown").'payment/xhpay3.php?ice_type='.$userType.'&type=2" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> 微信支付</a>';
							}
							if(get_option('erphpdown_codepay_appid')){
								if(!get_option('erphpdown_codepay_alipay')){
									$payment .= '<a href="'.constant("erphpdown").'payment/codepay.php?ice_type='.$userType.'&type=1" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> 支付宝</a>';
								}
								$payment .= '<a href="'.constant("erphpdown").'payment/codepay.php?ice_type='.$userType.'&type=3" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> 微信支付</a>';
								if(!get_option('erphpdown_codepay_qqpay')){
									$payment .= '<a href="'.constant("erphpdown").'payment/codepay.php?ice_type='.$userType.'&type=2" class="erphpdown-type-link erphpdown-type-qqpay" target="_blank"><i class="icon icon-qq"></i> QQ钱包</a>';
								}
							}
							if(get_option('erphpdown_paypy_key')){
								if(!get_option('erphpdown_paypy_wxpay')){
									$payment .= '<a href="'.constant("erphpdown").'payment/paypy.php?ice_type='.$userType.'" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> 微信支付</a>';
								}
								if(!get_option('erphpdown_paypy_alipay')){
									$payment .= '<a href="'.constant("erphpdown").'payment/paypy.php?ice_type='.$userType.'&type=alipay" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> 支付宝</a>';
								}
							}
							if(function_exists('plugin_check_epay')){
							if(plugin_check_epay() && get_option('erphpdown_epay_id')){
								if(!get_option('erphpdown_epay_wxpay')){
									$payment .= '<a href="'.ERPHPDOWN_EPAY_URL.'/epay.php?ice_type='.$userType.'&type=wxpay" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> 微信支付</a>';
								}
								if(!get_option('erphpdown_epay_alipay')){
									$payment .= '<a href="'.ERPHPDOWN_EPAY_URL.'/epay.php?ice_type='.$userType.'&type=alipay" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> 支付宝</a>';
								}
							}}
							if(get_option('ice_payapl_api_uid')){
								$payment .= '<a href="'.constant("erphpdown").'payment/paypal.php?ice_type='.$userType.'" class="erphpdown-type-link erphpdown-type-paypal" target="_blank"><i class="icon icon-paypal"></i> Paypal</a>';
							}	
						}
						if(!_MBT('vip_only_pay')){
							$payment .= '<a href="javascript:;" class="erphpdown-type-link erphpdown-type-credit" data-type="'.$userType.'"><i class="icon icon-ticket"></i> 余额支付</a>';
						}
					}
				}
			}else{
				$error = 1;$msg = '升级失败';
			}
		}
		

		$arr=array(
			"error"=>$error, 
			"msg"=>$msg,
			"link"=>$link,
			"payment"=>$payment
		); 
		
		$jarr=json_encode($arr); 
		echo $jarr; 
	}elseif($_POST['action'] == 'user.charge.card'){
		$error = 0;$msg = '';
		$num = $wpdb->escape($_POST['num']);
		$result = MBThemes_do_card($num);
		if($result == '5'){
			$error = 1;
			$msg = '充值卡不存在！';
		}elseif($result == '0'){
			$error = 1;
			$msg = '充值卡已被使用！';
		}elseif($result == '1'){
			
		}else{
			$error = 1;
			$msg = '系统错误，请稍后重试！';
		}
		$arr=array(
			"error"=>$error, 
			"msg"=>$msg
		);

		$jarr=json_encode($arr); 
		echo $jarr; 
	}elseif($_POST['action'] == 'user.mycred'){
		$error = 0;$msg = '';

		$epdmycrednum = $wpdb->escape($_POST['num']);
		if(is_numeric($epdmycrednum) && $epdmycrednum > 0 && get_option('erphp_mycred') == 'yes'){
			if(floatval(mycred_get_users_cred( $current_user->ID )) < floatval($epdmycrednum*get_option('erphp_to_mycred'))){
				$mycred_core = get_option('mycred_pref_core');
				$error = 1;
				$msg = $mycred_core['name']['plural']."不足！";
			}
			else
			{
				mycred_add( '兑换', $current_user->ID, '-'.$epdmycrednum*get_option('erphp_to_mycred'), '兑换扣除%plural%!', date("Y-m-d H:i:s") );
				$money = $epdmycrednum;
				if(addUserMoney($current_user->ID, $money))
				{
					$sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_time,ice_success,ice_note,ice_success_time,ice_alipay)
					VALUES ('$money','".date("ymd").mt_rand(10000,99999)."','".$current_user->ID."','".date("Y-m-d H:i:s")."',1,'4','".date("Y-m-d H:i:s")."','')";
					$wpdb->query($sql);
				}
				else
				{
					$error = 1;
					$msg = '兑换失败！';
				}
			}
		}

		$arr=array(
			"error"=>$error, 
			"msg"=>$msg
		);

		$jarr=json_encode($arr); 
		echo $jarr; 

	}elseif($_POST['action']=='user.social.cancel'){
		$error = 0;$msg = '';
		if($_POST['type'] == 'weixin'){
			$wpdb->query("update $wpdb->users set weixinid='' where ID=".$current_user->ID);
		}elseif($_POST['type'] == 'weibo'){
			$wpdb->query("update $wpdb->users set sinaid='' where ID=".$current_user->ID);
		}elseif($_POST['type'] == 'qq'){
			$wpdb->query("update $wpdb->users set qqid='' where ID=".$current_user->ID);
		}else{
			$error = 1;
			$msg = '解绑失败';
		}
		$arr=array(
			"error"=>$error, 
			"msg"=>$msg
		);
		$jarr=json_encode($arr); 
		echo $jarr; 
	}elseif($_POST['action']=='user.checkin'){
		$error = 0;$msg = '';
		if(_MBT('checkin')){
			if(MBThemes_check_checkin($current_user->ID)){
				$error = 1;
				$msg = '您今天已经签过到了，明儿再来哦～';
			}else{
				$result = $wpdb->query("insert into ".$wpdb->prefix . "checkins (user_id,create_time) values(".$current_user->ID.",'".date("Y-m-d H:i:s")."')");
				if($result){
					if(function_exists('addUserMoney')){
						$gift = 0;
						if(_MBT('checkin_random')){
							$gift_min = _MBT('checkin_gift_min')?_MBT('checkin_gift_min'):0;
							$gift_max = _MBT('checkin_gift_max')?_MBT('checkin_gift_max'):0;
							$gift = rand($gift_min,$gift_max);
						}else{
							$gift = _MBT('checkin_gift')?_MBT('checkin_gift'):0;
						}
						addUserMoney($current_user->ID, $gift);
					}
				}else{
					$error = 1;
					$msg = '签到失败，请稍后重试！';
				}
			}
		}else{
			$error = 1;
			$msg = '签到失败！';
		}
		$arr=array(
			"error"=>$error, 
			"msg"=>$msg
		);
		$jarr=json_encode($arr); 
		echo $jarr;
	}elseif($_POST['action'] == 'user.withdraw' && _MBT('withdraw')){
    	$error = 0;$msg = '';
    	$okMoney = erphpGetUserOkMoney();
    	$ice_alipay = $wpdb->escape($_POST['ice_alipay']);
		$ice_name   = $wpdb->escape($_POST['ice_name']);
		$ice_money  = isset($_POST['ice_money']) && is_numeric($_POST['ice_money']) ?$wpdb->escape($_POST['ice_money']) :0;
		if($ice_money >0){
			if($ice_money<get_option('ice_ali_money_limit'))
			{
				$error = 1;
				$msg = '提现金额至少得满'.get_option('ice_ali_money_limit').get_option('ice_name_alipay');
			}
			elseif(empty($ice_name) || empty($ice_alipay))
			{
				$error = 1;
				$msg = '请输入支付宝帐号和姓名！';
			}
			elseif($ice_money > $okMoney)
			{
				$error = 1;
				$msg = '余额不足';
			}
			else
			{
				$sql="insert into ".$wpdb->iceget."(ice_money,ice_user_id,ice_time,ice_success,ice_success_time,ice_note,ice_name,ice_alipay)values
					('".$ice_money."','".$current_user->ID."','".date("Y-m-d H:i:s")."',0,'".date("Y-m-d H:i:s")."','','$ice_name','$ice_alipay')";
				if($wpdb->query($sql))
				{	
					addUserMoney($current_user->ID, '-'.$ice_money);
				}
				else
				{
					$error = 1;
					$msg = '系统错误请稍后重试！';
				}
			}
		}else{
			$error = 1;
			$msg = '你想干嘛？';
		}
		$arr=array(
			"error"=>$error, 
			"msg"=>$msg
		);
		$jarr=json_encode($arr); 
		echo $jarr; 
	}elseif($_POST['action']=='tougao.tax'){
		$cat = $wpdb->escape($_POST['cat']);
		$taxonomys = get_term_meta($cat,'taxonomys',true);
		if($taxonomys){
			$post_texonomys = explode('|', $taxonomys);
            foreach ($post_texonomys as $post_texonomy) { 
                $post_texonomy = explode(',', $post_texonomy);
                $taxonomy = get_terms( array(
                    'taxonomy' => $post_texonomy[1],
                    'hide_empty' => false,
                ) );
                if($taxonomy){
                	echo '<div class="tougao-select"><select name="'.$post_texonomy[1].'" class="postform"><option value="">选择'.$post_texonomy[0].'</option>';
                    foreach ( $taxonomy as $term ) {
                    	echo '<option value="'.$term->slug.'">'.$term->name .'</option>';
                    }
                    echo '</select></div> ';
                }
            }
		}
	}
}