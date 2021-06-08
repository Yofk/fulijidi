<?php  
add_action('widgets_init','unregister_mobantu_widget');
function unregister_mobantu_widget(){
    //unregister_widget ('WP_Nav_Menu_Widget');
	//unregister_widget ( 'WP_Widget_Pages' );
	unregister_widget ( 'WP_Widget_Archives' );
	unregister_widget ( 'WP_Widget_Links' );
	unregister_widget ( 'WP_Widget_Meta' );
	unregister_widget ( 'WP_Widget_Text' );
	unregister_widget ( 'WP_Widget_Recent_Posts' );
	unregister_widget ( 'WP_Widget_Recent_Comments' );
	unregister_widget ( 'WP_Widget_RSS' );
	//unregister_widget ( 'WP_Widget_Tag_Cloud' );
}



add_action( 'widgets_init', 'widget_tags_loader' );
function widget_tags_loader() {
	register_widget( 'widget_tags_loader' );
}

class widget_tags_loader extends WP_Widget {
	
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'widget-tags',
			'description' => '显示热门标签',
		);
		parent::__construct( 'widget_tags_loader', 'Modown-标签云', $widget_ops );
	}

	public function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_name', $instance['title']);
		$count = $instance['count'];
		$offset = $instance['offset'];
		$nopadding = isset($instance['nopadding']) ? $instance['nopadding'] : '';

		if($nopadding)
			echo '<div class="widget widget-tags nopadding">';
		else
			echo $before_widget;
		echo $before_title.'<i class="icon icon-tag"></i> '.$title.$after_title; 
		echo '<div class="items">';
		$tags_list = get_tags('orderby=count&order=DESC&number='.$count.'&offset='.$offset);
		if ($tags_list) { 
			foreach($tags_list as $tag) {
				echo '<a href="'.get_tag_link($tag).'">'. $tag->name .'</a>'; 
			} 
		}else{
			echo '暂无标签！';
		}
		echo '</div>';
		echo $after_widget;
	}

	public function form($instance) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '热门标签';
?>
		<p>
			<label>
				名称：
				<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
			</label>
		</p>
		<p>
			<label>
				显示数量：
				<input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="number" value="<?php echo $instance['count']; ?>" class="widefat" />
			</label>
		</p>
		<p>
			<label>
				去除前几个：
				<input id="<?php echo $this->get_field_id('offset'); ?>" name="<?php echo $this->get_field_name('offset'); ?>" type="number" value="<?php echo $instance['offset']; ?>" class="widefat" />
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked( $instance['nopadding'], 'on' ); ?> id="<?php echo $this->get_field_id('nopadding'); ?>" name="<?php echo $this->get_field_name('nopadding'); ?>">No padding（无边距）
			</label>
		</p>
		
<?php
	}
}


add_action( 'widgets_init', 'widget_author_loader' );
function widget_author_loader() {
	register_widget( 'widget_author' );
}

class widget_author extends WP_Widget {
	
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'widget-author',
			'description' => '文章页显示作者信息，仅对文章内页有效',
		);
		parent::__construct( 'widget_author', 'Modown-作者', $widget_ops );
	}

	public function widget( $args, $instance ) {
		extract( $args );
		if(is_singular()){
			$author_post_count = count_user_posts( get_the_author_meta( 'ID' ) );
			echo '<div class="widget widget_author nopadding">';
?>
			<div class="author-cover">
				<img src="<?php echo get_bloginfo('template_url').'/static/img/author-cover.jpg';?>">
			</div>
			<div class="author-avatar"> 
				<a target="_blank" href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' ));?>"  class="avatar-link"><?php echo get_avatar(get_the_author_meta( 'ID' ));?>
					<?php if(wp_is_erphpdown_active()){ 
					if(getUsreMemberTypeById(get_the_author_meta( 'ID' ))) echo '<span class="vip"></span>'; 
				}?>
				</a>
			</div>
			<div class="author-info">
				<p><span class="author-name"><?php echo get_the_author() ?></span><span class="author-group"><?php echo MBThemes_current_user_role(get_the_author_meta( 'ID' ));?></span></p>
				<p class="author-description"><?php the_author_description(); ?></p>
			</div>
<?php
			echo $after_widget;
		}	
	}
}



add_action( 'widgets_init', 'widget_ads_loader' );
function widget_ads_loader() {
	register_widget( 'widget_ads' );
}

class widget_ads extends WP_Widget {
	
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'widget-text',
			'description' => '显示一个广告(包括富媒体)',
		);
		parent::__construct( 'widget_ads', 'Modown-广告', $widget_ops );
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_name', $instance['title']);
		$code = $instance['code'];
		$nopadding = isset($instance['nopadding']) ? $instance['nopadding'] : '';

		if($nopadding)
			echo '<div class="widget widget-text nopadding">';
		else
			echo $before_widget;
		if($title) echo $before_title.$title.$after_title; 
		echo $code;
		echo $after_widget;
	}

	public function form($instance) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$code = ! empty( $instance['code'] ) ? $instance['code'] : '这里输入广告代码' ;
?>
	    <p>
			<label>
				名称：
				<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
			</label>
		</p>
		<p>
			<label>
				广告代码：
				<textarea id="<?php echo $this->get_field_id('code'); ?>" name="<?php echo $this->get_field_name('code'); ?>" class="widefat" rows="12" style="font-family:Courier New;"><?php echo $code; ?></textarea>
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked( $instance['nopadding'], 'on' ); ?> id="<?php echo $this->get_field_id('nopadding'); ?>" name="<?php echo $this->get_field_name('nopadding'); ?>">No padding（无边距）
			</label>
		</p>
<?php
	}
}

add_action( 'widgets_init', 'widget_bottom_loader' );
function widget_bottom_loader() {
	register_widget( 'widget_bottom' );
}

class widget_bottom extends WP_Widget {
	
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'widget-bottom',
			'description' => '网站底部内容，可以放链接导航、图片二维码等',
		);
		parent::__construct( 'widget_bottom', 'Modown-底部文本', $widget_ops );
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_name', $instance['title']);
		$code = $instance['code'];

		echo $before_widget;
		echo $before_title.$title.$after_title; 
		echo '<div class="footer-widget-content">'.$code.'</div>';
		echo $after_widget;
	}

	public function form($instance) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '文本标题';
		$code = ! empty( $instance['code'] ) ? $instance['code'] : '这里输入代码' ;
?>
	    <p>
			<label>
				名称：
				<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
			</label>
		</p>
		<p>
			<label>
				代码：<a href="http:// www.6gym.com/7472.html" target="_blank">示例代码</a>
				<textarea id="<?php echo $this->get_field_id('code'); ?>" name="<?php echo $this->get_field_name('code'); ?>" class="widefat" rows="12" style="font-family:Courier New;"><?php echo $code; ?></textarea>
			</label>
		</p>
<?php
	}
}


add_action( 'widgets_init', 'widget_bottom_logo_loader' );
function widget_bottom_logo_loader() {
	register_widget( 'widget_bottom_logo' );
}

class widget_bottom_logo extends WP_Widget {
	
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'widget-bottom widget-bottom-logo',
			'description' => '网站底部LOGO+文本代码',
		);
		parent::__construct( 'widget_bottom_logo', 'Modown-底部LOGO', $widget_ops );
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_name', $instance['title']);
		$code = $instance['code'];

		echo $before_widget;
		//echo $before_title.$title.$after_title; 
		echo '<a href="'.home_url().'" class="footer-logo"><img src="'.$title.'" alt="'.get_bloginfo('name').'"></a>';
		echo '<div class="footer-widget-content">'.$code.'</div>';
		echo $after_widget;
	}

	public function form($instance) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$code = ! empty( $instance['code'] ) ? $instance['code'] : get_bloginfo('description') ;
?>
	    <p>
			<label>
				LOGO图片地址：
				<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
			</label>
		</p>
		<p>
			<label>
				代码：
				<textarea id="<?php echo $this->get_field_id('code'); ?>" name="<?php echo $this->get_field_name('code'); ?>" class="widefat" rows="12" style="font-family:Courier New;"><?php echo $code; ?></textarea>
			</label>
		</p>
<?php
	}
}


add_action( 'widgets_init', 'widget_bottom_search_loader' );
function widget_bottom_search_loader() {
	register_widget( 'widget_bottom_search' );
}

class widget_bottom_search extends WP_Widget {
	
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'widget-bottom widget-bottom-search',
			'description' => '网站底部搜索+内容',
		);
		parent::__construct( 'widget_bottom_search', 'Modown-底部搜索', $widget_ops );
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_name', $instance['title']);
		$code = $instance['code'];

		echo $before_widget;
		echo $before_title.$title.$after_title; 
		echo '<div class="footer-widget-content"><form role="search" method="get" class="searchform clearfix" action="'.home_url().'">
				<div>
					<input type="text" value="" name="s" id="s">
					<button type="submit"><i class="icon icon-search"></i></button>
				</div>
			</form>'.$code.'</div>';
		echo $after_widget;
	}

	public function form($instance) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '文本标题';
		$code = ! empty( $instance['code'] ) ? $instance['code'] : '这里输入代码' ;
?>
	    <p>
			<label>
				名称：
				<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
			</label>
		</p>
		<p>
			<label>
				搜索下代码：
				<textarea id="<?php echo $this->get_field_id('code'); ?>" name="<?php echo $this->get_field_name('code'); ?>" class="widefat" rows="12" style="font-family:Courier New;"><?php echo $code; ?></textarea>
			</label>
		</p>
<?php
	}
}


add_action( 'widgets_init', 'widget_postlist_loader' );
function widget_postlist_loader() {
	register_widget( 'widget_postlist' );
}

class widget_postlist extends WP_Widget {
	
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'widget-postlist',
			'description' => '最新文章+热评文章+随机文章+推荐文章',
		);
		parent::__construct( 'widget_postlist', 'Modown-文章列表', $widget_ops );
	}

	public function widget( $args, $instance ) {
		global $post_target;
		extract( $args );

		$title        = apply_filters('widget_name', $instance['title']);
		$limit        = $instance['limit'];
		$cat          = $instance['cat'];
		$orderby      = $instance['orderby'];
		$img = isset($instance['img']) ? $instance['img'] : '';
		$img2 = isset($instance['img2']) ? $instance['img2'] : '';
		$nopadding = isset($instance['nopadding']) ? $instance['nopadding'] : '';

		$class = '';
		if($img) $class .= ' hasimg';
		if($img2) $class .= ' hasimg2';

		if($nopadding)
			echo '<div class="widget widget-postlist nopadding">';
		else
			echo $before_widget;
		echo $before_title.'<i class="icon icon-posts"></i> '.$title.$after_title.'<ul class="clearfix'.$class.'">';
		if($orderby == 'recommend'){
			$args = array(
			  'order'            => 'DESC',
			  'orderby'          => 'date',
			  'cat'              => $cat,
			  'meta_query' => array(array('key'=>'down_recommend','value'=>'1')),
			  'showposts'        => $limit,
			  'ignore_sticky_posts' => 1
			);
		}else{
			$args = array(
				'order'            => 'DESC',
				'cat'              => $cat,
				'orderby'          => $orderby,
				'showposts'        => $limit,
				'ignore_sticky_posts' => 1
			);
		}
		query_posts($args);
		while (have_posts()) : the_post(); 
		?>
        <li>
          <?php if($img){?>
          <a href="<?php the_permalink();?>" title="<?php the_title();?>" target="<?php echo $post_target;?>" rel="bookmark" class="img">
		    <img src="<?php echo MBThemes_thumbnail(105,66,0);?>" class="thumb" alt="<?php the_title();?>">
		  </a>
		  <?php }?>
		  <?php if(!$img2){?>
          <h4><a href="<?php the_permalink(); ?>" target="<?php echo $post_target;?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
          <p class="meta">
          	<?php if(_MBT('post_date')){?><span class="time"><?php echo MBThemes_timeago( get_the_time('Y-m-d G:i:s') ) ?></span><?php }?>
          	<?php if(_MBT('post_views')){?><span><i class="icon icon-eye"></i> <?php MBThemes_views();?></span><?php }?>
          	<?php 
		    $start_down=get_post_meta(get_the_ID(), 'start_down', true);
	        $start_down2=get_post_meta(get_the_ID(), 'start_down2', true);
	        $start_see=get_post_meta(get_the_ID(), 'start_see', true);
	        $start_see2=get_post_meta(get_the_ID(), 'start_see2', true);
		    $price=MBThemes_erphpdown_price(get_the_ID());
		    $memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
		    if(($start_down || $start_down2 || $start_see || $start_see2) && wp_is_erphpdown_active() && (!_MBT('post_price') && (is_user_logged_in() || !_MBT('hide_user_all')))){
		    	echo '<span class="price">';
			    if($memberDown == '4' || $memberDown == '8' || $memberDown == '9') echo 'VIP';
			    elseif($price) echo '<span class="fee"><i class="icon icon-ticket"></i> '.$price.'</span>';
			    else echo '<span class="fee">免费</span>';
			    echo '</span>';
			}
		    ?>
          </p>
          <?php }?>
        </li>
		<?php
		endwhile; wp_reset_query();
		echo '</ul>';
		echo $after_widget;
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '最新文章' ;
?>
		<p>
			<label>
				标题：
				<input style="width:100%;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</label>
		</p>
		<p>
			<label>
				排序：
				<select style="width:100%;" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" style="width:100%;">
					<option value="date" <?php selected('date', $instance['orderby']); ?>>发布时间</option>
                	<option value="recommend" <?php selected('recommend', $instance['orderby']); ?>>推荐</option>
					<option value="comment_count" <?php selected('comment_count', $instance['orderby']); ?>>评论数</option>
					<option value="rand" <?php selected('rand', $instance['orderby']); ?>>随机</option>
				</select>
			</label>
		</p>
		<p>
			<label>
				分类限制：
				<a style="font-weight:bold;color:#f60;text-decoration:none;" href="javascript:;" title="格式：1,2 &nbsp;表限制ID为1,2分类的文章&#13;格式：-1,-2 &nbsp;表排除分类ID为1,2的文章&#13;也可直接写1或者-1；注意逗号须是英文的">？</a>
				<input style="width:100%;" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>" type="text" value="<?php echo $instance['cat']; ?>" size="24" />
			</label>
		</p>
		<p>
			<label>
				显示数目：
				<input style="width:100%;" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" size="24" />
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked( $instance['img'], 'on' ); ?> id="<?php echo $this->get_field_id('img'); ?>" name="<?php echo $this->get_field_name('img'); ?>">显示图片
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked( $instance['img2'], 'on' ); ?> id="<?php echo $this->get_field_id('img2'); ?>" name="<?php echo $this->get_field_name('img2'); ?>">纯图模式（不显示标题与字段）
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked( $instance['nopadding'], 'on' ); ?> id="<?php echo $this->get_field_id('nopadding'); ?>" name="<?php echo $this->get_field_name('nopadding'); ?>">No padding（无边距）
			</label>
		</p>
		
	<?php
	}
}

add_action( 'widgets_init', 'widget_posttoplist_loader' );
function widget_posttoplist_loader() {
	register_widget( 'widget_posttoplist' );
}

class widget_posttoplist extends WP_Widget {
	
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'widget-postlist widget-toplist',
			'description' => '下载排行榜，需安装erphpdown插件',
		);
		parent::__construct( 'widget_posttoplist', 'Modown-下载排行', $widget_ops );
	}

	public function widget( $args, $instance ) {
		global $post_target;
		extract( $args );

		$title        = apply_filters('widget_name', $instance['title']);
		$limit        = $instance['limit'];
		$caton        = $instance['cat'];
		$nopadding = isset($instance['nopadding']) ? $instance['nopadding'] : '';

		if($nopadding)
			echo '<div class="widget widget-postlist widget-toplist nopadding">';
		else
			echo $before_widget;
		echo $before_title.'<i class="icon icon-top"></i> '.$title.$after_title.'<ul>';

		if($caton && is_single()){
			$categories = get_the_category(); 
			$cid = $categories[0]->term_id;
		    foreach($categories as $cate){
		        $children = get_term_children( $cate->term_id , 'category');
		        if ( count($children) == '0') {
		            $cid = $cate->term_id;
		        }
		    }
			
			$args = array(
			  'order'            => 'DESC',
			  'orderby'          => 'meta_value_num',
			  'meta_key'         => 'down_times',
			  'cat'              => $cid,
			  'showposts'        => $limit,
			  'ignore_sticky_posts' => 1
			);
		}else{
			$args = array(
			  'order'            => 'DESC',
			  'orderby'          => 'meta_value_num',
			  'meta_key'         => 'down_times',
			  'showposts'        => $limit,
			  'ignore_sticky_posts' => 1
			);
		}

		query_posts($args);
		$i = 1;
		while (have_posts()) : the_post(); 
			$start_down=get_post_meta(get_the_ID(), 'start_down', true);
		    $price=MBThemes_erphpdown_price(get_the_ID());
		    $memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
		    $down_times=get_post_meta(get_the_ID(), 'down_times',TRUE);
		?>
        <li>
        	<span class="sort"><?php echo $i;?></span>
          <h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" target="<?php echo $post_target;?>"><?php the_title(); ?></a></h4>
          <p class="meta">
          	<span class="downloads"><i class="icon icon-download"></i> <?php echo $down_times?$down_times:'0';?></span>
          	<?php if(_MBT('post_views')){?><span class="views"><i class="icon icon-eye"></i> <?php MBThemes_views();?></span><?php }?>
          	<?php 
		    if($start_down && !_MBT('post_price') && (is_user_logged_in() || !_MBT('hide_user_all'))){
		    	echo '<span class="price">';
			    if($memberDown == '4' || $memberDown == '8' || $memberDown == '9') echo 'VIP';
			    elseif($price) echo '<span class="fee"><i class="icon icon-ticket"></i> '.$price.'</span>';
			    else echo '<span class="fee">免费</span>';
			    echo '</span>';
			}
		    ?>
          </p>
        </li>
		<?php
		$i++;
		endwhile; wp_reset_query();
		echo '</ul>';
		echo $after_widget;
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '最新文章' ;
?>
		<p>
			<label>
				标题：
				<input style="width:100%;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</label>
		</p>
		<p>
			<label>
				显示数目：
				<input style="width:100%;" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" size="24" />
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked( $instance['cat'], 'on' ); ?> id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>">文章页显示当前分类排行
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked( $instance['nopadding'], 'on' ); ?> id="<?php echo $this->get_field_id('nopadding'); ?>" name="<?php echo $this->get_field_name('nopadding'); ?>">No padding（无边距）
			</label>
		</p>
	<?php
	}
}


add_action( 'widgets_init', 'widget_bloglist_loader' );
function widget_bloglist_loader() {
	register_widget( 'widget_bloglist' );
}

class widget_bloglist extends WP_Widget {
	
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'widget-postlist widget-bloglist',
			'description' => '最新博客+热评博客+随机博客',
		);
		parent::__construct( 'widget_bloglist', 'Modown-博客列表', $widget_ops );
	}

	public function widget( $args, $instance ) {
		global $post_target;
		extract( $args );

		$title        = apply_filters('widget_name', $instance['title']);
		$limit        = $instance['limit'];
		$orderby      = $instance['orderby'];
		$nopadding = isset($instance['nopadding']) ? $instance['nopadding'] : '';

		if($nopadding)
			echo '<div class="widget widget-postlist widget-bloglist nopadding">';
		else
			echo $before_widget;

		echo $before_title.'<i class="icon icon-posts"></i> '.$title.$after_title.'<ul>';

		$args = array(
			'post_type'        => 'blog',
			'order'            => 'DESC',
			'orderby'          => $orderby,
			'showposts'        => $limit,
			'ignore_sticky_posts' => 1
		);
		
		query_posts($args);
		while (have_posts()) : the_post(); 
		?>
        <li>
          <h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" target="<?php echo $post_target;?>"><?php the_title(); ?></a></h4>
          <p class="meta">
          	<?php if(_MBT('post_date')){?><span class="time"><i class="icon icon-time"></i> <?php echo MBThemes_timeago( get_the_time('Y-m-d G:i:s') ) ?></span><?php }?><?php if(_MBT('post_views')){?><span class="views"><i class="icon icon-eye"></i> <?php MBThemes_views();?></span><?php }?>
          </p>
        </li>
		<?php
		endwhile; wp_reset_query();
		echo '</ul>';
		echo $after_widget;
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '最新博客' ;
?>
		<p>
			<label>
				标题：
				<input style="width:100%;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</label>
		</p>
		<p>
			<label>
				排序：
				<select style="width:100%;" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" style="width:100%;">
					<option value="comment_count" <?php selected('comment_count', $instance['orderby']); ?>>评论数</option>
					<option value="date" <?php selected('date', $instance['orderby']); ?>>发布时间</option>
					<option value="rand" <?php selected('rand', $instance['orderby']); ?>>随机</option>
				</select>
			</label>
		</p>
		<p>
			<label>
				显示数目：
				<input style="width:100%;" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" size="24" />
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked( $instance['nopadding'], 'on' ); ?> id="<?php echo $this->get_field_id('nopadding'); ?>" name="<?php echo $this->get_field_name('nopadding'); ?>">No padding（无边距）
			</label>
		</p>
		
	<?php
	}
}


add_action( 'widgets_init', 'widget_comment_loader' );

function widget_comment_loader() {
	register_widget( 'widget_commentlist' );
}

class widget_commentlist extends WP_Widget {
	
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'widget-commentlist',
			'description' => '边侧栏显示网友最新评论',
		);
		parent::__construct( 'widget_commentlist', 'Modown-评论', $widget_ops );
	}

	public function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_name', $instance['title']);
		$not = $instance['not'];
		$limit = $instance['limit'];
		$nopadding = isset($instance['nopadding']) ? $instance['nopadding'] : '';

		if($nopadding)
			echo '<div class="widget widget-commentlist nopadding">';
		else
			echo $before_widget;
		
		echo $before_title.'<i class="icon icon-comments"></i> '.$title.$after_title; 
		echo '<div>'.mowidget_comments($limit,$not).'</div>';
		echo $after_widget;
	}

	public function form($instance) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '网友评论' ;
?>
		<p>
			<label>
				标题：
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</label>
		</p>
        <p>
			<label>
				排除用户IDs：
				<input class="widefat" id="<?php echo $this->get_field_id('not'); ?>" name="<?php echo $this->get_field_name('not'); ?>" type="text" value="<?php echo $instance['not']; ?>" />
			</label>
		</p>
		<p>
			<label>
				显示数目：
				<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" />
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked( $instance['nopadding'], 'on' ); ?> id="<?php echo $this->get_field_id('nopadding'); ?>" name="<?php echo $this->get_field_name('nopadding'); ?>">No padding（无边距）
			</label>
		</p>

<?php
	}
}

function mowidget_comments($limit,$not){
	global $wpdb,$post_target;
	$output = '';
	$commargs = array('number' => $limit, 'author__not_in' => $not);
	$comments = get_comments($commargs);
	foreach ( $comments as $comment ) {
		if($comment->comment_approved == '1'){
			$author = $comment->comment_author;
			if($comment->user_id){
				$author = get_user_by('ID',$comment->user_id)->nickname;
			}
			if(MBThemes_check_vip($comment->user_id)) $author .= '<span class="is-vip" title="VIP用户"><i class="icon icon-crown-s"></i></span>';
			$output .='<div class="comment-item comment-'.$comment->comment_ID.'">
			      <div class="postmeta">'.$author.' • '.MBThemes_timeago( get_comment_date('Y-m-d G:i:s',$comment->comment_ID) ).'</div>
			      <div class="sidebar-comments-comment">'.convert_smilies( mb_strimwidth( MBThemes_strip_tags( $comment->comment_content ), 0, 80, '...')).'</div>
			      <div class="sidebar-comments-title">
			        <p>来源：<a href="'.get_permalink($comment->comment_post_ID).'" target="'.$post_target.'">'.$comment->post_title.'</a></p>
			      </div>
			    </div>';
		}
	}
	
	return $output;
}
