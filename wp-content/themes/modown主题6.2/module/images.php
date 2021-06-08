<?php $images = get_post_meta(get_the_ID(),'images',true);?>
<div class="single-images">
	<link rel="stylesheet" href="<?php bloginfo('template_url');?>/static/css/swiper.min.css">
	<div class="swiper-post-container">
	  	<div class="swiper-wrapper">
	  	  	<?php $cnt = count($images['src']);
                if($cnt){
                    for($i=0; $i<$cnt;$i++){?>
	      	<div class="swiper-slide">
	    		<img src="<?php echo $images['src'][$i];?>" alt="<?php echo $images['alt'][$i]?$images['alt'][$i]:get_the_title();?>" title="<?php echo $images['alt'][$i]?$images['alt'][$i]:get_the_title();?>">
	      	</div>
	      	<?php }}?>
	  	</div>
	  	<div class="swiper-pagination"></div>
	</div>
	<script src="<?php bloginfo('template_url');?>/static/js/swiper.min.js"></script>
	<script>
	    var swiper = new Swiper('.swiper-post-container', {
	        slidesPerView: '1',
	        autoHeight: true,
	        autoplay: {
	          delay: 3000,
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