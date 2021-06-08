<?php
//by mobantu.com
function MBThemes_erphpdown_box(){
	global $post, $wpdb;
	if(MBThemes_check_reply()){
	if(is_user_logged_in() || !_MBT('hide_user_all')){
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
		$nosidebar = get_post_meta(get_the_ID(),'nosidebar',true);
		$userType=getUsreMemberType();
		$vip = '';$vip2 = '';$vip3 = '';$downMsg = '';$downclass = '';$hasfree = 0;$iframe = '';$downMsgFree = '';$yituan = '';$down_tuan=0;$down_repeat=0;
		$erphp_popdown = get_option('erphp_popdown');
		if($erphp_popdown){
			$downclass = ' erphpdown-down-layui';
			$iframe = '&iframe=1';
		}

		if(function_exists('erphpdown_tuan_install')){
			$down_tuan=get_post_meta(get_the_ID(), 'down_tuan', true);
		}

		if(function_exists('doErphpAct')){
			$down_repeat = get_post_meta(get_the_ID(), 'down_repeat', true);
		}

		$erphp_see2_style = get_option('erphp_see2_style');
		$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
		$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
		$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
		$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
		$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';
		$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';

		$erphp_blank_domains = get_option('erphp_blank_domains')?get_option('erphp_blank_domains'):'pan.baidu.com';
		$erphp_colon_domains = get_option('erphp_colon_domains')?get_option('erphp_colon_domains'):'pan.baidu.com';

		if($down_tuan && is_user_logged_in()){
			global $current_user;
			$yituan = $wpdb->get_var("select ice_status from $wpdb->tuanorder where ice_user_id=".$current_user->ID." and ice_post=".get_the_ID()." and ice_status>0");
		}

		if($nosidebar || MBThemes_post_down_position() == 'top' || MBThemes_post_down_position() == 'sidetop' || MBThemes_post_down_position() == 'bottom' || MBThemes_post_down_position() == 'sidebottom' || MBThemes_post_down_position() == 'boxbottom') echo '<style>.erphpdown-box{display:block;}</style>';

		if($url_free){
			$hasfree = 1;
			echo '<fieldset class="erphpdown-box erphpdown-box2 erphpdown-free-box"><legend>免费资源</legend>';
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
							$downMsgFree.="<div class='item item2'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
						}elseif($arrlength == 2){
							$downMsgFree.="<div class='item item2'>".$filearr[0]."<a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
						}elseif($arrlength == 3){
							$filearr2 = str_replace('：', ': ', $filearr[2]);
							$downMsgFree.="<div class='item item2'>".$filearr[0]."<a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a>（".$filearr2."）<a class='erphpdown-copy' data-clipboard-text='".str_replace('提取码: ', '', $filearr2)."' href='javascript:;'>复制</a></div>";
						}
					}elseif(strpos($filepath,'  ') && $erphp_blank_domain_is){
						$filearr = explode('  ',$filepath);
						$arrlength = count($filearr);
						if($arrlength == 1){
							$downMsgFree.="<div class='item item2'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
						}elseif($arrlength >= 2){
							$filearr2 = explode(':',$filearr[0]);
							$filearr3 = explode(':',$filearr[1]);
							$downMsgFree.="<div class='item item2'>".$filearr2[0]."<a href='".trim($filearr2[1].':'.$filearr2[2])."' target='_blank' class='erphpdown-down'>点击下载</a>（提取码: ".trim($filearr3[1])."）<a class='erphpdown-copy' data-clipboard-text='".trim($filearr3[1])."' href='javascript:;'>复制</a></div>";
						}
					}elseif(strpos($filepath,' ') && $erphp_blank_domain_is){
						$filearr = explode(' ',$filepath);
						$arrlength = count($filearr);
						if($arrlength == 1){
							$downMsgFree.="<div class='item item2'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
						}elseif($arrlength == 2){
							$downMsgFree.="<div class='item item2'>".$filearr[0]."<a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
						}elseif($arrlength >= 3){
							$downMsgFree.="<div class='item item2'>".str_replace(':', '', $filearr[0])."<a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a>（".$filearr[2].' '.$filearr[3]."）<a class='erphpdown-copy' data-clipboard-text='".$filearr[3]."' href='javascript:;'>复制</a></div>";
						}
					}else{
						$downMsgFree.="<div class='item item2'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
					}
				}
			}
			echo $downMsgFree;

			if(get_option('ice_tips_free')) echo '<div class="tips2">'.get_option('ice_tips_free').'</div>';

			echo '</fieldset>';
		}

		if($start_down){
			echo '<fieldset class="erphpdown-box"><legend>资源下载</legend>';
			if($down_tuan == '2' && function_exists('erphpdown_tuan_install')){
				$tuanHtml = erphpdown_tuan_modown_html2();
				echo $tuanHtml;
				if(function_exists('get_field_objects')){
		            $fields = get_field_objects();
		            if( $fields ){
		            	echo '<div class="custom-metas">';
		                foreach( $fields as $field_name => $field ){
		                	if($field['value']){
		                        echo '<div class="item item2">';
		                            echo '<t>' . $field['label'] . '：</t>';
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
		            }
		        }
			}else{
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
										$down_checkpan = '<a class="down erphpdown-checkpan" href="javascript:;" data-id="'.get_the_ID().'" data-index="'.$index.'" data-buy="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.'">点击检测网盘有效后购买</a>';
									}
								}

		    					echo '<fieldset class="erphpdown-child"><legend>'.$index_name.'</legend>';
		    					if($price){
									if($indexMemberDown != 4 && $indexMemberDown != 8 && $indexMemberDown != 9){
										echo '<div class="item price"><t>下载价格：</t><span>'.$price.'</span> '.get_option("ice_name_alipay").'</div>';
									}else{
										echo '<div class="item price"><t>下载价格：</t><span>'.$erphp_vip_name.'专享</span></div>';
									}
								}else{
									if($indexMemberDown != 4 && $indexMemberDown != 8 && $indexMemberDown != 9){
										echo '<div class="item price"><t>下载价格：</t><span>免费</span></div>';
									}else{
										echo '<div class="item price"><t>下载价格：</t><span>'.$erphp_vip_name.'专享</span></div>';
									}
								}
								if($price || $indexMemberDown == 4 || $indexMemberDown == 8 || $indexMemberDown == 9){
									if(is_user_logged_in()){
										$user_info=wp_get_current_user();
										$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_success=1 and ice_index='".$index."' and ice_user_id=".$user_info->ID." order by ice_time desc");
										if($days > 0){
											$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
											$nowDate = date('Y-m-d H:i:s');
											if(strtotime($nowDate) > strtotime($lastDownDate)){
												$down_info = null;
											}
										}

										if(!$down_info){
											if(!$userType){
												$vip = '<a href="'.add_query_arg('action','vip',get_permalink(MBThemes_page("template/user.php"))).'" target="_blank">升级'.$erphp_vip_name.'</a>';
											}else{
												if(($indexMemberDown == 13 || $indexMemberDown == 14) && $userType < 10){
													$vip = '<a href="'.add_query_arg('action','vip',get_permalink(MBThemes_page("template/user.php"))).'" target="_blank">升级'.$erphp_life_name.'</a>';
												}
											}
											if($userType < 9){
												$vip2 = '<a href="'.add_query_arg('action','vip',get_permalink(MBThemes_page("template/user.php"))).'" target="_blank">升级'.$erphp_year_name.'</a>';
											}
											if($userType < 10){
												$vip3 = '<a href="'.add_query_arg('action','vip',get_permalink(MBThemes_page("template/user.php"))).'" target="_blank">升级'.$erphp_life_name.'</a>';
											}
										}

										if($indexMemberDown==3){
											echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>免费'.$vip.'</div>';
										}elseif($indexMemberDown==2){
											echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>5 折'.$vip.'</div>';
										}elseif($indexMemberDown==13){
											echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>5 折、'.$erphp_life_name.'免费'.$vip.'</div>';
										}elseif($indexMemberDown==5){
											echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>8 折'.$vip.'</div>';
										}elseif($indexMemberDown==14){
											echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>8 折、'.$erphp_life_name.'免费'.$vip.'</div>';
										}elseif($indexMemberDown==6){
											echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>'.$erphp_year_name.'免费'.$vip2.'</div>';
										}elseif($indexMemberDown==7){
											echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>'.$erphp_life_name.'免费'.$vip3.'</div>';
										}

										if($indexMemberDown==4 && !$userType){
											echo '<div class="item vip vip-only">此资源仅对'.$erphp_vip_name.'开放下载'.$vip.'</div>';
										}elseif($indexMemberDown==8 && $userType < 9){
											echo '<div class="item vip vip-only">此资源仅对'.$erphp_year_name.'开放下载'.$vip2.'</div>';
										}elseif($indexMemberDown==9 && $userType < 10){
											echo '<div class="item vip vip-only">此资源仅对'.$erphp_life_name.'开放下载'.$vip3.'</div>';
										}elseif($indexMemberDown==10){
											if($down_info){
												echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.' target="_blank" class="down bought'.$downclass.'">已购买，立即下载</a>';
											}elseif($userType){
												echo '<div class="item vip vip-only">此资源仅限'.$erphp_vip_name.'购买'.$vip.'</div>';
												if($down_checkpan) echo $down_checkpan;
												else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.' class="down erphpdown-iframe">立即购买</a>';
											}else{
												echo '<div class="item vip vip-only">此资源仅限'.$erphp_vip_name.'购买'.$vip.'</div>';
											}
										}elseif($indexMemberDown==11){
											if($down_info){
												echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.' target="_blank" class="down bought'.$downclass.'">已购买，立即下载</a>';
											}elseif($userType){
												echo '<div class="item vip vip-only">此资源仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 5折'.$vip.'</div>';
												if($down_checkpan) echo $down_checkpan;
												else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.' class="down erphpdown-iframe">立即购买</a>';
											}else{
												echo '<div class="item vip vip-only">此资源仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 5折'.$vip.'</div>';
											}
										}elseif($indexMemberDown==12){
											if($down_info){
												echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.' target="_blank" class="down bought'.$downclass.'">已购买，立即下载</a>';
											}elseif($userType){
												echo '<div class="item vip vip-only">此资源仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 8折'.$vip.'</div>';
												if($down_checkpan) echo $down_checkpan;
												else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.' class="down erphpdown-iframe">立即购买</a>';
											}else{
												echo '<div class="item vip vip-only">此资源仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 8折'.$vip.'</div>';
											}
										}else{
											if($down_info){
												echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.' target="_blank" class="down bought'.$downclass.'">已购买，立即下载</a>';
											}else{
												if (($indexMemberDown==6 || $indexMemberDown==8) && ($userType == 9 || $userType == 10)){
													echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.' target="_blank" class="down'.$downclass.'">立即下载</a>';
												}elseif (($indexMemberDown==7 || $indexMemberDown==9 || $indexMemberDown==13 || $indexMemberDown==14) && $userType == 10){
													echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.' target="_blank" class="down'.$downclass.'">立即下载</a>';
												}elseif( ($indexMemberDown==3 || $indexMemberDown==4) && $userType){
													echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.' target="_blank" class="down'.$downclass.'">立即下载</a>';
												}else{
													if($down_checkpan) echo $down_checkpan;
													else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.' class="down erphpdown-iframe">立即购买</a>';
													if($days){
														echo '<div class="tips">（此资源购买后'.$days.'天内可下载）</div>';
													}
												}
											}
										}
									}else{
										if($indexMemberDown==3){
											echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>免费'.$vip.'</div>';
										}elseif($indexMemberDown==2){
											echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>5 折'.$vip.'</div>';
										}elseif($indexMemberDown==13){
											echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>5 折、'.$erphp_life_name.'免费'.$vip.'</div>';
										}elseif($indexMemberDown==5){
											echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>8 折'.$vip.'</div>';
										}elseif($indexMemberDown==14){
											echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>8 折、'.$erphp_life_name.'免费'.$vip.'</div>';
										}elseif($indexMemberDown==6){
											echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>'.$erphp_year_name.'免费'.$vip2.'</div>';
										}elseif($indexMemberDown==7){
											echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>'.$erphp_life_name.'免费'.$vip3.'</div>';
										}elseif($indexMemberDown==4 || $indexMemberDown == 8 || $indexMemberDown == 9){
											echo '<div class="item vip vip-only">此资源仅对'.$erphp_vip_name.'开放下载'.$vip.'</div>';
										}
										echo '<a href="javascript:;" class="down signin-loader">请先登录</a>';
									}
								}else{
									if(is_user_logged_in()){
										if($indexMemberDown != 4 && $indexMemberDown != 8 && $indexMemberDown != 9){
											echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.' target="_blank" class="down'.$downclass.'">立即下载</a>';
										}
									}else{
										echo '<a href="javascript:;" class="down signin-loader">请先登录</a>';
									}
								}
		    					echo '</fieldset>';
		    				}
		    			}
		    		}
				}else{
					if(function_exists('epd_check_pan_callback')){
						if(strpos($url,'pan.baidu.com') !== false){
							$down_checkpan = '<a class="down erphpdown-checkpan" href="javascript:;" data-id="'.get_the_ID().'" data-index="'.$index.'" data-buy="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'">点击检测网盘有效后购买</a>';
						}
					}

					if($price){
						if($memberDown != 4 && $memberDown != 8 && $memberDown != 9){
							echo '<div class="item price"><t>下载价格：</t><span>'.$price.'</span> '.get_option("ice_name_alipay").'</div>';
						}else{
							echo '<div class="item price"><t>下载价格：</t><span>'.$erphp_vip_name.'专享</span></div>';
						}
					}else{
						if($memberDown != 4 && $memberDown != 8 && $memberDown != 9){
							echo '<div class="item price"><t>下载价格：</t><span>免费</span></div>';
						}else{
							echo '<div class="item price"><t>下载价格：</t><span>'.$erphp_vip_name.'专享</span></div>';
						}
					}
					if($price || $memberDown == 4 || $memberDown == 8 || $memberDown == 9){
						if(is_user_logged_in()){
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

							if(!$down_info){
								if(!$userType){
									$vip = '<a href="'.add_query_arg('action','vip',get_permalink(MBThemes_page("template/user.php"))).'" target="_blank">升级'.$erphp_vip_name.'</a>';
								}else{
									if(($memberDown == 13 || $memberDown == 14) && $userType < 10){
										$vip = '<a href="'.add_query_arg('action','vip',get_permalink(MBThemes_page("template/user.php"))).'" target="_blank">升级'.$erphp_life_name.'</a>';
									}
								}
								if($userType < 9){
									$vip2 = '<a href="'.add_query_arg('action','vip',get_permalink(MBThemes_page("template/user.php"))).'" target="_blank">升级'.$erphp_year_name.'</a>';
								}
								if($userType < 10){
									$vip3 = '<a href="'.add_query_arg('action','vip',get_permalink(MBThemes_page("template/user.php"))).'" target="_blank">升级'.$erphp_life_name.'</a>';
								}
							}

							if($memberDown==3){
								echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>免费'.$vip.'</div>';
							}elseif($memberDown==2){
								echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>5 折'.$vip.'</div>';
							}elseif($memberDown==13){
								echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>5 折、'.$erphp_life_name.'免费'.$vip.'</div>';
							}elseif($memberDown==5){
								echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>8 折'.$vip.'</div>';
							}elseif($memberDown==14){
								echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>8 折、'.$erphp_life_name.'免费'.$vip.'</div>';
							}elseif($memberDown==6){
								echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>'.$erphp_year_name.'免费'.$vip2.'</div>';
							}elseif($memberDown==7){
								echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>'.$erphp_life_name.'免费'.$vip3.'</div>';
							}

							if($memberDown==4 && !$userType){
								echo '<div class="item vip vip-only">此资源仅对'.$erphp_vip_name.'开放下载'.$vip.'</div>';
							}elseif($memberDown==8 && $userType < 9){
								echo '<div class="item vip vip-only">此资源仅对'.$erphp_year_name.'开放下载'.$vip2.'</div>';
							}elseif($memberDown==9 && $userType < 10){
								echo '<div class="item vip vip-only">此资源仅对'.$erphp_life_name.'开放下载'.$vip3.'</div>';
							}elseif($memberDown==10){
								if($down_info){
									echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' target="_blank" class="down bought'.$downclass.'">已购买，立即下载</a>';
								}elseif($userType){
									echo '<div class="item vip vip-only">此资源仅限'.$erphp_vip_name.'购买'.$vip.'</div>';
									if($down_checkpan) echo $down_checkpan;
									else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' class="down erphpdown-iframe">立即购买</a>';
								}else{
									echo '<div class="item vip vip-only">此资源仅限'.$erphp_vip_name.'购买'.$vip.'</div>';
								}
							}elseif($memberDown==11){
								if($down_info){
									echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' target="_blank" class="down bought'.$downclass.'">已购买，立即下载</a>';
								}elseif($userType){
									echo '<div class="item vip vip-only">此资源仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 5折'.$vip.'</div>';
									if($down_checkpan) echo $down_checkpan;
									else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' class="down erphpdown-iframe">立即购买</a>';
								}else{
									echo '<div class="item vip vip-only">此资源仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 5折'.$vip.'</div>';
								}
							}elseif($memberDown==12){
								if($down_info){
									echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' target="_blank" class="down bought'.$downclass.'">已购买，立即下载</a>';
								}elseif($userType){
									echo '<div class="item vip vip-only">此资源仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 8折'.$vip.'</div>';
									if($down_checkpan) echo $down_checkpan;
									else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' class="down erphpdown-iframe">立即购买</a>';
								}else{
									echo '<div class="item vip vip-only">此资源仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 8折'.$vip.'</div>';
								}
							}else{
								if($down_info){
									echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' target="_blank" class="down bought'.$downclass.'">已购买，立即下载</a>';
								}else{
									if (($memberDown==6 || $memberDown==8) && ($userType == 9 || $userType == 10)){
										echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' target="_blank" class="down'.$downclass.'">立即下载</a>';
									}elseif (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14) && $userType == 10){
										echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' target="_blank" class="down'.$downclass.'">立即下载</a>';
									}elseif( ($memberDown==3 || $memberDown==4) && $userType){
										echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' target="_blank" class="down'.$downclass.'">立即下载</a>';
									}else{
										if($down_checkpan) echo $down_checkpan;
										else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' class="down erphpdown-iframe">立即购买</a>';
										if($days){
											echo '<div class="tips">（此资源购买后'.$days.'天内可下载）</div>';
										}
									}
								}
							}
						}else{
							if($memberDown==3){
								echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>免费'.$vip.'</div>';
							}elseif($memberDown==2){
								echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>5 折'.$vip.'</div>';
							}elseif($memberDown==13){
								echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>5 折、'.$erphp_life_name.'免费'.$vip.'</div>';
							}elseif($memberDown==5){
								echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>8 折'.$vip.'</div>';
							}elseif($memberDown==14){
								echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>8 折、'.$erphp_life_name.'免费'.$vip.'</div>';
							}elseif($memberDown==6){
								echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>'.$erphp_year_name.'免费'.$vip2.'</div>';
							}elseif($memberDown==7){
								echo '<div class="item vip"><t>'.$erphp_vip_name.'优惠：</t>'.$erphp_life_name.'免费'.$vip3.'</div>';
							}elseif($memberDown==4 || $memberDown == 8 || $memberDown == 9){
								echo '<div class="item vip vip-only">此资源仅对'.$erphp_vip_name.'开放下载'.$vip.'</div>';
							}
							echo '<a href="javascript:;" class="down signin-loader">请先登录</a>';
						}
					}else{
						if(is_user_logged_in()){
							if($memberDown != 4 && $memberDown != 8 && $memberDown != 9){
								echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' target="_blank" class="down'.$downclass.'">立即下载</a>';
							}
						}else{
							echo '<a href="javascript:;" class="down signin-loader">请先登录</a>';
						}
					}

					if(function_exists('erphpdown_tuan_install')){
						echo erphpdown_tuan_modown_html2();
					}
				}

				if(function_exists('get_field_objects')){
		            $fields = get_field_objects();
		            if( $fields ){
		            	echo '<div class="custom-metas">';
		                foreach( $fields as $field_name => $field ){
		                	if($field['value']){
		                        echo '<div class="item item2">';
		                            echo '<t>' . $field['label'] . '：</t>';
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
		            }
		        }

				if(get_option('ice_tips')) echo '<div class="tips2">'.get_option('ice_tips').'</div>';
			}
			echo '</fieldset>';
		}elseif($start_down2){
			if($url){
				echo '<fieldset class="erphpdown-box erphpdown-box2"><legend>资源下载</legend>';
				$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
				$wppay = new EPD(get_the_ID(), $user_id);
				if($wppay->isWppayPaid() || !$price || ($memberDown == 3 && $userType)){
					if($url){
						$downList=explode("\r\n",$url);
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
										$downMsg.="<div class='item item2'><t>文件".($k+1)."地址</t><a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
									}elseif($arrlength == 2){
										$downMsg.="<div class='item item2'><t>".$filearr[0]."</t><a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
									}elseif($arrlength == 3){
										$filearr2 = str_replace('：', ': ', $filearr[2]);
										$downMsg.="<div class='item item2'><t>".$filearr[0]."</t><a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a>（".$filearr2."）<a class='erphpdown-copy' data-clipboard-text='".str_replace('提取码: ', '', $filearr2)."' href='javascript:;'>复制</a></div>";
									}
								}elseif(strpos($filepath,'  ') && $erphp_blank_domain_is){
									$filearr = explode('  ',$filepath);
									$arrlength = count($filearr);
									if($arrlength == 1){
										$downMsg.="<div class='item item2'><t>文件".($k+1)."地址</t><a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
									}elseif($arrlength >= 2){
										$filearr2 = explode(':',$filearr[0]);
										$filearr3 = explode(':',$filearr[1]);
										$downMsg.="<div class='item item2'><t>".$filearr2[0]."</t><a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a>（提取码: ".trim($filearr3[1])."）<a class='erphpdown-copy' data-clipboard-text='".trim($filearr3[1])."' href='javascript:;'>复制</a></div>";
									}
								}elseif(strpos($filepath,' ') && $erphp_blank_domain_is){
									$filearr = explode(' ',$filepath);
									$arrlength = count($filearr);
									if($arrlength == 1){
										$downMsg.="<div class='item item2'><t>文件".($k+1)."地址</t><a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
									}elseif($arrlength == 2){
										$downMsg.="<div class='item item2'><t>".$filearr[0]."</t><a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
									}elseif($arrlength >= 3){
										$downMsg.="<div class='item item2'><t>".str_replace(':', '', $filearr[0])."</t><a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a>（".$filearr[2].' '.$filearr[3]."）<a class='erphpdown-copy' data-clipboard-text='".$filearr[3]."' href='javascript:;'>复制</a></div>";
									}
								}else{
									$downMsg.="<div class='item item2'><t>文件".($k+1)."地址</t><a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
								}
							}
						}
						echo $downMsg;
						if($hidden){
							echo '<div class="item item2">提取码：'.$hidden.' <a class="erphpdown-copy" data-clipboard-text="'.$hidden.'" href="javascript:;">复制</a></div>';
						}
					}else{
						echo '<style>#erphpdown{display:none !important;}</style>';
					}
				}else{
					if($url){
						$tname = '资源下载';
					}else{
						$tname = '内容查看';
					}
					if($memberDown == 3){
						echo $tname.'价格<span class="erphpdown-price">'.$price.'</span>元<a href="javascript:;" class="down erphp-wppay-loader" data-post="'.get_the_ID().'">立即支付</a>&nbsp;&nbsp;<b>或</b>&nbsp;&nbsp;升级'.$erphp_vip_name.'后免费<a href="'.get_permalink(MBThemes_page("template/user.php")).'?action=vip" target="_blank" class="erphpdown-vip">立即升级</a>';
					}else{
						echo $tname.'价格<span class="erphpdown-price">'.$price.'</span>元<a href="javascript:;" class="down erphp-wppay-loader" data-post="'.get_the_ID().'">立即支付</a>';
					}
				}

				if(function_exists('get_field_objects')){
		            $fields = get_field_objects();
		            if( $fields ){
		            	echo '<div class="custom-metas">';
		                foreach( $fields as $field_name => $field ){
		                	if($field['value']){
		                        echo '<div class="item item2">';
		                            echo '<t>' . $field['label'] . '：</t>';
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
		            }
		        }
				if(get_option('ice_tips')) echo '<div class="tips2">'.get_option('ice_tips').'</div>';
				echo '</fieldset>';
			}
		}
	}
	}else{
		echo '<div class="modown-reply">此下载/查看详情 <a href="javascript:scrollTo(\'#respond\',-120);">评论</a> 本文后<span>刷新页面</span>可见！</div>';
	}
}


function MBThemes_erphpdown_download($msg,$pid){
	get_header();
?>
	<link rel="stylesheet" href="<?php echo constant("erphpdown"); ?>static/erphpdown.css" type="text/css" />
	<style>
	body{background: #f9f9f9}
	.banner{background: #2d3237 !important}
	.banner:after{content: "";position: absolute;top: 0;bottom: 0;left: 0;right: 0;background: rgba(45, 50, 55, .5);z-index: 9;}
	.archive-title{margin-bottom:0}
	.content-wrap{margin-bottom: 10px;text-align: center;}
	.content{display: inline-block;max-width: 580px;text-align: left;width:100%}
	.single-content{padding:50px 30px;margin-bottom: 0;border-radius:0;box-shadow: inset 0px 15px 10px -15px #d4d4d4;}
	.article-content{margin-bottom: 0;font-size: 15px;}
	.article-content p{text-indent:0}
	
	body.night .single-content{box-shadow: inset 0px 15px 10px -15px #232735;}
	body.night .banner{background: #212533 !important}
	body.night .banner:after{background: rgba(20, 22, 31, .5);}
	
	@media (max-width:620px){
		.single-content{padding:30px 20px;}
		.modown-erphpdown-bottom{padding:20px;}
	}
	</style>
	<div class="banner-archive">
		<div class="container">
			<h1 class="archive-title"><?php
			$index=isset($_GET['index']) ? $_GET['index'] : '';
			$index_name = '';
			if($index){
				$urls = get_post_meta($pid, 'down_urls', true);
				if($urls){
					$cnt = count($urls['index']);
					if($cnt){
						for($i=0; $i<$cnt;$i++){
							if($urls['index'][$i] == $index){
		    					$index_name = ' - '.$urls['name'][$i];
		    					break;
		    				}
						}
					}
				}
			}
			echo get_the_title($pid).$index_name;?></h1>
		</div>
	</div>
	<div class="main">
		<div class="container">
			<div class="content-wrap">
		    	<div class="content">
		    		<article class="single-content">
		    			<span class="mbt-down-top"></span>
			    		<div class="article-content">
			    			<div class="erphpdown-msg">
			    				<?php echo $msg;?>
			    			</div>
			            </div>
		            </article>
		            <div class="modown-erphpdown-bottom">
		            	<span class="line"></span>
		            	<?php if(_MBT('ad_erphpdown_s')){?>
		    			<div class="erphpdown-ad"><?php echo _MBT('ad_erphpdown');?></div>
		    			<?php }?>
		            </div>
		    	</div>
		    </div>
		</div>
	</div>
<?php
	get_footer();
?>
<script>
	new Clipboard(".erphpdown-down-btn");
	jQuery(".erphpdown-down-btn").click(function(){
		layer.msg("已复制提取码");
	});
</script>
<?php
	exit;
}

function MBThemes_erphpdown_viphtml(){
	global $current_user;

	$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
	$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
	$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
	$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
	$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';
	$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';

	$userTypeId=getUsreMemberType();
    if($userTypeId==6){
        echo $erphp_day_name;
    }elseif($userTypeId==7){
        echo $erphp_month_name;
    }elseif ($userTypeId==8){
        echo $erphp_quarter_name;
    }elseif ($userTypeId==9){
        echo $erphp_year_name;
    }elseif ($userTypeId==10){
        echo $erphp_life_name;
    }else {
        echo '普通用户';
    }
    echo ($userTypeId>0&&$userTypeId<10) ?'<t>'.getUsreMemberTypeEndTime().'到期</t>':'';
    echo ($userTypeId == 10) ?'<t>永久尊享</t>':'';

    if($userTypeId){
	    $erphp_life_times    = get_option('erphp_life_times');
		$erphp_year_times    = get_option('erphp_year_times');
		$erphp_quarter_times = get_option('erphp_quarter_times');
		$erphp_month_times  = get_option('erphp_month_times');
		$erphp_day_times  = get_option('erphp_day_times');
		if($userTypeId == 6 && $erphp_day_times > 0){
			echo '<div class="down-left">今日剩余'.$erphp_vip_name.'免费下载数：<b>'.($erphp_day_times-getSeeCount($current_user->ID)).'</b></div>';
		}elseif($userTypeId == 7 && $erphp_month_times > 0){
			echo '<div class="down-left">今日剩余'.$erphp_vip_name.'免费下载数：<b>'.($erphp_month_times-getSeeCount($current_user->ID)).'</b></div>';
		}elseif($userTypeId == 8 && $erphp_quarter_times > 0){
			echo '<div class="down-left">今日剩余'.$erphp_vip_name.'免费下载数：<b>'.($erphp_quarter_times-getSeeCount($current_user->ID)).'</b></div>';
		}elseif($userTypeId == 9 && $erphp_year_times > 0){
			echo '<div class="down-left">今日剩余'.$erphp_vip_name.'免费下载数：<b>'.($erphp_year_times-getSeeCount($current_user->ID)).'</b></div>';
		}elseif($userTypeId == 10 && $erphp_life_times > 0){
			echo '<div class="down-left">今日剩余'.$erphp_vip_name.'免费下载数：<b>'.($erphp_life_times-getSeeCount($current_user->ID)).'</b></div>';
		}
	}
}

function MBThemes_aff_money($uid){
	global $wpdb;
	$money = 0;
	$ice_ali_money_ref = get_option('ice_ali_money_ref')?get_option('ice_ali_money_ref'):0;
	$ice_ali_money_ref = $ice_ali_money_ref*0.01;
	$ice_ali_money_ref2 = get_option('ice_ali_money_ref2')?get_option('ice_ali_money_ref2'):0;
	$ice_ali_money_ref2 = $ice_ali_money_ref2*0.01;
	if($ice_ali_money_ref){
		$money1 = 0;$money2 = 0;
		$list = $wpdb->get_results("SELECT ID FROM $wpdb->users WHERE father_id=".$uid);
		if($list){
			foreach($list as $value){
				$money1 += erphpGetUserAllXiaofei($value->ID);
				if($ice_ali_money_ref2){
					$list2 = $wpdb->get_results("SELECT ID FROM $wpdb->users WHERE father_id=".$value->ID);
					if($list2){
						foreach($list2 as $value){
							$money2 += erphpGetUserAllXiaofei($value->ID);
						}
					}
				}
			}
		}
		$money = $money1*$ice_ali_money_ref + $money2*$ice_ali_money_ref2;
	}
	return sprintf("%.2f",$money);
}