<?php 
/*
	template name: 菜单页面
	description: template for mobantu.com modown theme 
*/
get_header();?>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container clearfix">
		<div class="content-wrap content-nav">
			<div class="pageside">
			    <div class="pagemenus">
			    	<ul class="pagemenu">
			        <?php echo str_replace("</ul></div>", "", preg_replace("{<div[^>]*><ul[^>]*>}", "", wp_nav_menu(array('theme_location' => 'page', 'echo' => false, 'fallback_cb'=> 'wp_menu_none')) )); ?>
			    	</ul>
			    </div>
			</div>
	    	<div class="content" style="min-height: 500px;">
	    		<?php while (have_posts()) : the_post(); ?>
	    		<article class="single-content">
		    		<header class="article-header">
		    			<h1 class="article-title center"><?php the_title(); ?></h1>
		    		</header>
		    		<div class="article-content">
		    			<?php the_content(); ?>
		            </div>
		    		<?php endwhile;  ?>
	            </article>
	            <?php comments_template('', true); ?>
	    	</div>
	    </div>
	</div>
</div>
<?php get_footer();?>