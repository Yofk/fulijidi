<link rel="stylesheet" href="<?php bloginfo('template_url');?>/static/css/swiper.min.css">
<?php if(_MBT('slider_fullwidth')){?>
  <style>
  <?php if(_MBT('header_type') == 'default'){?>
  body.home .swiper-container{margin-top: -70px;}
  <?php }?>
  .swiper-container{border-radius: 0}
  .swiper-container .swiper-slide{background-color:#2d3757;height: 400px !important;padding-top:110px;background-image:url(../img/banner.jpg);background-position:center center;background-size:cover;background-repeat:no-repeat;position:relative;text-align: center;}
  .swiper-container .swiper-slide .container{z-index:10;position: relative;}
  .swiper-container .swiper-slide h2{font-size:35px;font-weight:600;margin-bottom:10px;color:#fff;}
  .swiper-container .swiper-slide p{font-size:18px;color: #fff}
  .swiper-container .swiper-slide .swiper-slide-btn{border:1px solid #fff;color:#1d1d1d;background:#fff;font-size:18px;border-radius:30px;display:inline-block;padding:10px 40px;margin-top:40px;width:auto;}
  .swiper-container .swiper-slide img{width:100%;height: auto;}
  .swiper-container .swiper-slide .swiper-slide-link{position: absolute;top:0;left: 0;right: 0;left: 0}
  @media (max-width: 768px){
    <?php if(_MBT('header_type') == 'default'){?>
    body.home .swiper-container{margin-top: -60px;}
    <?php }?>
    .swiper-container .swiper-slide{padding-top: 70px;height: 250px !important;}
    .swiper-container .swiper-slide h2{font-size:24px;margin-bottom: 5px}
    .swiper-container .swiper-slide p{font-size: 16px;margin-top: 0}
    .swiper-container .swiper-slide .swiper-slide-btn{margin-top: 20px;padding:5px 24px;font-size: 16px}
  }
  </style>
  <div class="swiper-container swiper-container-fullwidth">
      <div class="swiper-wrapper">
        <?php 
          $sort = '1 2 3 4 5';
          $sort = array_unique(explode(' ', trim($sort)));
          $i = 0;
          foreach ($sort as $key => $value) {
              if( _MBT('slider_img'.$value) ){
        ?>
          <div class="swiper-slide" style="background-image: url(<?php echo _MBT('slider_img'.$value);?>);">
            <div class="container">
              <?php if(_MBT('slider_title'.$value)){?><h2><?php echo _MBT('slider_title'.$value);?></h2><?php }?>
              <?php if(_MBT('slider_desc'.$value)){?><p><?php echo _MBT('slider_desc'.$value);?></p><?php }?>
              <?php if(_MBT('slider_btn'.$value)){?><a href="<?php echo _MBT('slider_link'.$value);?>" target="_blank" class="swiper-slide-btn"><?php echo _MBT('slider_btn'.$value);?></a><?php }?>
            </div>
            <?php if(_MBT('slider_link'.$value)){?><a href="<?php echo _MBT('slider_link'.$value);?>" target="_blank" class="swiper-slide-link"></a><?php }?>
          </div>
        <?php }}?>
      </div>
      <div class="swiper-pagination"></div>
  </div>
  <script src="<?php bloginfo('template_url');?>/static/js/swiper.min.js"></script>
  <script>
      var swiper = new Swiper('.swiper-container', {
        slidesPerView: '1',
        autoHeight: true,
        autoplay: {
          delay: 4000,
          disableOnInteraction: false,
        },
        pagination: {
          el: '.swiper-pagination',
          dynamicBullets: false,
          clickable: true,
        },
      });
  </script>
<?php }else{?>
  <div class="banner-slider">
    <div class="container">
      <div class="<?php if(_MBT('slider_right')){ if(_MBT('slider_right_banner')) echo 'slider-left2'; else echo 'slider-left'; }else echo 'slider-full';?>">
        <div class="swiper-container">
            <div class="swiper-wrapper">
              <?php 
                $sort = '1 2 3 4 5';
                $sort = array_unique(explode(' ', trim($sort)));
                $i = 0;
                foreach ($sort as $key => $value) {
                    if( _MBT('slider_img'.$value) ){
              ?>
                <div class="swiper-slide">
                  <a href="<?php echo _MBT('slider_link'.$value);?>" target="_blank">
                  <img src="<?php echo _MBT('slider_img'.$value);?>" alt="<?php echo _MBT('slider_title'.$value);?>" title="<?php echo _MBT('slider_title'.$value);?>">
                  </a>
                </div>
              <?php }}?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <script src="<?php bloginfo('template_url');?>/static/js/swiper.min.js"></script>
        <script>
            var swiper = new Swiper('.swiper-container', {
              slidesPerView: '1',
              autoHeight: true,
              autoplay: {
                delay: 4000,
                disableOnInteraction: false,
              },
              pagination: {
                el: '.swiper-pagination',
                dynamicBullets: false,
                clickable: true,
              },
            });
        </script>
      </div>
      <?php if(_MBT('slider_right')){?>
      <div class="<?php if(_MBT('slider_right_banner')) echo 'slider-right2'; else echo 'slider-right';?>">
        <?php if(_MBT('slider_right_banner')){?>
        <div class="item2">
          <a href="<?php echo _MBT("slider_right_banner_link");?>" target="_blank"><img src="<?php echo _MBT("slider_right_banner_img");?>"></a>
        </div>
        <?php }?>
        <div class="item">
          <a href="<?php echo _MBT("slider_right_link1");?>" target="_blank"><img src="<?php echo _MBT("slider_right_img1");?>"></a>
        </div>
        <div class="item">
          <a href="<?php echo _MBT("slider_right_link2");?>" target="_blank"><img src="<?php echo _MBT("slider_right_img2");?>"></a>
        </div>
      </div>
      <?php }?>
    </div>
  </div>
<?php }?>