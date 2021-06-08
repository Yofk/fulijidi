<div class="sign">			
	<div class="sign-mask"></div>			
	<div class="sign-box">			
		<div class="sign-tips"></div>			
		<form id="sign-in">  
		    <div class="form-item" style="text-align:center"><a href="<?php echo home_url();?>"><img class="logo-login" src="<?php echo _MBT('logo_login');?>" alt="<?php bloginfo('name');?>"></a></div>
			<div class="form-item"><input type="text" name="user_login" class="form-control" id="user_login" placeholder="用户名/邮箱"><i class="icon icon-user"></i></div>			
			<div class="form-item"><input type="password" name="password" class="form-control" id="user_pass" placeholder="密码"><i class="icon icon-lock"></i></div>		
			<?php if(_MBT('captcha_login')){?>
			<div class="form-item">
				<input type="text" class="form-control" id="user_captcha" name="user_captcha" placeholder="验证码"><img src="<?php bloginfo("template_url");?>/static/img/captcha.png" class="captcha-clk2"/>
				<i class="icon icon-safe"></i>
			</div>
			<?php }?>	
			<div class="sign-submit">			
				<input type="button" class="btn signinsubmit-loader" name="submit" value="登录">  			
				<input type="hidden" name="action" value="signin">			
			</div>			
			<div class="sign-trans"><?php if(!_MBT('register')){?>没有账号？ <a href="javascript:;" class="erphp-reg-must">注册</a>&nbsp;&nbsp;<?php }?><a href="<?php echo get_permalink(MBThemes_page("template/login.php"));?>?action=password" rel="nofollow" target="_blank">忘记密码？</a><?php if(_MBT('oauth_sms')){?><a href="javascript:;" class="signsms-loader" style="float:right;position: relative;top: -5px;"><i class="icon icon-mobile"></i>手机号登录</a><?php }?></div>	
			<?php if(_MBT('oauth_qq') || _MBT('oauth_weibo') || (_MBT('oauth_weixin') || (_MBT('oauth_weixin_mobile') && modown_is_mobile())) || (_MBT('oauth_weixin_mp') && function_exists('ews_login'))){?>			
			<div class="sign-social">
				<h2>社交账号快速登录</h2>
				<?php if(_MBT('oauth_qq')){?><a class="login-qq" href="<?php echo home_url();?>/oauth/qq?rurl=<?php echo MBThemes_selfURL();?>"><i class="icon icon-qq"></i></a><?php }?>
				<?php if(_MBT('oauth_weibo')){?><a class="login-weibo" href="<?php echo home_url();?>/oauth/weibo?rurl=<?php echo MBThemes_selfURL();?>"><i class="icon icon-weibo"></i></a><?php }?>
				<?php if(_MBT('oauth_weixin') || _MBT('oauth_weixin_mobile')){?>
					<?php if(modown_is_mobile() && _MBT('oauth_weixin_mobile')){?>
					<a class="login-weixin" href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=<?php echo _MBT('oauth_weixinid_mobile');?>&redirect_uri=<?php echo home_url();?>/oauth/weixin/&response_type=code&scope=snsapi_userinfo&state=MBT_weixin_login#wechat_redirect" rel="nofollow"><i class="icon icon-weixin"></i></a>
					<?php }elseif(_MBT('oauth_weixin')){?>
					<a class="login-weixin" href="https://open.weixin.qq.com/connect/qrconnect?appid=<?php echo _MBT('oauth_weixinid');?>&redirect_uri=<?php echo home_url();?>/oauth/weixin/&response_type=code&scope=snsapi_login&state=MBT_weixin_login#wechat_redirect" rel="nofollow"><i class="icon icon-weixin"></i></a>
					<?php }?>
				<?php }?>	
				<?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?><a class="login-weixin signmp-loader" href="javascript:;"><i class="icon icon-weixin"></i></a><?php }?>		
			</div>	
			<?php }?>	
			<?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
		    <div class="expend-container">
	            <a href="javascript:;" title="扫码登录" class="signmp-loader"><svg class="icon toggle" style="width: 4em; height: 4em;vertical-align: middle;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6487"><path d="M540.9 866h59v59h-59v-59zM422.8 423.1V98.4H98.1v324.8h59v59h59v-59h206.7z m-265.7-59V157.4h206.7v206.7H157.1z m0 0M216.2 216.4h88.6V305h-88.6v-88.6zM600 98.4v324.8h324.8V98.4H600z m265.7 265.7H659V157.4h206.7v206.7z m0 0M718.1 216.4h88.6V305h-88.6v-88.6zM216.2 718.3h88.6v88.6h-88.6v-88.6zM98.1 482.2h59v59h-59v-59z m118.1 0h59.1v59h-59.1v-59z m0 0M275.2 600.2H98.1V925h324.8V600.2h-88.6v-59h-59v59z m88.6 59.1V866H157.1V659.3h206.7z m118.1-531.4h59v88.6h-59v-88.6z m0 147.6h59v59h-59v-59zM659 482.2H540.9v-88.6h-59v88.6H334.3v59H600v59h59v-118z m0 118h59.1v59H659v-59z m-177.1 0h59v88.6h-59v-88.6z m0 147.7h59V866h-59V747.9zM600 688.8h59V866h-59V688.8z m177.1-88.6h147.6v59H777.1v-59z m88.6-118h59v59h-59v-59z m-147.6 0h118.1v59H718.1v-59z m0 206.6h59v59h-59v-59z m147.6 59.1h-29.5v59h59v-59h29.5v-59h-59v59z m-147.6 59h59V866h-59v-59.1z m59 59.1h147.6v59H777.1v-59z m0 0" p-id="6488"></path></svg></a>
	        </div>
	    	<?php }?>	
		</form>	
		<?php if(!_MBT('register')){?>		
		<form id="sign-up" style="display: none;"> 	
		    <div class="form-item" style="text-align:center"><a href="<?php echo home_url();?>"><img class="logo-login" src="<?php echo _MBT('logo_login');?>" alt="<?php bloginfo('name');?>"></a></div>				
			<div class="form-item"><input type="text" name="name" class="form-control" id="user_register" placeholder="用户名"><i class="icon icon-user"></i></div>			
			<div class="form-item"><input type="email" name="email" class="form-control" id="user_email" placeholder="邮箱"><i class="icon icon-mail"></i></div>		
			<div class="form-item"><input type="password" name="password2" class="form-control" id="user_pass2" placeholder="密码"><i class="icon icon-lock"></i></div>
			<?php if(_MBT('captcha') == 'email'){?>	
			<div class="form-item">
				<input type="text" class="form-control" id="captcha" name="captcha" placeholder="验证码"><span class="captcha-clk">获取邮箱验证码</span>
				<i class="icon icon-safe"></i>
			</div>	
			<?php }elseif(_MBT('captcha') == 'image'){?>
			<div class="form-item">
				<input type="text" class="form-control" id="captcha" name="captcha" placeholder="验证码"><img src="<?php bloginfo("template_url");?>/static/img/captcha.png" class="captcha-clk2"/>
				<i class="icon icon-safe"></i>
			</div>
			<?php }elseif(_MBT('captcha') == 'invitation' && function_exists('ashuwp_check_invitation_code')){?>	
			<div class="form-item">
				<input type="text" name="captcha" class="form-control" id="captcha" placeholder="邀请码">
				<i class="icon icon-safe"></i>
				<?php if(_MBT('invitation_link')){?><a href="<?php echo _MBT('invitation_link');?>" target="_blank" rel="nofollow" class="invitation-link">获取邀请码</a><?php }?>
			</div>
			<?php }?>	
			<div class="sign-submit">			
				<input type="button" class="btn signupsubmit-loader" name="submit" value="注册">  			
				<input type="hidden" name="action" value="signup">  	
				<?php if(_MBT('register_policy')){?>
				<div class="form-policy"><input type="checkbox" id="policy_reg" name="policy_reg" value="1" checked> <label for="policy_reg">我已阅读并同意《<a href="<?php echo _MBT('register_policy');?>" target="_blank">用户注册协议</a>》</label></div>
				<?php }?>			
			</div>			
			<div class="sign-trans">已有账号？ <a href="javascript:;" class="erphp-login-must">登录</a><?php if(_MBT('oauth_sms')){?>&nbsp;&nbsp;<a href="javascript:;" class="signsms-loader" style="float:right;position: relative;top: -5px;"><i class="icon icon-mobile"></i>手机号登录</a><?php }?></div>		
			<?php if(_MBT('oauth_qq') || _MBT('oauth_weibo') || (_MBT('oauth_weixin') || (_MBT('oauth_weixin_mobile') && modown_is_mobile())) || (_MBT('oauth_weixin_mp') && function_exists('ews_login'))){?>			
			<div class="sign-social">
				<h2>社交账号快速注册</h2>
				<?php if(_MBT('oauth_qq')){?><a class="login-qq" href="<?php echo home_url();?>/oauth/qq?rurl=<?php echo MBThemes_selfURL();?>"><i class="icon icon-qq"></i></a><?php }?>
				<?php if(_MBT('oauth_weibo')){?><a class="login-weibo" href="<?php echo home_url();?>/oauth/weibo?rurl=<?php echo MBThemes_selfURL();?>"><i class="icon icon-weibo"></i></a><?php }?>	
				<?php if(_MBT('oauth_weixin') || _MBT('oauth_weixin_mobile')){?>
					<?php if(modown_is_mobile() && _MBT('oauth_weixin_mobile')){?>
					<a class="login-weixin" href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=<?php echo _MBT('oauth_weixinid_mobile');?>&redirect_uri=<?php echo home_url();?>/oauth/weixin/&response_type=code&scope=snsapi_userinfo&state=MBT_weixin_login#wechat_redirect" rel="nofollow"><i class="icon icon-weixin"></i></a>
					<?php }elseif(_MBT('oauth_weixin')){?>
					<a class="login-weixin" href="https://open.weixin.qq.com/connect/qrconnect?appid=<?php echo _MBT('oauth_weixinid');?>&redirect_uri=<?php echo home_url();?>/oauth/weixin/&response_type=code&scope=snsapi_login&state=MBT_weixin_login#wechat_redirect" rel="nofollow"><i class="icon icon-weixin"></i></a>
					<?php }?>
				<?php }?>	
				<?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?><a class="login-weixin signmp-loader" href="javascript:;"><i class="icon icon-weixin"></i></a><?php }?>	
			</div>	
			<?php }?>
			<?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
		    <div class="expend-container">
	            <a href="javascript:;" title="扫码登录" class="signmp-loader"><svg class="icon toggle" style="width: 4em; height: 4em;vertical-align: middle;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6487"><path d="M540.9 866h59v59h-59v-59zM422.8 423.1V98.4H98.1v324.8h59v59h59v-59h206.7z m-265.7-59V157.4h206.7v206.7H157.1z m0 0M216.2 216.4h88.6V305h-88.6v-88.6zM600 98.4v324.8h324.8V98.4H600z m265.7 265.7H659V157.4h206.7v206.7z m0 0M718.1 216.4h88.6V305h-88.6v-88.6zM216.2 718.3h88.6v88.6h-88.6v-88.6zM98.1 482.2h59v59h-59v-59z m118.1 0h59.1v59h-59.1v-59z m0 0M275.2 600.2H98.1V925h324.8V600.2h-88.6v-59h-59v59z m88.6 59.1V866H157.1V659.3h206.7z m118.1-531.4h59v88.6h-59v-88.6z m0 147.6h59v59h-59v-59zM659 482.2H540.9v-88.6h-59v88.6H334.3v59H600v59h59v-118z m0 118h59.1v59H659v-59z m-177.1 0h59v88.6h-59v-88.6z m0 147.7h59V866h-59V747.9zM600 688.8h59V866h-59V688.8z m177.1-88.6h147.6v59H777.1v-59z m88.6-118h59v59h-59v-59z m-147.6 0h118.1v59H718.1v-59z m0 206.6h59v59h-59v-59z m147.6 59.1h-29.5v59h59v-59h29.5v-59h-59v59z m-147.6 59h59V866h-59v-59.1z m59 59.1h147.6v59H777.1v-59z m0 0" p-id="6488"></path></svg></a>
	        </div>
	    	<?php }?>	
		</form>	
		<?php }?>	
		<?php if(_MBT('oauth_sms')){?>
		<form id="sign-sms" style="display: none;">  
		    <div class="form-item" style="text-align:center"><a href="<?php echo home_url();?>"><img class="logo-login" src="<?php echo _MBT('logo_login');?>" alt="<?php bloginfo('name');?>"></a></div>
			<div class="form-item"><input type="text" name="user_mobile" class="form-control" id="user_mobile" placeholder="手机号"><i class="icon icon-mobile"></i></div>			
			<div class="form-item"><input type="text" name="user_mobile_captcha" class="form-control" id="user_mobile_captcha" placeholder="验证码"><span class="captcha-sms-clk">获取手机验证码</span><i class="icon icon-safe"></i></div>			
			<div class="sign-submit">			
				<input type="button" class="btn signsmssubmit-loader" name="submit" value="快速登录">  			
				<input type="hidden" name="action" value="signsms">		
				<?php if(_MBT('register_policy')){?>
				<div class="form-policy"><input type="checkbox" name="policy_sms" id="policy_sms" value="1" checked> <label for="policy_sms">我已阅读并同意《<a href="<?php echo _MBT('register_policy');?>" target="_blank">用户注册协议</a>》</label></div>
				<?php }?>	
			</div>			
			<div class="sign-trans">手机不在身边？ <a href="javascript:;" class="erphp-login-must">账号登录</a></div>	
			<?php if(_MBT('oauth_qq') || _MBT('oauth_weibo') || (_MBT('oauth_weixin') || (_MBT('oauth_weixin_mobile') && modown_is_mobile())) || (_MBT('oauth_weixin_mp') && function_exists('ews_login'))){?>			
			<div class="sign-social">
				<h2>社交账号快速登录</h2>
				<?php if(_MBT('oauth_qq')){?><a class="login-qq" href="<?php echo home_url();?>/oauth/qq?rurl=<?php echo MBThemes_selfURL();?>"><i class="icon icon-qq"></i></a><?php }?>
				<?php if(_MBT('oauth_weibo')){?><a class="login-weibo" href="<?php echo home_url();?>/oauth/weibo?rurl=<?php echo MBThemes_selfURL();?>"><i class="icon icon-weibo"></i></a><?php }?>
				<?php if(_MBT('oauth_weixin') || _MBT('oauth_weixin_mobile')){?>
					<?php if(modown_is_mobile() && _MBT('oauth_weixin_mobile')){?>
					<a class="login-weixin" href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=<?php echo _MBT('oauth_weixinid_mobile');?>&redirect_uri=<?php echo home_url();?>/oauth/weixin/&response_type=code&scope=snsapi_userinfo&state=MBT_weixin_login#wechat_redirect" rel="nofollow"><i class="icon icon-weixin"></i></a>
					<?php }elseif(_MBT('oauth_weixin')){?>
					<a class="login-weixin" href="https://open.weixin.qq.com/connect/qrconnect?appid=<?php echo _MBT('oauth_weixinid');?>&redirect_uri=<?php echo home_url();?>/oauth/weixin/&response_type=code&scope=snsapi_login&state=MBT_weixin_login#wechat_redirect" rel="nofollow"><i class="icon icon-weixin"></i></a>
					<?php }?>
				<?php }?>	
				<?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?><a class="login-weixin signmp-loader" href="javascript:;"><i class="icon icon-weixin"></i></a><?php }?>		
			</div>	
			<?php }?>	
			<?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
		    <div class="expend-container">
	            <a href="javascript:;" title="扫码登录" class="signmp-loader"><svg class="icon toggle" style="width: 4em; height: 4em;vertical-align: middle;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6487"><path d="M540.9 866h59v59h-59v-59zM422.8 423.1V98.4H98.1v324.8h59v59h59v-59h206.7z m-265.7-59V157.4h206.7v206.7H157.1z m0 0M216.2 216.4h88.6V305h-88.6v-88.6zM600 98.4v324.8h324.8V98.4H600z m265.7 265.7H659V157.4h206.7v206.7z m0 0M718.1 216.4h88.6V305h-88.6v-88.6zM216.2 718.3h88.6v88.6h-88.6v-88.6zM98.1 482.2h59v59h-59v-59z m118.1 0h59.1v59h-59.1v-59z m0 0M275.2 600.2H98.1V925h324.8V600.2h-88.6v-59h-59v59z m88.6 59.1V866H157.1V659.3h206.7z m118.1-531.4h59v88.6h-59v-88.6z m0 147.6h59v59h-59v-59zM659 482.2H540.9v-88.6h-59v88.6H334.3v59H600v59h59v-118z m0 118h59.1v59H659v-59z m-177.1 0h59v88.6h-59v-88.6z m0 147.7h59V866h-59V747.9zM600 688.8h59V866h-59V688.8z m177.1-88.6h147.6v59H777.1v-59z m88.6-118h59v59h-59v-59z m-147.6 0h118.1v59H718.1v-59z m0 206.6h59v59h-59v-59z m147.6 59.1h-29.5v59h59v-59h29.5v-59h-59v59z m-147.6 59h59V866h-59v-59.1z m59 59.1h147.6v59H777.1v-59z m0 0" p-id="6488"></path></svg></a>
	        </div>
	    	<?php }?>	
		</form>
		<?php }?>
		<?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
		<form id="sign-mp">
			<div class="form-item">
				<?php echo do_shortcode('[erphp_weixin_scan type=1]');?>
			</div>					
			<div class="sign-trans" style="text-align:center"><a href="javascript:;" class="erphp-login-must">使用其他方式登录/注册</a></div>
			<?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
		    <div class="expend-container">
	            <a href="javascript:;" title="账号登录" class="erphp-login-must"><svg class="icon toggle" hidden style="padding:0.5rem;width: 4em; height: 4em;vertical-align: middle;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1166" data-spm-anchor-id="a313x.7781069.0.i0"><path d="M192 960h640v64H192v-64z" p-id="1167"></path><path d="M384 768h256v256H384v-256zM960 0H64a64 64 0 0 0-64 64v640a64 64 0 0 0 64 64h896a64 64 0 0 0 64-64V64a64 64 0 0 0-64-64z m0 704H64V64h896v640z" p-id="1168"></path><path d="M128 128h768v512H128V128z" p-id="1169"></path></svg></a>
	        </div>
	    	<?php }?>
		</form>
		<?php }?>	
	</div>			
</div>