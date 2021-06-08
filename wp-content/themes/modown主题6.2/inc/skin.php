<style>
  <?php 
  MBThemes_color(); 
  
  $timthumb_height = _MBT('timthumb_height');
  if(is_category()){
    $cat_ID = get_query_var('cat');
    $timthumb_height_cat = get_term_meta($cat_ID,'timthumb_height',true);
    if($timthumb_height_cat){
      $timthumb_height = $timthumb_height_cat;
    }
  }

  if(_MBT('list_column') == 'five-mini'){
    if($timthumb_height && $timthumb_height != '144'){
    ?>
    .gd-mini .grids .grid .img{height: <?php echo $timthumb_height;?>px;}
    .gd-mini .widget-postlist .hasimg li{padding-left: calc(<?php echo ($timthumb_height=="228")?"66":(66*228/$timthumb_height);?>px + 10px);}
    .gd-mini .widget-postlist .hasimg li .img{width:<?php echo ($timthumb_height=="228")?"66":(66*228/$timthumb_height);?>px;}
    @media (max-width: 1230px){
      .gd-mini .grids .grid .img{height: <?php echo ($timthumb_height=="228")?"236.25":(236.25*$timthumb_height/228);?>px;}
    }
    @media (max-width: 1024px){
      .gd-mini .grids .grid .img{height: <?php echo $timthumb_height;?>px;}
    }
    @media (max-width: 925px){
      .gd-mini .grids .grid .img{height: <?php echo ($timthumb_height=="228")?"236.25":(236.25*$timthumb_height/228);?>px;}
    }
    @media (max-width: 768px){
      .gd-mini .grids .grid .img{height: <?php echo $timthumb_height;?>px;}
    }
    @media (max-width: 620px){
      .gd-mini .grids .grid .img{height: <?php echo ($timthumb_height=="228")?"236.25":(236.25*$timthumb_height/228);?>px;}
    }
    @media (max-width: 480px){
      .gd-mini .grids .grid .img{height: <?php echo ($timthumb_height=="228")?"144":(144*$timthumb_height/228);?>px;}
    }
    <?php
    }
  }else{
    if($timthumb_height && $timthumb_height != '180'){
    ?>
    .grids .grid .img{height: <?php echo $timthumb_height;?>px;}
    .widget-postlist .hasimg li{padding-left: calc(<?php echo ($timthumb_height=="285")?"66":(66*285/$timthumb_height);?>px + 10px);}
    .widget-postlist .hasimg li .img{width:<?php echo ($timthumb_height=="285")?"66":(66*285/$timthumb_height);?>px;}
    @media (max-width: 1230px){
      .grids .grid .img{height: <?php echo ($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285);?>px;}
    }
    @media (max-width: 1024px){
      .grids .grid .img{height: <?php echo $timthumb_height;?>px;}
    }
    @media (max-width: 925px){
      .grids .grid .img{height: <?php echo ($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285);?>px;}
    }
    @media (max-width: 768px){
      .grids .grid .img{height: <?php echo $timthumb_height;?>px;}
    }
    @media (max-width: 620px){
      .grids .grid .img{height: <?php echo ($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285);?>px;}
    }
    @media (max-width: 480px){
      .grids .grid .img{height: <?php echo ($timthumb_height=="285")?"180":(180*$timthumb_height/285);?>px;}
    }
    <?php
    }
  }

  if(_MBT('thumbnail_auto')){
  ?>
    @media (max-width: 620px){
      .grids .grid .img, .single-related .grids .grid .img {height: auto !important;}
    }
  <?php
  }

  if(_MBT('thumbnail_type') && _MBT('thumbnail_type') != 'cover'){
  ?>
  .grids .grid img, .lists .list img, .mocat .lists .grid img, .home-blogs ul li img{object-fit: <?php echo _MBT('thumbnail_type');?>;}
  <?php
  }

  if(_MBT('header_type') == 'dark'){
    $header_txtcolor = _MBT('header_txtcolor')?_MBT('header_txtcolor'):'#fff';
  ?>
  .header{background: #1c1f2b}
  .nav-main > li, .nav-main > li > a, .nav-right a{color:<?php echo $header_txtcolor;?>;}
  @media (max-width: 768px){
    .nav-right .nav-button a {color: <?php echo $header_txtcolor;?>;}
  }
  <?php
  }elseif(_MBT('header_type') == 'light'){
    $header_txtcolor = _MBT('header_txtcolor')?_MBT('header_txtcolor'):'#062743';
  ?>
    .nav-main > li, .nav-main > li > a, .nav-right a{color:<?php echo $header_txtcolor;?>;}
    @media (max-width: 768px){
      .nav-right .nav-button a {color: <?php echo $header_txtcolor;?>;}
    }
  <?php
  }elseif(_MBT('header_type') == 'custom'){
    $header_bgcolor = _MBT('header_bgcolor');
    $header_txtcolor = _MBT('header_txtcolor')?_MBT('header_txtcolor'):'#062743';

    $theme_color_custom = _MBT('theme_color_custom');
    $theme_color = _MBT('theme_color');
    $color = '';
    if($theme_color && $theme_color != '#ff5f33'){
     $color = $theme_color;
    }
    if($theme_color_custom && $theme_color_custom != '#ff5f33'){
     $color = $theme_color_custom;
    }
  ?>
  .header{background: <?php echo $header_bgcolor;?>}
  .nav-main > li, .nav-main > li > a, .nav-right a{color:<?php echo $header_txtcolor;?>;}
  <?php if($color == $header_bgcolor){?>
  body.home .header:not(.scrolled) .nav-main > li > a:hover, body.home .header:not(.scrolled) .nav-right > li > a:hover, .nav-main > li > a:hover, .nav-right a:hover{color:<?php echo $header_txtcolor;?>;}
  <?php }?>
  @media (max-width: 768px){
    .nav-right .nav-button a {color: <?php echo $header_txtcolor;?>;}
  }
  <?php
  }else{
    if( _MBT('banner') == '1' || _MBT('banner') == '3' || (_MBT('banner') == '2' && _MBT('slider_fullwidth')) ){
      $header_color = _MBT('header_color')?_MBT('header_color'):'#fff';
    ?>
    body.home .banner{margin-top: -70px;}
    body.home .banner-slider{padding-top: 90px;background: #fff;background-image: none;}
    <?php if(_MBT('banner') == '3'){?>
    body.home .banner-slider{padding-top: 25px;}
    <?php }?>
    body.home .banner-slider:after{content: none;}
    body.home .header{background: transparent;box-shadow: none;webkit-box-shadow:none;}
    body.home .header.scrolled{background: #fff;webkit-box-shadow: 0px 5px 10px 0px rgba(17, 58, 93, 0.1);-ms-box-shadow: 0px 5px 10px 0px rgba(17, 58, 93, 0.1);box-shadow: 0px 5px 10px 0px rgba(17, 58, 93, 0.1);}

    body.home .header:not(.scrolled) .nav-main > li, body.home .header:not(.scrolled) .nav-main > li > a, body.home .header:not(.scrolled) .nav-right > li > a{color:<?php echo $header_color;?>;}

    @media (max-width: 925px){
      body.home .banner{padding-top: 85px;}
      body.home .banner-slider{padding-top: 85px;}
      <?php if(_MBT('banner') == '3'){?>
      body.home .banner-slider{padding-top: 20px;}
      <?php }?>
    }

    @media (max-width: 768px){
      body.home .banner{margin-top: -60px;padding-top: 70px;}
      body.home .banner-slider{padding-top: 70px;}
      <?php if(_MBT('banner') == '3'){?>
      body.home .banner-slider{padding-top: 20px;}
      <?php }?>
    }
    <?php 
    }
  }

  if(_MBT('banner_dark')){
  ?>
  body.home .banner:after, .banner-archive:after, body.home .swiper-container-fullwidth .swiper-slide:after, .mocats .moli .moli-header:after{content:"";position:absolute;top:0;bottom:0;left:0;right:0;background:linear-gradient(180deg,rgba(0,0,0,.38) 0,rgba(0,0,0,.38) 3.5%,rgba(0,0,0,.379) 7%,rgba(0,0,0,.377) 10.35%,rgba(0,0,0,.375) 13.85%,rgba(0,0,0,.372) 17.35%,rgba(0,0,0,.369) 20.85%,rgba(0,0,0,.366) 24.35%,rgba(0,0,0,.364) 27.85%,rgba(0,0,0,.361) 31.35%,rgba(0,0,0,.358) 34.85%,rgba(0,0,0,.355) 38.35%,rgba(0,0,0,.353) 41.85%,rgba(0,0,0,.351) 45.35%,rgba(0,0,0,.35) 48.85%,rgba(0,0,0,.353) 52.35%,rgba(0,0,0,.36) 55.85%,rgba(0,0,0,.371) 59.35%,rgba(0,0,0,.385) 62.85%,rgba(0,0,0,.402) 66.35%,rgba(0,0,0,.42) 69.85%,rgba(0,0,0,.44) 73.35%,rgba(0,0,0,.46) 76.85%,rgba(0,0,0,.48) 80.35%,rgba(0,0,0,.498) 83.85%,rgba(0,0,0,.515) 87.35%,rgba(0,0,0,.529) 90.85%,rgba(0,0,0,.54) 94.35%,rgba(0,0,0,.547) 97.85%,rgba(0,0,0,.55));z-index:9}
  <?php
  }

  if(_MBT('banner_archive')){
  ?>
    body.archive .banner-archive, body.page-template-all .banner-archive, body.page-template-all-vip .banner-archive, body.page-template-waterfall .banner-archive{display: none;}
  <?php
  }

  if(_MBT('list_column') == 'six' && _MBT('list_style') != 'list'){
  ?>
    .container{max-width:1810px;}
    <?php if(_MBT('slider_right_banner')){?>
    .slider-left2{max-width: 1200px;}
    <?php }?>
    .slider-left{max-width: 1505px;}
    @media (max-width:1840px){
      .container{max-width:1505px;}
      .modown-ad .item:nth-child(6){display: none;}
    }
    @media (max-width:1535px){
      .modown-ad .item:nth-child(5){display: none;}
    }

    <?php
    if(_MBT('nav_position') == '1'){
    ?>
      body.nv-left .container{max-width:1810px;}
      <?php if(_MBT('slider_right_banner')){?>
      body.nv-left .slider-left2{max-width: 1200px;}
      <?php }?>
      body.nv-left .slider-left{max-width: 1505px;}
      @media (max-width:2080px) and (min-width:1025px){
        body.nv-left .container{max-width:1505px;}
        body.nv-left .modown-ad .item:nth-child(6){display: none;}
      }
      @media (max-width:1775px) and (min-width:1025px){
        body.nv-left .container{max-width: 1200px}
        body.nv-left .modown-ad .item:nth-child(5){display: none;}
      }
    <?php
    }

  }

  if(_MBT('list_column') == 'five' && _MBT('list_style') != 'list'){
  ?>
    .container{max-width:1505px;}
    <?php if(_MBT('slider_right_banner')){?>
    .slider-left2{max-width: 895px;}
    <?php }?>
    .slider-left{max-width: 1200px;}
    @media (max-width:1535px){
      .modown-ad .item:nth-child(5){display: none;}
      .slider-right2{width: 285px;}
      .slider-right2 .item2{display: none;}
    }

    <?php
    if(_MBT('nav_position') == '1'){
    ?>
      body.nv-left .container{max-width:1505px;}
      <?php if(_MBT('slider_right_banner')){?>
      body.nv-left .slider-left2{max-width: 895px;}
      <?php }?>
      body.nv-left .slider-left{max-width: 1200px;}
      @media (max-width:1775px) and (min-width:1025px){
        body.nv-left .container{max-width: 1200px}
        body.nv-left .modown-ad .item:nth-child(5){display: none;}
      }
    <?php
    }

  }

  if(_MBT('post_title')){
  ?>
    .grids .grid h3 a{height: 40px;-webkit-line-clamp:2;}
  <?php
  }

  if(_MBT('post_title_bold')){
  ?>
    .grids .grid h3 a, .lists .list h3 a, .lists .grid h3 a, .home-blogs ul li h3 a{font-weight:600;}
  <?php
  }

  if(_MBT('post_vip_free')){
  ?>
    .vip-tag, .free-tag{display:none !important;}
  <?php
  }

  if(_MBT('post_paragraph')){
  ?>
    .article-content p{text-indent: 2em;}
  <?php
  }

  if(_MBT('post_gallery_square')){
  ?>
    .article-content .gallery-item > a, .article-content .gallery-item .img{width:100%;height:0;position: relative;padding-bottom: 100%;display: block;}
    .article-content .gallery-item img{width:100%;height:100%;position: absolute;}
  <?php
  }

  if(_MBT('header_fullwidth')){
    echo '.header .container{max-width:none !important;padding:0 15px;}';
  }

  if(_MBT('logo_width')){
    echo '.logo{width:'._MBT('logo_width').'px;}';
    echo '@media (max-width: 1024px){.logo, .logo a {width: '._MBT('logo_width_wap').'px;height: 60px;}}';
  }

  if(_MBT('header_vip_wap')){
    echo '@media (max-width: 768px){.nav-right .nav-vip{display: none;}}';
  }

  if(_MBT('footer_widget_num') == '4'){
    echo '.footer-widget{width:calc(25% - 20px);}@media (max-width: 768px){.footer-widget{width:calc(50% - 20px);}}';
  }elseif(_MBT('footer_widget_num') == '3'){
    echo '.footer-widget{width:calc(33.3333% - 20px);}@media (max-width: 480px){.footer-widget{width:calc(100% - 20px);}}';
  }elseif(_MBT('footer_widget_num') == '2'){
    echo '.footer-widget{width:calc(50% - 20px);}@media (max-width: 768px){.footer-widget{width:calc(50% - 20px);}}@media (max-width: 480px){.footer-widget{width:calc(100% - 20px);}}';
  }

  if(_MBT('footer_nav')){
    echo '@media (max-width: 768px){
      .sitetips{bottom:100px;}
      .rollbar{display: none;}
    }';
  }

  if(_MBT('rollbar_wap')){
    echo '@media (max-width: 768px){
      .rollbar{display: block;}
    }';
  }

  if(_MBT('footer_nav_num') == '5'){
    echo '.footer-fixed-nav a{width: 20%}';
  }

  if(!is_user_logged_in() && _MBT('hide_user_all')){
    echo '.free-tag, .vip-tag, .grid-meta .price, .list-meta .price, .widget .price, .erphpdown{display: none !important}';
  }

  if(_MBT('theme_night')){
    if(isset($_COOKIE['mbt_theme_night'])){
        if($_COOKIE['mbt_theme_night'] == '1'){
          echo '::-webkit-scrollbar-thumb {background-color: rgba(56, 59, 78, 0.7);}';
        }
    }elseif(_MBT('theme_night_default')){
        echo '::-webkit-scrollbar-thumb {background-color: rgba(56, 59, 78, 0.7);}';
    }elseif(_MBT('theme_night_auto')){
        $time = intval(date("Hi"));
        if ($time < 730 || $time > 1930) {
            echo '::-webkit-scrollbar-thumb {background-color: rgba(56, 59, 78, 0.7);}';
        }
    }
  }

  if(_MBT('user_height')){
    echo '.user-main{min-height: '._MBT('user_height').'px;}';
  }

  echo _MBT('css');

  do_action("modown_skin");

  ?>
</style>