<?php 
/*
	template name: 首页模板一
	description: template for mobantu.com modown theme 
*/
get_header();?>
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
	<?php if(_MBT('ad_banner_footer_s')) {echo '<div class="container">';MBThemes_ad('ad_banner_footer', '-10');echo '</div>';}?>
	<div class="contents">
		<?php while (have_posts()) : the_post(); ?>
		<?php the_content(); ?>
		<?php endwhile;  ?>
	</div>
	<?php if(_MBT('home_blog')) get_template_part("module/home-blogs");?>
	<?php if(_MBT('home_authors')) get_template_part("module/home-authors");?>
	<?php if(_MBT('home_vip') && (is_user_logged_in() || !_MBT('hide_user_all'))) get_template_part("module/vip");?>
	<?php if(_MBT('home_why')) get_template_part("module/why");?>
	<?php if(_MBT('home_total')) get_template_part("module/total");?>
	<?php if(_MBT('ad_home_footer_s')) {echo '<div class="container">';MBThemes_ad('ad_home_footer');echo '</div>';}?>
</div>
<?php get_footer();?>