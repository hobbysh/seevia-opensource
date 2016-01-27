<p class="action-span"><?php echo $html->link($ld['circle_image_list'],"/flashs/",'',false,false);?></p>
<?php echo $form->create('flashs',array('action'=>'type_edit/'.$type."/".$type_id."/"));?> <input name="data[Flashe][id]" type="hidden" value="<?php echo isset($flash_info['Flashe']['id'])?$flash_info['Flashe']['id']:'';?>">
<div id="tablemain" class="tablemain">
	<div>
		<h2><?php echo $ld['flash_circle_image_parameters']?></h2>
		<div class="show_border">
			<table class="alonetable doublerow">
				<tr>
					<th><?php echo $ld['flash_rounded']?></th>
					<td><input type="text" name="data[Flashe][roundcorner]" value="<?php echo $flash_info['Flashe']['roundcorner'];?>"/></td>
					<th><?php echo $ld['auto_play_time']?></th>
					<td><input type="text" name="data[Flashe][autoplaytime]" value="<?php echo $flash_info['Flashe']['autoplaytime'];?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['high_quality']?></th>
					<td><input type="text" name="data[Flashe][isheightquality]" value="<?php echo $flash_info['Flashe']['isheightquality'];?>" /></td>
					<th><?php echo $ld['mixed_mode']?></th>
					<td><input type="text" name="data[Flashe][blendmode]" value="<?php echo $flash_info['Flashe']['blendmode'];?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['intertemporal']?></th>
					<td><input type="text" name="data[Flashe][transduration]" value="<?php echo $flash_info['Flashe']['transduration'];?>" /></td>
					<th><?php echo $ld['window_open']?></th>
					<td><input type="text" name="data[Flashe][windowopen]" value="<?php echo $flash_info['Flashe']['windowopen'];?>" /></td>
				</tr>
				<tr>
					<th>btnsetmargin</th>
					<td><input type="text" name="data[Flashe][btnsetmargin]" value="<?php echo $flash_info['Flashe']['btnsetmargin'];?>" /></td>
					<th><?php echo $ld['distance']?></th>
					<td><input type="text" name="data[Flashe][btndistance]" value="<?php echo $flash_info['Flashe']['btndistance'];?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['title_color']?></th>
					<td><input type="text" name="data[Flashe][titlebgcolor]" value="<?php echo $flash_info['Flashe']['titlebgcolor'];?>" /></td>
					<th><?php echo $ld['title_text_color']?></th>
					<td><input type="text" name="data[Flashe][titletextcolor]" value="<?php echo $flash_info['Flashe']['titletextcolor'];?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['title_transparency']?></th>
					<td><input type="text" name="data[Flashe][titlebgalpha]" value="<?php echo $flash_info['Flashe']['titlebgalpha'];?>" /></td>
					<th><?php echo $ld['move_title_time']?></th>
					<td><input type="text" name="data[Flashe][titlemoveduration]" value="<?php echo $flash_info['Flashe']['titlemoveduration'];?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['button_transparency']?></th>
					<td><input type="text" name="data[Flashe][btnalpha]" value="<?php echo $flash_info['Flashe']['btnalpha'];?>" /></td>
					<th><?php echo $ld['button_text_color']?></th>
					<td><input type="text" name="data[Flashe][btntextcolor]" value="<?php echo $flash_info['Flashe']['btntextcolor'];?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['button_default_color']?></th>
					<td><input type="text" name="data[Flashe][btndefaultcolor]" value="<?php echo $flash_info['Flashe']['btndefaultcolor'];?>" /></td>
					<th><?php echo $ld['button_hover_color']?></th>
					<td><input type="text" name="data[Flashe][btnhovercolor]" value="<?php echo $flash_info['Flashe']['btnhovercolor'];?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['button_key_color']?></th>
					<td><input type="text" name="data[Flashe][btnfocuscolor]" value="<?php echo $flash_info['Flashe']['btnfocuscolor'];?>" /></td>
					<th><?php echo $ld['image_mode']?></th>
					<td><input type="text" name="data[Flashe][changimagemode]" value="<?php echo $flash_info['Flashe']['changimagemode'];?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['display_button']?></th>
					<td><input type="text" name="data[Flashe][isshowbtn]" value="<?php echo $flash_info['Flashe']['isshowbtn'];?>" /></td>
					<th><?php echo $ld['show_title']?></th>
					<td><input type="text" name="data[Flashe][isshowtitle]" value="<?php echo $flash_info['Flashe']['isshowtitle'];?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['zoom_mode']?></th>
					<td><input type="text" name="data[Flashe][scalemode]" value="<?php echo $flash_info['Flashe']['scalemode'];?>" /></td>
					<th><?php echo $ld['transform']?></th>
					<td><input type="text" name="data[Flashe][transform]" value="<?php echo $flash_info['Flashe']['transform'];?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['displayed_about']?></th>
					<td><input type="text" name="data[Flashe][isshowabout]" value="<?php echo $flash_info['Flashe']['isshowabout'];?>" /></td>
					<th><?php echo $ld['title_font']?></th>
					<td><input type="text" name="data[Flashe][titlefont]" value="<?php echo $flash_info['Flashe']['titlefont'];?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['width']?></th>
					<td><input type="text" name="data[Flashe][width]" value="<?php echo $flash_info['Flashe']['width'];?>" /></td>
					<th><?php echo $ld['height']?></th>
					<td><input type="text" name="data[Flashe][height]" value="<?php echo $flash_info['Flashe']['height'];?>" /></td>
				</tr>
			</table>
		</div>
		<div class="btnouter">
			<input type="submit" value="<?php echo $ld['d_submit']?>" /> <input type="reset" value="<?php echo $ld['d_reset']?>" />
		</div>
	</div>
	<!--<div class="btnouter"><input type="submit" value="<?php echo $ld['d_submit']?>" /><input type="reset" value="<?php echo $ld['d_reset']?>" /></div>-->
</div>
<?php echo $form->end();?> 