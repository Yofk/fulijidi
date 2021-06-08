<!DOCTYPE HTML>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
  <meta name="apple-mobile-web-app-title" content="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
  <meta http-equiv="Cache-Control" content="no-siteapp">
  <title><?php wp_title( _MBT('delimiter','-'), true, 'right' ); ?></title>
  <?php if(!_MBT('seo')){MBThemes_keywords();MBThemes_description();}?>
  <link rel="shortcut icon" href="<?php echo _MBT('favicon')?>">
  <?php wp_head();?>
  <script>window._MBT = {uri: '<?php bloginfo('template_url') ?>', url:'<?php bloginfo('url');?>',usr: '<?php echo get_permalink(MBThemes_page("template/user.php"));?>', roll: [<?php echo _MBT('sidebar_fixed');?>], admin_ajax: '<?php echo admin_url('admin-ajax.php');?>', erphpdown: '<?php if(defined("erphpdown")) echo constant("erphpdown");?>', image: '<?php $default_width = 285;if(_MBT('list_column') == 'five-mini'){$default_width = 228;} if(_MBT('timthumb_height')) echo round(_MBT('timthumb_height')/$default_width,4);else echo '0.6316';?>', hanimated: '<?php if(_MBT('header_animated')) echo '1';else echo '0';?>'}</script>
  <?php get_template_part("inc/skin");?>
</head>
<body <?php body_class(); ?>>
<header class="header">
  <div class="container clearfix">
  	<?php $logoTagName = (is_home() || is_front_page()) ? 'h1' : 'div';?>
    <?php echo '<'.$logoTagName;?> class="logo<?php if(_MBT('logo_scan')) echo ' scaning';?>"><a <?php if(_MBT('logo')) echo 'style="background-image:url('._MBT('logo').')"';?> href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></a></<?php echo $logoTagName.'>';?>
    <ul class="nav-main">
      <?php echo str_replace("</ul></div>", "", preg_replace("{<div[^>]*><ul[^>]*>}", "", wp_nav_menu(array('theme_location' => 'main', 'echo' => false, 'fallback_cb'=> 'wp_menu_none')) )); ?>
    </ul>
    <?php do_action("modown_header_nav");?>
    <ul class="nav-right">
      <?php if(_MBT('header_vip')){ if(is_user_logged_in() || !_MBT('hide_user_all')){?>
      <li class="nav-vip">
        <a href="<?php echo get_permalink(MBThemes_page("template/vip.php"));?>"><i class="icon icon-vip-s"></i></a>
      </li>
      <?php }}?>
      <?php if(_MBT('header_tougao')){
        if(is_user_logged_in() || !_MBT('hide_user_all')){
      ?>
      <li class="nav-tougao">
        <a href="<?php echo get_permalink(MBThemes_page("template/tougao.php"));?>" title="投稿"><i class="icon icon-edit"></i></a>
      </li>
      <?php }}?>
      <?php if ( class_exists( 'WooCommerce', false ) && _MBT('header_cart')) {global $woocommerce;$items = $woocommerce->cart->get_cart();$wc_count = count($items);?>
      <li class="nav-cart">
        <a href="<?php echo wc_get_cart_url();?>" title="购物车"><i class="icon icon-cart"></i><?php if($wc_count){?><span><?php echo $wc_count;?></span><?php }?></a>
      </li>
      <?php }?>
      <li class="nav-search">
        <a href="javascript:;" class="search-loader" title="搜索"><i class="icon icon-search"></i></a>
      </li>
      <?php if(!is_user_logged_in()){ if(!_MBT('hide_user_all')){?>
      <li class="nav-login no"><a href="<?php echo get_permalink(MBThemes_page("template/login.php"));?>" class="signin-loader"><i class="icon icon-user"></i><span>登录</span></a><?php if(!_MBT('register')){?><b class="nav-line"></b><a href="<?php echo get_permalink(MBThemes_page("template/login.php"));?>?action=register" class="signup-loader"><span>注册</span></a><?php }?></li>
      <?php }}else{ global $current_user;?>
      <li class="nav-login yes"><a href="<?php echo get_permalink(MBThemes_page("template/user.php"));?>" title="进入个人中心"><?php echo get_avatar($current_user->ID,36);?><i class="icon icon-user"></i><?php if(wp_is_erphpdown_active()){ if(getUsreMemberTypeById($current_user->ID)) echo '<span class="vip"></span>'; }?></a>
        <ul class="sub-menu">
          <?php if(wp_is_erphpdown_active()){ $okMoney = erphpGetUserOkMoney(); $userTypeId=getUsreMemberType();
            $userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$current_user->ID);
          ?>
          <li class="first">
            <div class="user-info">
              <a href="<?php echo get_permalink(MBThemes_page("template/user.php"));?>" title="进入个人中心"><?php echo get_avatar($current_user->ID,36);?><?php if(wp_is_erphpdown_active()){ if(getUsreMemberTypeById($current_user->ID)) echo '<span class="vip"></span>'; }?></a>
              <div class="name"><?php echo '<div class="nickname">'.$current_user->display_name.'</div>';if(wp_is_erphpdown_active()){$userTypeId=getUsreMemberType();if($userTypeId>0&&$userTypeId<10) echo '<t>'.getUsreMemberTypeEndTime().'</t>';elseif($userTypeId==10) echo '<t>永久尊享</t>';else echo '<d>普通用户</d>';}?></div>
            </div>
            <div class="user-money clearfix">
              <div class="money-left"><?php echo '余额 '.sprintf("%.2f",$okMoney);?><t>消费 <?php echo $userMoney->ice_get_money?$userMoney->ice_get_money:'0.00';?></t></div>
            </div>
            <?php if(!_MBT('vip_hidden')){
              $erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';
              ?>
            <div class="user-vip clearfix">
              <div class="vip-left">
              <?php if($userTypeId>0&&$userTypeId<=10){
                MBThemes_erphpdown_viphtml();
              }else{
                echo '普通用户<v onclick="javascript:location.href=\''.add_query_arg('action','vip',get_permalink(MBThemes_page("template/user.php"))).'\';">升级'.$erphp_vip_name.'</v>';
                if(_MBT('header_vip_desc')) echo '<div class="down-left">'._MBT('header_vip_desc').'</div>';
              }
              ?>
              </div>
            </div>
            <?php }?>
            <?php if ( class_exists( 'WooCommerce', false ) && _MBT('header_cart')) {?>
            <div class="user-cart clearfix">
              <a href="<?php echo wc_get_page_permalink( 'myaccount' );?>" class="cart-left"><i class="icon icon-cart"></i><t>我的购物</t></a>
            </div>
            <?php }?>
          </li>
          <?php }?>
          <?php if(wp_is_erphpdown_active()){?>
            <?php if(current_user_can('administrator')){?>
            <li class="item"><a href="<?php echo admin_url();?>"><i class="icon icon-setting"></i><br>后台</a></li>
            <?php }?>
            <li class="item"><a href="<?php echo get_permalink(MBThemes_page("template/user.php"));?>"><i class="icon icon-money"></i><br>充值</a></li>
            <li class="item"><a href="<?php echo add_query_arg('action','order',get_permalink(MBThemes_page("template/user.php")));?>"><i class="icon icon-order2"></i><br>购买</a></li>
            <?php if(!current_user_can('administrator')){?>
            <li class="item"><a href="<?php echo add_query_arg('action','info',get_permalink(MBThemes_page("template/user.php")));?>"><i class="icon icon-info"></i><br>资料</a></li>
            <?php }?>
          <?php }else{?>
            <?php if(_MBT('post_collect')){?>
            <li class="item"><a href="<?php echo add_query_arg('action','collect',get_permalink(MBThemes_page("template/user.php")));?>"><i class="icon icon-stars"></i><br>收藏</a></li>
            <?php }?>
            <li class="item"><a href="<?php echo add_query_arg('action','comment',get_permalink(MBThemes_page("template/user.php")));?>"><i class="icon icon-comments"></i><br>评论</a></li>
          <?php }?>
          <li class="item"><a href="<?php echo add_query_arg('action','password',get_permalink(MBThemes_page("template/user.php")));?>"><i class="icon icon-lock"></i><br>密码</a></li>
          <li class="item"><a href="<?php echo wp_logout_url(MBThemes_selfURL());?>"><i class="icon icon-signout"></i><br>退出</a></li>
        </ul>
      </li>
      <?php }?>
      <li class="nav-button"><a href="javascript:;" class="nav-loader"><i class="icon icon-menu"></i></a></li>
    </ul>
  </div>
</header>
<div class="search-wrap">
  <div class="container">
    <form action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-form" method="get">
      <input autocomplete="off" class="search-input" name="s" placeholder="输入关键字回车" type="text">
      <i class="icon icon-close"></i>
    </form>
  </div>
</div>