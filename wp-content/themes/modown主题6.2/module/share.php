<?php 
	$share_link = get_the_permalink();
	if(is_user_logged_in()){
		global $current_user;
		$share_link = add_query_arg('aff',$current_user->ID,get_permalink());
	}
	echo '<div class="article-shares">';
    echo '<a href="javascript:;" data-url="'. $share_link .'" class="share-weixin" title="分享到微信"><i class="icon icon-weixin"></i></a><a data-share="qzone" class="share-qzone" data-url="'. $share_link .'" title="分享到QQ空间"><i class="icon icon-qzone"></i></a><a data-share="weibo" class="share-tsina" data-url="'. $share_link .'" title="分享到新浪微博"><i class="icon icon-weibo"></i></a><a data-share="qq" class="share-sqq" data-url="'. $share_link .'" title="分享到QQ好友"><i class="icon icon-qq"></i></a><a data-share="douban" class="share-douban" data-url="'. $share_link .'" title="分享到豆瓣网"><i class="icon icon-douban"></i></a>';
	if(_MBT('post_share_cover_img')){
    	echo '<a href="javascript:;" class="article-cover right" title="分享卡片" data-s-id="'.get_the_ID().'"><i class="icon icon-cover"></i> 封面图<span id="wx-thumb-qrcode" data-url="'.$share_link.'"></span></a>';
    }
    echo '</div>';
?>