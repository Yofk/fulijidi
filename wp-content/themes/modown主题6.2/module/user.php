<div class="main"><?php global $current_user;?>
	<?php do_action("modown_main");?>
	<div class="container container-user">
	  <div class="userside">
	    <div class="usertitle"> 
	    	<a href="javascript:;" class="edit-avatar"<?php if(!_MBT('user_avatar')){?> evt="user.avatar.submit"<?php }?> title="<?php if(!_MBT('user_avatar')) echo '点击修改头像'; else echo '暂未开放上传头像功能';?>"><?php echo get_avatar($current_user->ID,50);?></a>
	      	<h2><?php echo $current_user->nickname;?></h2>
	      	<?php if(!_MBT('user_avatar')){?>
	      	<form id="uploadphoto" action="<?php echo get_bloginfo('template_url').'/action/photo.php';?>" method="post" enctype="multipart/form-data" style="display:none;">
	            <input type="file" id="avatarphoto" name="avatarphoto" accept="image/png, image/jpeg">
	        </form>
	    	<?php }?>
	    </div>
	    <div class="usermenus">
	      <ul class="usermenu">
	        <li class="usermenu-user <?php if($_GET['action'] == 'info' || !isset($_GET['action'])) echo 'active';?>"><a href="<?php echo add_query_arg('action','info',get_permalink())?>"><i class="icon icon-info"></i> 我的资料</a></li>
			<li class="usermenu-comments <?php if(isset($_GET['action']) && $_GET['action'] == 'comment') echo 'active';?>"><a href="<?php echo add_query_arg('action','comment',get_permalink())?>"><i class="icon icon-comments"></i> 我的评论</a></li>
			<?php if(_MBT('user_sell')){?>
			<li class="usermenu-post <?php if(isset($_GET['action']) && $_GET['action'] == 'post') echo 'active';?>"><a href="<?php echo add_query_arg('action','post',get_permalink())?>"><i class="icon icon-posts"></i> 我的投稿</a></li>
			<?php }?>
			<?php if(_MBT('post_collect')){?><li class="usermenu-collect <?php if(isset($_GET['action']) && $_GET['action'] == 'collect') echo 'active';?>"><a href="<?php echo add_query_arg('action','collect',get_permalink())?>"><i class="icon icon-stars"></i> 我的收藏</a></li><?php }?>
			<?php if(_MBT('ticket')){?>
			<li class="usermenu-ticket <?php if(isset($_GET['action']) && $_GET['action'] == 'ticket') echo 'active';?>"><a href="<?php echo add_query_arg('action','ticket',get_permalink())?>"><i class="icon icon-temp-new"></i> 提交工单</a></li>
			<li class="usermenu-tickets <?php if(isset($_GET['action']) && $_GET['action'] == 'tickets') echo 'active';?>"><a href="<?php echo add_query_arg('action','tickets',get_permalink())?>"><i class="icon icon-temp"></i> 我的工单</a></li>
			<?php }?>
	        <li class="usermenu-password <?php if(isset($_GET['action']) && $_GET['action'] == 'password') echo 'active';?>"><a href="<?php echo add_query_arg('action','password',get_permalink())?>"><i class="icon icon-lock"></i> 修改密码</a></li>
	        <li class="usermenu-signout"><a href="<?php echo wp_logout_url(get_bloginfo("url"));?>"><i class="icon icon-signout"></i> 安全退出</a></li>
	      </ul>
	    </div>
	  </div>
	  <div class="content" id="contentframe">
	    <div class="user-main">
	      
	      <?php if($_GET['action'] == 'comment'){ ?>
	      	  <!---------------------------------------------------我的评论开始-->
	          <?php 
			  	$perpage = 10;
				if (!get_query_var('paged')) {
					$paged = 1;
				}else{
					$paged = $wpdb->escape(get_query_var('paged'));
				}
				$total_comment = $wpdb->get_var("select count(comment_ID) from $wpdb->comments where comment_approved='1' and user_id=".$current_user->ID);
				$pagess = ceil($total_comment / $perpage);
				$offset = $perpage*($paged-1);
				$results = $wpdb->get_results("select $wpdb->comments.comment_ID,$wpdb->comments.comment_post_ID,$wpdb->comments.comment_content,$wpdb->comments.comment_date,$wpdb->posts.post_title from $wpdb->comments left join $wpdb->posts on $wpdb->comments.comment_post_ID = $wpdb->posts.ID where $wpdb->comments.comment_approved='1' and $wpdb->comments.user_id=".$current_user->ID." order by $wpdb->comments.comment_date DESC limit $offset,$perpage");
				if($results){
			  ?>
	          <ul class="user-commentlist">
	            <?php foreach($results as $result){?>
	          	<li><time><?php echo $result->comment_date;?></time><p class="note"><?php echo $result->comment_content;?></p><p class="text-muted">文章：<a target="_blank" href="<?php echo get_permalink($result->comment_post_ID);?>"><?php echo $result->post_title;?></a></p></li>
	            <?php }?>
	          </ul>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无评论！</h6></div>
	          <?php }?>
	          <!---------------------------------------------------我的评论结束-->
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'post'){
	      		$totallists = $wpdb->get_var("SELECT count(ID) FROM $wpdb->posts WHERE post_author=".$current_user->ID." and post_status='publish' and post_type='post'");
				$perpage = 10;
				$pagess = ceil($totallists / $perpage);
				if (!get_query_var('paged')) {
					$paged = 1;
				}else{
					$paged = $wpdb->escape(get_query_var('paged'));
				}
				$offset = $perpage*($paged-1);
				$lists = $wpdb->get_results("SELECT * FROM $wpdb->posts where post_author=".$current_user->ID." and post_status='publish' and post_type='post' order by post_date DESC limit $offset,$perpage");
	      ?>
	      	  <?php if($lists) {?>
	          <ul class="user-postlist">
	          	<?php foreach($lists as $value){ $post = get_post($value->ID); setup_postdata($post);?>
	          	<li>
					<img class="thumb" src="<?php echo MBThemes_thumbnail();?>">
					<h2><a target="_blank" href="<?php the_permalink($value->ID);?>"><?php the_title();?></a></h2>
					<p class="note"><?php echo MBThemes_get_excerpt();?></p>
					<p class="text-muted"><?php echo $value->post_date;?></p>
				</li>
	          	<?php }?>
	          </ul>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无记录！</h6></div>
	          <?php }?>
	      <?php }elseif($_GET['action'] == 'collect'){ ?>
	      	  <!---------------------------------------------------我的收藏开始-->
	          <?php 
			  	$perpage = 10;
				if (!get_query_var('paged')) {
					$paged = 1;
				}else{
					$paged = $wpdb->escape(get_query_var('paged'));
				}
				$total_collect = $wpdb->get_var("select count(ID) from ".$wpdb->prefix."collects where user_id=".$current_user->ID);
				$pagess = ceil($total_collect / $perpage);
				$offset = $perpage*($paged-1);
				$results = $wpdb->get_results("select * from ".$wpdb->prefix."collects where user_id=".$current_user->ID." order by create_time DESC limit $offset,$perpage");
				if($results){
			  ?>
	          <ul class="user-commentlist">
	            <?php foreach($results as $result){?>
	          	<li><time><?php echo $result->create_time;?></time><p class="note"><a href="<?php the_permalink($result->post_id);?>" target="_blank"><?php echo get_the_title($result->post_id);?></a></p><p class="text-muted"><a href="javascript:;" class="article-collect" data-id="<?php echo $result->post_id;?>" title="取消收藏">取消收藏</a></li>
	            <?php }?>
	          </ul>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无收藏！</h6></div>
	          <?php }?>
	          <!---------------------------------------------------我的收藏结束-->
	      <?php }elseif($_GET['action'] == 'password'){ ?>
	      	  <!---------------------------------------------------修改密码开始-->
	          <form>
	            <ul class="user-meta">
	              <li>
	                <label>原密码</label>
	                <input type="password" class="form-control" name="passwordold">
	              </li>
	              <li>
	                <label>新密码</label>
	                <input type="password" class="form-control" name="password">
	              </li>
	              <li>
	                <label>重复新密码</label>
	                <input type="password" class="form-control" name="password2">
	              </li>
	              <li>
	                <input type="button" evt="user.data.submit" class="btn btn-primary" value="修改密码">
	                <input type="hidden" name="action" value="user.password">
	              </li>
	            </ul>
	          </form>
	          <!---------------------------------------------------修改密码结束-->
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'ticket'){ 
	      		if(function_exists('modown_ticket_new_html')){
	      			modown_ticket_new_html();
	      		}else{
	      			echo '您暂未购买此扩展功能，如需要请联系QQ82708210。';
	      		}
	      }elseif(isset($_GET['action']) && $_GET['action'] == 'tickets'){ 
	      		if(function_exists('modown_ticket_list_html')){
	      			modown_ticket_list_html();
	      		}else{
	      			echo '您暂未购买此扩展功能，如需要请联系QQ82708210。';
	      		}
	      }else{?>
	          <!---------------------------------------------------我的资料开始-->
	          <form>
	            <ul class="user-meta">
	              <li>
	                <label>用户名</label>
	                <?php echo $current_user->user_login;?> </li>
	              <li>
	                <label>昵称</label>
	                <input type="input" class="form-control" name="nickname" value="<?php echo $current_user->nickname;?>">
	              </li>
	              <li>
	                <label>QQ</label>
	                <input type="input" class="form-control" name="qq" value="<?php echo get_user_meta($current_user->ID, 'qq', true);?>">
	              </li>
	              <li>
	                <label>个性签名</label>
	                <textarea class="form-control" name="description" rows="5" style="height: 80px;padding: 5px 10px;"><?php echo $current_user->description;?></textarea>
	              </li>
	              <li>
	                <input type="button" evt="user.data.submit" class="btn btn-primary" value="修改资料">
	                <input type="hidden" name="action" value="user.edit">
	              </li>
	            </ul>
	          </form>
	          <form>
	            <ul class="user-meta">
	            <li>
	                <label>邮箱</label>
	                <input type="email" class="form-control" name="email" value="<?php echo $current_user->user_email;?>">
	              </li>
	              <li>
	                <label>验证码</label>
	                <input type="text" class="form-control" name="captcha" value="" style="width:150px;display:inline-block"> <a evt="user.email.captcha.submit" style="display:inline-block;font-size: 13px;cursor: pointer;" id="captcha_btn">获取验证码</a>
	              </li>
	              <li>
	                <input type="button" evt="user.email.submit" class="btn btn-primary" value="修改邮箱">
	                <input type="hidden" name="action" value="user.email">
	              </li>               
	             </ul>
	          </form>
	          <?php if(_MBT('oauth_sms')){
	          	$mobile = $wpdb->get_var("select mobile from $wpdb->users where ID=".$current_user->ID);
	          	?>
	          <form style="margin-bottom: 30px">
	            <ul class="user-meta">
	            <li>
	                <label>手机号</label>
	                <input type="text" class="form-control" name="mobile" value="<?php echo $mobile;?>">
	              </li>
	              <li>
	                <label>验证码</label>
	                <input type="text" class="form-control" name="captcha" value="" style="width:150px;display:inline-block"> <a evt="user.mobile.captcha.submit" style="display:inline-block;font-size: 13px;cursor: pointer;"><i class="icon icon-mobile"></i> 获取验证码</a>
	              </li>
	              <li>
	                <input type="button" evt="user.mobile.submit" class="btn btn-primary" value="修改手机号">
	                <input type="hidden" name="action" value="user.mobile">
	              </li>               
	             </ul>
	          </form>
	          <?php }?>
	          <?php if(_MBT('oauth_qq') || _MBT('oauth_weibo') || (_MBT('oauth_weixin') || (_MBT('oauth_weixin_mobile') && modown_is_mobile())) || _MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
	          	<ul class="user-meta">
				<li class="secondItem">
					<?php 
						$userSocial = $wpdb->get_row("select qqid,sinaid,weixinid from $wpdb->users where ID=".$current_user->ID);
					?>
					<label>社交账号绑定</label>
					<?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
					<section class="item">
						<section class="platform weixin">
							<i class="icon icon-weixin"></i>
						</section>
						<section class="platform-info">
							<p class="name">微信</p><p class="status">
							<?php if($userSocial->weixinid){?>
							<span>已绑定</span>
							<a href="javascript:;" evt="user.social.cancel" data-type="weixin">取消绑定</a>
							<?php }else{?>
							<a href="javascript:;" evt="user.social.ews.bind">立即绑定</a>
							<div class="erphp-weixin-scan-bind"><?php echo do_shortcode('[erphp_weixin_scan_bind type=1]');?></div>
							<?php }?>
							</p>
						</section>
					</section>
					<?php }?>
					<?php if(_MBT('oauth_weixin') || (_MBT('oauth_weixin_mobile') && modown_is_mobile())){?>
					<section class="item">
						<section class="platform weixin">
							<i class="icon icon-weixin"></i>
						</section>
						<section class="platform-info">
							<p class="name">微信</p><p class="status">
							<?php if($userSocial->weixinid){?>
							<span>已绑定</span>
							<a href="javascript:;" evt="user.social.cancel" data-type="weixin">取消绑定</a>
							<?php }else{?>
								<?php if(modown_is_mobile() && _MBT('oauth_weixin_mobile')){?>
								<a class="login-weixin" href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=<?php echo _MBT('oauth_weixinid_mobile');?>&redirect_uri=<?php echo home_url();?>/oauth/weixin/bind.php&response_type=code&scope=snsapi_userinfo&state=MBT_weixin_login#wechat_redirect" rel="nofollow">立即绑定</a>
								<?php }elseif(_MBT('oauth_weixin')){?>
								<a href="https://open.weixin.qq.com/connect/qrconnect?appid=<?php echo _MBT('oauth_weixinid');?>&redirect_uri=<?php bloginfo("url")?>/oauth/weixin/bind.php&response_type=code&scope=snsapi_login&state=MBT_weixin_login#wechat_redirect" >立即绑定</a>
								<?php }?>
							<?php }?>
							</p>
						</section>
					</section>
					<?php }?>
					<?php if(_MBT('oauth_weibo')){?>
					<section class="item">
						<section class="platform weibo">
							<i class="icon icon-weibo"></i>
						</section>
						<section class="platform-info">
							<p class="name">微博</p><p class="status">
							<?php if($userSocial->sinaid){?>
							<span>已绑定</span>
							<a href="javascript:;" evt="user.social.cancel" data-type="weibo">取消绑定</a>
							<?php }else{?>
							<a href="<?php bloginfo("url");?>/oauth/weibo/bind.php?rurl=<?php echo get_permalink(MBThemes_page('template/user.php'));?>?action=info" >立即绑定</a>
							<?php }?>
							</p>
						</section>
					</section>
					<?php }?>
					<?php if(_MBT('oauth_qq')){?>
					<section class="item">
						<section class="platform qq">
							<i class="icon icon-qq"></i>
						</section>
						<section class="platform-info">
							<p class="name">QQ</p><p class="status">
							<?php if($userSocial->qqid){?>
							<span>已绑定</span>
							<a href="javascript:;" evt="user.social.cancel" data-type="qq">取消绑定</a>
							<?php }else{?>
							<a href="<?php bloginfo("url");?>/oauth/qq/bind.php?rurl=<?php echo get_permalink(MBThemes_page('template/user.php'));?>?action=info" >立即绑定</a>
							<?php }?>
							</p>
						</section>
					</section>
					<?php }?>
				</li>
				</ul>
				<?php }?>
				<div class="user-alerts">
	          	  <h4>注意事项：</h4>
	          	  <ul>
	                      <li>请务必修改成你正确的邮箱地址，以便于忘记密码时用来重置密码。</li>
	                      <li>获取验证码时，邮件发送时间有时会稍长，请您耐心等待。</li>
	                 </ul>
	          </div>
	          <!---------------------------------------------------我的资料结束-->
	      <?php }?>
	    </div>
	    <div class="user-tips"></div>
	  </div>
	</div>
	<script src="<?php bloginfo("template_url")?>/static/js/user.js"></script>
</div>