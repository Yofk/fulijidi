<?php get_header();?>
<div class="banner-archive" <?php if(_MBT('banner_archive_img')){?> style="background-image: url(<?php echo _MBT('banner_archive_img');?>);" <?php }?>>
	<div class="container">
		<h1 class="archive-title"><?php echo _MBT('blog_name')?_MBT('blog_name'):'博客';?></h1>
		<p><?php echo _MBT('blog_desc');?></p>
	</div>
</div>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container clearfix">
		<div class="content-wrap">
	    	<div class="content">
				<?php MBThemes_ad('ad_list_header');?>
				<div id="posts" class="lists clearfix">
					<?php 
						while ( have_posts() ) : the_post(); 
						get_template_part( 'content-blog', get_post_format() );
						endwhile; wp_reset_query(); 
					?>
				</div>
				<?php MBThemes_paging();?>
				<?php MBThemes_ad('ad_list_footer');?>
			</div>
		</div>
		<?php get_sidebar(); ?>
	</div>
</div>
<?php get_footer();?>