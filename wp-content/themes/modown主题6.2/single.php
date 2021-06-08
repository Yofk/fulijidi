<?php 
get_header();
$tips = get_post_meta(get_the_ID(),'tips',true);
$nosidebar = get_post_meta(get_the_ID(),'nosidebar',true);
$images = get_post_meta(get_the_ID(),'images',true);
$audio = get_post_meta(get_the_ID(),'audio',true);
$audio_time = get_post_meta(get_the_ID(),'audio_time',true);
?>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container clearfix">
		<?php if (function_exists('MBThemes_breadcrumbs')) MBThemes_breadcrumbs(); ?>
		<?php if(_MBT('post_video_fullwidth')) get_template_part('module/video');?>
		<?php if(MBThemes_post_down_position() == 'box' || MBThemes_post_down_position() == 'boxbottom' || MBThemes_post_down_position() == 'boxside') get_template_part('module/post-box');?>
		<div class="content-wrap">
	    	<div class="content<?php if($nosidebar) echo ' nosidebar';?>">
	    		<?php MBThemes_ad('ad_post_header');?>
	    		<?php if(!_MBT('post_video_fullwidth')) get_template_part('module/video');?>
	    		<?php if($images) get_template_part('module/images');?>
	    		<?php while (have_posts()) : the_post(); ?>
	    		<article class="single-content">
		    		<?php if(MBThemes_post_down_position() != 'box' && MBThemes_post_down_position() != 'boxbottom' && MBThemes_post_down_position() != 'boxside') get_template_part('module/post-header');?>
		    		<div class="article-content">
		    			<?php if($tips) echo '<div class="article-tips"><div><i class="icon icon-smile"></i> '.$tips.'</div></div>';?>
		    			<?php if(wp_is_erphpdown_active()){ if(MBThemes_post_down_position() == 'top' || MBThemes_post_down_position() == 'sidetop') MBThemes_erphpdown_box();}?>
		    			<?php if($audio){?>
		    			<center><div class="article-audio">
		                    <i class="guxz"></i>
		                    <i class="dy"></i>
		                    <i class="xy"></i>
		                    <i class="gp audio-stick"></i>
		                </div></center>
		                <div class="audio">
			                <i class="audio-play"></i>
			                <audio preload="none" id="audio-<?php the_ID();?>" data-time="<?php echo $audio_time?$audio_time:'0';?>">
			                    <source src="<?php echo $audio;?>" type="audio/mpeg">
			                </audio>
			                <span class="star-time">00:00</span>
			                <div class="time-bar">
			                    <span class="progressBar"></span>
			                    <i class="move-color"></i>
			                    <p class="timetip"></p>
			                </div>
			                <span class="end-time"><?php echo mbt_sec_to_time($audio_time);?></span>
			                <p class="timeTip"></p><p class="timeTip"></p>
			            </div>
		            	<?php }?>
		    			<?php the_content(); ?>
		    			<?php wp_link_pages('link_before=<span>&link_after=</span>&before=<div class="article-paging">&after=</div>&next_or_number=number'); ?>
		    			<?php if(wp_is_erphpdown_active()){ if(MBThemes_post_down_position() == 'bottom' || MBThemes_post_down_position() == 'sidebottom' || MBThemes_post_down_position() == 'boxbottom' || MBThemes_post_down_position() == 'side') MBThemes_erphpdown_box();}?>
		    			<?php if(_MBT('post_copyright')){?>
		    			<p class="article-copyright"><?php if(_MBT('post_copyright_custom')){
		    					echo str_replace('%post%', '<a href="'.get_permalink(get_the_ID()).'">'.get_permalink(get_the_ID()).'</a>', _MBT('post_copyright_custom'));
		    				}else{?>
		    				本文链接：<a href="<?php the_permalink();?>"><?php the_permalink();?></a>，转载请注明出处。
		    				<?php }?>
		    			</p><?php }?>
		            </div>
		    		<?php get_template_part('module/act');?>
		            <?php if(_MBT('post_tags')) the_tags('<div class="article-tags">','','</div>'); ?>
					<?php if(_MBT('post_share')) get_template_part('module/share');?>
	            </article>
	            <?php endwhile;  ?>
	            <?php if(_MBT('post_nav')){?>
	            <nav class="article-nav">
	                <span class="article-nav-prev"><?php previous_post_link('上一篇<br>%link'); ?></span>
	                <span class="article-nav-next"><?php next_post_link('下一篇<br>%link'); ?></span>
	            </nav>
	            <?php }?>
	            <?php MBThemes_ad('ad_post_footer');?>
	            <?php if(_MBT('post_related')) get_template_part('module/related');?>
	            <?php comments_template('', true); ?>
	            <?php MBThemes_ad('ad_post_comment');?>
	    	</div>
	    </div>
		<?php if(!$nosidebar) get_sidebar(); ?>
	</div>
</div>
<?php get_footer();?>