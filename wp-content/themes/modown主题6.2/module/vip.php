<?php 
	$erphp_quarter_price = get_option('ciphp_quarter_price');
	$erphp_month_price  = get_option('ciphp_month_price');
	$erphp_day_price  = get_option('ciphp_day_price');
	$erphp_year_price    = get_option('ciphp_year_price');
	$erphp_life_price  = get_option('ciphp_life_price');

    $erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
    $erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
    $erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
    $erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
    $erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';
    $erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';

    $moneyVipName = get_option('ice_name_alipay');
    if(_MBT('vip_only_pay')){
        if($erphp_life_price) $erphp_life_price = $erphp_life_price/get_option('ice_proportion_alipay');
        if($erphp_year_price) $erphp_year_price = $erphp_year_price/get_option('ice_proportion_alipay');
        if($erphp_quarter_price) $erphp_quarter_price = $erphp_quarter_price/get_option('ice_proportion_alipay');
        if($erphp_month_price) $erphp_month_price = $erphp_month_price/get_option('ice_proportion_alipay');
        if($erphp_day_price) $erphp_day_price = $erphp_day_price/get_option('ice_proportion_alipay');
        $moneyVipName = '元';
    }
    ?>
<div class="vip-content clearfix">
    <div class="container">
        <h2><span><?php echo _MBT("home_vip_title","关于VIP");?></span></h2>
    	<?php if($erphp_day_price){?>
        <div class="vip-item item-0">
            <h6><?php echo $erphp_day_name;?></h6>
            <span class="price"><?php echo $erphp_day_price;?><small><?php echo $moneyVipName;?></small></span>
            <p class="border-decor"><span>1天</span></p>
            <?php echo _MBT('vip_day');?>
            <?php if(is_user_logged_in()){?>
            <a href="javascript:;" data-type="6" class="btn btn-small btn-vip-action">立即升级</a>
        	<?php }else{?>
        	<a href="javascript:;" class="btn btn-small signin-loader">立即升级</a>
        	<?php }?>
        </div>  
    	<?php }?>

    	<?php if($erphp_month_price){?>
        <div class="vip-item item-1">
            <h6><?php echo $erphp_month_name;?></h6>
            <span class="price"><?php echo $erphp_month_price;?><small><?php echo $moneyVipName;?></small></span>
            <p class="border-decor"><span>1个月</span></p>
            <?php echo _MBT('vip_month');?>
            <?php if(is_user_logged_in()){?>
            <a href="javascript:;" data-type="7" class="btn btn-small btn-vip-action">立即升级</a>
        	<?php }else{?>
        	<a href="javascript:;" class="btn btn-small signin-loader">立即升级</a>
        	<?php }?>
        </div>  
    	<?php }?>

    	<?php if($erphp_quarter_price){?>
        <div class="vip-item item-2">
            <h6><?php echo $erphp_quarter_name;?></h6>
            <span class="price"><?php echo $erphp_quarter_price;?><small><?php echo $moneyVipName;?></small></span>
            <p class="border-decor"><span>3个月</span></p>
            <?php echo _MBT('vip_quarter');?>
            <?php if(is_user_logged_in()){?>
            <a href="javascript:;" data-type="8" class="btn btn-small btn-vip-action">立即升级</a>
        	<?php }else{?>
        	<a href="javascript:;" class="btn btn-small signin-loader">立即升级</a>
        	<?php }?>
        </div>  
    	<?php }?>

    	<?php if($erphp_year_price){?>
        <div class="vip-item item-3">
            <h6><?php echo $erphp_year_name;?></h6>
            <span class="price"><?php echo $erphp_year_price;?><small><?php echo $moneyVipName;?></small></span>
            <p class="border-decor"><span>12个月</span></p>
            <?php echo _MBT('vip_year');?>
            <?php if(is_user_logged_in()){?>
            <a href="javascript:;" data-type="9" class="btn btn-small btn-vip-action">立即升级</a>
        	<?php }else{?>
        	<a href="javascript:;" class="btn btn-small signin-loader">立即升级</a>
        	<?php }?>
        </div>  
    	<?php }?>

    	<?php if($erphp_life_price){?>
        <div class="vip-item item-4">
            <h6><?php echo $erphp_life_name;?></h6>
            <span class="price"><?php echo $erphp_life_price;?><small><?php echo $moneyVipName;?></small></span>
            <p class="border-decor"><span>永久</span></p>
            <?php echo _MBT('vip_life');?>
            <?php if(is_user_logged_in()){?>
            <a href="javascript:;" data-type="10" class="btn btn-small btn-vip-action">立即升级</a>
        	<?php }else{?>
        	<a href="javascript:;" class="btn btn-small signin-loader">立即升级</a>
        	<?php }?>
        </div>  
    	<?php }?>
    </div>

</div>