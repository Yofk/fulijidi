<div class="article-header-box clearfix">
	<div class="header-box-img">
		<img <?php if(_MBT('lazyload')) echo 'src="'.get_bloginfo("template_directory").'/static/img/thumbnail.png"';?> <?php echo _MBT('lazyload')?'data-src':'src';?>="<?php echo MBThemes_thumbnail_full();?>" class="thumb" alt="<?php the_title();?>">
	</div>
	<div class="header-box-con">
		<?php get_template_part('module/post-header');?>
		<?php
			if(function_exists('get_field_objects')){
                $fields = get_field_objects();
                if( $fields ){
                	echo '<div class="custom-metas">';
                    foreach( $fields as $field_name => $field ){
                    	if($field['value']){
	                        echo '<div class="meta">';
	                            echo '<span>' . $field['label'] . '：</span>';
	                            if(is_array($field['value'])){
	                            	if($field['type'] == 'link'){
	                            		echo '<a href="'.$field['value']['url'].'" target="'.$field['value']['target'].'">'.$field['value']['title'].'</a>';
	                            	}elseif($field['type'] == 'taxonomy'){
	                            		$tax_html = '';
	                            		foreach ($field['value'] as $tax) {
	                            			$term = get_term_by('term_id',$tax,$field['taxonomy']);
	                            			$tax_html .= '<a href="'.get_term_link($tax).'" target="_blank">'.$term->name.'</a>, ';
	                            		}
	                            		echo rtrim($tax_html, ', ');
	                            	}else{
										echo implode(',', $field['value']);
									}
								}else{
									if($field['type'] == 'radio'){
										$vv = $field['value'];
										echo $field['choices'][$vv];
									}else{
										echo $field['value'];
									}
								}
	                        echo '</div>';
	                    }
                    }
                    echo '</div>';
                }else{
                	echo '<div class="excerpt">'.MBThemes_get_excerpt(250).'</div>';
                }
            }else{
            	echo '<div class="excerpt">'.MBThemes_get_excerpt(250).'</div>';
            }
		?>
		<?php
		if(MBThemes_check_reply()){
		if(wp_is_erphpdown_active() && (is_user_logged_in() || !_MBT('hide_user_all'))){
			$start_down=get_post_meta(get_the_ID(), 'start_down', true);
			$start_down2=get_post_meta(get_the_ID(), 'start_down2', true);
			$days=get_post_meta(get_the_ID(), 'down_days', true);
			$price=get_post_meta(get_the_ID(), 'down_price', true);
			$price_type=get_post_meta(get_the_ID(), 'down_price_type', true);
			$url=get_post_meta(get_the_ID(), 'down_url', true);
			$urls=get_post_meta(get_the_ID(), 'down_urls', true);
			$url_free=get_post_meta(get_the_ID(), 'down_url_free', true);
			$memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
			$hidden=get_post_meta(get_the_ID(), 'hidden_content', true);
			$demo=get_post_meta(get_the_ID(), 'demo', true);
			$userType=getUsreMemberType();
			$down_info = null;$downMsgFree = '';$yituan = '';$down_tuan=0;$down_demo = '';$down_repeat=0;

			$erphp_popdown = get_option('erphp_popdown');
			if($erphp_popdown){
				$iframe = '&iframe=1';
			}

			if(function_exists('erphpdown_tuan_install')){
				$down_tuan=get_post_meta(get_the_ID(), 'down_tuan', true);
			}

			if(function_exists('doErphpAct')){
				$down_repeat = get_post_meta(get_the_ID(), 'down_repeat', true);
			}

			if($demo){
				$down_demo = '<a href="'.$demo.'" target="_blank" rel="nofollow" class="erphpdown-btn-large demo-demo">在线演示</a>';
			}

			$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
			$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
			$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
			$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
			$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';
			$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';
			
			
			$erphp_url_front_vip = add_query_arg('action','vip',get_permalink(MBThemes_page("template/user.php")));
			$erphp_url_front_login = get_permalink(MBThemes_page("template/login.php"));
			

			$erphp_blank_domains = get_option('erphp_blank_domains')?get_option('erphp_blank_domains'):'pan.baidu.com';
			$erphp_colon_domains = get_option('erphp_colon_domains')?get_option('erphp_colon_domains'):'pan.baidu.com';

			if($down_tuan && is_user_logged_in()){
				global $current_user;
				$yituan = $wpdb->get_var("select ice_status from $wpdb->tuanorder where ice_user_id=".$current_user->ID." and ice_post=".get_the_ID()." and ice_status>0");
			}

			$content = '';

			if($url_free){
				$downMsgFree .= '<fieldset class="erphpdown-child erphpdown-free"><legend>免费资源</legend>';
				$downList=explode("\r\n",$url_free);
				foreach ($downList as $k=>$v){
					$filepath = $downList[$k];
					if($filepath){

						if($erphp_colon_domains){
							$erphp_colon_domains_arr = explode(',', $erphp_colon_domains);
							foreach ($erphp_colon_domains_arr as $erphp_colon_domain) {
								if(strpos($filepath, $erphp_colon_domain)){
									$filepath = str_replace('：', ': ', $filepath);
									break;
								}
							}
						}

						$erphp_blank_domain_is = 0;
						if($erphp_blank_domains){
							$erphp_blank_domains_arr = explode(',', $erphp_blank_domains);
							foreach ($erphp_blank_domains_arr as $erphp_blank_domain) {
								if(strpos($filepath, $erphp_blank_domain)){
									$erphp_blank_domain_is = 1;
									break;
								}
							}
						}
						if(strpos($filepath,',')){
							$filearr = explode(',',$filepath);
							$arrlength = count($filearr);
							if($arrlength == 1){
								$downMsgFree.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
							}elseif($arrlength == 2){
								$downMsgFree.="<div class='erphpdown-item'>".$filearr[0]."<a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
							}elseif($arrlength == 3){
								$filearr2 = str_replace('：', ': ', $filearr[2]);
								$downMsgFree.="<div class='erphpdown-item'>".$filearr[0]."<a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a>（".$filearr2."）<a class='erphpdown-copy' data-clipboard-text='".str_replace('提取码: ', '', $filearr2)."' href='javascript:;'>复制</a></div>";
							}
						}elseif(strpos($filepath,'  ') && $erphp_blank_domain_is){
							$filearr = explode('  ',$filepath);
							$arrlength = count($filearr);
							if($arrlength == 1){
								$downMsgFree.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
							}elseif($arrlength >= 2){
								$filearr2 = explode(':',$filearr[0]);
								$filearr3 = explode(':',$filearr[1]);
								$downMsgFree.="<div class='erphpdown-item'>".$filearr2[0]."<a href='".trim($filearr2[1].':'.$filearr2[2])."' target='_blank' class='erphpdown-down'>点击下载</a>（提取码: ".trim($filearr3[1])."）<a class='erphpdown-copy' data-clipboard-text='".trim($filearr3[1])."' href='javascript:;'>复制</a></div>";
							}
						}elseif(strpos($filepath,' ') && $erphp_blank_domain_is){
							$filearr = explode(' ',$filepath);
							$arrlength = count($filearr);
							if($arrlength == 1){
								$downMsgFree.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
							}elseif($arrlength == 2){
								$downMsgFree.="<div class='erphpdown-item'>".$filearr[0]."<a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
							}elseif($arrlength >= 3){
								$downMsgFree.="<div class='erphpdown-item'>".str_replace(':', '', $filearr[0])."<a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a>（".$filearr[2].' '.$filearr[3]."）<a class='erphpdown-copy' data-clipboard-text='".$filearr[3]."' href='javascript:;'>复制</a></div>";
							}
						}else{
							$downMsgFree.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
						}
					}
				}
				if(get_option('ice_tips_free')) $downMsgFree.='<div class="erphpdown-tips erphpdown-tips-free">'.get_option('ice_tips_free').'</div>';
				$downMsgFree .= '</fieldset>';
				
			}
			
			if($start_down2){
				if($url){
					
					$content.='<fieldset class="erphpdown" id="erphpdown" style="display:block"><legend>资源下载</legend>'.$downMsgFree;
					$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
					$wppay = new EPD(get_the_ID(), $user_id);
					if($wppay->isWppayPaid() || !$price || ($memberDown == 3 && $userType)){
						$content .= '<fieldset class="erphpdown-child"><legend>资源下载</legend>';
						$downList=explode("\r\n",trim($url));
						foreach ($downList as $k=>$v){
							$filepath = trim($downList[$k]);
							if($filepath){

								if($erphp_colon_domains){
									$erphp_colon_domains_arr = explode(',', $erphp_colon_domains);
									foreach ($erphp_colon_domains_arr as $erphp_colon_domain) {
										if(strpos($filepath, $erphp_colon_domain)){
											$filepath = str_replace('：', ': ', $filepath);
											break;
										}
									}
								}

								$erphp_blank_domain_is = 0;
								if($erphp_blank_domains){
									$erphp_blank_domains_arr = explode(',', $erphp_blank_domains);
									foreach ($erphp_blank_domains_arr as $erphp_blank_domain) {
										if(strpos($filepath, $erphp_blank_domain)){
											$erphp_blank_domain_is = 1;
											break;
										}
									}
								}
								if(strpos($filepath,',')){
									$filearr = explode(',',$filepath);
									$arrlength = count($filearr);
									if($arrlength == 1){
										$downMsg.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
									}elseif($arrlength == 2){
										$downMsg.="<div class='erphpdown-item'>".$filearr[0]."<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
									}elseif($arrlength == 3){
										$filearr2 = str_replace('：', ': ', $filearr[2]);
										$downMsg.="<div class='erphpdown-item'>".$filearr[0]."<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a>（".$filearr2."）<a class='erphpdown-copy' data-clipboard-text='".str_replace('提取码: ', '', $filearr2)."' href='javascript:;'>复制</a></div>";
									}
								}elseif(strpos($filepath,'  ') && $erphp_blank_domain_is){
									$filearr = explode('  ',$filepath);
									$arrlength = count($filearr);
									if($arrlength == 1){
										$downMsg.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
									}elseif($arrlength >= 2){
										$filearr2 = explode(':',$filearr[0]);
										$filearr3 = explode(':',$filearr[1]);
										$downMsg.="<div class='erphpdown-item'>".$filearr2[0]."<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a>（提取码: ".trim($filearr3[1])."）<a class='erphpdown-copy' data-clipboard-text='".trim($filearr3[1])."' href='javascript:;'>复制</a></div>";
									}
								}elseif(strpos($filepath,' ') && $erphp_blank_domain_is){
									$filearr = explode(' ',$filepath);
									$arrlength = count($filearr);
									if($arrlength == 1){
										$downMsg.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
									}elseif($arrlength == 2){
										$downMsg.="<div class='erphpdown-item'>".$filearr[0]."<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
									}elseif($arrlength >= 3){
										$downMsg.="<div class='erphpdown-item'>".str_replace(':', '', $filearr[0])."<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a>（".$filearr[2].' '.$filearr[3]."）<a class='erphpdown-copy' data-clipboard-text='".$filearr[3]."' href='javascript:;'>复制</a></div>";
									}
								}else{
									$downMsg.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
								}
							}
						}
						$content .= $downMsg;	
						if($hidden){
							$content .= '<div class="erphpdown-item">提取码：'.$hidden.' <a class="erphpdown-copy" data-clipboard-text="'.$hidden.'" href="javascript:;">复制</a></div>';
						}
						$content .= '</fieldset>';
					}else{
						$content .= '<div class="erphpdown-box-tips">';
						if($memberDown == 3){
							$content .= '此资源下载价格为<span class="erphpdown-price">'.$price.'</span>元，'.$erphp_vip_name.'免费<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
						}else{
							$content .= '此资源下载价格为<span class="erphpdown-price">'.$price.'</span>元';	
						}
						$content .= '</div>';
						$content .= $down_demo.'<a href="javascript:;" class="erphp-wppay-loader erphpdown-buy erphpdown-btn-large" data-post="'.get_the_ID().'">立即支付</a>';
					}
					
					if(get_option('ice_tips')) $content.='<div class="erphpdown-tips">'.get_option('ice_tips').'</div>';
					$content.='</fieldset>';
				}

			}elseif($start_down){
				$content.='<fieldset class="erphpdown erphpdown-header-box" id="erphpdown" style="display:block"><legend>资源下载</legend>'.$downMsgFree;
				if($down_tuan == '2' && function_exists('erphpdown_tuan_install')){
					$tuanHtml = erphpdown_tuan_modown_html3();
					$content.= $tuanHtml;
				}else{
					$content .= '<div class="erphpdown-fee clearfix">';
					if($price_type){
						if($urls){
							$cnt = count($urls['index']);
			    			if($cnt){
			    				for($i=0; $i<$cnt;$i++){
			    					$index = $urls['index'][$i];
			    					$index_name = $urls['name'][$i];
			    					$price = $urls['price'][$i];
			    					$index_url = $urls['url'][$i];
			    					$index_vip = $urls['vip'][$i];

			    					$indexMemberDown = $memberDown;
	            					if($index_vip){
	            						$indexMemberDown = $index_vip;
	            					}

	            					if(function_exists('epd_check_pan_callback')){
										if(strpos($index_url,'pan.baidu.com') !== false){
											$down_checkpan = '<a class="erphpdown-buy erphpdown-checkpan erphpdown-btn-large" href="javascript:;" data-id="'.get_the_ID().'" data-index="'.$index.'" data-buy="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.'">点击检测网盘有效后购买</a>';
										}
									}
	            					
			    					$content .= '<fieldset class="erphpdown-child"><legend>'.$index_name.'</legend>';
			    					if(is_user_logged_in()){
			    						$content .= '<div class="erphpdown-box-tips">';
										if($price){
											if($indexMemberDown != 4 && $indexMemberDown != 8 && $indexMemberDown != 9)
												$content.='此资源下载价格为<span class="erphpdown-price">'.$price.'</span>'.get_option("ice_name_alipay");
										}else{
											if($indexMemberDown != 4 && $indexMemberDown != 8 && $indexMemberDown != 9)
												$content.='此资源仅限注册用户下载';
										}

										if($price || $indexMemberDown == 4 || $indexMemberDown == 8 || $indexMemberDown == 9){
											global $wpdb;
											$user_info=wp_get_current_user();
											$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_index='".$index."' and ice_success=1 and ice_user_id=".$user_info->ID." order by ice_time desc");
											if($days > 0){
												$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
												$nowDate = date('Y-m-d H:i:s');
												if(strtotime($nowDate) > strtotime($lastDownDate)){
													$down_info = null;
												}
											}

											if($indexMemberDown > 1){
												$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
												if($userType){
													$vipText = '';
													if(($indexMemberDown == 13 || $indexMemberDown == 14) && $userType < 10){
														$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
													}
												}
												if($indexMemberDown==3 && $down_info==null){
													$content.='，'.$erphp_vip_name.'免费'.$vipText.'';
												}elseif ($indexMemberDown==2 && $down_info==null){
													$content.='，'.$erphp_vip_name.' 5折'.$vipText.'';
												}elseif ($indexMemberDown==13 && $down_info==null){
													$content.='，'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费'.$vipText.'';
												}elseif ($indexMemberDown==14 && $down_info==null){
													$content.='，'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费'.$vipText.'';
												}elseif ($indexMemberDown==5 && $down_info==null){
													$content.='，'.$erphp_vip_name.' 8折'.$vipText.'';
												}elseif ($indexMemberDown==6 && $down_info==null){
													if($userType < 9){
														$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
													}
													$content.='，'.$erphp_year_name.'免费'.$vipText.'';
												}elseif ($indexMemberDown==7 && $down_info==null){
													if($userType < 10){
														$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
													}
													$content.='，'.$erphp_life_name.'免费'.$vipText.'';
												}elseif ($indexMemberDown==4){
													if($userType){
														$content.='此资源为'.$erphp_vip_name.'专享资源';
													}
												}elseif ($indexMemberDown==8){
													if($userType >= 9){
														$content.='此资源为'.$erphp_year_name.'专享资源';
													}
												}elseif ($indexMemberDown==9){
													if($userType >= 10){
														$content.='此资源为'.$erphp_life_name.'专享资源';
													}
												}
											}

											if($indexMemberDown==4 && $userType==FALSE){
												$content.='此资源仅限'.$erphp_vip_name.'下载</div>';
												$content.="<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip' target='_blank'>升级".$erphp_vip_name."</a>";
											}elseif($indexMemberDown==8 && $userType < 9){
												$content.='此资源仅限'.$erphp_year_name.'下载</div>';
												$content.="<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip' target='_blank'>升级".$erphp_year_name."</a>";
											}elseif($indexMemberDown==9 && $userType < 10){
												$content.='此资源仅限'.$erphp_life_name.'下载</div>';
												$content.="<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip' target='_blank'>升级".$erphp_life_name."</a>";
											}elseif($indexMemberDown==10){
												if($down_info){
													if(get_option('erphp_popdown')){
														$content.='<a href="'.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.'" class="erphpdown-down erphpdown-down-layui erphpdown-btn-large bought">立即下载</a>';
													}else{
														$content.='<a href="'.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.'" class="erphpdown-down erphpdown-btn-large bought" target="_blank">立即下载</a>';
													}
												}elseif ($userType) {
													$content.='，仅限'.$erphp_vip_name.'购买</div>';
													if($down_checkpan){
														$content.= $down_checkpan;
													}else{
														$content.='<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.'" target="_blank">立即购买</a>';
													}
												}else{
													$content.='，仅限'.$erphp_vip_name.'购买</div>';
													$content.="<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip' target='_blank'>升级".$erphp_vip_name."</a>";
												}
											}elseif($indexMemberDown==11){
												if($down_info){
													if(get_option('erphp_popdown')){
														$content.='<a href="'.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.'" class="erphpdown-down erphpdown-down-layui erphpdown-btn-large bought">立即下载</a>';
													}else{
														$content.='<a href="'.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.'" class="erphpdown-down erphpdown-btn-large bought" target="_blank">立即下载</a>';
													}
												}elseif ($userType) {
													$content.='，仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折</div>';
													if($down_checkpan){
														$content.= $down_checkpan;
													}else{
														$content.='<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.'" target="_blank">立即购买</a>';
													}
												}else{
													$content.='，仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折</div>';
													$content.="<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip' target='_blank'>升级".$erphp_vip_name."</a>";
												}
											}elseif($indexMemberDown==12){
												if($down_info){
													if(get_option('erphp_popdown')){
														$content.='<a href="'.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.'" class="erphpdown-down erphpdown-down-layui erphpdown-btn-large bought">立即下载</a>';
													}else{
														$content.='<a href="'.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.'" class="erphpdown-down erphpdown-btn-large bought" target="_blank">立即下载</a>';
													}
												}elseif ($userType) {
													$content.='，仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折</div>';
													if($down_checkpan){
														$content.= $down_checkpan;
													}else{
														$content.='<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.'" target="_blank">立即购买</a>';
													}
												}else{
													$content.='，仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折</div>';
													$content.="<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip' target='_blank'>升级".$erphp_vip_name."</a>";
												}
											}else{
												$content .= '</div>';
												if($userType && $indexMemberDown > 1){
													if($indexMemberDown==3 || $indexMemberDown==4){
														if(get_option('erphp_popdown')){
															$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index.$iframe."' class='erphpdown-down erphpdown-down-layui erphpdown-btn-large'>立即下载</a>";
														}else{
															$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index."' class='erphpdown-down erphpdown-btn-large' target='_blank'>立即下载</a>";
														}
													}elseif ($indexMemberDown==2 && $down_info==null){
														if($down_checkpan){
															$content.= $down_checkpan;
														}else{
															$content.='<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.'" target="_blank">立即购买</a>';
														}
													}elseif ($indexMemberDown==5 && $down_info==null){
														if($down_checkpan){
															$content.= $down_checkpan;
														}else{
															$content.='<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.'" target="_blank">立即购买</a>';
														}
													}elseif ($indexMemberDown==6 && $down_info==null){
														if($userType == 9){
															if(get_option('erphp_popdown')){
																$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index.$iframe."' class='erphpdown-down erphpdown-down-layui erphpdown-btn-large'>立即下载</a>";
															}else{
																$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index."' class='erphpdown-down erphpdown-btn-large' target='_blank'>立即下载</a>";
															}	
														}elseif($userType == 10){
															if(get_option('erphp_popdown')){
																$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index.$iframe."' class='erphpdown-down erphpdown-down-layui erphpdown-btn-large'>立即下载</a>";
															}else{
																$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index."' class='erphpdown-down erphpdown-btn-large' target='_blank'>立即下载</a>";
															}	
														}else{
															if($down_checkpan){
																$content.= $down_checkpan;
															}else{
																$content.='<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.'" target="_blank">立即购买</a>';
															}
														}
													}elseif (($indexMemberDown==7 || $indexMemberDown==13 || $indexMemberDown==14) && $down_info==null){
														if($userType == 10){
															if(get_option('erphp_popdown')){
																$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index.$iframe."' class='erphpdown-down erphpdown-down-layui erphpdown-btn-large'>立即下载</a>";
															}else{
																$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index."' class='erphpdown-down erphpdown-btn-large' target='_blank'>立即下载</a>";
															}	
														}else{
															if($down_checkpan){
																$content.= $down_checkpan;
															}else{
																$content.='<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.'" target="_blank">立即购买</a>';
															}
														}
													}elseif ($indexMemberDown==8 && $down_info==null){
														if($userType >= 9){
															if(get_option('erphp_popdown')){
																$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index.$iframe."' class='erphpdown-down erphpdown-down-layui erphpdown-btn-large'>立即下载</a>";
															}else{
																$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index."' class='erphpdown-down erphpdown-btn-large' target='_blank'>立即下载</a>";
															}	
														}
													}elseif ($indexMemberDown==9 && $down_info==null){
														if($userType >= 10){
															if(get_option('erphp_popdown')){
																$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index.$iframe."' class='erphpdown-down erphpdown-down-layui erphpdown-btn-large'>立即下载</a>";
															}else{
																$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index."' class='erphpdown-down erphpdown-btn-large' target='_blank'>立即下载</a>";
															}	
														}
													}elseif($down_info){
														if(get_option('erphp_popdown')){
															$content.='<a href="'.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.'" class="erphpdown-down erphpdown-down-layui erphpdown-btn-large bought">立即下载</a>';
														}else{
															$content.='<a href="'.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.'" class="erphpdown-down erphpdown-btn-large bought" target="_blank">立即下载</a>';
														}
													}
												}else{
													if($down_info){
														if(get_option('erphp_popdown')){
															$content.='<a href="'.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.'" class="erphpdown-down erphpdown-down-layui erphpdown-btn-large bought">立即下载</a>';
														}else{
															$content.='<a href="'.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.'" class="erphpdown-down erphpdown-btn-large bought" target="_blank">立即下载</a>';
														}
													}else{
														if($down_checkpan){
															$content.= $down_checkpan;
														}else{
															$content.='<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.'" target="_blank">立即购买</a>';
														}
													}
												}
											}
											
										}else{
											$content .= '</div>';
											if(get_option('erphp_popdown')){
												$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index.$iframe."' class='erphpdown-down erphpdown-down-layui erphpdown-btn-large'>立即下载</a>";
											}else{
												$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index."' class='erphpdown-down erphpdown-btn-large' target='_blank'>立即下载</a>";
											}
										}
										
									}else{
										$content .= '<div class="erphpdown-box-tips">';
										if($indexMemberDown == 4 || $indexMemberDown == 8 || $indexMemberDown == 9){
											$content.='此资源仅限'.$erphp_vip_name.'下载，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>后下载';
										}else{
											if($price){
												$content.='此资源下载价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay').'，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>后下载';
											}else{
												$content.='此资源为免费资源，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>后下载';
											}
										}
										$content .= '</div>';
										$content .= "<a href='javascript:;' class='erphpdown-down erphpdown-btn-large signin-loader'>立即下载</a>";
									}
			    					$content .= '</fieldset>';
			    				}
			    			}
						}
					}else{
						if(function_exists('erphpdown_tuan_install')){
							$content.= erphpdown_tuan_modown_html3();
						}

						if(function_exists('epd_check_pan_callback')){
							if(strpos($url,'pan.baidu.com') !== false){
								$down_checkpan = $down_demo.'<a class="erphpdown-buy erphpdown-checkpan erphpdown-btn-large" href="javascript:;" data-id="'.get_the_ID().'" data-index="'.$index.'" data-buy="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'">点击检测网盘有效后购买</a>';
							}
						}

						if(is_user_logged_in()){
							$content .= '<div class="erphpdown-box-tips">';
							if($price){
								if($memberDown != 4 && $memberDown != 8 && $memberDown != 9)
									$content.='此资源下载价格为<span class="erphpdown-price">'.$price.'</span>'.get_option("ice_name_alipay");
							}else{
								if($memberDown != 4 && $memberDown != 8 && $memberDown != 9)
									$content.='此资源为免费资源';
							}

							if($price || $memberDown == 4 || $memberDown == 8 || $memberDown == 9){
								global $wpdb;
								$user_info=wp_get_current_user();
								$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_success=1 and (ice_index is null or ice_index = '') and ice_user_id=".$user_info->ID." order by ice_time desc");
								if($days > 0){
									$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
									$nowDate = date('Y-m-d H:i:s');
									if(strtotime($nowDate) > strtotime($lastDownDate)){
										$down_info = null;
									}
								}

								if($down_repeat){
									$down_info = null;
								}

								if($memberDown > 1){
									$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
									if($userType){
										$vipText = '';
										if(($memberDown == 13 || $memberDown == 14) && $userType < 10){
											$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
										}
									}
									if($memberDown==3 && $down_info==null){
										$content.='，'.$erphp_vip_name.'免费'.$vipText.'';
									}elseif ($memberDown==2 && $down_info==null){
										$content.='，'.$erphp_vip_name.' 5折'.$vipText.'';
									}elseif ($memberDown==13 && $down_info==null){
										$content.='，'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费'.$vipText.'';
									}elseif ($memberDown==14 && $down_info==null){
										$content.='，'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费'.$vipText.'';
									}elseif ($memberDown==5 && $down_info==null){
										$content.='，'.$erphp_vip_name.' 8折'.$vipText.'';
									}elseif ($memberDown==6 && $down_info==null){
										if($userType < 9){
											$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
										}
										$content.='，'.$erphp_year_name.'免费'.$vipText.'';
									}elseif ($memberDown==7 && $down_info==null){
										if($userType < 10){
											$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
										}
										$content.='，'.$erphp_life_name.'免费'.$vipText.'';
									}elseif ($memberDown==4){
										if($userType){
											$content.='此资源为'.$erphp_vip_name.'专享资源';
										}
									}elseif ($memberDown==8){
										if($userType >= 9){
											$content.='此资源为'.$erphp_year_name.'专享资源';
										}
									}elseif ($memberDown==9){
										if($userType >= 10){
											$content.='此资源为'.$erphp_life_name.'专享资源';
										}
									}
								}

								if($memberDown==4 && $userType==FALSE){
									$content.='此资源仅限'.$erphp_vip_name.'下载</div>';
									$content.=$down_demo."<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip' target='_blank'>升级".$erphp_vip_name."</a>";
								}elseif($memberDown==8 && $userType < 9){
									$content.='此资源仅限'.$erphp_year_name.'下载</div>';
									$content.=$down_demo."<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip' target='_blank'>升级".$erphp_year_name."</a>";
								}elseif($memberDown==9 && $userType < 10){
									$content.='此资源仅限'.$erphp_life_name.'下载</div>';
									$content.=$down_demo."<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip' target='_blank'>升级".$erphp_life_name."</a>";
								}elseif($memberDown==10){
									if($down_info){
										if(get_option('erphp_popdown')){
											$content.=$down_demo.'<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' class="erphpdown-down erphpdown-down-layui erphpdown-btn-large bought">立即下载</a>';
										}else{
											$content.=$down_demo.'<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().' class="erphpdown-down erphpdown-btn-large bought" target="_blank">立即下载</a>';
										}
									}elseif($userType){
										$content.='，仅限'.$erphp_vip_name.'购买</div>';
										if($down_checkpan){
											$content .= $down_checkpan;
										}else{
											$content.=$down_demo.'<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
										}
									}else{
										$content.='，仅限'.$erphp_vip_name.'购买</div>';
										$content.=$down_demo."<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip' target='_blank'>升级".$erphp_vip_name."</a>";
									}
								}elseif($memberDown==11){
									if($down_info){
										if(get_option('erphp_popdown')){
											$content.=$down_demo.'<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' class="erphpdown-down erphpdown-down-layui erphpdown-btn-large bought">立即下载</a>';
										}else{
											$content.=$down_demo.'<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().' class="erphpdown-down erphpdown-btn-large bought" target="_blank">立即下载</a>';
										}
									}elseif($userType){
										$content.='，仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折</div>';
										if($down_checkpan){
											$content .= $down_checkpan;
										}else{
											$content.=$down_demo.'<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
										}
									}else{
										$content.='，仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折</div>';
										$content.=$down_demo."<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip' target='_blank'>升级".$erphp_vip_name."</a>";
									}
								}elseif($memberDown==12){
									if($down_info){
										if(get_option('erphp_popdown')){
											$content.=$down_demo.'<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' class="erphpdown-down erphpdown-down-layui erphpdown-btn-large bought">立即下载</a>';
										}else{
											$content.=$down_demo.'<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().' class="erphpdown-down erphpdown-btn-large bought" target="_blank">立即下载</a>';
										}
									}elseif($userType){
										$content.='，仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折</div>';
										if($down_checkpan){
											$content .= $down_checkpan;
										}else{
											$content.=$down_demo.'<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
										}
									}else{
										$content.='，仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折</div>';
										$content.=$down_demo."<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip' target='_blank'>升级".$erphp_vip_name."</a>";
									}
								}else{
									$content .= '</div>';
									if($userType && $memberDown > 1){
										if($memberDown==3 || $memberDown==4){
											if(get_option('erphp_popdown')){
												$content.=$down_demo."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down erphpdown-down-layui erphpdown-btn-large'>立即下载</a>";
											}else{
												$content.=$down_demo."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID()." class='erphpdown-down erphpdown-btn-large' target='_blank'>立即下载</a>";
											}
										}elseif ($memberDown==2 && $down_info==null){
											if($down_checkpan){
												$content .= $down_checkpan;
											}else{
												$content.=$down_demo.'<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
											}
										}elseif ($memberDown==5 && $down_info==null){
											if($down_checkpan){
												$content .= $down_checkpan;
											}else{
												$content.=$down_demo.'<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
											}
										}elseif ($memberDown==6 && $down_info==null){
											if($userType == 9){
												if(get_option('erphp_popdown')){
													$content.=$down_demo."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down erphpdown-down-layui erphpdown-btn-large'>立即下载</a>";
												}else{
													$content.=$down_demo."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID()." class='erphpdown-down erphpdown-btn-large' target='_blank'>立即下载</a>";
												}	
											}elseif($userType == 10){
												if(get_option('erphp_popdown')){
													$content.=$down_demo."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down erphpdown-down-layui erphpdown-btn-large'>立即下载</a>";
												}else{
													$content.=$down_demo."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID()." class='erphpdown-down erphpdown-btn-large' target='_blank'>立即下载</a>";
												}	
											}else{
												if($down_checkpan){
													$content .= $down_checkpan;
												}else{
													$content.=$down_demo.'<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
												}
											}
										}elseif (($memberDown==7 || $memberDown==13 || $memberDown==14) && $down_info==null){
											if($userType == 10){
												if(get_option('erphp_popdown')){
													$content.=$down_demo."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down erphpdown-down-layui erphpdown-btn-large'>立即下载</a>";
												}else{
													$content.=$down_demo."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID()." class='erphpdown-down erphpdown-btn-large' target='_blank'>立即下载</a>";
												}
											}else{
												if($down_checkpan){
													$content .= $down_checkpan;
												}else{
													$content.=$down_demo.'<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
												}
											}
										}elseif ($memberDown==8 && $down_info==null){
											if($userType >= 9){
												if(get_option('erphp_popdown')){
													$content.=$down_demo."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down erphpdown-down-layui erphpdown-btn-large'>立即下载</a>";
												}else{
													$content.=$down_demo."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID()." class='erphpdown-down erphpdown-btn-large' target='_blank'>立即下载</a>";
												}
													
											}
										}elseif ($memberDown==9 && $down_info==null){
											if($userType >= 10){
												if(get_option('erphp_popdown')){
													$content.=$down_demo."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down erphpdown-down-layui erphpdown-btn-large'>立即下载</a>";
												}else{
													$content.=$down_demo."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID()." class='erphpdown-down erphpdown-btn-large' target='_blank'>立即下载</a>";
												}
													
											}
										}elseif($down_info){
											if(get_option('erphp_popdown')){
												$content.=$down_demo.'<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' class="erphpdown-down erphpdown-down-layui erphpdown-btn-large bought">立即下载</a>';
											}else{
												$content.=$down_demo.'<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().' class="erphpdown-down erphpdown-btn-large bought" target="_blank">立即下载</a>';
											}
										}
									}else {
										if($down_info){
											if(get_option('erphp_popdown')){
												$content.=$down_demo.'<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' class="erphpdown-down erphpdown-down-layui erphpdown-btn-large bought">立即下载</a>';
											}else{
												$content.=$down_demo.'<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().' class="erphpdown-down erphpdown-btn-large bought" target="_blank">立即下载</a>';
											}
										}else{
											if($down_checkpan){
												$content .= $down_checkpan;
											}else{
												$content.=$down_demo.'<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
											}
										}
									}
								}
								
							}else{
								$content .= '</div>';
								if(get_option('erphp_popdown')){
									$content.=$down_demo."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down erphpdown-down-layui erphpdown-btn-large'>立即下载</a>";
								}else{
									$content.=$down_demo."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID()." class='erphpdown-down erphpdown-btn-large' target='_blank'>立即下载</a>";
								}
							}
							
						}else {
							$content .= '<div class="erphpdown-box-tips">';
							if($memberDown == 4 || $memberDown == 8 || $memberDown == 9){
								$content.='此资源仅限'.$erphp_vip_name.'下载，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>后下载';
							}else{
								if($price){
									$content.='此资源下载价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay').'，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>后下载';
								}else{
									$content.='此资源为免费资源，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>后下载';
								}
							}
							$content .= '</div>';
							$content .= $down_demo."<a href='javascript:;' class='erphpdown-down erphpdown-btn-large signin-loader'>立即下载</a>";
						}
					}
					$content .= '</div>';
					if(get_option('ice_tips')) $content.='<div class="erphpdown-tips">'.get_option('ice_tips').'</div>';
				}
				$content.='</fieldset>';
				
			}else{
				if($downMsgFree) $content.='<fieldset class="erphpdown" id="erphpdown" style="display:block"><legend>资源下载</legend>'.$downMsgFree.'</fieldset>';
			}
			echo $content;
		}
		}else{
			echo '<div class="modown-reply">此下载/查看详情 <a href="javascript:scrollTo(\'#respond\',-120);">评论</a> 本文后<span>刷新页面</span>可见！</div>';
		}
		?>
	</div>
</div>