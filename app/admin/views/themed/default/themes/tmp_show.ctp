	<div>
		<h2><?php echo $ld['current_template']?></h2>
		<div>
			<table><tr><td>
				<div class="nowthemes">
					<?php
						$curr_template_img = $curr_template['screenshot'];
						if(isset($curr_template['template_style']) && $curr_template['template_style'] != ""){
						$style = explode("_",$curr_template['screenshot']);
						$s_type = explode(".",$style[(sizeof($style)-1)]);
						$s_type[0] = $curr_template['template_style'];
						$style[(sizeof($style)-1)] = implode(".",$s_type);
						$curr_template_img = implode("_",$style);
						}
						//echo "../../".$curr_template['name'].DS."img".DS."themes".DS.$duoyu[$curr_template['name']]["template_img"][$curr_template_img]
						//pr($curr_template);
						//echo $duoyu[$curr_template['name']]["template_img"][$curr_template_img];
						if(isset($duoyu[$curr_template['name']]["template_img"][$curr_template_img]))
							$img_foo=$duoyu[$curr_template['name']]["template_img"][$curr_template_img];
						else{
							$img_foo=array_values($duoyu[$curr_template['name']]["template_img"]);
							$img_foo=$img_foo[0];
						}
						echo @$html->image($img_foo,array('height'=>'190','id'=>'theme_img'))?>
				</div>
				<div>
					<p><?php
							if(isset($curr_template['name'])){ echo $curr_template['description']; }
							if(isset($curr_template['version'])){ echo '&nbsp;'.$curr_template['version']; }
					?></p>
					<p><?php if(isset($curr_template['author']))echo $html->link($curr_template['author'],$curr_template['author_uri'],'',false,false);?></p>
					<p><?php if(isset($curr_template['desc']))echo $curr_template['desc']?>
					<p><?php
							if(isset($curr_template['style']) && sizeof($curr_template['style'])>0){
								if(!empty($curr_template['style'])){
									foreach($curr_template['style'] as $key=>$val){
										if($val != ""){
											if(isset($curr_template['template_style']) && $curr_template['template_style'] == $val){
												echo '<span style="margin:0 5px;">'.$html->image('themes/'.$val.'_over.gif',array("title"=>$val)).'</span>';
											}else{
												if(isset($curr_template['template_style']) ){
													$style = explode("_",$curr_template['screenshot']);
													//$s_type = explode(".",$style[2]);
													$s_type[0] = $val;
													//$style[2] = implode(".",$s_type);
													$this_template_img = implode("_",$style);
										?><span onMouseOver="javascript:onSOver('theme_img','<?php echo $duoyu[$curr_template['name']]['template_img'][$val];?>');" onMouseOut="onSOut('theme_img','<?php echo $duoyu[$curr_template['name']]['template_img'][$val]?>');" onclick="select_style('<?php echo $curr_template['name'];?>','<?php echo $val?>');" style="margin:0 5px;"><?php echo $html->image('themes/'.$val.'.gif',array("title"=>$val));?></span><?php
							}	}	}	}	}	}?></p>
					<?php echo $html->link($ld['preview'],'javascript:;',array("onclick"=>"wopen('{$server_host}?themes={$curr_template['name']}')"),false,false);?>|<?php echo $html->link('css edit','javascript:;',array("onclick"=>"show_css_edit2('{$curr_template['name']}');"),false,false);?>
					<input type='hidden' id='defs' value='<?php echo $curr_template["name"];?>'>
				</div>
			</td></tr></table>
		</div>
	</div>
	<div>
		<h2><?php echo $ld['available_templates']?></h2>
		<div class="imagelistnew">
			<ul><?php
				if(isset($available_templates) && sizeof($available_templates)>0){
					foreach($available_templates as $k=>$themed){
				?><li>
					<blockquote>
						<span class="div_img" onclick="use_theme('<?php echo $themed['BaseTheme']['name'];?>')" title="<?php echo $ld['set_default'];?>">
							<?php echo $html->image("../../".$themed["BaseTheme"]['name'].DS."img".DS."themes".DS."screenshot_".$themed["BaseTheme"]['name'].".png",array('id'=>'theme_img')); ?>
						</span>
						<p class="div_img_name">
							<span class="btn_to_set" onclick="use_theme('<?php echo $themed['BaseTheme']['name'];?>')"><?php echo $ld['set_default'];?></span>
							<?php if(isset($themed["BaseTheme"]['flag'])&& $themed["BaseTheme"]['flag']=="1"){?><span class="btn_status" style="background-image:url(<?php echo $admin_webroot.'/themed'.$admin_webroot.'img/'.'yes.gif' ?>)"><?php echo $ld['available']."： ";?></span><?php } ?>
							<?php echo $html->link($themed["BaseTheme"]['description'],$themed["BaseTheme"]['url'],'',false,false)?>
							<span class="btn_to_uninstall" onclick="deletethemed('<?php echo $themed['BaseTheme']['name'];?>')"><?php echo $ld['uninstall']; ?></span>
						</p>
						<?php if(isset($themed['desc'])) echo '<p>'.$themed["BaseTheme"]['desc'].'</p>';?>
						<p>
						<?php if(isset($themed['style']) && sizeof($themed["BaseTheme"]['style'])>0){
								foreach($themed['style'] as $key=>$val){
									$style = explode("_",$themed["BaseTheme"]['screenshot']);
									$s_type = explode(".",$style[2]);
									$s_type[0] = $val;
									$style[2] = implode(".",$s_type);
									$this_template_img = implode("_",$style);
						?><span onMouseOver="javascript:onSOver('<?php echo $themed['name'];?>','<?php echo $server_host.$this_template_img?>');" onMouseOut="onSOut('<?php echo $themed['name'];?>','<?php echo $server_host.$this_template_img?>');" onclick="select_style('<?php echo $themed['name'];?>','<?php echo $val?>');"><?php echo $html->image('themes/'.$val.'.gif',array("title"=>$val));?></span>
						<?php }	}?>
						</p>
					</blockquote>
				</li><?php
				}	}


				if(isset($fei_templates) && sizeof($fei_templates)>0){
					foreach($fei_templates as $k=>$themed){
						if(!isset($apps['Applications']['APP-THM-'.strtoupper($themed["Template"]['name'])])){ continue;}
				?><li>
					<blockquote>
						<span class="div_img" onclick="use_theme('<?php echo $themed['Template']['name'];?>')" title="<?php echo $ld['set_default'];?>">
							<?php echo $html->image("../../".$themed["Template"]['name'].DS."img".DS."themes".DS."screenshot_".$themed["Template"]['name'].".png",array('id'=>'theme_img')); ?>
						</span>
						<p class="div_img_name">
							<span class="btn_to_set" onclick="use_theme('<?php echo $themed['Template']['name'];?>')"><?php echo $ld['set_default'];?></span>
							<span class="btn_status" style="background-image:url(<?php echo $admin_webroot.'/themed'.$admin_webroot.'img/'.'yes.gif' ?>)"><?php echo $ld['available']."： ";?></span>
							<?php echo $html->link($themed["Template"]['description'],$themed["Template"]['url'],array("target"=>"_blank"),false,false)?>
						</p>
						<p><?php if(isset($themed['desc']))echo $themed["Template"]['desc'];?></p>
						<p>
						<?php if(isset($themed['style']) && sizeof($themed["Template"]['style'])>0){
								foreach($themed['style'] as $key=>$val){
									$style = explode("_",$themed["Template"]['screenshot']);
									$s_type = explode(".",$style[2]);
									$s_type[0] = $val;
									$style[2] = implode(".",$s_type);
									$this_template_img = implode("_",$style);
						?><span onMouseOver="javascript:onSOver('<?php echo $themed['name'];?>','<?php echo $server_host.$this_template_img?>');" onMouseOut="onSOut('<?php echo $themed['name'];?>','<?php echo $server_host.$this_template_img?>');" onclick="select_style('<?php echo $themed['name'];?>','<?php echo $val?>');"><?php echo $html->image('themes/'.$val.'.gif',array("title"=>$val));?></span>
						<?php }	}?>
						</p>
					</blockquote>
				</li><?php
				}	}
			?></ul>
		</div>
	</div>
	<div>
		<h2><?php echo $ld['sale_template']?></h2>
		<div class="imagelistnew">
			<ul><?php
				if(isset($fei_templates) && sizeof($fei_templates)>0){
					foreach($fei_templates as $k=>$themed){
				?><li>
					<blockquote>
						<span class="div_img"
							<?php
								if(isset($apps['Applications']['APP-THM-'.strtoupper($themed["Template"]['name'])])){
									echo "onclick=use_theme('".$themed['Template']['name']."')";
									echo " title='".$ld['set_default']."'";
								}
							?>
						>
							<?php echo $html->image("../../".$themed["Template"]['name'].DS."img".DS."themes".DS."screenshot_".$themed["Template"]['name'].".png",array('id'=>'theme_img')); ?>
						</span>
						<p class="div_img_name">
							<?php if(isset($apps['Applications']['APP-THM-'.strtoupper($themed["Template"]['name'])])){?>
								<span class="btn_to_set" onclick="use_theme('<?php echo $themed['Template']['name'];?>')"><?php echo $ld['set_default'];?></span>
							<?php }?>
							<?php echo $html->link($themed["Template"]['description'],$themed["Template"]['url'],array("target"=>"_blank"),false,false)?>
						</p>
						<p><?php if(isset($themed['desc']))echo $themed["Template"]['desc'];?></p>
						<p><?php if(isset($themed['style']) && sizeof($themed["Template"]['style'])>0){
								foreach($themed['style'] as $key=>$val){
									$style = explode("_",$themed["Template"]['screenshot']);
									$s_type = explode(".",$style[2]);
									$s_type[0] = $val;
									$style[2] = implode(".",$s_type);
									$this_template_img = implode("_",$style);
							?><span onMouseOver="javascript:onSOver('<?php echo $themed['name'];?>','<?php echo $server_host.$this_template_img?>');" onMouseOut="onSOut('<?php echo $themed['name'];?>','<?php echo $server_host.$this_template_img?>');" onclick="select_style('<?php echo $themed['name'];?>','<?php echo $val?>');"><?php echo $html->image('themes/'.$val.'.gif',array("title"=>$val));?></span>
						<?php }	}?></p>
					</blockquote>
				</li><?php
				}	}


				if(isset($fei_thm_base) && sizeof($fei_thm_base)>0){
					foreach($fei_thm_base as $k=>$themed){
				?><li>
					<blockquote>
						<span class="div_img"><?php
								$x=explode(",",$themed["BaseTheme"]['template_img']);
						?><img id="theme_img" src="<?php echo $x[0];?>" /></span>
						<p class="div_img_name">
							<?php echo $html->link($themed["BaseTheme"]['description'],$themed["BaseTheme"]['url'],array('target'=>'_blank'),false,false)?>
						</p>
						<?php if(isset($themed['desc'])) echo '<p>'.$themed["BaseTheme"]['desc'].'</p>';?>
						<p><?php
							if(isset($themed['style']) && sizeof($themed["BaseTheme"]['style'])>0){
								foreach($themed['style'] as $key=>$val){
									$style = explode("_",$themed["BaseTheme"]['screenshot']);
									$s_type = explode(".",$style[2]);
									$s_type[0] = $val;
									$style[2] = implode(".",$s_type);
									$this_template_img = implode("_",$style);
						?><span onMouseOver="javascript:onSOver('<?php echo $themed['name'];?>','<?php echo $server_host.$this_template_img?>');" onMouseOut="onSOut('<?php echo $themed['name'];?>','<?php echo $server_host.$this_template_img?>');" onclick="select_style('<?php echo $themed['name'];?>','<?php echo $val?>');"><?php echo $html->image('themes/'.$val.'.gif',array("title"=>$val));?></span>
						<?php }	}?>
						</p>
					</blockquote>
				</li><?php
				}	}
			?></ul>
		</div>
	</div>
