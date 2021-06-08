<?php 
/*
	template name: 全屏页面
	description: template for mobantu.com modown theme 
*/
get_header();?>
<style>
	.main{padding: 0;}
	.content-wrap{margin:0;}
	.single-content{padding:0;margin:0;}
	.article-content{margin-bottom: 0;}
</style>
<div class="main">
	<div class="content-wrap">
    	<div class="content">
    		<?php while (have_posts()) : the_post(); ?>
    		<article class="single-content">
	    		<div class="article-content">
	    			<?php the_content(); ?>
	            </div>
	    		<?php endwhile;  ?>
            </article>
    	</div>
    </div>
</div>
<?php get_footer();?>