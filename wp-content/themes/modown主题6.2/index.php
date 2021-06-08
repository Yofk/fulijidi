<?php get_header();$style = _MBT('list_style');$cat_class = 'grids'; if($style == 'list') $cat_class = 'lists';?>
<?php 
if(_MBT('banner') == '1'){
	get_template_part("module/banner");
}elseif(_MBT('banner') == '2'){ 
	get_template_part("module/slider");
}elseif(_MBT('banner') == '3'){ 
	get_template_part("module/banner");
	get_template_part("module/slider");
}?>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container clearfix">
		<?php MBThemes_ad('ad_banner_footer', '-10');?>
		<?php if($style == 'list') echo '<div class="content-wrap"><div class="content">';?>
		<?php if(_MBT('home_cat')){?>
		<div class="cat-nav-wrap">
			<ul class="cat-nav">
				<?php echo str_replace("</ul></div>", "", preg_replace("{<div[^>]*><ul[^>]*>}", "", wp_nav_menu(array('theme_location' => 'cat', 'echo' => false)) )); ?>
			</ul>
		</div>
		<?php }?>
		<div id="posts" class="posts <?php echo $cat_class;?> <?php if(_MBT('waterfall') && $style != 'list') echo 'waterfall';?> clearfix">
			<?php 
			  	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$args = array(
				    //'ignore_sticky_posts' => 1,
				    'category__not_in' => explode(',', _MBT('home_cats_exclude')),
				    'paged' => $paged
				);
				query_posts($args);
				$ccc = 'content';if($style == 'list') $ccc = 'content-list';
				while ( have_posts() ) : the_post(); 
				get_template_part( $ccc, get_post_format() );
				endwhile; wp_reset_query(); 
			?>
		</div>
		<?php MBThemes_paging();?>
		<div class="posts-loading"><img src="<?php bloginfo('template_url')?>/static/img/loader.gif"></div>
		<?php if($style == 'list') {echo '</div></div>';get_sidebar();}?>
	</div>
	<?php if(_MBT('home_blog')) get_template_part("module/home-blogs");?>
	<?php if(_MBT('home_authors')) get_template_part("module/home-authors");?>
	<?php if(_MBT('home_vip') && (is_user_logged_in() || !_MBT('hide_user_all'))) get_template_part("module/vip");?>
	<?php if(_MBT('home_why')) get_template_part("module/why");?>
	<?php if(_MBT('home_total')) get_template_part("module/total");?>
	<?php if(_MBT('ad_home_footer_s')) {echo '<div class="container">';MBThemes_ad('ad_home_footer');echo '</div>';}?>
</div>
<?php get_footer();?>