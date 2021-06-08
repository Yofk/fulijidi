<?php
require( dirname(__FILE__).'/../../../../../wp-load.php' );
if(isset($_POST['action']) && current_user_can('administrator')){
	if($_POST['action'] == 'check'){
		$v = get_url_contents("http://api.mobantu.com/theme/modown.php");
		if($v > THEME_VER){
			$arr=array(
				"status"=>1
			); 
		}else{
			$arr=array(
				"status"=>0
			);
		}
		$jarr=json_encode($arr); 
		echo $jarr;
	}
}