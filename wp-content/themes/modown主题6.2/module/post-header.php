<header class="article-header"><?php $post = get_post();setup_postdata($post);$sign = get_post_meta(get_the_ID(),'sign',true);
$sign = $sign?'<span class="item post-sign">'.$sign.'</span>':'';?>
	<h1 class="article-title"><?php the_title(); ?></h1>
	<div class="article-meta">
		<?php echo $sign;?><?php if(_MBT('post_author')){?><span class="item"><i class="icon icon-user"></i> <a target="_blank" href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' ));?>" class="avatar-link"><?php echo get_the_author(); ?></a></span><?php }?>
		<?php if(_MBT('post_date')){?><span class="item"><i class="icon icon-time"></i> <?php echo MBThemes_timeago( get_the_time('Y-m-d G:i:s') ) ?></span><?php }?>
		<span class="item"><i class="icon icon-cat"></i> <?php $category = get_the_category(); ?><a href="<?php echo get_category_link($category[0]->term_id );?>"><?php echo $category[0]->cat_name;?></a></span>
		<?php if(_MBT('post_views')){?><span class="item"><i class="icon icon-eye"></i> <?php MBThemes_views() ?></span><?php }?>
		<?php $downtimes = get_post_meta(get_the_ID(),'down_times',true);
		if(_MBT('post_downloads')) echo '<span class="item"><i class="icon icon-download"></i> '.($downtimes?$downtimes:'0').'</span>';?>
		<?php edit_post_link('[编辑]'); ?>
		<?php if(wp_is_erphpdown_active() && _MBT('post_copy_aff')){?><span class="item right"><i class="icon icon-copy"></i> <a href="javascript:;" class="article-aff" <?php if(is_user_logged_in()){global $current_user;?>data-clipboard-text="<?php echo add_query_arg('aff',$current_user->ID,get_permalink());?>"<?php }?>>推广</a></span><?php }?>
	</div>
</header>