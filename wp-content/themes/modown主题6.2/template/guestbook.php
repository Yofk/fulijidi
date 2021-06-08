<?php 
/*
	template name: 留言板
	description: template for mobantu.com modown theme 
*/
get_header();?>
<div class="banner-archive" <?php if(_MBT('banner_archive_img')){?> style="background-image: url(<?php echo _MBT('banner_archive_img');?>);" <?php }?>>
	<div class="container">
		<h1 class="archive-title"><?php the_title();?></h1>
	</div>
</div>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container clearfix">
		<div class="content-wrap">
	    	<div class="content">
	    		<?php while (have_posts()) : the_post(); ?>
	    		<article class="single-content">
		    		<div class="article-content"><?php the_content(); ?></div>
	            </article>
	            <?php endwhile;  ?>
	            <?php comments_template('/guestcomments.php', true); ?>
	    	</div>
	    </div>
	</div>
</div>
<?php get_footer();?>