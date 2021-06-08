<?php get_header();?>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container clearfix">
		<?php if (function_exists('MBThemes_breadcrumbs')) MBThemes_breadcrumbs(); ?>
		<div class="content-wrap">
	    	<div class="content">
	    		<?php MBThemes_ad('ad_post_header');?>
	    		<?php while (have_posts()) : the_post(); ?>
	    		<article class="single-content">
		    		<header class="article-header">
		    			<h1 class="article-title"><?php the_title(); ?></h1>
		    			<div class="article-meta">
		    				<?php if(_MBT('post_date')){?><span class="item"><i class="icon icon-time"></i> <?php echo MBThemes_timeago( get_the_time('Y-m-d G:i:s') ) ?></span><?php }?>
		    				<?php if(_MBT('post_views')){?><span class="item"><i class="icon icon-eye"></i> <?php MBThemes_views() ?></span><?php }?>
		    				<span class="item"><?php edit_post_link('[编辑]'); ?></span>
		    			</div>
		    		</header>
		    		<div class="article-content">
		    			<?php the_content(); ?>
		    			<?php wp_link_pages('link_before=<span>&link_after=</span>&before=<div class="article-paging">&after=</div>&next_or_number=number'); ?>
		            </div>
		    		<?php endwhile;  ?>
		    		<?php if(_MBT('post_share')) get_template_part('module/share');?>
	            </article>
	            <?php MBThemes_ad('ad_post_footer');?>
	            <?php comments_template('', true); ?>
	            <?php MBThemes_ad('ad_post_comment');?>
	    	</div>
	    </div>
		<?php get_sidebar(); ?>
	</div>
</div>
<?php get_footer();?>