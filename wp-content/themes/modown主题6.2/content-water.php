<?php 
$tj = get_post_meta(get_the_ID(),'down_recommend',true);
$sign = get_post_meta(get_the_ID(),'sign',true);
$sign = $sign?'<span class="post-sign">'.$sign.'</span>':'';
global $post_target;
?>
<div class="post grid<?php if($tj) echo ' grid-tj';if(_MBT('post_author')) echo ' grid-zz';?>">
  <div class="img"><a href="<?php the_permalink();?>" title="<?php the_title();?>" target="<?php echo $post_target;?>" rel="bookmark">
    <img src="<?php echo MBThemes_thumbnail_full();?>" class="thumb" alt="<?php the_title();?>">
  </a></div>
  <?php if(_MBT('post_cat')){?><?php $category = MBThemes_youngest_category(); ?><a href="<?php echo get_category_link($category->term_id );?>" class="cat"><?php echo $category->cat_name;?></a><?php }?>
  <h3 itemprop="name headline"><a itemprop="url" rel="bookmark" href="<?php the_permalink();?>" title="<?php the_title();?>" target="<?php echo $post_target;?>"><?php echo $sign;?><?php the_title();?></a></h3>
  <?php if(!_MBT('post_metas')){?>
  <div class="grid-meta">
    <?php if(_MBT('post_date')){?><span class="time"><i class="icon icon-time"></i> <?php echo MBThemes_timeago( get_the_time('Y-m-d G:i:s') ) ?></span><?php }?><?php if(_MBT('post_views')){?><span class="views"><i class="icon icon-eye"></i> <?php MBThemes_views();?></span><?php }?><?php if(_MBT('post_comments')){?><span class="comments"><i class="icon icon-comment"></i> <?php echo get_comments_number('0', '1', '%');?></span><?php }?><?php 
      if(wp_is_erphpdown_active()){
        $start_down=get_post_meta(get_the_ID(), 'start_down', true);
        $start_down2=get_post_meta(get_the_ID(), 'start_down2', true);
        $start_see=get_post_meta(get_the_ID(), 'start_see', true);
        $start_see2=get_post_meta(get_the_ID(), 'start_see2', true);
        $price=MBThemes_erphpdown_price(get_the_ID());
        $memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
        $downtimes = get_post_meta(get_the_ID(),'down_times',true);
        if($start_down || $start_down2 || $start_see || $start_see2){
          if(_MBT('post_downloads')) echo '<span class="downs"><i class="icon icon-download"></i> '.($downtimes?$downtimes:'0').'</span>';
          if(!_MBT('post_price')){
            echo '<span class="price">';
            $down_tuan = '';
            if(function_exists('erphpdown_tuan_install')){
              $down_tuan=get_post_meta(get_the_ID(), 'down_tuan', true);
            }
            if($down_tuan) echo '<span class="vip-tag tuan-tag"><i>拼团</i></span>';
      	    elseif($memberDown == '4' || $memberDown == '8' || $memberDown == '9') echo '<span class="vip-tag"><i>VIP</i></span>';
      	    elseif($price) echo '<span class="fee"><i class="icon icon-ticket"></i> '.$price.'</span>';
      	    else echo '<span class="vip-tag free-tag"><i>免费</i></span>';
            echo '</span>';
      	  }
        }
      }
      ?>
  </div>
  <?php }?>
  <?php if(_MBT('post_author')){?>
  <div class="grid-author">
    <a target="_blank" href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' ));?>"  class="avatar-link"><?php echo get_avatar(get_the_author_meta( 'ID' ));?><span class="author-name"><?php echo get_the_author() ?></a>
    <span class="time"><?php echo MBThemes_timeago( get_the_time('Y-m-d G:i:s') ) ?></span>
  </div>
  <?php }?>
</div>