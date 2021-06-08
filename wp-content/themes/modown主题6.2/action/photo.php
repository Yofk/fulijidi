<?php
require( dirname(__FILE__).'/../../../../wp-load.php' );
if(!_MBT('user_avatar')){
	if(is_uploaded_file($_FILES['avatarphoto']['tmp_name']) && is_user_logged_in()){
		global $current_user;
		$error = 1;$msg = '上传失败';
		$vname = $_FILES['avatarphoto']['name'];
		$arrType=array('image/jpg','image/png','image/jpeg');
		$uploaded_ext  = substr( $vname, strrpos( $vname, '.' ) + 1);
		$uploaded_type = $_FILES[ 'avatarphoto' ][ 'type' ];
		$uploaded_size = $_FILES['avatarphoto']['size'];
		$uploaded_tmp  = $_FILES[ 'avatarphoto' ][ 'tmp_name' ];
		if ($vname != "") {
			if (in_array($uploaded_type,$arrType) && (strtolower( $uploaded_ext ) == 'jpg' || strtolower( $uploaded_ext ) == 'jpeg' || strtolower( $uploaded_ext ) == 'png' )) {
				if ($uploaded_size > 102400*5) {
					$msg = "图片大小至多500K";
				}elseif(!(in_array($uploaded_type,$arrType) && (strtolower( $uploaded_ext ) == 'jpg' || strtolower( $uploaded_ext ) == 'jpeg' || strtolower( $uploaded_ext ) == 'png' ))){
					$msg = "图片格式只支持.jpg .png";
				}else{
					
					if(_MBT('user_avatar_media')){
						$wordpress_upload_dir = wp_upload_dir();
						$profilepicture = $_FILES['avatarphoto'];
						//$new_file_path = $wordpress_upload_dir['path'] . '/' . $profilepicture['name'];
						//$new_file_mime = mime_content_type( $profilepicture['tmp_name'] );
					 	$fname = 'avatar'.$current_user->ID.'-'.time().strrchr($vname,'.');
						$new_file_path = $wordpress_upload_dir['path'] . '/' . $fname;
						 
						if( move_uploaded_file( $profilepicture['tmp_name'], $new_file_path ) ) {
							$upload_id = wp_insert_attachment( array(
								'guid'           => $new_file_path, 
								'post_mime_type' => $uploaded_type,
								'post_title'     => preg_replace( '/\.[^.]+$/', '', $profilepicture['name'] ),
								'post_content'   => '',
								'post_status'    => 'inherit'
							), $new_file_path );
							require_once( ABSPATH . 'wp-admin/includes/image.php' );
							wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );
							//$url = $wordpress_upload_dir['url'] . '/' .$fname;
							update_user_meta($current_user->ID, 'photo', $wordpress_upload_dir['url'] . '/' .$fname);
							$error = 0;
							$msg = "上传成功";
						}
					}else{
						$upfile = '../../../../wp-content/uploads/avatar/';
						if(!file_exists($upfile)){  mkdir($upfile,0777,true);} 
						$userid = wp_get_current_user()->ID;
						$filename = md5($userid).strrchr($vname,'.');
						$file_path = '../../../../wp-content/uploads/avatar/'. $filename;
						if( $uploaded_type == 'image/jpeg' ) {
				            $img = imagecreatefromjpeg( $uploaded_tmp );
				            imagejpeg( $img, $file_path, 100);
				        }else {
				            $img = imagecreatefrompng( $uploaded_tmp );
				            imagepng( $img, $file_path, 9);
				        }
				        imagedestroy( $img );
				        update_user_meta($userid, 'photo', get_bloginfo('siteurl').'/wp-content/uploads/avatar/'.$filename);
				        $error = 0;
				        $msg = "上传成功";
				    }
			    }
			}
		}
		$arr=array(
			"error"=>$error, 
			"msg"=>$msg
		); 
		$jarr=json_encode($arr); 
		echo $jarr;
	}
}else{
	$arr=array(
		"error"=>1, 
		"msg"=>'暂未开放上传头像功能'
	); 
	$jarr=json_encode($arr); 
	echo $jarr;
}