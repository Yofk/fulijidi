<?php 
/*
	template name: 投稿页面
	description: template for mobantu.com modown theme 
*/
if(!is_user_logged_in()){
	wp_redirect(get_permalink(MBThemes_page("template/login.php")));
	exit;
}
$is_submit_page = 1;
date_default_timezone_set('Asia/Shanghai');
$post_tougao_role = _MBT('post_tougao_role')?_MBT('post_tougao_role'):'read';
if(!current_user_can($post_tougao_role)){
	wp_redirect(home_url());
	exit;
}
get_header();
global $current_user;
$security_nonce = wp_create_nonce( 'security_nonce' );
if(isset($_POST['security']) && is_user_logged_in()){
	if($security_nonce == $_POST['security']){
		$post_tougao_time = _MBT('post_tougao_time');
		$last_post = $wpdb->get_var("SELECT post_date FROM $wpdb->posts WHERE post_author='".$current_user->ID."' AND post_type = 'post' ORDER BY post_date DESC LIMIT 1");
	    if ( $post_tougao_time && (time() - strtotime($last_post) < $post_tougao_time*60) ){
	        echo '<script>alert("这也太快了吧，喝杯咖啡~");</script>'; 
	    }else{

			$title =   $wpdb->escape($_POST['title']) ;
			$content =  $_POST['content'] ;
			$cat =  $wpdb->escape($_POST['cat']);
			$status = 'pending';
			$submit = array(
				'post_title' => strip_tags($title),
				'post_author' => $current_user->ID,
				'post_content' => $content,
				'post_category' => array($cat),
				'post_status' => $status
			);
			$status = wp_insert_post( $submit );
			
			if ($status != 0) {

				$taxonomys = get_term_meta($cat,'taxonomys',true);
				if($taxonomys){
					$post_texonomys = explode('|', $taxonomys);
	                foreach ($post_texonomys as $post_texonomy) { 
	                    $post_texonomy = explode(',', $post_texonomy);
	                    wp_set_object_terms( $status, $_POST[$post_texonomy[1]], $post_texonomy[1] );
	                }
	            }

				if($_POST['image']){
					update_post_meta($status,'_thumbnail_ext_url',$wpdb->escape($_POST['image']));
				}
				if(wp_is_erphpdown_active() && $_POST['start_down'] == '1'){
					update_post_meta($status,'start_down',"yes");
					update_post_meta($status,'member_down','1');
			        update_post_meta($status,'down_price',$wpdb->escape($_POST['down_price']));
			        update_post_meta($status,'down_url',$wpdb->escape($_POST['down_url']));
			        update_post_meta($status,'hidden_content',$wpdb->escape($_POST['hidden_content'])); 
				}elseif(wp_is_erphpdown_active() && $_POST['start_down'] == '2'){
					update_post_meta($status,'start_see',"yes");
					update_post_meta($status,'member_down','1');
			        update_post_meta($status,'down_price',$wpdb->escape($_POST['down_price']));
				}elseif(wp_is_erphpdown_active() && $_POST['start_down'] == '3'){
					update_post_meta($status,'start_see2',"yes");
					update_post_meta($status,'member_down','1');
			        update_post_meta($status,'down_price',$wpdb->escape($_POST['down_price']));
				}
				echo '<script>alert("投稿成功，请等待管理员审核！");</script>';
			}else{
				echo '<script>alert("投稿失败，请稍后重试！");</script>';
			}
		}
	}
}
?>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container">
		<div class="content-wrap">
	    	<div class="content">
	    		<article class="single-content">
		    		<header class="article-header">
		    			<h1 class="article-title tougao-title"><i class="icon icon-edit"></i> <?php the_title(); ?></h1>
		    		</header>
		    		<div class="tougao-content">
		    			<form method="post">
		    				<div class="tougao-item">
		    					<label>标题 *</label>
		    					<input type="text" name="title" class="tougao-input" required="" />
		    				</div>
		    				<div class="tougao-item">
		    					<label>分类 *</label>
		    					<div>
			    					<div class="tougao-select"><?php wp_dropdown_categories('show_option_all=选择分类&orderby=name&hierarchical=1&selected=-1&depth=0&hide_empty=0');?></div> <div class="tougao-tax"></div>
			    				</div>
		    				</div>
		    				<div class="tougao-item">
		    					<label>封面图</label>
		    					<div class="tougao-image-wrap clearfix">
			    					<div class="tougao-image-box tougao-upload" title="上传图片"><i class="icon icon-plus"></i><br>上传图片</div>
			    					<div class="tougao-image-input">
				    					<input type="url" name="image" id="image" class="tougao-input" placeholder="请输入外链图片地址" />
				    					<span id="file-progress" class="file-progress"></span>
				    				</div>
			    				</div>
		    				</div>
		    				<div class="tougao-item">
		    					<label>内容 *</label>
		    					<?php wp_editor( '', 'content',post_editor_settings(array('textarea_name'=>'content')) ); ?>
		    				</div>
		    				<?php if(wp_is_erphpdown_active()){?>
		    				<div class="tougao-item">
		    					<label>收费选项</label>
		    					<div class="tougao-select">
		    						<input type="radio" name="start_down" id="start_down1" value="0" checked=""> <label for="start_down1">不启用</label>
		    						<input type="radio" name="start_down" id="start_down2" value="1"> <label for="start_down2">收费下载</label>
		    						<input type="radio" name="start_down" id="start_see1" value="2"> <label for="start_see1">收费查看全文</label>
		    						<input type="radio" name="start_down" id="start_see2" value="3"> <label for="start_see2">收费查看部分 <span style="font-size: 12px;color: #aaa;">[erphpdown]隐藏内容[/erphpdown]</span></label>
		    					</div>
		    					<p>售卖总额的<?php echo (get_option('ice_ali_money_author')?get_option('ice_ali_money_author'):'100');?>%将直接进入您的网站余额</p>
		    				</div>
		    				<div class="tougao-item tougao-item-erphpdown tougao-item-erphpdown-see">
		    					<label>价格</label>
		    					<input type="number" name="down_price" class="tougao-input" min="0" step="0.01" style="width:150px;"/>
		    					<p>留空或0则表示免费</p>
		    				</div>
		    				<div class="tougao-item tougao-item-erphpdown">
		    					<label>下载地址</label>
		    					<div class="tougao-file-wrap clearfix">
			    					<div class="tougao-file-box tougao-upload2" title="上传附件"><i class="icon icon-plus"></i><br>上传附件<br><font style="font-size: 12px;">支持.zip .rar .7z</font></div>
			    					<div class="tougao-file-input">
				    					<input type="url" name="down_url" id="down_url" class="tougao-input" placeholder="请输入附件下载地址" />
				    					<span id="file-progress2" class="file-progress"></span>
				    				</div>
			    				</div>
		    				</div>
		    				<div class="tougao-item tougao-item-erphpdown">
		    					<label>提取码</label>
		    					<input type="text" name="hidden_content" class="tougao-input" style="width:200px;"/>
		    					<p>提取码或者解压密码</p>
		    				</div>
		    				<?php }?>
		    				<div class="tougao-item">
		    					<button class="tougao-btn" type="submit">提交</button>
		    					<input type="hidden" name="security" value="<?php echo $security_nonce;?>">
		    				</div>
		    			</form>
		    			<?php if(_MBT('tougao_upload')){?>
		    			<form style="display:none" id="imageForm" action="<?php bloginfo("template_url");?>/action/image.php" enctype="multipart/form-data" method="post"><input type="file" id="imageFile" name="imageFile" accept="image/png, image/jpeg"></form>
		    			<form style="display:none" id="fileForm" action="<?php bloginfo("template_url");?>/action/file.php" enctype="multipart/form-data" method="post"><input type="file" id="fileFile" name="fileFile" accept=".zip, .rar, .7z"></form><?php }?>
		    		</div>
	            </article>
	    	</div>
	    </div>
	    <div class="sidebar"><div class="theiaStickySidebar"><div class="widget"><h3><i class="icon icon-horn"></i> 投稿说明</h3><div class="textwidget custom-html-widget"><?php while (have_posts()) : the_post(); the_content(); endwhile;?></div></div></div></div>
	</div>
</div>
<?php get_footer();?>