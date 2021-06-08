<?php 
/**
* MOBANTU THEME INIT
*/
add_action('after_switch_theme', 'MBTheme_active_theme');
function MBTheme_active_theme($oldthemename){
  global $pagenow, $wpdb;
  
  $var = $wpdb->query("SELECT qqid FROM $wpdb->users");
  if(!$var){
    $wpdb->query("ALTER TABLE $wpdb->users ADD qqid varchar(100)");
  }
  $var1 = $wpdb->query("SELECT sinaid FROM $wpdb->users");
  if(!$var1){
   $wpdb->query("ALTER TABLE $wpdb->users ADD sinaid varchar(100)");
  }
  $var2 = $wpdb->query("SELECT weixinid FROM $wpdb->users");
  if(!$var2){
    $wpdb->query("ALTER TABLE $wpdb->users ADD weixinid varchar(100)");
  }
  $var3 = $wpdb->query("SELECT weixin_unionid FROM $wpdb->users");
  if(!$var3){
    $wpdb->query("ALTER TABLE $wpdb->users ADD weixin_unionid varchar(200)");
  }
  $var4 = $wpdb->query("SELECT mobile FROM $wpdb->users");
  if(!$var4){
    $wpdb->query("ALTER TABLE $wpdb->users ADD mobile varchar(20)");
  }

  $collect_table_name = $wpdb->prefix . "collects";
  $collect_sql = "CREATE TABLE $collect_table_name (
  ID int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL,
  post_id int(11) NOT NULL,
  create_time datetime NOT NULL,
  PRIMARY KEY (ID)
  );";
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta($collect_sql);

  $checkin_table_name = $wpdb->prefix . "checkins";
  $checkin_sql = "CREATE TABLE $checkin_table_name (
  ID int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL,
  credit varchar(10),
  create_time datetime NOT NULL,
  PRIMARY KEY (ID)
  );";
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta($checkin_sql);

  $table_ticket_name = $wpdb->prefix . "tickets";
  $table_ticket_sql = "CREATE TABLE $table_ticket_name (
    id int(11) NOT NULL auto_increment,
    user_id int(11) NOT NULL,
    type int(1) NOT NULL,
    number varchar(50) NOT NULL,
    email varchar(200),
    status int(1) default 0 NOT NULL,
    score int(1),
    note text NOT NULL,
    create_time datetime,
    PRIMARY KEY (id)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta($table_ticket_sql);

  $table_ticket_name2 = $wpdb->prefix . "ticket_item";
  $table_ticket_sql2 = "CREATE TABLE $table_ticket_name2 (
    id int(11) NOT NULL auto_increment,
    user_id int(11) NOT NULL,
    ticket_id int(11) NOT NULL,
    type int(1) NOT NULL,
    note text NOT NULL,
    image varchar(500) NOT NULL,
    create_time datetime,
    PRIMARY KEY (id)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta($table_ticket_sql2);

  if( get_option('thumbnail_size_w') < 285 ){
    update_option('thumbnail_size_w', 285);
    update_option('thumbnail_size_h', 180);
  }
  update_option('thumbnail_crop', 1);

  $init_pages = array(
    'template/user.php' => array( '个人中心', 'user' ),
    'template/login.php' => array( '登录', 'login' ),
    'template/vip.php' => array( 'VIP', 'vip' ),
    'template/tougao.php' => array( '投稿', 'tougao' ),
    'template/all.php' => array( '最新发布', 'all' )
  );
  foreach ($init_pages as $template => $item) {
    $one_page = array(
      'post_title'  => $item[0],
      'post_name'   => $item[1],
      'post_status' => 'publish',
      'post_type'   => 'page',
      'post_author' => 1
    );
    $one_page_check = get_page_by_title( $item[0] );
    if(!isset($one_page_check->ID)){
      $one_page_id = wp_insert_post($one_page);
      update_post_meta($one_page_id, '_wp_page_template', $template);
    }
  }

  if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
    wp_redirect( admin_url( "themes.php?page=options-framework" ) );
    exit;
  }
}


function custom_toolbar_link($wp_admin_bar) {$args = array('id' => 'themeset','title' => '主题设置', 'href' => admin_url("themes.php?page=options-framework"),'meta' => array('class' => 'mobantu-modown-bar', 'title' => 'Modown主题设置'));$wp_admin_bar->add_node($args);}
add_action('admin_bar_menu', 'custom_toolbar_link', 999);

function MBThemes_scripts() {
  global $erphpdown_version;
  $static_url = get_bloginfo('template_url');
  wp_dequeue_style( 'erphpdown' );
  wp_dequeue_style( 'wp-block-library' ); 
  wp_enqueue_style('mobantu-libs', $static_url .'/static/css/libs.css', array(), THEME_VER, 'screen');
  wp_enqueue_style('mobantu-base', $static_url .'/static/css/base.css', array(), THEME_VER, 'screen');
  if(is_page_template("template/user.php")){
    wp_enqueue_style('mobantu-user', $static_url .'/static/css/user.css', array(), THEME_VER, 'screen');
  }
  wp_enqueue_style( 'modown-style', get_stylesheet_uri(), array(), THEME_VER, 'screen' );
  wp_enqueue_script("jquery");
  //wp_enqueue_script( 'ckplayer', get_bloginfo('template_url') . '/module/ckplayer/ckplayer.min.js', false, '', false);
  //wp_enqueue_script( 'dplayer', get_bloginfo('template_url') . '/module/dplayer/dplayer.min.js', false, '', false);
  wp_enqueue_script( 'modown-lib', $static_url . '/static/js/lib.js', false, THEME_VER, true);
  wp_enqueue_script( 'modown-base', $static_url . '/static/js/base.js', false, THEME_VER, true);
  if(wp_is_erphpdown_active()){
    wp_enqueue_script( 'erphpdown', constant("erphpdown")."static/erphpdown.js", false, $erphpdown_version, true);
    wp_localize_script( 'erphpdown', 'erphpdown_ajax_url', admin_url("admin-ajax.php"));
  }
}
add_action('wp_enqueue_scripts', 'MBThemes_scripts', 30, 1);

if (function_exists('register_nav_menus')){
  register_nav_menus( array(
    'main' => __('主导航'),
    'page' => __('菜单页面导航'),
    'cat' => __('分类导航(仅支持一级菜单)')
  ));
}

if (function_exists('register_sidebar')){
  register_sidebar(array(
    'name'          => '文章侧栏',
    'id'            => 'widget_single',
    'before_widget' => '<div class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ));
  register_sidebar(array(
    'name'          => '文章侧栏购买框上',
    'id'            => 'widget_single_above',
    'before_widget' => '<div class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ));
  register_sidebar(array(
    'name'          => '首页侧栏',
    'id'            => 'widget_index',
    'before_widget' => '<div class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ));
  register_sidebar(array(
    'name'          => '分类侧栏',
    'id'            => 'widget_archive',
    'before_widget' => '<div class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ));
  register_sidebar(array(
    'name'          => '博客侧栏',
    'id'            => 'widget_blog',
    'before_widget' => '<div class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ));
  register_sidebar(array(
    'name'          => '底部小工具',
    'id'            => 'widget_bottom',
    'before_widget' => '<div class="footer-widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ));
}

if ( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) ) {
  $current_user = wp_get_current_user();
  if($current_user->roles[0] == get_option('default_role')) {
    wp_safe_redirect( get_permalink(MBThemes_page('template/user.php')) );
    exit();
  }
}

function MBThemes_thumbnail($width="285", $height="180", $thumb=1){
  global $post;
  $dir = get_bloginfo('template_directory');
  if($thumb && _MBT('timthumb_height') && _MBT('timthumb_height') != "180"){
    $height = _MBT('timthumb_height');
  }
  $thumbnail_ext_url = get_post_meta( $post->ID, '_thumbnail_ext_url', TRUE );
  if($thumbnail_ext_url){
    $src = $thumbnail_ext_url;
    $thumb_low = strtolower($src);
    if (strpos($thumb_low, '.gif') === false) {
      if(_MBT('timthumb_cdn')){
        $src = $src._MBT('timthumb_cdn_after');
      }elseif(_MBT('timthumb')){
        if(_MBT('waterfall'))
          $src = "$dir/timthumb.php?src=$src&w=$width&zc=1&q=100";
        else
          $src = "$dir/timthumb.php?src=$src&w=$width&h=$height&zc=1&q=100";
      }
    }
  }else{
    if( has_post_thumbnail() ){
      if(_MBT('timthumb_cdn')){
        $timthumb_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $src = $timthumb_src[0];
        $thumb_low = strtolower($src);
        if (strpos($thumb_low, '.gif') === false) {
          $src = $src._MBT('timthumb_cdn_after');
        }
      }elseif(_MBT('timthumb')){
        $timthumb_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $src = $timthumb_src[0];
        $thumb_low = strtolower($src);
        if (strpos($thumb_low, '.gif') === false) {
          if(_MBT('waterfall'))
            $src = "$dir/timthumb.php?src=$src&w=$width&zc=1&q=100";
          else
            $src = "$dir/timthumb.php?src=$src&w=$width&h=$height&zc=1&q=100";
        }
      }else{
        $thumbnail_size = _MBT('thumbnail_size')?_MBT('thumbnail_size'):'full';
        $timthumb_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),$thumbnail_size);
        $src = $timthumb_src[0];
      }
      if(!$src){
        	ob_start();
		      ob_end_clean();
		      $output = preg_match_all('/\<img.+?src="(.+?)".*?\/>/is',$post->post_content,$matches ,PREG_SET_ORDER);
		      $cnt = count( $matches );
		      if($cnt>0){
		        $src = $matches[0][1];
		        $thumb_low = strtolower($src);
		        if (strpos($thumb_low, '.gif') === false) {
              if(_MBT('timthumb_cdn')){
                $src = $src._MBT('timthumb_cdn_after');
              }elseif(_MBT('timthumb')){
		            if(_MBT('waterfall'))
		              $src = "$dir/timthumb.php?src=$src&w=$width&zc=1&q=100";
		            else
		              $src = "$dir/timthumb.php?src=$src&w=$width&h=$height&zc=1&q=100";
		          }
		        }
		      }else{
		        $src = "{$dir}/static/img/thumbnail.png";
		        if(_MBT('thumbnail_default'))
		          $src = _MBT('thumbnail_default');
		      }
        }
    }else{
      ob_start();
      ob_end_clean();
      $output = preg_match_all('/\<img.+?src="(.+?)".*?\/>/is',$post->post_content,$matches ,PREG_SET_ORDER);
      $cnt = count( $matches );
      if($cnt>0){
        $src = $matches[0][1];
        $thumb_low = strtolower($src);
        if (strpos($thumb_low, '.gif') === false) {
          if(_MBT('timthumb_cdn')){
            $src = $src._MBT('timthumb_cdn_after');
          }elseif(_MBT('timthumb')){
            if(_MBT('waterfall'))
              $src = "$dir/timthumb.php?src=$src&w=$width&zc=1&q=100";
            else
              $src = "$dir/timthumb.php?src=$src&w=$width&h=$height&zc=1&q=100";
          }
        }
      }else{
        $src = "{$dir}/static/img/thumbnail.png";
        if(_MBT('thumbnail_default'))
          $src = _MBT('thumbnail_default');
      }
    }
  }
  return $src;
}

function MBThemes_thumbnail_full(){
  global $post;
  $dir = get_bloginfo('template_directory');

  $thumbnail_ext_url = get_post_meta( $post->ID, '_thumbnail_ext_url', TRUE );
  if($thumbnail_ext_url){
    $src = $thumbnail_ext_url;
  }else{
    if( has_post_thumbnail() ){
      $timthumb_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
      $src = $timthumb_src[0];
      if(!$src){
          ob_start();
	      ob_end_clean();
	      $output = preg_match_all('/\<img.+?src="(.+?)".*?\/>/is',$post->post_content,$matches ,PREG_SET_ORDER);
	      $cnt = count( $matches );
	      if($cnt>0){
	        $src = $matches[0][1];
	      }else{
	        $src = "{$dir}/static/img/thumbnail.png";
	        if(_MBT('thumbnail_default'))
	          $src = _MBT('thumbnail_default');
	      }
        }
    }else{
      ob_start();
      ob_end_clean();
      $output = preg_match_all('/\<img.+?src="(.+?)".*?\/>/is',$post->post_content,$matches ,PREG_SET_ORDER);
      $cnt = count( $matches );
      if($cnt>0){
        $src = $matches[0][1];
      }else{
        $src = "{$dir}/static/img/thumbnail.png";
        if(_MBT('thumbnail_default'))
          $src = _MBT('thumbnail_default');
      }
    }
  }
  return $src;
}

function MBThemes_thumbnail_share($post){
  $dir = get_bloginfo('template_directory');

  $thumbnail_ext_url = get_post_meta( $post->ID, '_thumbnail_ext_url', TRUE );
  if($thumbnail_ext_url){
    $src = $thumbnail_ext_url;
  }else{
    if( has_post_thumbnail() ){
      $timthumb_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
      $src = $timthumb_src[0];
      if(!$src){
      	ob_start();
	      ob_end_clean();
	      $output = preg_match_all('/\<img.+?src="(.+?)".*?\/>/is',$post->post_content,$matches ,PREG_SET_ORDER);
	      $cnt = count( $matches );
	      if($cnt>0){
	        $src = $matches[0][1];
	      }
      }
    }else{
      ob_start();
      ob_end_clean();
      $output = preg_match_all('/\<img.+?src="(.+?)".*?\/>/is',$post->post_content,$matches ,PREG_SET_ORDER);
      $cnt = count( $matches );
      if($cnt>0){
        $src = $matches[0][1];
      }
    }
  }
  return $src;
}

function MBThemes_thumbnail_has(){
  global $post;
  $dir = get_bloginfo('template_directory');

  $thumbnail_ext_url = get_post_meta( $post->ID, '_thumbnail_ext_url', TRUE );
  if($thumbnail_ext_url){
    return true;
  }else{
    if( has_post_thumbnail() ){
      return true;
    }else{
      ob_start();
      ob_end_clean();
      $output = preg_match_all('/\<img.+?src="(.+?)".*?\/>/is',$post->post_content,$matches ,PREG_SET_ORDER);
      $cnt = count( $matches );
      if($cnt>0){
        return true;
      }
    }
  }
  return false;
}

function MBThemes_paging() {
  $p = 2;
  global $wp_query, $paged;
  $max_page = $wp_query->max_num_pages;
  if ( $max_page > 1 ){
    echo '<div class="pagination"><ul>';
    if ( empty( $paged ) ) $paged = 1;
    echo '<li class="prev-page">'; previous_posts_link('上一页'); echo '</li>';

    if ( $paged > $p + 1 ) p_link( 1, '<li>第一页</li>' );
    if ( $paged > $p + 2 ) echo "<li><span>···</span></li>";
    for( $i = $paged - $p; $i <= $paged + $p; $i++ ) { 
      if ( $i > 0 && $i <= $max_page ) $i == $paged ? print "<li class=\"active\"><span>{$i}</span></li>" : p_link( $i );
    }
    if ( $paged < $max_page - $p - 1 ) echo "<li><span> ... </span></li>";
    if ( $paged < $max_page - $p ) p_link( $max_page, '&raquo;' );
    echo '<li class="next-page">'; next_posts_link('下一页'); echo '</li>';
    //echo '<li><span>共 '.$max_page.' 页</span></li>';
    if($max_page>3){
      echo '<li><input type="number" id="jump_page" min="1" step="1" /><a href="javascript:;" class="page-jump">跳转</a></li>';
    }
    echo '</ul></div>';
  }
}

function MBThemes_custom_paging($paged,$max_page) {
  $p = 2;
  global $wp_query;
  if ( $max_page > 1 ){
    echo '<div class="pagination"><ul>';
    if ( empty( $paged ) ) $paged = 1;
    echo '<li class="prev-page">'; previous_posts_link('上一页'); echo '</li>';

    if ( $paged > $p + 1 ) p_link( 1, '<li>第一页</li>' );
    if ( $paged > $p + 2 ) echo "<li><span>···</span></li>";
    for( $i = $paged - $p; $i <= $paged + $p; $i++ ) { 
      if ( $i > 0 && $i <= $max_page ) $i == $paged ? print "<li class=\"active\"><span>{$i}</span></li>" : p_link( $i );
    }
    if ( $paged < $max_page - $p - 1 ) echo "<li><span> ... </span></li>";
    if ( $paged < $max_page - $p ) p_link( $max_page, '&raquo;' );
    echo '<li class="next-page">'; next_posts_link('下一页'); echo '</li>';
    //echo '<li><span>共 '.$max_page.' 页</span></li>';
    echo '</ul></div>';
  }
}

function p_link( $i, $title = '' ) {
  if ( $title == '' ) $title = "第 {$i} 页";
  echo "<li><a href='", esc_html( get_pagenum_link( $i ) ), "'>{$i}</a></li>";
}
function p_curr_link( $i) {
  echo '<li><span class="page-numbers current">'.$i.'</span></li>';
}

function MBThemes_timeago( $ptime ) {
  date_default_timezone_set('Asia/Shanghai');
  if(_MBT('post_date_format')){
    return date("Y-m-d",strtotime($ptime));
  }else{
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if($etime < 1) return '刚刚';
    if($etime > 30 * 24 * 60 * 60) return date('m-d', $ptime);
    $interval = array (
      12 * 30 * 24 * 60 * 60  =>  '年前',
      30 * 24 * 60 * 60       =>  '月前',
      7 * 24 * 60 * 60        =>  '周前',
      24 * 60 * 60            =>  '天前',
      60 * 60                 =>  '小时前',
      60                      =>  '分钟前',
      1                       =>  '秒前'
    );
    foreach ($interval as $secs => $str) {
      $d = $etime / $secs;
      if ($d >= 1) {
        $r = round($d);
        return $r . $str;
      }
    };
  }
}

if ( ! function_exists( 'MBThemes_views' ) ) :
  function MBThemes_record_visitors(){
    if (is_singular()) {
      global $post;
      $post_ID = $post->ID;
      if($post_ID) {
        $post_views = (int)get_post_meta($post_ID, 'views', true);
        if(!update_post_meta($post_ID, 'views', ($post_views+1))){
          add_post_meta($post_ID, 'views', 1, true);
        }
      }
    }
  }
  add_action('wp_head', 'MBThemes_record_visitors');  

  function MBThemes_views($echo = true, $after=''){
    global $post;
    $post_ID = $post->ID;
    $views = MBThemes_num2tring((int)get_post_meta($post_ID, 'views', true));
    if($echo)
      echo $views.$after;
    else
      return $views.$after;
  }
endif;


function MBThemes_wp_title( $title, $sep ) {
  global $paged, $page, $post;
  if ( is_feed() )
   return $title;
 $title .= get_bloginfo( 'name' );
 $site_description = get_bloginfo( 'description', 'display' );

 if(is_home() || is_front_page()){
   if ( $site_description ){
     $title = "$title $sep $site_description";
   }
 }elseif ( is_single() ) {
   $seo_title = get_post_meta($post->ID,"seo_title",true);
   if($seo_title ){
     $title = "$seo_title $sep ".get_bloginfo( 'name' );
   }
 }elseif(is_category()){
   $cat_ID = get_query_var('cat');
   $seo_title = get_term_meta($cat_ID,'seo-title',true);
   if($seo_title ){
     $title = "$seo_title $sep ".get_bloginfo( 'name' );
   }
 }elseif(is_tag()){
  $tag_slug = get_query_var('tag');
  $tag = get_term_by('slug',$tag_slug,'post_tag');
  $seo_title = get_term_meta($tag->term_id,'seo-title',true);
  if($seo_title ){
    $title = "$seo_title $sep ".get_bloginfo( 'name' );
  }
}

if ( $paged >= 2 || $page >= 2 )
 $title = "$title $sep " . sprintf( __( '第%s页', 'mobantu' ), max( $paged, $page ) );
$title = str_replace('&#8211;', '-', $title);
if(_MBT('delimiter_space')){
  $title = str_replace(' '._MBT('delimiter','-').' ', _MBT('delimiter','-'), $title);
}
return $title;
}
add_filter( 'wp_title', 'MBThemes_wp_title', 10, 2 );

function MBThemes_keywords() {
  global $s, $post;
  $keywords = '';
  if ( is_single() ) {
    $seo_keyword = get_post_meta($post->ID,"seo_keyword",true);
    if($seo_keyword){
      $keywords = $seo_keyword;
    }else{
     if ( get_the_tags( $post->ID ) ) {
       foreach ( get_the_tags( $post->ID ) as $tag ) $keywords .= $tag->name . ',';
     }
     foreach ( get_the_category( $post->ID ) as $category ) $keywords .= $category->cat_name . ',';
     $keywords = substr_replace( $keywords , '' , -1);
   }
 } elseif ( is_home () || is_front_page())    { $keywords = _MBT('keywords');
} elseif ( is_tag() )      { 
  $tag_slug = get_query_var('tag');
  $tag = get_term_by('slug',$tag_slug,'post_tag');
  $seo_keyword = get_term_meta($tag->term_id,'seo-keyword',true);
  $keywords = single_tag_title('', false);
  if($seo_keyword){
    $keywords = $seo_keyword;
  }
} elseif ( is_category() ) {
  $cat_ID = get_query_var('cat');
  $seo_keyword = get_term_meta($cat_ID,'seo-keyword',true);
  $keywords = single_cat_title('', false);
  if($seo_keyword){
    $keywords = $seo_keyword;
  }
} elseif ( is_search() )   { $keywords = esc_html( $s, 1 );
} else { $keywords = trim( wp_title(',', false, 'right') );
}
if ( $keywords ) {
  echo "<meta name=\"keywords\" content=\"$keywords\">\n";
}
}

function MBThemes_description() {
  global $s, $post;
  $description = '';
  $blog_name = get_bloginfo('name');
  if ( is_single() ) {
    $seo_desc = get_post_meta($post->ID,"seo_description",true);
    if($seo_desc){
      $description = $seo_desc;
    }else{
     if( !empty( $post->post_excerpt ) ) {
       $text = $post->post_excerpt;
     } else {
       $text = MBThemes_get_excerpt();
     }
     $description = trim( str_replace( array( "\r\n", "\r", "\n", "　", " "), " ", str_replace( "\"", "'", strip_tags( $text ) ) ) );
   }
   if ( !$description ) $description = get_the_title();
 } elseif ( is_home () || is_front_page())    { $description = _MBT('description');
} elseif ( is_tag() )      { 
  $tag_slug = get_query_var('tag');
  $tag = get_term_by('slug',$tag_slug,'post_tag');
  $seo_description = get_term_meta($tag->term_id,'seo-description',true);
  $description = trim(strip_tags(tag_description()));
  if($seo_description){
    $description = $seo_description;
  }
} elseif ( is_category() ) {
  $cat_ID = get_query_var('cat');
  $seo_description = get_term_meta($cat_ID,'seo-description',true);
  $description = trim(strip_tags(category_description()));
  if($seo_description){
    $description = $seo_description;
  }
} elseif ( is_archive() )  { $description = $blog_name . "上关于" . trim( wp_title('', false) )."的内容";
} elseif ( is_search() )   { $description = $blog_name . "上关于" . esc_html( $s, 1 ) . "的搜索結果";
} else { $description = trim( wp_title('', false) );
}
$description = mb_substr( $description, 0, 220, 'utf-8' );
$description = strip_tags(do_shortcode($description));
echo "<meta name=\"description\" content=\"$description\">\n";
}

function MBThemes_comments_list($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;
  global $wpdb, $post;

  echo '<li '; comment_class(); echo ' id="comment-'.get_comment_ID().'">';

  echo '<div class="comt-avatar">';
  MBThemes_avatar($comment->user_id);
  echo '</div>';


  echo '<div class="comt-main" id="div-comment-'.get_comment_ID().'">';
  echo convert_smilies(get_comment_text());

  echo '<div class="comt-meta">';
  if ($comment->comment_approved == '0'){
    echo '<span class="comt-approved">待审核</span>';
  }

  echo '<span class="comt-author">';
  if($comment->user_id){
    echo get_user_by('ID',$comment->user_id)->nickname;
  }else{
    echo get_comment_author_link(); 
  }
  if(MBThemes_check_vip($comment->user_id)) echo '<span class="is-vip" title="VIP用户"><i class="icon icon-crown-s"></i></span>';
  if(MBThemes_post_bought($comment->user_id,$post->ID) && _MBT('post_comment_bought')) echo '<span class="comt-bought">已购买</span>';
  echo '</span>';
  $_commenttime = strtotime($comment->comment_date); 
  echo MBThemes_timeago(date('Y-m-d G:i:s', $_commenttime));
  if ($comment->comment_approved !== '0'){
    $replyText = get_comment_reply_link( array_merge( $args, array('add_below' => 'div-comment', 'reply_text' => '<i class="dripicons dripicons-reply"></i> 回复', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
    echo preg_replace('# href=[\s\S]*? onclick=#', ' href="javascript:;" onclick=', $replyText );
  }
  echo '</div>';
  echo '</div>';
}

add_filter('get_avatar', 'MBT_get_avatar', 10, 3);
function MBT_get_avatar($avatar, $id_or_email, $size = 50){
  $default_avatar = get_bloginfo('template_url').'/static/img/avatar.png';
  if(is_object($id_or_email)) {
    if($id_or_email->user_id != 0) {
      $email = $id_or_email->user_id;
      $user = get_user_by('email',$email);
      $user_avatar = get_user_meta($id_or_email->user_id, 'photo', true);
      if($user_avatar){
        $user_avatar = str_replace('http://', '//', $user_avatar);
      }
      if($user_avatar)
        return '<img src="'.$user_avatar.'" class="avatar avatar-'.$size.' photo" width="'.$size.'" height="'.$size.'" />';
      else
        return '<img src="'.$default_avatar.'" class="avatar avatar-'.$size.' photo" width="'.$size.'" height="'.$size.'" />';
      
    }elseif(!empty($id_or_email->comment_author_email)) {
      return '<img src="'.$default_avatar.'" class="avatar avatar-'.$size.' photo" width="'.$size.'" height="'.$size.'" />';
    }
  }else{
    if(is_numeric($id_or_email) && $id_or_email > 0){
      $user = get_user_by('id',$id_or_email);
      $user_avatar = get_user_meta($id_or_email, 'photo', true);
      if($user_avatar){
        $user_avatar = str_replace('http://', '//', $user_avatar);
      }
      if($user_avatar)
        return '<img src="'.$user_avatar.'" class="avatar avatar-'.$size.' photo" width="'.$size.'" height="'.$size.'" />';
      else
        return '<img src="'.$default_avatar.'" class="avatar avatar-'.$size.' photo" width="'.$size.'" height="'.$size.'" />';
    }elseif(is_email($id_or_email)){
      $user = get_user_by('email',$id_or_email);
      $user_avatar = get_user_meta($user->ID, 'photo', true);
      if($user_avatar){
        $user_avatar = str_replace('http://', '//', $user_avatar);
      }
      if($user_avatar)
        return '<img src="'.$user_avatar.'" class="avatar avatar-'.$size.' photo" width="'.$size.'" height="'.$size.'" />';
      else
        return '<img src="'.$default_avatar.'" class="avatar avatar-'.$size.' photo" width="'.$size.'" height="'.$size.'" />';
    }else{
      return '<img src="'.$default_avatar.'" class="avatar avatar-'.$size.' photo" width="'.$size.'" height="'.$size.'" />';
    }
  }
  return $avatar;
}

function MBThemes_get_avatar($uid){
  $photo = get_user_meta($uid, 'photo', true);
  if($photo){ 
    return $photo;
  }
  else return get_bloginfo("template_url").'/static/img/avatar.png';
}

function MBThemes_avatar($id=0,$size='50',$class=''){
  $photo = get_user_meta($id, 'photo', true);
  if($photo){ 
    $photo = str_replace('http://', '//', $photo);
    echo '<img class="avatar '.$class.'" src="'.$photo.'" width="'.$size.'" height="'.$size.'" />';
  }
  else echo get_avatar($id,$size);
}

function MBThemes_get_excerpt($limit = 120, $after = "..."){
  global $post;
  if(has_excerpt($post->ID)) {
    return get_the_excerpt();
  }else {
    if(function_exists('mb_strimwidth')){
      return mb_strimwidth( MBThemes_strip_tags( do_shortcode($post->post_content) ), 0, $limit, $after);
    }else{
      return get_the_excerpt();
    }
  }
}

function MBThemes_get_zans($pid){
  $result = get_post_meta($pid,"zan",true);
  return $result ? $result : "0";
}

function MBThemes_check_collect($pid){
  global $wpdb;
  if(is_user_logged_in()){
    global $current_user;
    $result = $wpdb->get_var("select count(*) from ".$wpdb->prefix ."collects where user_id = ".$current_user->ID." and post_id = $pid");
    return $result;
  }else{
    return false;
  }
}

function MBThemes_get_collects($pid){
  global $wpdb;
  $result = $wpdb->get_var("select count(*) from ".$wpdb->prefix ."collects where post_id = $pid");
  return $result;

}

function MBThemes_page($template) {
  global $wpdb;
  $page_id = $wpdb->get_var($wpdb->prepare("SELECT `post_id` 
    FROM `$wpdb->postmeta`, `$wpdb->posts`
    WHERE `post_id` = `ID`
    AND `post_status` = 'publish'
    AND `meta_key` = '_wp_page_template'
    AND `meta_value` = %s
    LIMIT 1;", $template));
  return $page_id;
}

function MBThemes_selfURL(){  
  //global $wp;
  //return home_url( $wp->request );
  $pageURL = 'http';
  $pageURL .= (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")?"s":"";
  $pageURL .= "://";
  $pageURL .= $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
  return $pageURL;   
}

function MBThemes_color(){
  $theme_color_custom = _MBT('theme_color_custom');
  $theme_color = _MBT('theme_color');
  $color = '';
  if($theme_color && $theme_color != '#ff5f33'){
   $color = $theme_color;
 }
 if($theme_color_custom && $theme_color_custom != '#ff5f33'){
   $color = $theme_color_custom;
 }
 if($color){
   echo ".btn, .cat-nav li.current-menu-item a:after, .pagination ul > .active > a,.pagination ul > .active > span, .pagination-trigger a, .erphpdown-box .down, .widget-erphpdown .down, .comt-submit, .btn-primary, .mocat .more a, .mocat h2:after, .filter a.active, .mocat h2 i,.mocat h2:after,.pagemenu li.current_page_item a, .banner .search-form .search-btn, .comt-submit, .banner-archive,.home-blogs h2 span:after,.home-authors h2 span:after,.vip-content h2 span:after,.vip-why h2 span:after,.pagination ul > .active > a,.pagination ul > .active > span,.charge .charge-header h1 span,.widget-erphpdown .price i, .erphpdown .erphpdown-buy, .erphpdown .erphpdown-down, .erphpdown .erphp-login-must, .erphpdown-box .erphpdown-down,.erphpdown-box .erphpdown-buy, .article-content .erphpdown-content-vip .erphpdown-buy, .article-content .erphpdown-content-vip .erphp-login-must, .home-blogs .more a, .tagslist li .name:hover, .tagslist li:hover .name, .vip-why .items .item span,.widget_search input[type='submit'], .tougao-item .tougao-btn,.layui-layer-btn .layui-layer-btn0, #charge-form .item .prices label.active:after, .widget-bottom-search button, .mocats .moli ul li:first-child > i,.mocats .moli ul li:nth-child(2) > i,.mocats .moli ul li:nth-child(3) > i, .mocat .cfilter li a.active:after, .mocat .child li a.active:after, .mocats .moli .moli-header, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .erphpdown-tuan-process .line span, .nav-main> li.current-menu-item > a:after, .nav-main> li.current-menu-parent > a:after, .nav-main> li.current_page_item > a:after, .nav-main> li.current-post-ancestor > a:after, .nav-main> li.current-menu-ancestor > a:after, .nav-main> li.current-category-ancestor > a:after, .grids .audio .down{background-color:$color !important;}
   a:hover, body.home .header:not(.scrolled) .nav-main > li > a:hover, body.home .header:not(.scrolled) .nav-right > li > a:hover, .nav-main > li > a:hover, .nav-right a:hover, .nav-main .sub-menu a:hover, .nav-right .sub-menu a:hover, .banner a:hover, .cat-nav li.current-menu-item a, .grids .grid h3 a:hover, .widget-tags .items a:hover, .sign-trans a, .form-policy a, .widget-erphpdown .custom-metas .meta a, .charge .charge-header h1, .charge .charge-header .item .color,.widget-erphpdown .price span, .erphpdown-box .price span, .erphpdown .erphpdown-price, .article-content .erphpdown-content-vip .erphpdown-price, .comments-title small,.archives h3,.readers a:hover,.usermenu li.active i, .rollbar a.fullscreen.active, .mocat .cfilter li a.active, .mocat .child li a.active, .article-content .gallery-login a, .erphpdown .erphpdown-title, .sidebar .widget h3 .icon, .single-related .related-title .icon, .comments-title .icon, .article-content .erphpdown-content-vip > legend, .erphpdown > legend, .erphpdown-box > legend, .article-content .erphpdown-content-vip2, .nav-right .nav-cart span, .tougao-title .icon{color:$color;}
   .article-act .article-zan.active, .article-act .article-shang{color:$color !important;}
   .erphpdown-box, .erphpdown, .article-content .erphpdown-content-vip, .comt-submit, .btn-primary,.grids .grid .cat:after,.lists .list .cat:after,.mocat .lists .grid .cat:after,.layui-layer-btn .layui-layer-btn0, #charge-form .item .prices label.active, .article-content h3{border-color:$color !important;}";
 }
 do_action("modown_color");
}

function MBThemes_ad($pos,$mt='',$mb=''){
  if(_MBT($pos.'_s')){
    $style= 'style="';
    if($mt != ''){
      $style .= 'margin-top:'.$mt.'px; ';
    }
    if($mb != ''){
      $style .= 'margin-bottom:'.$mb.'px; ';
    }
    $style .='"';
    if($style == 'style=""') $style='';

    $class='';
    if($pos == 'ad_banner_footer'){
      $class = " modown-ad-items";
      if(_MBT('list_column') == 'five-mini'){
        $class = " modown-ad-items-five";
      }
    }

    echo '<div class="modown-ad'.$class.'" '.$style.'>'._MBT($pos).'</div>';
  }
}

function MBThemes_breadcrumbs() {
  /* === OPTIONS === */
  $text['home'] = '首页'; 
  $text['category'] = '%s'; 
  $text['search'] = '搜索结果：%s'; 
  $text['tag'] = '标签：%s'; 
  $text['author'] = '作者：%s'; 
  $text['404'] = '404'; 
  $text['page'] = '%s'; 
  $text['cpage'] = '%s'; 
  $wrap_before = '<div class="breadcrumbs">当前位置：'; 
  $wrap_after = '</div>'; 
  $sep = '<i class="dripicons dripicons-chevron-right"></i>'; 
  $sep_before = '<span class="sep">'; 
  $sep_after = '</span>'; 
  $show_home_link = 1; 
  $show_on_home = 0; 
  $show_current = 1;
  $before = '<span class="current">'; 
  $after = '</span>';
  /* === END OF OPTIONS === */
  global $post;
  $home_link = home_url('/');
  $link_before = '<span>';
  $link_after = '</span>';
  $link_attr = ' itemprop="url"';
  $link_in_before = '<span itemprop="title">';
  $link_in_after = '</span>';
  $link = $link_before . '<a href="%1$s"' . $link_attr . '>' . $link_in_before . '%2$s' . $link_in_after . '</a>' . $link_after;
  $frontpage_id = get_option('page_on_front');
  $parent_id = $post->post_parent;
  $sep = ' ' . $sep_before . $sep . $sep_after . ' ';
  if (is_home() || is_front_page()) {
   if ($show_on_home) echo $wrap_before . '<a href="' . $home_link . '">' . $text['home'] . '</a>' . $wrap_after;
 } else {
   echo $wrap_before;
   if ($show_home_link) echo sprintf($link, $home_link, $text['home']);
   if ( is_category() ) {
     $cat = get_category(get_query_var('cat'), false);
     if ($cat->parent != 0) {
       $cats = get_category_parents($cat->parent, TRUE, $sep);
       $cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
       $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
       if ($show_home_link) echo $sep;
       echo $cats;
     }
     if ( get_query_var('paged') ) {
       $cat = $cat->cat_ID;
       echo $sep . sprintf($link, get_category_link($cat), get_cat_name($cat)) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
     } else {
       if ($show_current) echo $sep . $before . sprintf($text['category'], single_cat_title('', false)) . $after;
     }
   } elseif ( is_search() ) {
     if (have_posts()) {
       if ($show_home_link && $show_current) echo $sep;
       if ($show_current) echo $before . sprintf($text['search'], get_search_query()) . $after;
     } else {
       if ($show_home_link) echo $sep;
       echo $before . sprintf($text['search'], get_search_query()) . $after;
     }
   } elseif ( is_day() ) {
     if ($show_home_link) echo $sep;
     echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $sep;
     echo sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F'));
     if ($show_current) echo $sep . $before . get_the_time('d') . $after;
   } elseif ( is_month() ) {
     if ($show_home_link) echo $sep;
     echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y'));
     if ($show_current) echo $sep . $before . get_the_time('F') . $after;
   } elseif ( is_year() ) {
     if ($show_home_link && $show_current) echo $sep;
     if ($show_current) echo $before . get_the_time('Y') . $after;
   } elseif ( is_single() && !is_attachment() ) {
     if ($show_home_link) echo $sep;
     if ( get_post_type() != 'post' ) {
       $post_type = get_post_type_object(get_post_type());
       $slug = $post_type->rewrite;
       printf($link, get_post_type_archive_link($slug['slug']) . '/', $post_type->labels->name);
       if ($show_current) echo $sep . $before . get_the_title() . $after;
     } else {
       $cat = get_the_category(); $cat = $cat[0];
       $cats = get_category_parents($cat, TRUE, $sep);
       if (!$show_current || get_query_var('cpage')) $cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
       $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
       echo $cats;
       if ( get_query_var('cpage') ) {
         echo $sep . sprintf($link, get_permalink(), '正文') . $sep . $before . sprintf($text['cpage'], get_query_var('cpage')) . $after;
       } else {
         if ($show_current) echo $before . '正文' . $after;
       }
     }
// custom post type
   } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
     $post_type = get_post_type_object(get_post_type());
     if ( get_query_var('paged') ) {
       echo $sep . sprintf($link, get_post_type_archive_link($post_type->name), $post_type->label) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
     } else {
       if ($show_current) echo $sep . $before . $post_type->label . $after;
     }
   } elseif ( is_attachment() ) {
     if ($show_home_link) echo $sep;
     $parent = get_post($parent_id);
     $cat = get_the_category($parent->ID); $cat = $cat[0];
     if ($cat) {
       $cats = get_category_parents($cat, TRUE, $sep);
       $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
       echo $cats;
     }
     printf($link, get_permalink($parent), $parent->post_title);
     if ($show_current) echo $sep . $before . get_the_title() . $after;
   } elseif ( is_page() && !$parent_id ) {
     if ($show_current) echo $sep . $before . get_the_title() . $after;
   } elseif ( is_page() && $parent_id ) {
     if ($show_home_link) echo $sep;
     if ($parent_id != $frontpage_id) {
       $breadcrumbs = array();
       while ($parent_id) {
         $page = get_page($parent_id);
         if ($parent_id != $frontpage_id) {
           $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
         }
         $parent_id = $page->post_parent;
       }
       $breadcrumbs = array_reverse($breadcrumbs);
       for ($i = 0; $i < count($breadcrumbs); $i++) {
         echo $breadcrumbs[$i];
         if ($i != count($breadcrumbs)-1) echo $sep;
       }
     }
     if ($show_current) echo $sep . $before . get_the_title() . $after;
   } elseif ( is_tag() ) {
     if ( get_query_var('paged') ) {
       $tag_id = get_queried_object_id();
       $tag = get_tag($tag_id);
       echo $sep . sprintf($link, get_tag_link($tag_id), $tag->name) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
     } else {
       if ($show_current) echo $sep . $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
     }
   } elseif ( is_author() ) {
     global $author;
     $author = get_userdata($author);
     if ( get_query_var('paged') ) {
       if ($show_home_link) echo $sep;
       echo sprintf($link, get_author_posts_url($author->ID), $author->display_name) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
     } else {
       if ($show_home_link && $show_current) echo $sep;
       if ($show_current) echo $before . sprintf($text['author'], $author->display_name) . $after;
     }
   } elseif ( is_404() ) {
     if ($show_home_link && $show_current) echo $sep;
     if ($show_current) echo $before . $text['404'] . $after;
   } elseif ( has_post_format() && !is_singular() ) {
     if ($show_home_link) echo $sep;
     echo get_post_format_string( get_post_format() );
   }
   echo $wrap_after;
 }
}

function MBThemes_related_tags($cat_ID){
  $args = array('cat' => $cat_ID,'posts_per_page'=>'-1');
  query_posts($args);
  if (have_posts()){
    $all_tags_arr=array(); 
    while (have_posts()){
      the_post();
      $posttags = get_the_tags();
      if ($posttags) {
        foreach($posttags as $tag) {
          $all_tags_arr[] = array('id'=>$tag->term_id, 'num'=>1);
        }
      }
    }
    wp_reset_query();
  }
  
  if($all_tags_arr){
    $tags = array();
    foreach($all_tags_arr as $item){
      if(! isset($tags[$item['id']])){
        $tags[$item['id']] = $item;
      }else {
        $tags[$item['id']]['num'] += $item['num'];
      }
    }
    $nums = array_column($tags,'num');
    array_multisort($nums,SORT_DESC,$tags);
    $i = 0;$is = _MBT('filter_tags_auto_count',20);
    $all_tags = '';
    foreach ($tags as $tag) {
      if($i == $is){ 
        break;
      }
      $all_tags .= $tag['id'].',';
      $i++;
    }
    return rtrim($all_tags,',');
  }else{
    return '';
  }
}

if(_MBT('file_rename')){
  add_filter('wp_handle_upload_prefilter', 'MBThemes_wp_handle_upload_prefilter'); 
  function MBThemes_wp_handle_upload_prefilter($file){  
    $time=date("dHis");  
    $file['name'] = $time."".mt_rand(100,999).".".pathinfo($file['name'] , PATHINFO_EXTENSION);  
    return $file;  
  } 
}

if(_MBT('thumbnail')){
  add_filter( 'intermediate_image_sizes_advanced', 'MBThemes_remove_image_sizes' );
  function MBThemes_remove_image_sizes( $sizes ) {
    $list_thumbnails = get_intermediate_image_sizes();
    foreach ( $list_thumbnails as $value ) {
      unset( $sizes[ $value ] );
    }
    return $sizes;
  }
}


add_action('init', 'MBThemes_allow_contributor_uploads');
function MBThemes_allow_contributor_uploads() {
  $user = wp_get_current_user();
  if( isset($user->roles) && $user->roles && ($user->roles[0] == 'contributor' || $user->roles[0] == 'subscriber')){
    $allow = _MBT('tougao_upload');
    $can_upload = isset($user->allcaps['upload_files']) ? $user->allcaps['upload_files'] : 0;

    if ( $allow && !$can_upload ) {
      $contributor = get_role('contributor');
      $contributor->add_cap('upload_files');

      $subscriber = get_role('subscriber');
      $subscriber->add_cap('upload_files');
    } else if(!$allow && $can_upload){
      $contributor = get_role('contributor');
      $contributor->remove_cap('upload_files');

      $subscriber = get_role('subscriber');
      $subscriber->remove_cap('upload_files');
    }
  }
}


function post_editor_settings($args = array()){
  $img = current_user_can('upload_files');
  return array(
    'textarea_name' => $args['textarea_name'],
    'media_buttons' => false,
    'quicktags' => false,
    'tinymce'       => array(
      'height'        => 350,
      'toolbar1' => 'formatselect,bold,underline,blockquote,forecolor,alignleft,aligncenter,alignright,link,unlink,bullist,numlist,'.($img?'mbtimg,':'image,').'undo,redo,fullscreen,wp_help',
      'toolbar2' => '',
      'toolbar3' => '',
    )
  );
}

add_filter( 'mce_external_plugins', 'MBThemes_mce_plugin');
function MBThemes_mce_plugin($plugin_array){
  global $is_submit_page;
  if ( $is_submit_page ) {
    wp_enqueue_media();
    $plugin_array['mbtimg'] = admin_url('admin-ajax.php?action=mbtimg');
  }
  return $plugin_array;
}

add_action('wp_ajax_mbtimg', 'MBThemes_mce_img');
function MBThemes_mce_img(){
  header("Content-type: text/javascript");
  echo '(function($) {
    tinymce.create("tinymce.plugins.mbtimg", {
      init : function(ed, url) {
        ed.addButton("mbtimg", {
          icon: "image",
          tooltip : "添加图片",
          onclick: function(){
            var uploader;
            if (uploader) {
              uploader.open();
            }else{
              uploader = wp.media.frames.file_frame = wp.media({
                title: "选择图片",
                button: {
                  text: "插入图片"
                  },
                  library : {
                    type : "image"
                    },
                    multiple: true
                    });
                    uploader.on("select", function() {
                      var attachments = uploader.state().get("selection").toJSON();
                      var img = "";
                      for(var i=0;i<attachments.length;i++){
                        img += "<img src=\""+attachments[i].url+"\" width=\""+attachments[i].width+"\" height=\""+attachments[i].height+"\" alt=\""+(attachments[i].alt?attachments[i].alt:attachments[i].title)+"\">";
                      }
                      tinymce.activeEditor.execCommand("mceInsertContent", false, img)
                      });
                      uploader.open();
                    }
                  }
                  });
                }
                });
      // Register plugin
                tinymce.PluginManager.add("mbtimg", tinymce.plugins.mbtimg);
              })(jQuery);';
              exit;
}

add_action('pre_get_posts','MBThemes_restrict_media_library');
function MBThemes_restrict_media_library( $wp_query_obj ) {
  global $current_user, $pagenow;
  if( ! $current_user instanceof WP_User )
    return;
  if( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' )
    return;
  if( !current_user_can('edit_others_posts') )
    $wp_query_obj->set('author', $current_user->ID );
  return;
}

function MBThemes_check_checkin($uid){
  date_default_timezone_set('Asia/Shanghai');
  global $wpdb;
  $result = $wpdb->get_var("select count(ID) from ".$wpdb->prefix . "checkins where TO_DAYS(create_time) = TO_DAYS(NOW()) and user_id=".$uid);
  if($result){
    return 1;
  }
  return 0;
}

function MBThemes_sanitize_user ($username, $raw_username, $strict) {
  $username = wp_strip_all_tags( $raw_username );
  $username = remove_accents( $username );
  $username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
  $username = preg_replace( '/&.+?;/', '', $username );
  if ($strict) {
    $username = preg_replace ('|[^a-z\p{Han}0-9 _.\-@]|iu', '', $username);
  }
  $username = trim( $username );
  $username = preg_replace( '|\s+|', ' ', $username );
  return $username;
}
add_filter ('sanitize_user', 'MBThemes_sanitize_user', 10, 3);

function MBThemes_current_user_role($user_id){
  $nick = get_user_meta($user_id,'nick',true);
  if($nick) return $nick;
  else{
    if(user_can($user_id,'install_plugins')){return '管理人员';}   
    elseif(user_can($user_id,'edit_others_posts')){return '编辑';}
    elseif(user_can($user_id,'publish_posts')){return '作者';}
    elseif(user_can($user_id,'delete_posts')){return '投稿者';}
    elseif(user_can($user_id,'read')){return '投稿者';}  
    else{return '火星人';} 
  }
}

function MBThemes_yestoday_post_count(){
  global $wpdb;
  $args = array(
    'date_query' => array(
      array(
        'year'  => date("Y",strtotime("-1 day")),
        'month' => date("m",strtotime("-1 day")),
        'day'   => date("d",strtotime("-1 day")),
      ),
    ),
  );
  $query = new WP_Query( $args );
  return $query->found_posts;
}

function MBThemes_yestoday_user_count(){
  global $wpdb;
  $result = $wpdb->get_var("select count(ID) from $wpdb->users where TO_DAYS(NOW())- TO_DAYS(user_registered) = 1 ");
  return $result;
}

function MBThemes_count_vip_posts(){
  $args['meta_query'] = array(array('key' => 'member_down', 'value' => array(4,3,8,9,6,7), 'compare' => 'IN'));
  $args['posts_per_page'] = '-1';
  $results = query_posts($args);
  $count = count($results);
  wp_reset_query(); 
  return $count;
}

function MBThemes_count_vip_users(){
  global $wpdb;
  $count  = $wpdb->get_var("select count(ice_id) from  ".$wpdb->iceinfo." where userType > 0");
  return $count;
}

function MBThemes_image_to_base64( $image ){
  $upload_info = wp_upload_dir();
  $upload_url = $upload_info['baseurl'];
  if ( false === strpos( $image, $upload_url ) ) {
    $http_options = array(
      'httpversion' => '1.0',
      'timeout' => 20,
      'redirection' => 20,
      'sslverify' => FALSE,
      'user-agent' => 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; MALC)'
    );

    $get = wp_remote_get($image, $http_options);
    if (!is_wp_error($get) && 200 === $get ['response'] ['code']) {
      $img_base64 = 'data:image/' . $get['headers']['content-type'] . ';base64,' . base64_encode($get ['body']);
      return $img_base64;
    }
  }else{
    return $image;
  }
}

function MBThemes_num2tring($num) {
  if ($num >= 100000) {
    $num = '10w+';
  } elseif ($num >= 10000) {
        $num = round($num / 10000 * 100) / 100 .'w';
    } elseif($num >= 1000) {
        $num = round($num / 1000 * 100) / 100 . 'k';
    } else {
        $num = $num;
    }
    return $num;
}

add_action( 'template_redirect', 'MBThemes_force_email' );
function MBThemes_force_email() {
  if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    return;
  }
  if( is_user_logged_in() ) {
    global $current_user;
    if(_MBT('user_force_email') && !$current_user->user_email){
      $schema = isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https://' : 'http://';
      $url = $schema . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      if ( preg_replace( '/\?.*/', '', $url ) !== preg_replace( '/\?.*/', '', get_permalink(MBThemes_page("template/user.php")) ) ) {
        $redirect_url = apply_filters( 'MBThemes_force_email', $url );
        nocache_headers();
        wp_safe_redirect(add_query_arg('action','info',get_permalink(MBThemes_page("template/user.php"))), 302);
        exit;
      }
    }
  }
}

function MBThemes_is_phone($mobile) {
  if (!is_numeric($mobile)) {
    return false;
  }
  return preg_match("/^1[3456789]{1}\d{9}$/", $mobile) ? true : false;
}

function MBThemes_check_reply(){
  global $post, $wpdb;
  $reply_see = 1;$email = '';
  $down_reply=get_post_meta($post->ID, 'down_reply', true);
  if($down_reply){
    $reply_see = 0;
    $user_ID = (int) wp_get_current_user()->ID;   
    if ($user_ID > 0) {   
      $email = get_userdata($user_ID)->user_email;   
      $admin_email = get_option('admin_email');  
      if ($email == $admin_email) {   
        $reply_see = 1;
      }   
    } else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {   
      $email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);   
    } else {   
      $reply_see = 0;
    } 

    if (empty($email)) {   
      $reply_see = 0;
    } 

    if(!$reply_see){
      $post_id = $post->ID;   
      $query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";   
      if ($wpdb->get_results($query)) {   
          $reply_see = 1;
      } else {
        if($user_ID){
          $query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `user_id`='{$user_ID}' LIMIT 1";   
          if ($wpdb->get_results($query)) {   
            $reply_see = 1;
          }else{
            $reply_see = 0;
          }
        }else{
          $reply_see = 0;
        } 
      } 
    }

  }
  return $reply_see;
}



