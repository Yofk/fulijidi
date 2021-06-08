<?php 
	if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
		header('Allow: POST');
		header('HTTP/1.1 405 Method Not Allowed');
		header('Content-Type: text/plain');
		exit;
	}

	require( dirname(__FILE__) . '/../../../../wp-load.php' ); 
	
	if($_POST['action'] == 'comment' && is_numeric($_POST['id'])){
		$g=(int)get_comment_meta(intval($_POST['id']),"like",true);
		if(!$g)$g=0;
		update_comment_meta(intval($_POST['id']),"like",$g+1);
		$printr["result"] = "1";
		$printr["total"] = $g+1;
	}elseif($_POST['action'] == 'post' && is_numeric($_POST['id'])){
		$g=(int)get_post_meta(intval($_POST['id']),"zan",true);
		if(!$g)$g=0;
		update_post_meta(intval($_POST['id']),"zan",$g+1);
		$printr["result"] = "1";
		$printr["total"] = $g+1;
	}
	
	echo json_encode($printr);