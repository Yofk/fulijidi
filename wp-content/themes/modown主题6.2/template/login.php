<?php 
/*
	template name: 登录页面
	description: template for mobantu.com modown theme 
*/
	if(is_user_logged_in()){
		if(isset($_GET["redirect_to"])){
			header("Location:".$_GET["redirect_to"]);
		}else{
			header("Location:".get_permalink(MBThemes_page('template/user.php')));
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
  <title>登录/注册 - <?php bloginfo("name");?></title>
  <link rel="shortcut icon" href="<?php echo _MBT('favicon')?>">
  <link rel="stylesheet"  href="<?php bloginfo("template_url");?>/static/css/libs.css" type="text/css" media="screen" />
  <link rel="stylesheet"  href="<?php bloginfo("template_url");?>/static/css/login.css" type="text/css" media="screen" />
  <script type="text/javascript" src="<?php bloginfo("template_url");?>/static/js/jquery.min.js"></script>
  <!--[if lt IE 9]><script src="<?php bloginfo("template_url");?>/static/js/html5.min.js"></script><![endif]-->
  <script>window._MBT = {uri: '<?php bloginfo('template_url') ?>', url:'<?php bloginfo('url');?>'}</script>
  <style><?php $theme_color_custom = _MBT('theme_color_custom');
  $theme_color = _MBT('theme_color');
  $color = '';
  if($theme_color && $theme_color != '#ff5f33'){
   $color = $theme_color;
  }
  if($theme_color_custom && $theme_color_custom != '#ff5f33'){
   $color = $theme_color_custom;
  }
  if($color){
  	echo '.loginbox .input-submit .submit{background:'.$color.';}.loginbox .input-item .captcha-clk, .loginbox .input-item .captcha-sms-clk{color:'.$color.'}';
  }?></style>
</head>
<?php 
if(_MBT('theme_night')){
	$night_class = '';
	if(isset($_COOKIE['mbt_theme_night'])){
	    if($_COOKIE['mbt_theme_night'] == '1'){
	      	$night_class = 'night';
	    }
	}elseif(_MBT('theme_night_default')){
	    $night_class = 'night';
	}elseif(_MBT('theme_night_auto')){
	    $time = intval(date("Hi"));
	    if ($time < 730 || $time > 1930) {
	      	$night_class = 'night';
	    }
	}
}
?>
<body class="<?php echo $night_class;?>">
	<div id="loginbox" class="loginbox">	
    	<?php if($_GET['action'] == 'register' && !_MBT('register')){?>
	    <div class="part regPart">
	    	<h2><a href="<?php bloginfo("url");?>"><img src="<?php echo _MBT('logo');?>" alt="<?php bloginfo("name");?>"></a></h2>
	        <form id="regform" class="loginform" method="post" novalidate="novalidate" onSubmit="return false;" autocomplete="off">
	            <p class="input-item">
	                <input class="input-control" id="regname" type="text" placeholder="用户名" name="regname" required="" ><i class="icon icon-user"></i>
	            </p>
	            <p class="input-item">
	                <input class="input-control" id="regemail" type="email" placeholder="邮箱" name="regemail" required="" ><i class="icon icon-mail"></i>
	            </p>
	            <p class="input-item">
	                <input class="input-control" id="regpass" type="password" placeholder="密码" name="regpass" required=""><i class="icon icon-lock"></i>
	            </p>
	            <?php if(_MBT('captcha') == 'email'){?>
	            <p class="input-item">
	                <input class="input-control" id="captcha" type="text" placeholder="验证码" name="captcha" required="">
	                <span class="captcha-clk">获取邮箱验证码</span><i class="icon icon-safe"></i>
	            </p>
	            <?php }elseif(_MBT('captcha') == 'image'){?>
	            <p class="input-item">
	            	<input class="input-control" id="captcha" type="text" placeholder="验证码" name="captcha" required="">
	                <img src="<?php bloginfo("template_url");?>/static/img/captcha.png" class="captcha-clk2" /><i class="icon icon-safe"></i>
	            </p>
	            <?php }elseif(_MBT('captcha') == 'invitation' && function_exists('ashuwp_check_invitation_code')){?>
	            <p class="input-item">
	            	<input class="input-control" id="captcha" type="text" placeholder="邀请码" name="captcha" required="">
	                <i class="icon icon-safe"></i>
	                <?php if(_MBT('invitation_link')){?><a href="<?php echo _MBT('invitation_link');?>" target="_blank" rel="nofollow" class="invitation-link">获取邀请码</a><?php }?>
	            </p>
	            <?php }?>
	            <p class="sign-tips"></p>
	            <p class="input-submit">
	                <input class="submit register-loader btn" type="submit" value="注册账号">
	                <input type="hidden" name="action" value="register">
	                <input type="hidden" id="security" name="security" value="<?php echo  wp_create_nonce( 'security_nonce' );?>">
        			<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
        			<?php if(_MBT('register_policy')){?>
					<div class="form-policy"><input type="checkbox" id="policy_reg" name="policy_reg" value="1" checked><label for="policy_reg"> 我已阅读并同意《<a href="<?php echo _MBT('register_policy');?>" target="_blank">用户注册协议</a>》</label></div>
					<?php }?>
	            </p>
	            <p class="safe">
	            	<?php if(_MBT('oauth_sms')){?><a href="<?php echo add_query_arg('action','sms',get_permalink(MBThemes_page('template/login.php')));?>" class="signsms-loader">手机号登录</a><?php }?>
	                <a class="signin-loader" href="<?php echo add_query_arg('action','login',get_permalink(MBThemes_page('template/login.php')));?>">返回登录</a>
	            </p>
	            <?php if(_MBT('oauth_qq') || _MBT('oauth_weibo') || (_MBT('oauth_weixin') || (_MBT('oauth_weixin_mobile') && modown_is_mobile())) || (_MBT('oauth_weixin_mp') && function_exists('ews_login'))){?>
	            <div class="social-login sign-social">
	            	<div class="social-title"><span>使用第三方账号注册</span></div>
                	<?php if(_MBT('oauth_qq')){?>
                	<a href="<?php bloginfo("url");?>/oauth/qq?rurl=<?php if(isset($_GET['redirect_to'])) echo $_GET['redirect_to'];else echo get_permalink(MBThemes_page('template/user.php'));?>" rel="nofollow" class="login-qq"><i class="icon icon-qq"></i></a>
                	<?php }?>
                	<?php if(_MBT('oauth_weibo')){?>
                	<a href="<?php bloginfo("url");?>/oauth/weibo?rurl=<?php if(isset($_GET['redirect_to'])) echo $_GET['redirect_to'];else echo get_permalink(MBThemes_page('template/user.php'));?>" rel="nofollow" class="login-weibo"><i class="icon icon-weibo"></i></a>
                	<?php }?>
                	<?php if(_MBT('oauth_weixin') || _MBT('oauth_weixin_mobile')){?>
						<?php if(modown_is_mobile() && _MBT('oauth_weixin_mobile')){?>
						<a class="login-weixin" href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=<?php echo _MBT('oauth_weixinid_mobile');?>&redirect_uri=<?php echo home_url();?>/oauth/weixin/&response_type=code&scope=snsapi_userinfo&state=MBT_weixin_login#wechat_redirect" rel="nofollow"><i class="icon icon-weixin"></i></a>
						<?php }elseif(_MBT('oauth_weixin')){?>
						<a class="login-weixin" href="https://open.weixin.qq.com/connect/qrconnect?appid=<?php echo _MBT('oauth_weixinid');?>&redirect_uri=<?php echo home_url();?>/oauth/weixin/&response_type=code&scope=snsapi_login&state=MBT_weixin_login#wechat_redirect" rel="nofollow"><i class="icon icon-weixin"></i></a>
						<?php }?>
					<?php }?>
                	<?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
                	<a href="<?php echo add_query_arg('action','mp',get_permalink(MBThemes_page('template/login.php')));?>" class="login-weixin"><i class="icon icon-weixin"></i></a>
                	<?php }?>
	            </div>
	            <?php }?>
	        </form>
	    </div>
	    <?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
	    <div class="expend-container">
            <a href="<?php echo add_query_arg('action','mp',get_permalink(MBThemes_page('template/login.php')));?>" title="扫码登录"><svg class="icon toggle" style="width: 4em; height: 4em;vertical-align: middle;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6487"><path d="M540.9 866h59v59h-59v-59zM422.8 423.1V98.4H98.1v324.8h59v59h59v-59h206.7z m-265.7-59V157.4h206.7v206.7H157.1z m0 0M216.2 216.4h88.6V305h-88.6v-88.6zM600 98.4v324.8h324.8V98.4H600z m265.7 265.7H659V157.4h206.7v206.7z m0 0M718.1 216.4h88.6V305h-88.6v-88.6zM216.2 718.3h88.6v88.6h-88.6v-88.6zM98.1 482.2h59v59h-59v-59z m118.1 0h59.1v59h-59.1v-59z m0 0M275.2 600.2H98.1V925h324.8V600.2h-88.6v-59h-59v59z m88.6 59.1V866H157.1V659.3h206.7z m118.1-531.4h59v88.6h-59v-88.6z m0 147.6h59v59h-59v-59zM659 482.2H540.9v-88.6h-59v88.6H334.3v59H600v59h59v-118z m0 118h59.1v59H659v-59z m-177.1 0h59v88.6h-59v-88.6z m0 147.7h59V866h-59V747.9zM600 688.8h59V866h-59V688.8z m177.1-88.6h147.6v59H777.1v-59z m88.6-118h59v59h-59v-59z m-147.6 0h118.1v59H718.1v-59z m0 206.6h59v59h-59v-59z m147.6 59.1h-29.5v59h59v-59h29.5v-59h-59v59z m-147.6 59h59V866h-59v-59.1z m59 59.1h147.6v59H777.1v-59z m0 0" p-id="6488"></path></svg></a>
        </div>
    	<?php }?>
	    <?php }elseif($_GET['action'] == 'password'){?>
	    <div class="part passPart">
	    	<h2><a href="<?php bloginfo("url");?>"><img src="<?php echo _MBT('logo');?>" alt="<?php bloginfo("name");?>"></a></h2>
	        <form id="passform" class="loginform" method="post" novalidate="novalidate" onSubmit="return false;">
	            <p class="input-item">
	                <input class="input-control" id="passname" type="text" placeholder="用户名/电子邮箱" name="passname" required=""><i class="icon icon-user"></i>
	            </p>
	            <p class="sign-tips"></p>
	            <p class="input-submit">
	                <input class="submit pass-loader btn" type="submit" value="找回密码">
	                <input type="hidden" name="action" value="password">
	                <input type="hidden" id="security" name="security" value="<?php echo  wp_create_nonce( 'security_nonce' );?>">
        			<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
	            </p>
	            <p class="safe">
	                <a class="signin-loader" href="<?php echo add_query_arg('action','login',get_permalink(MBThemes_page('template/login.php')));?>">返回登录</a>
	            </p> 
	        </form>
	    </div>
        <?php }elseif($_GET['action'] == 'mp'){?>
        <div class="part mpPart">
            <form class="loginform" method="post" novalidate="novalidate" onSubmit="return false;">
                <p class="input-item">
                    <?php echo do_shortcode('[erphp_weixin_scan]');?>
                </p>
                <p class="sign-tips"></p>
                <p class="safe" style="text-align:center;margin-bottom: 10px">
                    <a class="signin-loader" style="float:none" href="<?php echo add_query_arg('action','login',get_permalink(MBThemes_page('template/login.php')));?>">使用其他方式登录/注册</a>
                </p> 
            </form>
        </div>
        <?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
	    <div class="expend-container">
            <a href="<?php echo add_query_arg('action','login',get_permalink(MBThemes_page('template/login.php')));?>" title="账号登录"><svg class="icon toggle" hidden style="padding:0.5rem;width: 4em; height: 4em;vertical-align: middle;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1166" data-spm-anchor-id="a313x.7781069.0.i0"><path d="M192 960h640v64H192v-64z" p-id="1167"></path><path d="M384 768h256v256H384v-256zM960 0H64a64 64 0 0 0-64 64v640a64 64 0 0 0 64 64h896a64 64 0 0 0 64-64V64a64 64 0 0 0-64-64z m0 704H64V64h896v640z" p-id="1168"></path><path d="M128 128h768v512H128V128z" p-id="1169"></path></svg></a>
        </div>
    	<?php }?>
	    <?php }elseif($_GET['action'] == 'reset_password'){?>
	    <div class="part resetPart">
	    	<h2><a href="<?php bloginfo("url");?>"><img src="<?php echo _MBT('logo');?>" alt="<?php bloginfo("name");?>"></a></h2>
	    <?php
	    	$reset_key = $_GET['key']; 
			$user_login = esc_sql($_GET['login']); 
			$user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email, user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));   
			$user_login = $user_data->user_login;   
			$user_email = $user_data->user_email;   
			if(!empty($reset_key) && !empty($user_data) && md5('mbt'.$user_data->user_activation_key) == $reset_key) {   
	    ?>

	        <form id="resetform" class="loginform" method="post" novalidate="novalidate" onSubmit="return false;">
	            <p class="input-item">
                    <input class="input-control" id="resetpass" type="password" placeholder="新密码" name="resetpass"><i class="icon icon-lock"></i>
                </p>
                <p class="input-item">
                    <input class="input-control" id="resetpass2" type="password" placeholder="确认新密码" name="resetpass2"><i class="icon icon-lock"></i>
                </p>
                <p class="sign-tips"></p>
                <p class="input-submit">
                    <input class="submit reset-loader btn" type="button" value="修改密码">
                    <input type="hidden" name="action" value="reset">
                    <input type="hidden" name="key" id="resetkey" value="<?php echo $reset_key;?>">
                    <input type="hidden" name="user_login" id="user_login" value="<?php echo $user_login;?>">
                    <input type="hidden" id="security" name="security" value="<?php echo  wp_create_nonce( 'security_nonce' );?>">
    				<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                </p>
	        </form>
	        <?php }else{?>
	        	<div class=regSuccess>错误的请求，请查看邮箱里的重置密码链接。</div>
	        <?php }?>
	    </div>
		<?php }elseif($_GET['action'] == 'sms'){?>
		<div class="part smsPart">
	    	<h2><a href="<?php bloginfo("url");?>"><img src="<?php echo _MBT('logo');?>" alt="<?php bloginfo("name");?>"></a></h2>
	        <form id="smsform" class="loginform" method="post" novalidate="novalidate" onSubmit="return false;" autocomplete="off">
	            <p class="input-item">
	                <input class="input-control" id="regmobile" type="text" placeholder="手机号" name="regmobile" required="" ><i class="icon icon-mobile"></i>
	            </p>
	            <p class="input-item">
	                <input class="input-control" id="captcha" type="text" placeholder="验证码" name="captcha" required="">
	                <span class="captcha-sms-clk">获取手机验证码</span><i class="icon icon-safe"></i>
	            </p>
	            <p class="sign-tips"></p>
	            <p class="input-submit">
	                <input class="submit mobile-loader btn" type="submit" value="快速登录">
	                <input type="hidden" name="action" value="register-mobile">
	                <input type="hidden" id="security" name="security" value="<?php echo  wp_create_nonce( 'security_nonce' );?>">
        			<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
        			<?php if(_MBT('register_policy')){?>
					<div class="form-policy"><input type="checkbox" id="policy_reg" name="policy_reg" value="1" checked><label for="policy_reg"> 我已阅读并同意《<a href="<?php echo _MBT('register_policy');?>" target="_blank">用户注册协议</a>》</label></div>
					<?php }?>
	            </p>
	            <p class="safe">
	            	<?php if(!_MBT('register')){?><a class="signup-loader right" href="<?php echo add_query_arg('action','register',get_permalink(MBThemes_page('template/login.php')));?>">&nbsp;&nbsp;注册账号</a><?php }?>
	                <a class="signin-loader" href="<?php echo add_query_arg('action','login',get_permalink(MBThemes_page('template/login.php')));?>">账号登录</a>
	            </p>
	            <?php if(_MBT('oauth_qq') || _MBT('oauth_weibo') || (_MBT('oauth_weixin') || (_MBT('oauth_weixin_mobile') && modown_is_mobile())) || (_MBT('oauth_weixin_mp') && function_exists('ews_login'))){?>
	            <div class="social-login sign-social">
	            	<div class="social-title"><span>使用第三方账号注册</span></div>
                	<?php if(_MBT('oauth_qq')){?>
                	<a href="<?php bloginfo("url");?>/oauth/qq?rurl=<?php if(isset($_GET['redirect_to'])) echo $_GET['redirect_to'];else echo get_permalink(MBThemes_page('template/user.php'));?>" rel="nofollow" class="login-qq"><i class="icon icon-qq"></i></a>
                	<?php }?>
                	<?php if(_MBT('oauth_weibo')){?>
                	<a href="<?php bloginfo("url");?>/oauth/weibo?rurl=<?php if(isset($_GET['redirect_to'])) echo $_GET['redirect_to'];else echo get_permalink(MBThemes_page('template/user.php'));?>" rel="nofollow" class="login-weibo"><i class="icon icon-weibo"></i></a>
                	<?php }?>
                	<?php if(_MBT('oauth_weixin') || _MBT('oauth_weixin_mobile')){?>
						<?php if(modown_is_mobile() && _MBT('oauth_weixin_mobile')){?>
						<a class="login-weixin" href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=<?php echo _MBT('oauth_weixinid_mobile');?>&redirect_uri=<?php echo home_url();?>/oauth/weixin/&response_type=code&scope=snsapi_userinfo&state=MBT_weixin_login#wechat_redirect" rel="nofollow"><i class="icon icon-weixin"></i></a>
						<?php }elseif(_MBT('oauth_weixin')){?>
						<a class="login-weixin" href="https://open.weixin.qq.com/connect/qrconnect?appid=<?php echo _MBT('oauth_weixinid');?>&redirect_uri=<?php echo home_url();?>/oauth/weixin/&response_type=code&scope=snsapi_login&state=MBT_weixin_login#wechat_redirect" rel="nofollow"><i class="icon icon-weixin"></i></a>
						<?php }?>
					<?php }?>
                	<?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
                	<a href="<?php echo add_query_arg('action','mp',get_permalink(MBThemes_page('template/login.php')));?>" class="login-weixin"><i class="icon icon-weixin"></i></a>
                	<?php }?>
	            </div>
	            <?php }?>
	        </form>
	    </div>
	    <?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
	    <div class="expend-container">
            <a href="<?php echo add_query_arg('action','mp',get_permalink(MBThemes_page('template/login.php')));?>" title="扫码登录"><svg class="icon toggle" style="width: 4em; height: 4em;vertical-align: middle;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6487"><path d="M540.9 866h59v59h-59v-59zM422.8 423.1V98.4H98.1v324.8h59v59h59v-59h206.7z m-265.7-59V157.4h206.7v206.7H157.1z m0 0M216.2 216.4h88.6V305h-88.6v-88.6zM600 98.4v324.8h324.8V98.4H600z m265.7 265.7H659V157.4h206.7v206.7z m0 0M718.1 216.4h88.6V305h-88.6v-88.6zM216.2 718.3h88.6v88.6h-88.6v-88.6zM98.1 482.2h59v59h-59v-59z m118.1 0h59.1v59h-59.1v-59z m0 0M275.2 600.2H98.1V925h324.8V600.2h-88.6v-59h-59v59z m88.6 59.1V866H157.1V659.3h206.7z m118.1-531.4h59v88.6h-59v-88.6z m0 147.6h59v59h-59v-59zM659 482.2H540.9v-88.6h-59v88.6H334.3v59H600v59h59v-118z m0 118h59.1v59H659v-59z m-177.1 0h59v88.6h-59v-88.6z m0 147.7h59V866h-59V747.9zM600 688.8h59V866h-59V688.8z m177.1-88.6h147.6v59H777.1v-59z m88.6-118h59v59h-59v-59z m-147.6 0h118.1v59H718.1v-59z m0 206.6h59v59h-59v-59z m147.6 59.1h-29.5v59h59v-59h29.5v-59h-59v59z m-147.6 59h59V866h-59v-59.1z m59 59.1h147.6v59H777.1v-59z m0 0" p-id="6488"></path></svg></a>
        </div>
    	<?php }?>
	    <?php }else{?>
	    <div class="part loginPart">
	    	<h2><a href="<?php bloginfo("url");?>"><img src="<?php echo _MBT('logo');?>" alt="<?php bloginfo("name");?>"></a></h2>
	        <form id="loginform" class="loginform" method="post" novalidate="novalidate" onSubmit="return false;">
	            <p class="input-item">
	                <input class="input-control" id="username" type="text" placeholder="用户名/邮箱" name="username" required="" aria-required="true"><i class="icon icon-user"></i>
	            </p>
	            <p class="input-item">
	                <input class="input-control" id="password" type="password" placeholder="密码" name="password" required="" aria-required="true"><i class="icon icon-lock"></i>
	            </p>
	            <?php if(_MBT('captcha_login')){?>
	            <p class="input-item">
	            	<input class="input-control" id="captcha" type="text" placeholder="验证码" name="captcha" required="">
	                <img src="<?php bloginfo("template_url");?>/static/img/captcha.png" class="captcha-clk2" /><i class="icon icon-safe"></i>
	            </p>
	        	<?php }?>
	            <p class="sign-tips"></p>
	            <p class="input-submit">
	                <input class="submit login-loader btn" type="submit" value="登录">
	                <input type="hidden" name="action" value="login">
	                <input type="hidden" id="security" name="security" value="<?php echo  wp_create_nonce( 'security_nonce' );?>">
        			<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
        			<input type="hidden" id="redirect_to" value="<?php echo $_GET['redirect_to']; ?>">
	            </p>
	            <p class="safe">
	                <a class="lostpwd-loader left" href="<?php echo add_query_arg('action','password',get_permalink(MBThemes_page('template/login.php')));?>">忘记密码？</a>
	                <?php if(!_MBT('register')){?><a class="signup-loader right" href="<?php echo add_query_arg('action','register',get_permalink(MBThemes_page('template/login.php')));?>">&nbsp;&nbsp;注册账号</a><?php }?>
	                <?php if(_MBT('oauth_sms')){?><a href="<?php echo add_query_arg('action','sms',get_permalink(MBThemes_page('template/login.php')));?>" class="signsms-loader">手机号登录</a><?php }?>
	            </p>
	            <?php if(_MBT('oauth_qq') || _MBT('oauth_weibo') || (_MBT('oauth_weixin') || (_MBT('oauth_weixin_mobile') && modown_is_mobile())) || (_MBT('oauth_weixin_mp') && function_exists('ews_login'))){?>
	            <div class="social-login sign-social">
	            	<div class="social-title"><span>使用第三方账号登录</span></div>
                	<?php if(_MBT('oauth_qq')){?>
                	<a href="<?php bloginfo("url");?>/oauth/qq?rurl=<?php if(isset($_GET['redirect_to'])) echo $_GET['redirect_to'];else echo get_permalink(MBThemes_page('template/user.php'));?>" rel="nofollow" class="login-qq"><i class="icon icon-qq"></i></a>
                	<?php }?>
                	<?php if(_MBT('oauth_weibo')){?>
                	<a href="<?php bloginfo("url");?>/oauth/weibo?rurl=<?php if(isset($_GET['redirect_to'])) echo $_GET['redirect_to'];else echo get_permalink(MBThemes_page('template/user.php'));?>" rel="nofollow" class="login-weibo"><i class="icon icon-weibo"></i></a>
                	<?php }?>
                	<?php if(_MBT('oauth_weixin') || _MBT('oauth_weixin_mobile')){?>
						<?php if(modown_is_mobile() && _MBT('oauth_weixin_mobile')){?>
						<a class="login-weixin" href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=<?php echo _MBT('oauth_weixinid_mobile');?>&redirect_uri=<?php echo home_url();?>/oauth/weixin/&response_type=code&scope=snsapi_userinfo&state=MBT_weixin_login#wechat_redirect" rel="nofollow"><i class="icon icon-weixin"></i></a>
						<?php }elseif(_MBT('oauth_weixin')){?>
						<a class="login-weixin" href="https://open.weixin.qq.com/connect/qrconnect?appid=<?php echo _MBT('oauth_weixinid');?>&redirect_uri=<?php echo home_url();?>/oauth/weixin/&response_type=code&scope=snsapi_login&state=MBT_weixin_login#wechat_redirect" rel="nofollow"><i class="icon icon-weixin"></i></a>
						<?php }?>
					<?php }?>
                	<?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
                	<a href="<?php echo add_query_arg('action','mp',get_permalink(MBThemes_page('template/login.php')));?>" class="login-weixin"><i class="icon icon-weixin"></i></a>
                	<?php }?>
	            </div>
	            <?php }?>
	        </form>
	    </div>
	    <?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
	    <div class="expend-container">
            <a href="<?php echo add_query_arg('action','mp',get_permalink(MBThemes_page('template/login.php')));?>" title="扫码登录"><svg class="icon toggle" style="width: 4em; height: 4em;vertical-align: middle;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6487"><path d="M540.9 866h59v59h-59v-59zM422.8 423.1V98.4H98.1v324.8h59v59h59v-59h206.7z m-265.7-59V157.4h206.7v206.7H157.1z m0 0M216.2 216.4h88.6V305h-88.6v-88.6zM600 98.4v324.8h324.8V98.4H600z m265.7 265.7H659V157.4h206.7v206.7z m0 0M718.1 216.4h88.6V305h-88.6v-88.6zM216.2 718.3h88.6v88.6h-88.6v-88.6zM98.1 482.2h59v59h-59v-59z m118.1 0h59.1v59h-59.1v-59z m0 0M275.2 600.2H98.1V925h324.8V600.2h-88.6v-59h-59v59z m88.6 59.1V866H157.1V659.3h206.7z m118.1-531.4h59v88.6h-59v-88.6z m0 147.6h59v59h-59v-59zM659 482.2H540.9v-88.6h-59v88.6H334.3v59H600v59h59v-118z m0 118h59.1v59H659v-59z m-177.1 0h59v88.6h-59v-88.6z m0 147.7h59V866h-59V747.9zM600 688.8h59V866h-59V688.8z m177.1-88.6h147.6v59H777.1v-59z m88.6-118h59v59h-59v-59z m-147.6 0h118.1v59H718.1v-59z m0 206.6h59v59h-59v-59z m147.6 59.1h-29.5v59h59v-59h29.5v-59h-59v59z m-147.6 59h59V866h-59v-59.1z m59 59.1h147.6v59H777.1v-59z m0 0" p-id="6488"></path></svg></a>
        </div>
    	<?php }?>
	    <?php }?>
	    <a href="<?php echo home_url();?>" class="return-home">返回首页</a>
	</div>
	<script type="text/javascript" src="<?php bloginfo("template_url");?>/static/js/login.js"></script>
    <?php if(function_exists('ews_login')){?>
    <script>var ews_ajax_url = "<?php echo admin_url().'admin-ajax.php';?>";</script>
    <script type="text/javascript" src="<?php echo EWS_URL;?>/assets/ews.js"></script>
    <?php }?>
</body>
</html>