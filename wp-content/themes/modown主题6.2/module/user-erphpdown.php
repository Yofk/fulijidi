<?php 
	global $current_user;
	$moneyName = get_option('ice_name_alipay');
	$okMoney = erphpGetUserOkMoney();
	$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
	$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
	$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
	$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
	$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';
	$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';
?>
<div class="main">
	<?php 
		if(_MBT('user_force_email') && !$current_user->user_email){
			echo '<div class="container"><div class="warning"><i class="icon icon-smile"></i> 为了确保账号安全，请先绑定邮箱再进行其他操作！</div></div>';
		}
	?>
	<?php do_action("modown_main");?>
	<div class="container container-user">
	  <div class="userside">
	    <div class="usertitle"> 
	    	<a href="javascript:;" class="edit-avatar"<?php if(!_MBT('user_avatar')){?> evt="user.avatar.submit"<?php }?> title="<?php if(!_MBT('user_avatar')) echo '点击修改头像'; else echo '暂未开放上传头像功能';?>"><?php echo get_avatar($current_user->ID,50);?></a>
	        <h2><?php echo $current_user->nickname;?></h2>
	        <?php
	        	if(_MBT('checkin')) {
			        if(MBThemes_check_checkin($current_user->ID)){
			      		echo '<div class="mobantu-check"><a href="javascript:;" class="usercheck active">已签到</a><p>每日签到送'.$moneyName.'</p></div>';
			        }else{
			      		echo '<div class="mobantu-check"><a href="javascript:;" class="usercheck checkin">今日签到</a><p>每日签到送'.$moneyName.'</p></div>';
			        }
			    }
	        ?>
	        <?php if(!_MBT('user_avatar')){?>
	        <form id="uploadphoto" action="<?php echo get_bloginfo('template_url').'/action/photo.php';?>" method="post" enctype="multipart/form-data" style="display:none;">
	            <input type="file" id="avatarphoto" name="avatarphoto" accept="image/png, image/jpeg">
	        </form>
	    	<?php }?>
	        <?php if(function_exists('erphpad_install')){?>
	        <form id="uploadad" action="<?php echo get_bloginfo('template_url').'/action/ad.php';?>" method="post" enctype="multipart/form-data" style="display:none;">
	            <input type="file" id="adimage" name="adimage" accept="image/png, image/jpeg, image/gif">
	        </form>
	    	<?php }?>
	    </div>
	    <div class="usermenus">
	      <ul class="usermenu">
	      	<?php if ( class_exists( 'WooCommerce', false ) ) {?><li class="usermenu-cart"><a href="<?php echo wc_get_page_permalink( 'myaccount' );?>"><i class="icon icon-cart"></i> 我的购物</a></li><?php }?>
	        <li class="usermenu-charge <?php if((isset($_GET['action']) && $_GET['action'] == 'charge') || !isset($_GET['action'])) echo 'active';?>"><a href="<?php echo add_query_arg('action','charge',get_permalink())?>"><i class="icon icon-money"></i> 在线充值</a></li>
	        <?php if(!_MBT('vip_hidden')){?><li class="usermenu-vip <?php if(isset($_GET['action']) && $_GET['action'] == 'vip') echo 'active';?>"><a href="<?php echo add_query_arg('action','vip',get_permalink())?>"><i class="icon icon-crown"></i> 升级 <?php echo $erphp_vip_name;?></a></li><?php }?>
	        <li class="usermenu-history <?php if(isset($_GET['action']) && $_GET['action'] == 'history') echo 'active';?>"><a href="<?php echo add_query_arg('action','history',get_permalink())?>"><i class="icon icon-wallet"></i> 充值记录</a></li>
	        <?php if(plugin_check_cred() && get_option('erphp_mycred') == 'yes'){ $mycred_core = get_option('mycred_pref_core');?>
	        <li class="usermenu-mycred <?php if(isset($_GET['action']) && $_GET['action'] == 'mycred') echo 'active';?>"><a href="<?php echo add_query_arg('action','mycred',get_permalink())?>"><i class="icon icon-gift"></i> 积分记录</a></li>
	    	<?php }?>
	        <li class="usermenu-order <?php if(isset($_GET['action']) && $_GET['action'] == 'order') echo 'active';?>"><a href="<?php echo add_query_arg('action','order',get_permalink())?>"><i class="icon icon-order2"></i> 购买记录</a></li>
	        <?php if(_MBT('user_sell')){?>
	        <li class="usermenu-sell <?php if(isset($_GET['action']) && $_GET['action'] == 'sell') echo 'active';?>"><a href="<?php echo add_query_arg('action','sell',get_permalink())?>"><i class="icon icon-order-menu"></i> 销售订单</a></li>
	    	<?php }?>
	    	<?php if(function_exists('erphpdown_tuan_install')){?>
			<li class="usermenu-tuan <?php if(isset($_GET['action']) && $_GET['action'] == 'tuan') echo 'active';?>"><a href="<?php echo add_query_arg('action','tuan',get_permalink())?>"><i class="icon icon-tuan"></i> 我的拼团</a></li>
			<?php }?>
	    	<?php if(!_MBT('user_aff')){?>
	        <li class="usermenu-aff <?php if(isset($_GET['action']) && $_GET['action'] == 'aff') echo 'active';?>"><a href="<?php echo add_query_arg('action','aff',get_permalink())?>"><i class="icon icon-aff"></i> 我的推广</a></li>
	    	<?php }?>
	        <?php if(_MBT('withdraw')){?>
	        <li class="usermenu-withdraw <?php if(isset($_GET['action']) && $_GET['action'] == 'withdraw') echo 'active';?>"><a href="<?php echo add_query_arg('action','withdraw',get_permalink())?>"><i class="icon icon-withdraw"></i> 站内提现</a></li>
	        <li class="usermenu-withdraws <?php if(isset($_GET['action']) && $_GET['action'] == 'withdraws') echo 'active';?>"><a href="<?php echo add_query_arg('action','withdraws',get_permalink())?>"><i class="icon icon-withdraws"></i> 我的提现</a></li>
	    	<?php }?>
	        <li class="usermenu-user <?php if(isset($_GET['action']) && $_GET['action'] == 'info') echo 'active';?>"><a href="<?php echo add_query_arg('action','info',get_permalink())?>"><i class="icon icon-info"></i> 我的资料</a></li>
	        <?php if(function_exists('QAPress_scripts')){?>
	        <li class="usermenu-faqs <?php if(isset($_GET['action']) && $_GET['action'] == 'faq') echo 'active';?>"><a href="<?php echo add_query_arg('action','faq',get_permalink())?>"><i class="icon icon-discuz"></i> 我的提问</a></li>
	        <?php }?>
			<li class="usermenu-comments <?php if(isset($_GET['action']) && $_GET['action'] == 'comment') echo 'active';?>"><a href="<?php echo add_query_arg('action','comment',get_permalink())?>"><i class="icon icon-comments"></i> 我的评论</a></li>
			<?php if(_MBT('user_sell')){?>
			<li class="usermenu-post <?php if(isset($_GET['action']) && $_GET['action'] == 'post') echo 'active';?>"><a href="<?php echo add_query_arg('action','post',get_permalink())?>"><i class="icon icon-posts"></i> 我的投稿</a></li>
			<?php }?>
			<?php if(function_exists('erphp_task_scripts')){?>
			<li class="usermenu-post <?php if(isset($_GET['action']) && $_GET['action'] == 'task') echo 'active';?>"><a href="<?php echo add_query_arg('action','task',get_permalink())?>"><i class="icon icon-posts"></i> 我的任务</a></li>
			<?php }?>
			<?php if(_MBT('post_collect')){?><li class="usermenu-collect <?php if(isset($_GET['action']) && $_GET['action'] == 'collect') echo 'active';?>"><a href="<?php echo add_query_arg('action','collect',get_permalink())?>"><i class="icon icon-stars"></i> 我的收藏</a></li><?php }?>
			<?php if(_MBT('ticket')){?>
			<li class="usermenu-ticket <?php if(isset($_GET['action']) && $_GET['action'] == 'ticket') echo 'active';?>"><a href="<?php echo add_query_arg('action','ticket',get_permalink())?>"><i class="icon icon-temp-new"></i> 提交工单</a></li>
			<li class="usermenu-tickets <?php if(isset($_GET['action']) && $_GET['action'] == 'tickets') echo 'active';?>"><a href="<?php echo add_query_arg('action','tickets',get_permalink())?>"><i class="icon icon-temp"></i> 我的工单</a></li>
			<?php }?>
			<?php if(function_exists('erphpad_install')){?>
			<li class="usermenu-ad <?php if(isset($_GET['action']) && $_GET['action'] == 'ad') echo 'active';?>"><a href="<?php echo add_query_arg('action','ad',get_permalink())?>"><i class="icon icon-data"></i> 我的广告</a></li>
			<?php }?>
	        <li class="usermenu-password <?php if(isset($_GET['action']) && $_GET['action'] == 'password') echo 'active';?>"><a href="<?php echo add_query_arg('action','password',get_permalink())?>"><i class="icon icon-lock"></i> 修改密码</a></li>
	        <li class="usermenu-signout"><a href="<?php echo wp_logout_url(get_bloginfo("url"));?>"><i class="icon icon-signout"></i> 安全退出</a></li>
	      </ul>
	    </div>
	  </div>
	  <div class="content" id="contentframe">
	    <div class="user-main">
	      <?php if(isset($_GET['action']) && $_GET['action'] == 'ad'){ ?>
	      	  <?php 
	      	  		global $erphpad_table;
			  	    $totallists = $wpdb->get_var("SELECT count(id) FROM $erphpad_table WHERE user_id=".$current_user->ID." and order_status=1");
					$perpage = 15;
					$pagess = ceil($totallists / $perpage);
					if (!get_query_var('paged')) {
						$paged = 1;
					}else{
						$paged = $wpdb->escape(get_query_var('paged'));
					}
					$offset = $perpage*($paged-1);
					$lists = $wpdb->get_results("SELECT * FROM $erphpad_table where user_id=".$current_user->ID." and order_status=1 order by order_time DESC limit $offset,$perpage");
			  ?>
	          <?php if($lists) {?>
	          <table class="table table-striped table-hover user-orders">
	          	  <thead>
	              	  <tr>
	          			<th width="15%">广告位</th>
	          			<th width="10%" class="pc">金额</th>
	                    <th width="20%">生效时间</th>
	                    <th width="10%">周期</th>
	                    <th width="10%">状态</th>
	                    <th width="15%">说明</th>
	                    <th width="20%">操作</th>
	                  </tr>
	              </thead>
	              <tbody>
	              <?php foreach($lists as $value){?>
	            	  <tr>
	                  	<td><?php echo erphpad_get_pos_name($value->pos_id);?></td>
	                  	<td class="pc"><?php echo $value->order_price;?></td>
	                  	<td><?php echo $value->order_time;?></td>
	                  	<td><?php echo $value->order_cycle;?>天</td>
	                  	<td><?php echo $value->order_status == 1?'正常':'过期';?></td>
	                  	<td><?php echo erphpad_get_pos($value->pos_id)->pos_tips;?></td>
	                  	<td><a href="javascript:;" data-id="<?php echo $value->id;?>" class="erphpad-edit-loader">修改广告</a></td>
	                  </tr>
			      <?php }?>
	              </tbody>
	          </table>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无记录！</h6></div>
	          <?php }?>
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'tuan'){ ?>
	      	  <?php 
			  	    $totallists = $wpdb->get_var("SELECT count(ice_id) FROM $wpdb->tuanorder WHERE ice_user_id=".$current_user->ID." and ice_status>0");
					$perpage = 15;
					$pagess = ceil($totallists / $perpage);
					if (!get_query_var('paged')) {
						$paged = 1;
					}else{
						$paged = $wpdb->escape(get_query_var('paged'));
					}
					$offset = $perpage*($paged-1);
					$lists = $wpdb->get_results("SELECT * FROM $wpdb->tuanorder where ice_user_id=".$current_user->ID." and ice_status>0 order by ice_time DESC limit $offset,$perpage");
			  ?>
	          <?php if($lists) {?>
	          <table class="table table-striped table-hover user-orders">
	          	  <thead>
	              	  <tr>
	          			<th style="text-align: left;">商品名称</th>
	          			<th class="pc">订单号</th>
	                    <th>价格</th>
	                    <th>时间</th>
	                    <th>进度</th>
	                    <th>状态</th>
	                  </tr>
	              </thead>
	              <tbody>
	              <?php foreach($lists as $value){?>
	            	  <tr>
	            	  	<td style="text-align: left;"><a target="_blank" href="<?php echo get_permalink($value->ice_post);?>"><?php echo get_post($value->ice_post)->post_title;?></a></td>
	                  	<td class="pc"><?php echo $value->ice_num;?></td>
	                  	<td><?php echo $value->ice_price;?></td>
	                  	<td><?php echo $value->ice_time;?></td>
	                  	<td><?php echo get_erphpdown_tuan_percent($value->ice_post,$value->ice_tuan_num);?>%</td>
	                  	<td><?php echo $value->ice_status == 1?'进行中':'已完成';?></td>
	                  </tr>
			      <?php }?>
	              </tbody>
	          </table>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无记录！</h6></div>
	          <?php }?>
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'vip'){
	      	$erphp_year_price    = get_option('ciphp_year_price');
            $erphp_quarter_price = get_option('ciphp_quarter_price');
            $erphp_month_price  = get_option('ciphp_month_price');
            $erphp_day_price  = get_option('ciphp_day_price');
            $erphp_life_price  = get_option('ciphp_life_price');
            $userTypeId=getUsreMemberType();
            $moneyVipName = $moneyName;

            if(_MBT('vip_only_pay')){
            	if($erphp_life_price) $erphp_life_price = $erphp_life_price/get_option('ice_proportion_alipay');
            	if($erphp_year_price) $erphp_year_price = $erphp_year_price/get_option('ice_proportion_alipay');
            	if($erphp_quarter_price) $erphp_quarter_price = $erphp_quarter_price/get_option('ice_proportion_alipay');
            	if($erphp_month_price) $erphp_month_price = $erphp_month_price/get_option('ice_proportion_alipay');
            	if($erphp_day_price) $erphp_day_price = $erphp_day_price/get_option('ice_proportion_alipay');
            	$moneyVipName = '元';
            }
	      ?>
	          <div class="charge vip">
	                <div class="charge-header clearfix">
	                	<div class="item">
	                		<b class="color"><?php echo sprintf("%.2f",$okMoney);?></b><?php echo ' '.$moneyName;?>
	                		<p>可用余额</p>
	                	</div>
	                	<div class="item item-pc">
	                		<?php 
		                    if($userTypeId==6){
		                        echo "<b>".$erphp_day_name."</b>";
		                    }elseif($userTypeId==7){
		                        echo "<b>".$erphp_month_name."</b>";
		                    }elseif ($userTypeId==8){
		                        echo "<b>".$erphp_quarter_name."</b>";
		                    }elseif ($userTypeId==9){
		                        echo "<b>".$erphp_year_name."</b>";
		                    }elseif ($userTypeId==10){
		                        echo "<b>".$erphp_life_name."</b>";
		                    }else {
		                        echo '<b>普通用户</b>';
		                    }
		                    echo ($userTypeId>0&&$userTypeId<10) ?'<span class="tips">'.getUsreMemberTypeEndTime().'到期</span>':'';
		                    ?>
	                		<p>当前权限</p>
	                	</div>
	                	<div class="item item-tablet">
	                		<?php 
	                			if($userTypeId){
								    $erphp_life_times    = get_option('erphp_life_times');
									$erphp_year_times    = get_option('erphp_year_times');
									$erphp_quarter_times = get_option('erphp_quarter_times');
									$erphp_month_times  = get_option('erphp_month_times');
									$erphp_day_times  = get_option('erphp_day_times');
									if($userTypeId == 6 && $erphp_day_times > 0){
										echo '<b>'.($erphp_day_times-getSeeCount($current_user->ID)).'</b> / '.$erphp_day_times;
									}elseif($userTypeId == 7 && $erphp_month_times > 0){
										echo '<b>'.($erphp_month_times-getSeeCount($current_user->ID)).'</b> / '.$erphp_month_times;
									}elseif($userTypeId == 8 && $erphp_quarter_times > 0){
										echo '<b>'.($erphp_quarter_times-getSeeCount($current_user->ID)).'</b> / '.$erphp_quarter_times;
									}elseif($userTypeId == 9 && $erphp_year_times > 0){
										echo '<b>'.($erphp_year_times-getSeeCount($current_user->ID)).'</b> / '.$erphp_year_times;
									}elseif($userTypeId == 10 && $erphp_life_times > 0){
										echo '<b>'.($erphp_life_times-getSeeCount($current_user->ID)).'</b> / '.$erphp_life_times;
									}else{
										echo '<b>无限制</b>';
									}
								}else{
									echo '<b>无</b>';
								}
	                		?>
	                		<p>今日剩余<?php echo $erphp_vip_name;?>免费下载数</p>
	                	</div>
	                </div>
	                <form>
	                	<div class="vip-items">
	                		<?php if($erphp_day_price){?>
	                		<div class="item item-0">
	                			<div class="title"><?php echo $erphp_day_name;?></div>
	                			<div class="price"><?php echo $erphp_day_price;?><span><?php echo $moneyVipName;?></span></div>
	                			<div class="time">1天</div>
	                			<?php echo _MBT('vip_day');?>
	                			<a href="javascript:;" class="btn" evt="user.vip.submit" data-type="6">立即升级</a>
	                		</div>
	                		<?php }?>
	                		<?php if($erphp_month_price){?>
	                		<div class="item item-1">
	                			<div class="title"><?php echo $erphp_month_name;?></div>
	                			<div class="price"><?php echo $erphp_month_price;?><span><?php echo $moneyVipName;?></span></div>
	                			<div class="time">1个月</div>
	                			<?php echo _MBT('vip_month');?>
	                			<a href="javascript:;" class="btn" evt="user.vip.submit" data-type="7">立即升级</a>
	                		</div>
	                		<?php }?>
	                		<?php if($erphp_quarter_price){?>
	                		<div class="item item-2">
	                			<div class="title"><?php echo $erphp_quarter_name;?></div>
	                			<div class="price"><?php echo $erphp_quarter_price;?><span><?php echo $moneyVipName;?></span></div>
	                			<div class="time">3个月</div>
	                			<?php echo _MBT('vip_quarter');?>
	                			<a href="javascript:;" class="btn" evt="user.vip.submit" data-type="8">立即升级</a>
	                		</div>
	                		<?php }?>
	                		<?php if($erphp_year_price){?>
	                		<div class="item item-3">
	                			<div class="title"><?php echo $erphp_year_name;?></div>
	                			<div class="price"><?php echo $erphp_year_price;?><span><?php echo $moneyVipName;?></span></div>
	                			<div class="time">12个月</div>
	                			<?php echo _MBT('vip_year');?>
	                			<a href="javascript:;" class="btn" evt="user.vip.submit" data-type="9">立即升级</a>
	                		</div>
	                		<?php }?>
	                		<?php if($erphp_life_price){?>
	                		<div class="item item-4">
	                			<div class="title"><?php echo $erphp_life_name;?></div>
	                			<div class="price"><?php echo $erphp_life_price;?><span><?php echo $moneyVipName;?></span></div>
	                			<div class="time">永久</div>
	                			<?php echo _MBT('vip_life');?>
	                			<a href="javascript:;" class="btn" evt="user.vip.submit" data-type="10">立即升级</a>
	                		</div>
	                		<?php }?>
	                	</div>
	                </form>
	          </div>
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'withdraw'){ $userAli=$wpdb->get_row("select * from ".$wpdb->iceget." where ice_user_id=".$current_user->ID);?>
	      	  <form>
	      	  <ul class="user-meta">
		  		<li><label>支付宝账号</label>
					<input type="text" class="form-control" id="ice_alipay" name="ice_alipay" value="<?php echo $userAli->ice_alipay;?>">
		  		</li>
		  		<li><label>支付宝姓名</label>
					<input type="text" class="form-control" id="ice_name" name="ice_name" value="<?php echo $userAli->ice_name;?>">
		  		</li>
		  		<li><label>提现比例</label>
					<?php echo get_option('ice_proportion_alipay').get_option('ice_name_alipay');?> = 1元
		  		</li>
		  		<li><label>手续费</label>
					<?php echo get_option("ice_ali_money_site")?>%
		  		</li>
		  		<li><label>提现<?php echo get_option('ice_name_alipay');?></label>
					<input type="text" class="form-control" id="ice_money" name="ice_money" value="">( 总资产：<?php echo $okMoney.' '.get_option('ice_name_alipay');?> )
		  		</li>
		  		<li>
					<input type="button" evt="withdraw.submit" class="btn btn-primary" value="我要提现">
					<input type="hidden" name="action" value="user.withdrawal">
		  		</li>
		  	  </ul>
		  	</form>
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'withdraws'){ ?>
	          <?php 
			  	    $totallists = $wpdb->get_var("SELECT count(ice_id) FROM $wpdb->iceget WHERE ice_user_id=".$current_user->ID);
					$perpage = 15;
					$pagess = ceil($totallists / $perpage);
					if (!get_query_var('paged')) {
						$paged = 1;
					}else{
						$paged = $wpdb->escape(get_query_var('paged'));
					}
					$offset = $perpage*($paged-1);
					$lists = $wpdb->get_results("SELECT * FROM $wpdb->iceget where ice_user_id=".$current_user->ID." order by ice_time DESC limit $offset,$perpage");
			  ?>
	          <?php if($lists) {?>
	          <table class="table table-striped table-hover user-orders">
	          	  <thead>
	              	  <tr>
	          			<th><?php echo $moneyName;?></th>
	          			<th class="pc">实际到账（元）</th>
	                    <th>时间</th>
	                    <th>状态</th>
	                  </tr>
	              </thead>
	              <tbody>
	              <?php foreach($lists as $value){?>
	            	  <tr>
	                  	<td><?php echo $value->ice_money;?></td>
	                  	<td class="pc"><?php echo ( (100-get_option("ice_ali_money_site")) * $value->ice_money / 100) / get_option('ice_proportion_alipay');?></td>
	                  	<td><?php echo $value->ice_time;?></td>
	                  	<td><?php if($value->ice_success == 1){echo '<span style="color:green">已完成</span>';}else{echo '处理中';}?></td>
	                  </tr>
			      <?php }?>
	              </tbody>
	          </table>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无记录！</h6></div>
	          <?php }?>
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'history'){ ?>
	          <?php 
			  	    $totallists = $wpdb->get_var("SELECT count(*) FROM $wpdb->icemoney WHERE ice_success=1 and ice_user_id=".$current_user->ID);
					$perpage = 15;
					$pagess = ceil($totallists / $perpage);
					if (!get_query_var('paged')) {
						$paged = 1;
					}else{
						$paged = $wpdb->escape(get_query_var('paged'));
					}
					$offset = $perpage*($paged-1);
					$lists = $wpdb->get_results("SELECT * FROM $wpdb->icemoney where ice_success=1 and ice_user_id=".$current_user->ID." order by ice_time DESC limit $offset,$perpage");
			  ?>
	          <?php if($lists) {?>
	          <table class="table table-striped table-hover user-orders">
	          	  <thead>
	              	  <tr><th style="text-align: left;">充值时间</th><th style="text-align: left;">金额(<?php echo $moneyName;?>)</th><th class="pc">方式</th><th class=pc>状态</th></tr></thead>
	              <tbody>
	              <?php foreach($lists as $value){?>
	            	  <tr><td style="text-align: left;"><?php echo $value->ice_time;?></td><td style="text-align: left;"><dfn><?php echo $value->ice_money;?></dfn></td>
	                  <?php if(intval($value->ice_note)==0){echo "<td class=pc><font color=green>在线充值</font></td>\n";}elseif(intval($value->ice_note)==1){echo "<td class=pc>后台充值</td>\n";}elseif(intval($value->ice_note)==2){echo "<td class=pc><font color=blue>转账收款</font></td>\n";}elseif(intval($value->ice_note)==3){echo "<td class=pc><font color=orange>转账付款</font></td>\n";}elseif(intval($value->ice_note)==4){echo "<td class=pc><font color=orange>mycred兑换</font></td>\n";}elseif(intval($value->ice_note)==6){echo "<td class=pc><font color=orange>充值卡</font></td>\n";}else{echo '<td class=pc></td>';}?><td class=pc>成功</td></tr>
			      <?php }?>
	              </tbody>
	          </table>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <div class="user-alerts">
	          	  <h4>充值常见问题：</h4>
	          	  <ul><li>付款后系统会与支付服务方进行交互读取数据，可能会导致到账延迟，一般不会超过2分钟。</li></ul>
	          </div>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无记录！</h6></div>
	          <?php }?>
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'mycred'){ ?>
	          <?php 
	          		$mycred_get_all_references = mycred_get_all_references();
			  	    $totallists = $wpdb->get_var("SELECT COUNT(id) FROM ".$wpdb->prefix."myCRED_log WHERE user_id=".$current_user->ID);
					$perpage = 15;
					$pagess = ceil($totallists / $perpage);
					if (!get_query_var('paged')) {
						$paged = 1;
					}else{
						$paged = $wpdb->escape(get_query_var('paged'));
					}
					$offset = $perpage*($paged-1);
					$lists = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."myCRED_log where user_id=$current_user->ID order by time DESC limit $offset,$perpage");
			  ?>
	          <?php if($lists) {?>
	          <table class="table table-striped table-hover user-orders">
	          	  <thead>
	              	  <tr>
	          			<th width="20%">行为</th>
	                    <th width="35%" class=pc>时间</th>
	                    <th width="10%">积分</th>
	                    <th width="35%" class=pc>条目</th>
	                  </tr>
	              </thead>
	              <tbody>
	              <?php foreach($lists as $value){
	              ?>
	            	  <tr>
	            	  	<td><?php echo $mycred_get_all_references[$value->ref];?></td>
	                  	<td class=pc><?php echo date("Y-m-d H:i:s",$value->time);?></td>
	                  	<td><?php echo $value->creds;?></td>
	                  	<td class=pc><?php echo str_replace('%plural%', $mycred_core['name']['plural'], $value->entry);?></td>
	                  </tr>
			      <?php }?>
	              </tbody>
	          </table>
	          <?php MBThemes_custom_paging($paged,$pagess); ?>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无记录！</h6></div>
	          <?php }?>
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'order'){ ?>
	          <?php 
			  	    $totallists = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icealipay WHERE ice_success>0 and ice_user_id=".$current_user->ID);
					$perpage = 15;
					$pagess = ceil($totallists / $perpage);
					if (!get_query_var('paged')) {
						$paged = 1;
					}else{
						$paged = $wpdb->escape(get_query_var('paged'));
					}
					$offset = $perpage*($paged-1);
					$lists = $wpdb->get_results("SELECT * FROM $wpdb->icealipay where ice_success=1 and ice_user_id=$current_user->ID order by ice_time DESC limit $offset,$perpage");
			  ?>
	          <?php if($lists) {?>
	          <table class="table table-striped table-hover user-orders">
	          	  <thead>
	              	  <tr>
	                    <th width="35%" style="text-align: left;">商品名称</th>
	                    <th class=pc>订单号</th>
	                    <th class=pc>价格(<?php echo $moneyName;?>)</th>
	                    <th class=pc>交易时间</th>
	                    <th>操作</th>
	                  </tr>
	              </thead>
	              <tbody>
	              <?php foreach($lists as $value){
	              		$start_down = get_post_meta( $value->ice_post, 'start_down', true );
						$start_see = get_post_meta( $value->ice_post, 'start_see', true );
						$start_see2 = get_post_meta( $value->ice_post, 'start_see2', true );
						$start_down2 = get_post_meta( $value->ice_post, 'start_down2', true );
						$down_activation = get_post_meta($value->ice_post, 'down_activation', true);
	              ?>
	            	  <tr>
	                  	<td style="text-align: left;"><a target="_blank" href="<?php echo get_permalink($value->ice_post);?>"><?php echo $value->ice_title;?></a><?php
	                  		if($down_activation && function_exists('doErphpAct') && $value->ice_data){
	                  			echo '<p>激活码：'.$value->ice_data.'</p>';
	                  		}
	                  	?></td><td class=pc><?php echo $value->ice_num;?></td><td class=pc><?php echo $value->ice_price;?></td>
	                  	<td class=pc><?php echo $value->ice_time;?></td>
	                  	<?php if($start_down || $start_down2){?>
	                  	<td><a href="<?php echo constant("erphpdown").'download.php?postid='.$value->ice_post.'&index='.$value->ice_index;?>" target="_blank">下载</a></td>
	                  	<?php }elseif($start_see || $start_see2){?>
	                  	<td><a href="<?php echo get_permalink($value->ice_post);?>" target="_blank">查看</a></td>
	                  	<?php }?>
	                  </tr>
			      <?php }?>
	              </tbody>
	          </table>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无记录！</h6></div>
	          <?php }?>
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'sell'){ ?>
	          <?php 
			  	    $totallists = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icealipay WHERE ice_success>0 and ice_author=".$current_user->ID);
					$perpage = 15;
					$pagess = ceil($totallists / $perpage);
					if (!get_query_var('paged')) {
						$paged = 1;
					}else{
						$paged = $wpdb->escape(get_query_var('paged'));
					}
					$offset = $perpage*($paged-1);
					$lists = $wpdb->get_results("SELECT * FROM $wpdb->icealipay where ice_success=1 and ice_author=$current_user->ID order by ice_time DESC limit $offset,$perpage");
			  ?>
	          <?php if($lists) {?>
	          <table class="table table-striped table-hover user-orders">
	          	  <thead>
	              	  <tr>
	              	  	<th width="35%" style="text-align: left;">商品名称</th>
	          			<th width="15%" class=pc>订单号</th>
	                    <th width="15%" class=pc>用户</th>
	                    <th width="10%">价格(<?php echo $moneyName;?>)</th>
	                    <th width="25%" class=pc>交易时间</th>
	                  </tr>
	              </thead>
	              <tbody>
	              <?php foreach($lists as $value){?>
	            	  <tr>
	                  	<td style="text-align: left;"><a target="_blank" href="<?php echo get_permalink($value->ice_post);?>"><?php echo get_post($value->ice_post)->post_title;?></a></td><td class=pc><?php echo $value->ice_num;?></td>
	                  	<td><?php echo get_the_author_meta( 'user_login', $value->ice_user_id );?></td><td class=pc><?php echo $value->ice_price;?></td>
	                  	<td class=pc><?php echo $value->ice_time;?></td>
	                  </tr>
			      <?php }?>
	              </tbody>
	          </table>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无记录！</h6></div>
	          <?php }?>
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'aff' && !_MBT('user_aff')){ 
	      	$ice_ali_money_ref = get_option('ice_ali_money_ref')*0.01;
	      	$total_user   = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users WHERE father_id=".$current_user->ID);
	      	?>
	          <div class="charge aff">
	          		<div class="charge-header">
	                	<h3>您的专属推广链接：<font color="#5bc0de"><?php bloginfo("url");?>/?aff=<?php echo $current_user->ID;?></font> <a href="javascript:;" data-clipboard-text="<?php bloginfo("url");?>/?aff=<?php echo $current_user->ID;?>" class="article-aff" title="复制链接"><i class="icon icon-copy"></i></a> <?php if(_MBT('aff_card')){?><a href="javascript:;" class="user-aff-card" title="推广图片"><i class="icon icon-cover"></i><span id="aff-qrcode" data-url="<?php bloginfo("url");?>/?aff=<?php echo $current_user->ID;?>"></span></a><?php }?></h3>
	                	<p style="font-size: 13px;opacity: .7">已推广注册 <?php echo $total_user;?> 人</p>
	                </div>
			  </div>
	          <?php 
			  	    $totallists = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users WHERE father_id=".$current_user->ID);
			  	    $perpage = 15;
					$pagess = ceil($totallists / $perpage);
					if (!get_query_var('paged')) {
						$paged = 1;
					}else{
						$paged = $wpdb->escape(get_query_var('paged'));
					}
					$offset = $perpage*($paged-1);
					$lists = $wpdb->get_results("SELECT ID,user_login,user_registered FROM $wpdb->users where father_id=".$current_user->ID." order by user_registered DESC limit $offset,$perpage");
			  ?>
	          <?php if($lists) {?>
	          <table class="table table-striped table-hover user-orders">
	          	  <thead>
	              	  <tr>
	          			<th>用户</th>
	                    <th class="pc">注册时间</th>
	                    <th>消费额</th>
	                    <th>奖励</th>
	                  </tr>
	              </thead>
	              <tbody>
	              <?php foreach($lists as $value){?>
	            	  <tr>
	                  	<td><?php echo $value->user_login;?></td>
	                  	<td class="pc"><?php echo $value->user_registered;?></td>
	                  	<td><?php $tt = erphpGetUserAllXiaofei($value->ID);echo $tt?$tt:"0";?></td>
	                  	<td><?php echo $tt*($ice_ali_money_ref?$ice_ali_money_ref:0);?></td>
	                  </tr>
			      <?php }?>
	              </tbody>
	          </table>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <?php }else{?>
	          	<table class="table table-striped table-hover user-orders">
	          	  <thead>
	              	  <tr>
	          			<th>用户</th>
	                    <th class="pc">注册时间</th>
	                    <th>消费额</th>
	                    <th>奖励</th>
	                  </tr>
	              </thead>
	              <tbody>
	              	<tr><td colspan="4">暂无记录！</td></tr>
	              </tbody>
	            </table>
	          <?php }?>
	          <div class="user-alerts">
	            <h4>推广说明：</h4>
	            <ul>
	                <li>请勿作弊，否则封相关账户不通知； </li>
	                <li>推广链接可以是任意页面后加 <span class="label label-info">?aff=<?php echo $current_user->ID;?></span>即可；</li>
	                <li>推广统计可能与实际存在差异，具体以实际数据为准。</li>
	            </ul>
	            </div>
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'comment'){ ?>
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
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'task'){
	      		$totallists = $wpdb->get_var("SELECT count(ID) FROM $wpdb->posts WHERE post_author=".$current_user->ID." and post_status='publish' and post_type='task'");
				$perpage = 10;
				$pagess = ceil($totallists / $perpage);
				if (!get_query_var('paged')) {
					$paged = 1;
				}else{
					$paged = $wpdb->escape(get_query_var('paged'));
				}
				$offset = $perpage*($paged-1);
				$lists = $wpdb->get_results("SELECT * FROM $wpdb->posts where post_author=".$current_user->ID." and post_status='publish' and post_type='task' order by post_date DESC limit $offset,$perpage");
	      ?>
	      	  <?php if($lists) {?>
	          <ul class="user-postlist">
	          	<?php foreach($lists as $value){ $post = get_post($value->ID); setup_postdata($post);?>
	          	<li>
					<h2><a target="_blank" href="<?php the_permalink($value->ID);?>"><?php the_title();?></a></h2>
					<p class="note" style="height: auto;"><?php echo MBThemes_get_excerpt();?></p>
					<p class="text-muted"><?php echo $value->post_date;?></p>
				</li>
	          	<?php }?>
	          </ul>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无记录！</h6></div>
	          <?php }?>
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'faq'){
	      		$totallists = $wpdb->get_var("SELECT count(ID) FROM $wpdb->posts WHERE post_author=".$current_user->ID." and post_status='publish' and post_type='qa_post'");
				$perpage = 10;
				$pagess = ceil($totallists / $perpage);
				if (!get_query_var('paged')) {
					$paged = 1;
				}else{
					$paged = $wpdb->escape(get_query_var('paged'));
				}
				$offset = $perpage*($paged-1);
				$lists = $wpdb->get_results("SELECT * FROM $wpdb->posts where post_author=".$current_user->ID." and post_status='publish' and post_type='qa_post' order by post_date DESC limit $offset,$perpage");
	      ?>
	      	  <?php if($lists) {?>
	          <ul class="user-postlist">
	          	<?php foreach($lists as $value){ $post = get_post($value->ID); setup_postdata($post);?>
	          	<li>
					<h2><a target="_blank" href="<?php the_permalink($value->ID);?>"><?php the_title();?></a></h2>
					<p class="note" style="height: auto;"><?php echo MBThemes_get_excerpt();?></p>
					<p class="text-muted"><?php echo $value->post_date;?></p>
				</li>
	          	<?php }?>
	          </ul>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无记录！</h6></div>
	          <?php }?>
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'collect'){ ?>
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
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'info'){ ?>
	          <?php $userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$current_user->ID);?>
	          <form style="margin-bottom: 30px">
	            <ul class="user-meta">
	              <li>
	                <label>用户名</label>
	                <?php echo $current_user->user_login;?> </li>
	              <li>
	                <label>注册时间</label>
	                <?php echo get_date_from_gmt( $current_user->user_registered ); ?>
	                </li>
	              <li>
	                <label>昵称</label>
	                <input type="text" class="form-control" name="nickname" value="<?php echo $current_user->nickname;?>">
	              </li>
	              <li>
	                <label>QQ</label>
	                <input type="text" class="form-control" name="qq" value="<?php echo get_user_meta($current_user->ID, 'qq', true);?>">
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
	          <form style="margin-bottom: 30px">
	            <ul class="user-meta">
	            <li>
	                <label>邮箱</label>
	                <input type="email" class="form-control" name="email" value="<?php echo $current_user->user_email;?>">
	              </li>
	              <li>
	                <label>验证码</label>
	                <input type="text" class="form-control" name="captcha" value="" style="width:150px;display:inline-block"> <a evt="user.email.captcha.submit" style="display:inline-block;font-size: 13px;cursor: pointer;"><i class="icon icon-mail"></i> 获取验证码</a>
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
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'password'){ ?>
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
	      }else{ 

	      		if(isset($_POST['paytype']) && $_POST['paytype']){
					$paytype=intval($_POST['paytype']);
					$doo = 1;
					
					if(isset($_POST['paytype']) && $paytype==1)
					{
						$url=constant("erphpdown")."payment/alipay.php?ice_money=".$_POST['ice_money'];
					}
					elseif(isset($_POST['paytype']) && $paytype==2)
					{
						$url=constant("erphpdown")."payment/f2fpay.php?ice_money=".$_POST['ice_money'];
					}
					elseif(isset($_POST['paytype']) && $paytype==3)
					{
						if(erphpdown_is_weixin() && get_option('ice_weixin_app')){
							$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.get_option('ice_weixin_appid').'&redirect_uri='.urlencode(constant("erphpdown")).'payment%2Fweixin.php%3Fice_money%3D'.$_POST['ice_money'].'&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect';
						}else{
							$url=constant("erphpdown")."payment/weixin.php?ice_money=".$_POST['ice_money'];
						}
					}
					elseif(isset($_POST['paytype']) && $paytype==4)
					{
						$url=constant("erphpdown")."payment/paypal.php?ice_money=".$_POST['ice_money'];
					}
					elseif(isset($_POST['paytype']) && $paytype==52)
					{
						$url=constant("erphpdown")."payment/paypy.php?ice_money=".$_POST['ice_money'];
					}
					elseif(isset($_POST['paytype']) && $paytype==51)
					{
						$url=constant("erphpdown")."payment/paypy.php?ice_money=".$_POST['ice_money']."&type=alipay";
					}
					elseif(isset($_POST['paytype']) && $paytype==61)
					{
						$url=constant("erphpdown")."payment/xhpay3.php?ice_money=".$_POST['ice_money']."&type=2";
					}
					elseif(isset($_POST['paytype']) && $paytype==62)
					{
						$url=constant("erphpdown")."payment/xhpay3.php?ice_money=".$_POST['ice_money']."&type=1";
					}elseif(isset($_POST['paytype']) && $paytype==71)
				    {
				        $url=constant("erphpdown")."payment/codepay.php?ice_money=".$_POST['ice_money']."&type=1";
				    }elseif(isset($_POST['paytype']) && $paytype==72)
				    {
				        $url=constant("erphpdown")."payment/codepay.php?ice_money=".$_POST['ice_money']."&type=3";
				    }elseif(isset($_POST['paytype']) && $paytype==73)
				    {
				        $url=constant("erphpdown")."payment/codepay.php?ice_money=".$_POST['ice_money']."&type=2";
				    }elseif(isset($_POST['paytype']) && $paytype==81)
					{
						$url=ERPHPDOWN_EPAY_URL."/epay.php?ice_money=".$_POST['ice_money']."&type=alipay";
					}elseif(isset($_POST['paytype']) && $paytype==82)
					{
						$url=ERPHPDOWN_EPAY_URL."/epay.php?ice_money=".$_POST['ice_money']."&type=wxpay";
					}
					else{
						
					}
					if($doo) echo "<script>location.href='".$url."'</script>";
					exit;
				}
	      	?>
	          	<div class="charge">
	            	<div class="charge-header clearfix">
	                	<div class="item">
	                		<b class="color"><?php echo sprintf("%.2f",$okMoney);?></b><?php echo ' '.$moneyName;?>
	                		<p>可用余额</p>
	                	</div>
	                	<?php if(!_MBT('user_aff')){?>
	                	<div class="item item-pc">
	                		<?php echo '<b>'.MBThemes_aff_money($current_user->ID).'</b> '.$moneyName;
	                		?>
	                		<p>推广奖励&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo add_query_arg('action','aff',get_permalink())?>">推广详情&gt;&gt;</a></p>
	                		<span class="tips">统计存在差异，具体以实际到账为准</span>
	                	</div>
	                	<?php }else{?>
	                	<div class="item item-tablet">
	                		<b>无</b>
	                		<p>推广奖励</p>
	                		<span class="tips">暂未开放推广</span>
	                	</div>
	                	<?php }?>
	                	<div class="item item-tablet">
	                		<?php $userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$current_user->ID);
	                		echo '<b>'.($userMoney->ice_get_money?$userMoney->ice_get_money:'0.00').'</b> '.$moneyName;
	                		?>
	                		<p>站内消费</p>
	                	</div>
	                </div>
	                <?php if(!_MBT('recharge_default')){?>
	            	<form id="charge-form" action="" method="post">
		              	<div class="item" style="overflow: hidden;margin-bottom:0">
		              		<?php if(_MBT('recharge_price_s')){
		              			$prices = _MBT('recharge_price');
		              			if($prices){
		              				$price_arr = explode(',',$prices);
		              				echo '<div class="prices">';
		              				foreach ($price_arr as $price) {
		              					echo '<input type="radio" name="ice_money" id="ice_money'.$price.'" value="'.$price.'" checked><label for="ice_money'.$price.'" evt="price.select">'.$price.'元</label>';
		              				}
		              				echo '</div>';
		              			}
		              		?>
		              		<input type="submit" value="立即充值" class="btn btn-recharge" evt="user.charge.submit">
		              		<?php }else{?>
			                <input type="number" min="0" step="0.01" class="form-control input-recharge" name="ice_money" id="ice_money" required="" placeholder="金额，1 元 = <?php echo get_option('ice_proportion_alipay')?> <?php echo $moneyName;?>"><input type="submit" value="立即充值" class="btn btn-recharge" evt="user.charge.submit">
			            <?php }?>
			            </div>
			            <div class="item payment-radios">
		                    <?php if(get_option('ice_weixin_mchid')){?> 
		                    <input type="radio" id="paytype3" class="paytype" checked name="paytype" value="3" /> <label for="paytype3" class="payment-label payment-wxpay-label"><i class="icon icon-wxpay-color"></i></label>
		                    <?php }?>
		                    <?php if(get_option('ice_ali_partner')){?> 
		                    <input type="radio" id="paytype1" class="paytype" checked name="paytype" value="1" /> <label for="paytype1" class="payment-label payment-alipay-label"><i class="icon icon-alipay-color"></i></label>
		                    <?php }?>
		                    <?php if(get_option('erphpdown_f2fpay_id') && !get_option('erphpdown_f2fpay_alipay')){?> 
		                    <input type="radio" id="paytype2" class="paytype" checked name="paytype" value="2" /> <label for="paytype2" class="payment-label payment-alipay-label"><i class="icon icon-alipay-color"></i></label>
		                    <?php }?>
			                <?php if(get_option('erphpdown_xhpay_appid32')){?> 
			                <input type="radio" id="paytype62" class="paytype" name="paytype" value="62" checked /> <label for="paytype62" class="payment-label payment-alipay-label"><i class="icon icon-alipay-color"></i></label> 
			                <?php }?>
			                <?php if(get_option('erphpdown_xhpay_appid31')){?> 
			                <input type="radio" id="paytype61" class="paytype" name="paytype" value="61" checked /> <label for="paytype61" class="payment-label payment-wxpay-label"><i class="icon icon-wxpay-color"></i></label>   
			                <?php }?>
			                <?php if(get_option('erphpdown_codepay_appid')){?> 
			                <?php if(!get_option('erphpdown_codepay_alipay')){?>
			                <input type="radio" id="paytype71" class="paytype" name="paytype" value="71" checked /> <label for="paytype71" class="payment-label payment-alipay-label"><i class="icon icon-alipay-color"></i></label><?php }?>
			                <input type="radio" id="paytype72" class="paytype" name="paytype" value="72" /> <label for="paytype72" class="payment-label payment-wxpay-label"><i class="icon icon-wxpay-color"></i></label>
			                <?php if(!get_option('erphpdown_codepay_qqpay')){?>
			                <input type="radio" id="paytype73" class="paytype" name="paytype" value="73" /> <label for="paytype73" class="payment-label payment-qqpay-label"><i class="icon icon-qq"></i></label>    
			            	<?php }?>
			                <?php }?>
			                <?php if(get_option('erphpdown_paypy_key')){?> 
			                <?php if(!get_option('erphpdown_paypy_alipay')){?><input type="radio" id="paytype51" class="paytype" name="paytype" value="51" checked /> <label for="paytype51" class="payment-label payment-alipay-label"><i class="icon icon-alipay-color"></i></label><?php }?>
			                <?php if(!get_option('erphpdown_paypy_wxpay')){?><input type="radio" id="paytype52" class="paytype" name="paytype" value="52" checked /> <label for="paytype52" class="payment-label payment-wxpay-label"><i class="icon icon-wxpay-color"></i></label><?php }?>
			                <?php }?>
			                <?php if(function_exists('plugin_check_epay')){ if(plugin_check_epay() && get_option('erphpdown_epay_id')){?>
			                <?php if(!get_option('erphpdown_epay_alipay')){?><input type="radio" id="paytype81" class="paytype" name="paytype" value="81" checked /> <label for="paytype81" class="payment-label payment-alipay-label"><i class="icon icon-alipay-color"></i></label><?php }?>
			                <?php if(!get_option('erphpdown_epay_wxpay')){?><input type="radio" id="paytype82" class="paytype" name="paytype" value="82" /> <label for="paytype82" class="payment-label payment-wxpay-label"><i class="icon icon-wxpay-color"></i></label><?php }?>
			                <?php }}?>
		                    <?php if(get_option('ice_payapl_api_uid')){?> 
		                    <input type="radio" id="paytype4" class="paytype" checked name="paytype" value="4" /> <label for="paytype4" class="payment-label payment-paypal-label"><i class="icon icon-paypal"></i></label> (美元汇率：<?php echo get_option('ice_payapl_api_rmb')?>)
		                    <?php }?> 
		                </div>
		            </form>
		            <?php }?>
		            <?php 
		            	$epd_game_price  = get_option('epd_game_price');
				        if($epd_game_price){
				        	echo '<div class="charge-gift">';
				          	$cnt = count($epd_game_price['buy']);
				          	for($i=0; $i<$cnt;$i++){
				          		if($epd_game_price['buy'][$i]){
					          		echo '<div class="item">充值 <span>'.$epd_game_price['buy'][$i].'</span> 元，实际到账 <span>'.$epd_game_price['get'][$i].'</span> 元</div>';
					          	}
				          	}
				          	echo '</div>';
				        }
		            ?>
		            <?php if(_MBT('user_charge_tips')){?><p class="charge-tips"><i class="icon icon-notice"></i> <?php echo _MBT('user_charge_tips')?></p><?php }?>
		            <?php if(function_exists("checkDoCardResult")){?>
		            <form id="charge-form2" action="" method="post">
		            	<h3><span>充值卡充值</span></h3>
		              	<div class="item">
			                <input type="text" class="form-control input-recharge" id="erphpcard_num" name="erphpcard_num" required="" placeholder="卡号">
			            </div>
		                <div class="item">
			              	<input type="button" evt="user.charge.card.submit" value="立即充值" class="btn btn-card">
			            </div>
		            </form>
		            <?php }?>
		            <?php if(plugin_check_cred() && get_option('erphp_mycred') == 'yes'){?>
		            <form id="charge-form2" action="" method="post">
		            	<h3><span><?php echo $mycred_core['name']['plural'];?>兑换</span></h3>
		              	<div class="item">
			                <input type="number" min="0.01" step="0.01" class="form-control input-recharge" id="erphpmycred_num" name="erphpmycred_num" required="" placeholder="请输入<?php echo get_option('ice_name_alipay')?>数"> 
			            </div>
		                <div class="item">
			              	<input type="button" evt="user.mycred.submit" value="立即兑换" class="btn btn-card">
			              	<p><?php echo get_option('erphp_to_mycred').' '.$mycred_core['name']['plural'];?> = 1 <?php echo get_option('ice_name_alipay')?>，可用<?php echo $mycred_core['name']['plural'];?>：<?php echo mycred_get_users_cred( $current_user->ID )?></p>
			            </div>
		            </form>
		            <?php }?>
	            </div>
	      <?php }?>
	    </div>
	    <div class="user-tips"></div>
	  </div>
	</div>
	<script src="<?php bloginfo("template_url")?>/static/js/user.js"></script>
</div>