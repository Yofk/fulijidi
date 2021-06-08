<?php if(_MBT('post_zan') || _MBT('post_shang') || _MBT('post_collect')){?>
	<div class="article-act">
	<?php if(_MBT('post_collect')){?>
		<?php if(is_user_logged_in()){?>
			<?php if(MBThemes_check_collect(get_the_ID())){?>
			<a href="javascript:;" class="article-collect active" data-id="<?php the_ID();?>" title="已收藏"><i class="icon icon-star"></i> <span><?php echo MBThemes_get_collects(get_the_ID());?></span></a>
			<?php }else{?>
			<a href="javascript:;" class="article-collect" data-id="<?php the_ID();?>" title="收藏"><i class="icon icon-star"></i> <span><?php echo MBThemes_get_collects(get_the_ID());?></span></a>
			<?php }?>
		<?php }else{?>
			<a href="javascript:;" class="article-collect signin-loader" title="收藏"><i class="icon icon-star"></i> <span><?php echo MBThemes_get_collects(get_the_ID());?></span></a>
		<?php }?>
	<?php }?>
	<?php if(_MBT('post_shang')){?>
		<a href="javascript:void(0);" class="article-shang" data-weixin="<?php echo _MBT('post_shang_weixin');?>" data-alipay="<?php echo _MBT('post_shang_alipay');?>">赏</a>
	<?php }?>
	<?php if(_MBT('post_zan')){?>
		<a href="javascript:;" class="article-zan" data-id="<?php the_ID();?>" title="赞"><i class="icon icon-zan"></i> <span><?php echo MBThemes_get_zans(get_the_ID());?></span></a>
	<?php }?>
	</div>
<?php }?>