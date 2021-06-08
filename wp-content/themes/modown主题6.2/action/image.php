<?php
require( dirname(__FILE__).'/../../../../wp-load.php' );
if(is_uploaded_file($_FILES['imageFile']['tmp_name']) && is_user_logged_in() && _MBT('tougao_upload')){
	$error = 1;$msg = '上传失败';$url = '';
	$vname = $_FILES['imageFile']['name'];
	$arrType=array('image/jpg','image/png','image/jpeg');
	$uploaded_ext  = substr( $vname, strrpos( $vname, '.' ) + 1);
	$uploaded_type = $_FILES[ 'imageFile' ][ 'type' ];
	$uploaded_tmp  = $_FILES[ 'imageFile' ][ 'tmp_name' ];
	if ($vname != "") {
		if (in_array($uploaded_type,$arrType) && (strtolower( $uploaded_ext ) == 'jpg' || strtolower( $uploaded_ext ) == 'jpeg' || strtolower( $uploaded_ext ) == 'png' )) {

			if(_MBT('tougao_upload_media')){
				$wordpress_upload_dir = wp_upload_dir();
				$profilepicture = $_FILES['imageFile'];
				//$new_file_path = $wordpress_upload_dir['path'] . '/' . $profilepicture['name'];
				//$new_file_mime = mime_content_type( $profilepicture['tmp_name'] );
			 	$fname = md5(date("YmdHis").mt_rand(100,999)).strrchr($vname,'.');
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
					$url = $wordpress_upload_dir['url'] . '/' .$fname;
					$error = 0;
				}
			}else{
				$upfile = '../../../../wp-content/uploads/modown/';
				if(!file_exists($upfile)){  mkdir($upfile,0777,true);} 
				$filename = md5(date("YmdHis").mt_rand(100,999)).strrchr($vname,'.');
				$file_path = '../../../../wp-content/uploads/modown/'. $filename;
				if( $uploaded_type == 'image/jpeg' ) {
		            $img = imagecreatefromjpeg( $uploaded_tmp );
		            imagejpeg( $img, $file_path, 100);
		        }else {
		            $img = imagecreatefrompng( $uploaded_tmp );
		            imagepng( $img, $file_path, 9);
		        }
		        imagedestroy( $img );
		        $url = home_url().'/wp-content/uploads/modown/'. $filename;
		        $error = 0;
		    }
	        
		}else{
			$msg = "图片格式只支持.jpg .png";
		}
	}
	$arr=array(
		"error"=>$error, 
		"msg"=>$msg,
		"url" => $url
	); 
	$jarr=json_encode($arr); 
	echo $jarr;
}