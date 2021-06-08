<?php 
/**
 * remove actions from wp_head
 */
remove_action( 'wp_head',   'feed_links_extra', 3 ); 
remove_action( 'wp_head',   'rsd_link' ); 
remove_action( 'wp_head',   'wlwmanifest_link' ); 
remove_action( 'wp_head',   'index_rel_link' ); 
remove_action( 'wp_head',   'start_post_rel_link', 10, 0 ); 
remove_action( 'wp_head',   'wp_generator' ); 

if(_MBT('wp_category_remove')){
  require_once THEME_DIR . '/inc/plugin/no-category-base.php';
}

/**
 * 关闭自动更新
 */
if(_MBT('wp_auto_update')){
  add_filter('automatic_updater_disabled', '__return_true');
  remove_action('init', 'wp_schedule_update_checks');
  wp_clear_scheduled_hook('wp_version_check');
  wp_clear_scheduled_hook('wp_update_plugins');
  wp_clear_scheduled_hook('wp_update_themes'); 
  wp_clear_scheduled_hook('wp_maybe_auto_update'); 
  remove_action( 'admin_init', '_maybe_update_core' ); 
  remove_action( 'load-plugins.php', 'wp_update_plugins' ); 
  remove_action( 'load-update.php', 'wp_update_plugins' );
  remove_action( 'load-update-core.php', 'wp_update_plugins' );
  remove_action( 'admin_init', '_maybe_update_plugins' );
  remove_action( 'load-themes.php', 'wp_update_themes' );
  remove_action( 'load-update.php', 'wp_update_themes' );
  remove_action( 'load-update-core.php', 'wp_update_themes' );
  remove_action( 'admin_init', '_maybe_update_themes' );
}

/**
 * WordPress Emoji Delete
 */
remove_action( 'admin_print_scripts','print_emoji_detection_script');
remove_action( 'admin_print_styles','print_emoji_styles');
remove_action( 'wp_head',  'print_emoji_detection_script', 7);
remove_action( 'wp_print_styles','print_emoji_styles');
remove_filter( 'the_content_feed','wp_staticize_emoji');
remove_filter( 'comment_text_rss','wp_staticize_emoji');
remove_filter( 'wp_mail','wp_staticize_emoji_for_email');

/**
 * wp-json delete
 */
if(_MBT('wp_rest_api')){
  remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
  remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
  if( version_compare(get_bloginfo('version'), '4.7', '>=') ) {
    add_filter( 'rest_authentication_errors', function( $result ) {
        return new WP_Error( 'rest_not_available', 'What do you want?', array( 'status' => 401 ) );
    });
  }else{
    add_filter('rest_enabled', '__return_false');
    add_filter('rest_jsonp_enabled', '__return_false');
  }
}

if(_MBT('redirect_login')){
  add_action("init", "MBT_login_redirect");
  function MBT_login_redirect(){
    if($GLOBALS['pagenow'] == 'wp-login.php'){
      if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'logout'){
      }else{
        wp_safe_redirect(get_permalink(MBThemes_page("template/login.php")));
        exit;
      }
    }
  }
}

if(_MBT('hide_author_from')){
  if(!is_admin()){
    add_action( 'init', 'remove_author_from' );
    function remove_author_from() {
      if(isset($_REQUEST['author'])) {
        wp_redirect(home_url());
        exit;
      }
    }
  }
}

/**
 * open-sans delete
 */
function remove_open_sans() {
    wp_deregister_style( 'open-sans' );
    wp_register_style( 'open-sans', false );
    wp_enqueue_style('open-sans', '');
}
add_action( 'init', 'remove_open_sans' );

/**
 * Disable embeds
 */
if ( !function_exists( 'disable_embeds_init' ) ) :
    function disable_embeds_init(){
        global $wp;
        $wp->public_query_vars = array_diff($wp->public_query_vars, array('embed'));
        remove_action('rest_api_init', 'wp_oembed_register_route');
        add_filter('embed_oembed_discover', '__return_false');
        remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
        add_filter('tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin');
        add_filter('rewrite_rules_array', 'disable_embeds_rewrites');
    }
    add_action('init', 'disable_embeds_init', 9999);

    function disable_embeds_tiny_mce_plugin($plugins){
        return array_diff($plugins, array('wpembed'));
    }
    function disable_embeds_rewrites($rules){
        foreach ($rules as $rule => $rewrite) {
            if (false !== strpos($rewrite, 'embed=true')) {
                unset($rules[$rule]);
            }
        }
        return $rules;
    }
    function disable_embeds_remove_rewrite_rules(){
        add_filter('rewrite_rules_array', 'disable_embeds_rewrites');
        flush_rewrite_rules();
    }
    register_activation_hook(__FILE__, 'disable_embeds_remove_rewrite_rules');

    function disable_embeds_flush_rewrite_rules(){
        remove_filter('rewrite_rules_array', 'disable_embeds_rewrites');
        flush_rewrite_rules();
    }
    register_deactivation_hook(__FILE__, 'disable_embeds_flush_rewrite_rules');
endif;

/**
 * 禁用xmlrpc
 */
if(_MBT('wp_xmlrpc')){
  add_filter('xmlrpc_enabled','__return_false');
}

/**
 * hide admin bar
 */
add_filter('show_admin_bar','hide_admin_bar');
function hide_admin_bar($flag) {
    return false;
}

add_filter('upload_mimes','add_upload_webp');
function add_upload_webp ( $existing_mimes=array() ) {
  $existing_mimes['webp']='image/webp';
  return $existing_mimes;
}

/**
 * add theme thumbnail
 */
if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
}

/**
 * 禁用图片自适应尺寸
 */
function MBThemes_disable_srcset( $sources ) {
  return false;
}
add_filter( 'wp_calculate_image_srcset', 'MBThemes_disable_srcset' );

function MBThemes_gallery_defaults( $settings ) {
    $settings['galleryDefaults']['columns'] = 4;
    $settings['galleryDefaults']['size'] = 'thumbnail';
    $settings['galleryDefaults']['link'] = 'file';
    return $settings;
}
add_filter( 'media_view_settings', 'MBThemes_gallery_defaults' );

add_filter( 'pre_option_link_manager_enabled', '__return_true' );

/**
 * get theme option         
 */
$current_theme = wp_get_theme();
function _MBT( $name, $default = false ) {
    global $current_theme;
    $option_name = 'Modown';
    $options = get_option( $option_name );
    if ( isset( $options[$name] ) ) {
        return $options[$name];
    }
    return $default;
}


add_filter('mce_buttons','MBThemes_add_next_page_button');
function MBThemes_add_next_page_button($mce_buttons) {
  $pos = array_search('wp_more',$mce_buttons,true);
  if ($pos !== false) {
    $tmp_buttons = array_slice($mce_buttons, 0, $pos+1);
    $tmp_buttons[] = 'wp_page';
    $mce_buttons = array_merge($tmp_buttons, array_slice($mce_buttons, $pos+1));
  }
  return $mce_buttons;
}

function MBThemes_del_tags($str){
  return trim(strip_tags($str));
}
add_filter('category_description', 'MBThemes_del_tags');

add_filter( 'login_headerurl', 'MBThemes_login_logo_url' );
function MBThemes_login_logo_url($url) {
  return home_url();
}

function MBThemes_login_logo_url_title() {
    return get_bloginfo("name");
}
add_filter( 'login_headertitle', 'MBThemes_login_logo_url_title' );

function MBThemes_login_logo() { 
?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo _MBT('logo_login');?>);
            background-size: cover;
            width:100px;
            height: 100px;
        }
        #login h1 a:before, .login h1 a:before {
          content:none;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'MBThemes_login_logo' );

function MBThemes_filter_smilies_src($img_src, $img, $siteurl) {
    return THEME_URI . '/static/img/smilies/' . $img;
}
add_filter('smilies_src', 'MBThemes_filter_smilies_src', 1, 10);

function smilies_reset() {
    global $wpsmiliestrans, $wp_smiliessearch, $wp_version;
    if ( !get_option( 'use_smilies' ) || $wp_version < 4.2)
        return;
    $wpsmiliestrans = array(
    ':mrgreen:' => 'mrgreen.png',
    ':exclaim:' => 'exclaim.png',
    ':neutral:' => 'neutral.png',
    ':twisted:' => 'twisted.png',
      ':arrow:' => 'arrow.png',
        ':eek:' => 'eek.png',
      ':smile:' => 'smile.png',
   ':confused:' => 'confused.png',
       ':cool:' => 'cool.png',
       ':evil:' => 'evil.png',
    ':biggrin:' => 'biggrin.png',
       ':idea:' => 'idea.png',
    ':redface:' => 'redface.png',
       ':razz:' => 'razz.png',
   ':rolleyes:' => 'rolleyes.png',
       ':wink:' => 'wink.png',
        ':cry:' => 'cry.png',
        ':lol:' => 'lol.png',
        ':mad:' => 'mad.png',
   ':drooling:' => 'drooling.png',
':persevering:' => 'persevering.png',
    );
}
smilies_reset();

function do_post($url, $data) {
  $ch = curl_init ();
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
  curl_setopt ( $ch, CURLOPT_POST, TRUE );
  curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
  curl_setopt ( $ch, CURLOPT_URL, $url );
  curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  $ret = curl_exec ( $ch );
  curl_close ( $ch );
  return $ret;
}

function get_url_contents($url) {
  $ch = curl_init ();
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt ( $ch, CURLOPT_URL, $url );
  $result = curl_exec ( $ch );
  curl_close ( $ch );
  return $result;
}

function wp_is_erphpdown_active(){
  include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
  if(!is_plugin_active( 'erphpdown/erphpdown.php' )){
    return 0;
  }else{
    return 1;
  }

}

function wp_menu_none(){
  return '<li><a href="'.admin_url('nav-menus.php').'">请到后台 外观-菜单 设置此导航</a></li>';
}


add_action( 'tgmpa_register', 'MBThemes_register_required_plugins' );
function MBThemes_register_required_plugins() {
  $plugins = array(
    array(
      'name'      => '自定义字段',
      'slug'      => 'advanced-custom-fields',
      'required'  => false,
    ),
    array(
      'name'        => 'Erphpdown',
      'slug'        => 'erphpdown',
      'is_callable' => 'erphpdod',
      'source' => '/插件请在模板兔网站购买！https://www.mobantu.com/1780.html',
      'external_url' => 'https://www.mobantu.com/1780.html',
      'required'  => true,
    ),
  );

  $config = array(
    'id'           => 'Mobantu',                
    'default_path' => '',                      
    'menu'         => 'modown-install-plugins', 
    'parent_slug'  => 'themes.php',
    'capability'   => 'edit_theme_options',
    'has_notices'  => true,
    'dismissable'  => true,
    'dismiss_msg'  => '',
    'is_automatic' => false,
    'message'      => ''
  );

  tgmpa( $plugins, $config );
}

remove_shortcode( 'video', 'wp_video_shortcode' );
add_shortcode( 'video', 'MBT_video_shortcode' );
function MBT_video_shortcode( $attr, $content = '' ) {
  global $content_width;
  $post_id = get_post() ? get_the_ID() : 0;

  static $instance = 0;
  $instance++;
  $override = apply_filters( 'wp_video_shortcode_override', '', $attr, $content, $instance );
  if ( '' !== $override ) {
  return $override;
  }

  $video = null;

  $default_types = wp_get_video_extensions();
  $defaults_atts = array(
  'src' => '',
  'poster' => '',
  'loop' => '',
  'autoplay' => '',
  'preload' => 'metadata',
  'width' => 640,
  'height' => 360,
  //'class' => 'wp-video-shortcode',
  );

  foreach ( $default_types as $type ) {
  $defaults_atts[$type] = '';
  }

  $atts = shortcode_atts( $defaults_atts, $attr, 'video' );

  if ( is_admin() ) {
  if ( $atts['width'] > $defaults_atts['width'] ) {
  $atts['height'] = round( ( $atts['height'] * $defaults_atts['width'] ) / $atts['width'] );
  $atts['width'] = $defaults_atts['width'];
  }
  } else {
  if ( ! empty( $content_width ) && $atts['width'] > $content_width ) {
  $atts['height'] = round( ( $atts['height'] * $content_width ) / $atts['width'] );
  $atts['width'] = $content_width;
  }
  }

  $is_vimeo = $is_youtube = false;
  $yt_pattern = '#^https?://(?:www\.)?(?:youtube\.com/watch|youtu\.be/)#';
  $vimeo_pattern = '#^https?://(.+\.)?vimeo\.com/.*#';

  $primary = false;
  if ( ! empty( $atts['src'] ) ) {
  $is_vimeo = ( preg_match( $vimeo_pattern, $atts['src'] ) );
  $is_youtube = ( preg_match( $yt_pattern, $atts['src'] ) );
  if ( ! $is_youtube && ! $is_vimeo ) {
  $type = wp_check_filetype( $atts['src'], wp_get_mime_types() );
  if ( ! in_array( strtolower( $type['ext'] ), $default_types ) ) {
  return sprintf( '<a class="wp-embedded-video" href="%s">%s</a>', esc_url( $atts['src'] ), esc_html( $atts['src'] ) );
  }
  }

  if ( $is_vimeo ) {
  wp_enqueue_script( 'mediaelement-vimeo' );
  }

  $primary = true;
  array_unshift( $default_types, 'src' );
  } else {
  foreach ( $default_types as $ext ) {
  if ( ! empty( $atts[ $ext ] ) ) {
  $type = wp_check_filetype( $atts[ $ext ], wp_get_mime_types() );
  if ( strtolower( $type['ext'] ) === $ext ) {
  $primary = true;
  }
  }
  }
  }

  if ( ! $primary ) {
  $videos = get_attached_media( 'video', $post_id );
  if ( empty( $videos ) ) {
  return;
  }

  $video = reset( $videos );
  $atts['src'] = wp_get_attachment_url( $video->ID );
  if ( empty( $atts['src'] ) ) {
  return;
  }

  array_unshift( $default_types, 'src' );
  }

  $library = apply_filters( 'wp_video_shortcode_library', 'mediaelement' );
  if ( 'mediaelement' === $library && did_action( 'init' ) ) {
  wp_enqueue_style( 'wp-mediaelement' );
  wp_enqueue_script( 'wp-mediaelement' );
  wp_enqueue_script( 'mediaelement-vimeo' );
  }

  if ( 'mediaelement' === $library ) {
  if ( $is_youtube ) {
  $atts['src'] = remove_query_arg( 'feature', $atts['src'] );
  $atts['src'] = set_url_scheme( $atts['src'], 'https' );
  } elseif ( $is_vimeo ) {
  $parsed_vimeo_url = wp_parse_url( $atts['src'] );
  $vimeo_src = 'https://' . $parsed_vimeo_url['host'] . $parsed_vimeo_url['path'];

  $loop = $atts['loop'] ? '1' : '0';
  $atts['src'] = add_query_arg( 'loop', $loop, $vimeo_src );
  }
  }

  $atts['class'] = apply_filters( 'wp_video_shortcode_class', $atts['class'], $atts );

  $html_atts = array(
  //'class' => $atts['class'],
  //'id' => sprintf( 'video-%d-%d', $post_id, $instance ),
  //'width' => absint( $atts['width'] ),
  //'height' => absint( $atts['height'] ),
  'poster' => esc_url( $atts['poster'] ),
  'loop' => wp_validate_boolean( $atts['loop'] ),
  'autoplay' => wp_validate_boolean( $atts['autoplay'] ),
  //'preload' => $atts['preload'],
  );

  foreach ( array( 'poster', 'loop', 'autoplay', 'preload' ) as $a ) {
  if ( empty( $html_atts[$a] ) ) {
  unset( $html_atts[$a] );
  }
  }

  $attr_strings = array();
  foreach ( $html_atts as $k => $v ) {
  $attr_strings[] = $k . '="' . esc_attr( $v ) . '"';
  }

  $html = '';
  $fileurl = '';
  foreach ( $default_types as $fallback ) {
  if ( ! empty( $atts[ $fallback ] ) ) {
  if ( empty( $fileurl ) ) {
  $fileurl = $atts[ $fallback ];
  }
  if ( 'src' === $fallback && $is_youtube ) {
  $type = array( 'type' => 'video/youtube' );
  } elseif ( 'src' === $fallback && $is_vimeo ) {
  $type = array( 'type' => 'video/vimeo' );
  } else {
  $type = wp_check_filetype( $atts[ $fallback ], wp_get_mime_types() );
  }
  $url = add_query_arg( '_', $instance, $atts[ $fallback ] );
  }
  }

  $html .= sprintf( '<video %s src="'.esc_url( $url ).'" controls="controls">', join( ' ', $attr_strings ) );

  $html .= '</video>';

  $width_rule = '';
  if ( ! empty( $atts['width'] ) ) {
  $width_rule = sprintf( 'width: %dpx;', $atts['width'] );
  }
  $output = sprintf( '<div style="%s" class="wp-video">%s</div>', $width_rule, $html );
  //$output = $html;

  return apply_filters( 'MBT_video_shortcode', $output, $atts, $video, $post_id, $library );
}


add_filter( 'post_gallery', 'MBThemes_gallery_shortcode', 10, 2 );
function MBThemes_gallery_shortcode( $output, $attr ) {
    $post = get_post();

    static $instance = 0;
    $instance++;

    if ( empty(  $attr['link'] ) ) {
        $attr['link'] = 'none'; 
    }

    if ( !empty( $attr['ids'] ) ) {
        if ( empty( $attr['orderby'] ) )
            $attr['orderby'] = 'post__in';
        $attr['include'] = $attr['ids'];
    }

    if ( isset( $attr['orderby'] ) ) {
        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
        if ( !$attr['orderby'] )
            unset( $attr['orderby'] );
    }

    extract(shortcode_atts(array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post ? $post->ID : 0,
        'itemtag'    => 'div',
        'icontag'    => 'dt',
        'captiontag' => 'dd',
        'columns'    => 3,
        'size'       => 'thumbnail',
        'include'    => '',
        'exclude'    => '',
        'preview'    => '',
        'vip'        => '',
        'hide'       => ''
    ), $attr, 'gallery'));

    $id = intval($id);
    if ( 'RAND' == $order )
        $orderby = 'none';

    if ( !empty($include) ) {
        $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( !empty($exclude) ) {
        $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    } else {
        $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    }

    if ( empty($attachments) )
        return '';

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment )
            $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
        return $output;
    }

    $itemtag = tag_escape($itemtag);
    $captiontag = tag_escape($captiontag);
    $icontag = tag_escape($icontag);
    $valid_tags = wp_kses_allowed_html( 'post' );
    if ( ! isset( $valid_tags[ $itemtag ] ) )
        $itemtag = 'dl';
    if ( ! isset( $valid_tags[ $captiontag ] ) )
        $captiontag = 'dd';
    if ( ! isset( $valid_tags[ $icontag ] ) )
        $icontag = 'dt';

    $columns = intval($columns);
    $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
    $float = is_rtl() ? 'right' : 'left';

    $selector = "gallery-{$instance}";

    $gallery_style = $gallery_div = '';

    $size_class = sanitize_html_class( $size );
    $erphp_url_front_vip = add_query_arg('action','vip',get_permalink(MBThemes_page("template/user.php")));

    if(!is_user_logged_in()){
      if($vip && wp_is_erphpdown_active()){
        if($preview != ''){
          $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> 非VIP用户仅限浏览".$preview."张，共".count($attachments)."张<a href='javascript:;' class='signin-loader'>登录</a></span></div>";
        }
      }else{
        if($preview != ''){
          $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> 游客仅限浏览".$preview."张，共".count($attachments)."张<a href='javascript:;' class='signin-loader'>登录</a></span></div>";
        }
      }
    }else{
      if(wp_is_erphpdown_active()){
        $userType=getUsreMemberType();
        if($vip && !$userType){
          if($preview != ''){
            $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> 非VIP用户仅限浏览".$preview."张，共".count($attachments)."张<a href='".$erphp_url_front_vip."' target='_blank'>升级VIP</a></span></div>";
          }
        }
      }
    }
    $gallery_div .= "<div id='$selector' class='gallery galleryid-{$id} gallery-column-{$columns} clearfix'>";
    $output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

    $i = 0;
    foreach ( $attachments as $id => $attachment ) {
      $i++;

      if(($preview != '' && $i <= $preview) || $preview == '' || (!$vip && is_user_logged_in()) || ($vip && $userType)){
        if ( ! empty( $attr['link'] ) && 'file' === $attr['link'] )
            $image_output = wp_get_attachment_link( $id, $size, false, false );
        elseif ( ! empty( $attr['link'] ) && 'none' === $attr['link'] )
            $image_output = wp_get_attachment_image( $id, $size, false );
        else
            $image_output = wp_get_attachment_link( $id, $size, true, false );
        $image_meta  = wp_get_attachment_metadata( $id );
        $orientation = '';
        if ( isset( $image_meta['height'], $image_meta['width'] ) )
            $orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
        $output .= "<{$itemtag} class='gallery-item gallery-fancy-item'>";
      }else{
        if($hide){
          break;
        }else{
          $image_output = '<span class="img">'.wp_get_attachment_image( $id, $size, false ).'</span>';
          $image_meta  = wp_get_attachment_metadata( $id );
          $orientation = '';
          if ( isset( $image_meta['height'], $image_meta['width'] ) )
              $orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
          $output .= "<{$itemtag} class='gallery-item gallery-blur-item'>";
        }
      }

      $output .= "$image_output";
      $output .= "</{$itemtag}>";
       
    }

    $output .= "</div>\n";

    return $output;
}

add_action('print_media_templates', function(){
  ?>
  <script type="text/html" id="tmpl-my-custom-gallery-setting">
    <h2 style="float: left;width: 100%">预览设置</h2>
    <span class="setting">
      <label for="gallery-settings-preview" class="name">预览数</label>
      <input type="text" id="gallery-settings-preview" name="preview" data-setting="preview" style="float: left;width: 100px" />
    </span>
    <span class="setting">
      <label for="gallery-settings-hide" class="name">隐藏模式</label>
      <select id="gallery-settings-hide" class="hide" name="hide" data-setting="hide">
        <option value="0">毛玻璃</option>
        <option value="1">完全隐藏</option>
      </select>
    </span>
    <?php if(wp_is_erphpdown_active()){?>
    <span class="setting">
      <label for="gallery-settings-vip" class="name">VIP查看全部</label>
      <select id="gallery-settings-vip" class="vip" name="vip" data-setting="vip">
        <option value="0">不启用</option>
        <option value="1">启用</option>
      </select>
    </span>
    <?php }?>
  </script>
  <script>
    jQuery(document).ready(function(){
      _.extend(wp.media.gallery.defaults, {
        preview: '',
        hide: '',
        vip: ''
      });
      wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
        template: function(view){
          return wp.media.template('gallery-settings')(view)
               + wp.media.template('my-custom-gallery-setting')(view);
        }
      });
    });
  </script>
  <?php
});

function MBT_get_comment_list_by_user($clauses) {
  if (is_admin()) {
    global $current_user, $wpdb;
    $clauses['where'] .= " AND user_id = ".$current_user->ID;
  };
  return $clauses;
}

if(!current_user_can('edit_others_posts')) {
  add_filter('comments_clauses', 'MBT_get_comment_list_by_user');
}


add_filter('body_class','mobantu_body_classes');
function mobantu_body_classes($classes) {
  date_default_timezone_set('Asia/Shanghai');
  if(isset($_COOKIE['mbt_theme_night'])){
    if($_COOKIE['mbt_theme_night'] == '1'){
      $classes[] = 'night';
    }
  }elseif(_MBT('theme_night_default')){
    $classes[] = 'night';
  }elseif(_MBT('theme_night_auto')){
    $time = intval(date("Hi"));
    if ($time < 730 || $time > 1930) {
      $classes[] = 'night';
    }
  }

  if(_MBT('nav_position') == '1'){
    $classes[] = 'nv-left';
  }

  if(_MBT('list_column') == 'five-mini'){
    $classes[] = 'gd-mini';
  }
  return $classes;
}

function mbt_send_request($body, $method='POST'){
  $url = base64_decode('aHR0cDovL2FwaS5tb2JhbnR1LmNvbS9hdXRoL21vZG93bi1uZXcucGhw');
  $result = wp_remote_request($url, array('method' => $method, 'body'=>$body));
  if(is_array($result)){
    return $result['body'];
  }
}

class MBThemes_Show_IDs {

  public function __construct() {

    add_action( 'admin_init', array( $this, 'custom_objects' ) );
    add_action( 'admin_head', array( $this, 'add_css' ) );

    // For Post Management
    add_filter( 'manage_posts_columns', array( $this, 'add_column' ) );
    add_action( 'manage_posts_custom_column', array( $this, 'add_value' ), 10, 2 );

    // For Page Management
    add_filter( 'manage_pages_columns', array( $this, 'add_column' ) );
    add_action( 'manage_pages_custom_column', array( $this, 'add_value' ), 10, 2 );

    // For Media Management
    add_filter( 'manage_media_columns', array( $this, 'add_column' ) );
    add_action( 'manage_media_custom_column', array( $this, 'add_value' ), 10, 2 );

    // For Link Management
    //add_filter( 'manage_link-manager_columns', array( $this, 'add_column' ) );
    //add_action( 'manage_link_custom_column', array( $this, 'add_value' ), 10, 2 );

    // For Category Management
    add_action( 'manage_edit-link-categories_columns', array( $this, 'add_column' ) );
    add_filter( 'manage_link_categories_custom_column', array( $this, 'add_return_value' ), 10, 3 );

    // For User Management
    add_action( 'manage_users_columns', array( $this, 'add_column' ) );
    add_filter( 'manage_users_custom_column', array( $this, 'add_return_value' ), 10, 3 );

    // For Comment Management
    //add_action( 'manage_edit-comments_columns', array( $this, 'add_column' ) );
    //add_action( 'manage_comments_custom_column', array( $this, 'add_value' ), 10, 2 );
  }

  public function custom_objects() {

    $taxonomies = get_taxonomies( array( 'public' => true ), 'names' );
    foreach ( $taxonomies as $custom_taxonomy ) {
      if ( isset( $custom_taxonomy ) ) {
        add_action( 'manage_edit-' . $custom_taxonomy . '_columns', array( $this, 'add_column' ) );
        add_filter( 'manage_' . $custom_taxonomy . '_custom_column', array( $this, 'add_return_value' ), 10, 3 );
      }
    }

    $post_types = get_post_types( array( 'public' => true ), 'names' );
    foreach ( $post_types as $post_type ) {
      if ( isset( $post_type ) ) {
        add_action( 'manage_edit-' . $post_type . '_columns', array( $this, 'add_column' ) );
        add_filter( 'manage_' . $post_type . '_custom_column', array( $this, 'add_return_value' ), 10, 3 );
      }
    }
  }

  public function add_css() {
    ?>
    <style type="text/css">
      #modown-show-ids {
        width: 50px;
      }
    </style>
    <?php
  }

  public function add_column( $cols ) {

    $cols['modown-show-ids'] = 'ID';

    return $cols;
  }

  public function add_value( $column_name, $id ) {
    if ( 'modown-show-ids' === $column_name ) {
      echo $id;
    }
  }


  public function add_return_value( $value, $column_name, $id ) {

    if ( 'modown-show-ids' === $column_name ) {
      $value = $id;
    }

    return $value;
  }
}

new MBThemes_Show_IDs;


add_filter( 'display_post_states', 'MBThemes_add_post_state', 10, 2 );
function MBThemes_add_post_state( $post_states, $post ) {
  if( get_page_template_slug($post->ID) == 'template/user.php' ) {
    $post_states[] = 'Modown User';
  }elseif( get_page_template_slug($post->ID) == 'template/vip.php' ) {
    $post_states[] = 'Modown VIP';
  }elseif( get_page_template_slug($post->ID) == 'template/login.php' ) {
    $post_states[] = 'Modown Login';
  }
  return $post_states;
}

if ( class_exists( 'WooCommerce', false ) ) {
  add_theme_support('woocommerce');

  add_action('woocommerce_before_main_content', 'MBThemes_woocommerce_wrapper_start', 10);
  add_action('woocommerce_after_main_content', 'MBThemes_woocommerce_wrapper_end', 10);

  function MBThemes_woocommerce_wrapper_start() {
      echo '<div class="container clearfix">';
  }

  function MBThemes_woocommerce_wrapper_end() {
      echo '</div>';
  }

  add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
  function custom_override_checkout_fields( $fields ) {
    unset($fields['billing']['billing_first_name']);
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    //unset($fields['billing']['billing_phone']);
    //unset($fields['order']['order_comments']);
    //unset($fields['billing']['billing_email']);
    //unset($fields['account']['account_username']);
    //unset($fields['account']['account_password']);
    //unset($fields['account']['account_password-2']);
    return $fields;
  }
}

add_action('wp_dashboard_setup', 'MBT_thread_modify_dashboard_widgets' );
function MBT_thread_modify_dashboard_widgets() {
 global $wp_meta_boxes;
if(current_user_can('manage_options')){ //只有管理员才能看到
 add_meta_box( 'pending_posts_dashboard_widget', '待审文章', 'pending_posts_dashboard_widget_function','dashboard', 'normal', 'core' );
 }
}

function pending_posts_dashboard_widget_function() {
 global $wpdb;
 $pending_posts = $wpdb->get_results("SELECT * FROM {$wpdb->posts} WHERE post_status = 'pending' and post_type='post' ORDER BY post_modified DESC");
echo '<ul>';
 foreach ($pending_posts as $pending_post){
 echo '<li><a href="'.admin_url().'post.php?post='.$pending_post->ID.'&action=edit">'.$pending_post->post_title.'</a></li>';
 }
 echo '</ul>';
}

function modown_is_mobile(){  
    $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';  
    $mobile_browser = '0';  
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
      $clientkeywords = array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini','ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung','palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser','up.link', 'blazer', 'helio', 'hosin', 'huawei', 'xiaomi', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource','alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone','iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop','benq', 'haier', '^lct', '320x320', '240x320', '176x220', 'windows phone');
      if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
        $mobile_browser++;  
      }
    }
    if(preg_match('/(up.browser|up.link|ucweb|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))  
        $mobile_browser++;  
    if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))  
        $mobile_browser++;  
    if(isset($_SERVER['HTTP_X_WAP_PROFILE']))  
        $mobile_browser++;  
    if(isset($_SERVER['HTTP_PROFILE']))  
        $mobile_browser++;  
    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));  
    $mobile_agents = array(  
        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',  
        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',  
        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',  
        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',  
        'newt','noki','oper','palm','pana','pant','phil','play','port','prox',  
        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',  
        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',  
        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',  
        'wapr','webc','winw','winw','xda','xda-' 
        );  
    if(in_array($mobile_ua, $mobile_agents))  
        $mobile_browser++;  
    if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)  
        $mobile_browser++;  
    // Pre-final check to reset everything if the user is on Windows  
    if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)  
        $mobile_browser=0;  
    // But WP7 is also Windows, with a slightly different characteristic  
    if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)  
        $mobile_browser++;  
    if($mobile_browser>0)  
        return true;  
    else
        return false;  
}

function modown_is_weixin(){ 
  if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
        return true;
    }  
    return false;
}

function mbt_sec_to_time($seconds){  
  $time = '00:00';  
  if($seconds > 0){
   if ($seconds >3600){
       $hours =intval($seconds/3600);
       $minutes = $seconds % 3600;
       $time = $hours.":".gmstrftime('%M:%S',$minutes);
   }else{
       $time = gmstrftime('%M:%S',$seconds);
   }
  }
  return $time;  
}