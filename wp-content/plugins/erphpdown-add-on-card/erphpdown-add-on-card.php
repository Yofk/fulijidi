<?php 
/*
Plugin Name: Erphpdown充值卡
Plugin URI: http://www.mobantu.com/6015.html
Description: 在会员推广下载专业版的基础上集成充值卡，运行此插件需要先安装会员推广下载专业版。
Version: 2.0
Author: 模板兔
Author URI: http://www.mobantu.com
*/
define("erphpdown-card",plugin_dir_url( __FILE__ ));

include('inc/mobantu.php');
include('inc/card.php');

register_activation_hook(__FILE__, 'erphpdown_card_install');
//register_deactivation_hook(__FILE__, 'erphpdown_card_uninstall');
?>