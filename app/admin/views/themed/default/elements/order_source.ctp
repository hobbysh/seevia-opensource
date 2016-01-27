<div class="checkbox" id = 'y1'>
		<div class="a1" id="reffer_name"><?php echo $ld['order_reffer']?></div>
		<div class="b1">
			<div class="bb0">
				<?php $i=0; foreach($order_type as $k=>$v){$i++;?>
				<div class='bb00 check<?php echo $i; ?>'>
					<label>
						<input type="checkbox" class = 'checkbox' value="0" name="<?php echo $k; ?>"  ><span><?php echo $order_type_arr[$k];?></span>
					</label>
					<cite>
					<?php foreach ($v as $kk=>$vv) {?>
					<label>
						<input type="checkbox" class = 'checkbox1' value="<?php echo $k.":".$kk; ?>" name="box" <?php if(in_array($k.":".$kk,$type_arr)) echo 'checked';?>><span><?php echo $vv?></span>
					</label>
					<?php }?>
					</cite>
				</div>
				<?php }?>
			</div>
			<div class="bb1">
				<label>
					<input type="checkbox" id="select" class="bb2" />
					<span><?php echo $ld['select_all']?></span></label>
				<input type="button" class="btn" value="<?php echo $ld['submit']?>" onclick="checkbox()" />
			</div>
		</div>
	</div>
<script>
	function checkbox(){
		
	alert("aa");
	var str=document.getElementsByName("box");
	var leng=str.length;
	var chestr="";
	for(i=0;i<leng;i++){
		if(str[i].checked == true)
	  {
	   chestr+=str[i].value+",";
	  };
	};

	return chestr;
	};

	YUI().use('node', function(Y){
	var all=Y.one('.a1'),
	bll=Y.one('.b1'),
	cll=Y.one('.btn'),
	allclick = function(){
	if(bll.getAttribute("class")!="b1"){bll.removeClass('c1');all.removeClass('up');
	}
	else{bll.addClass('c1');all.addClass('up');}
	
	},
	removeclick = function(){
		all.removeClass('up');
		bll.removeClass('c1');
	};
	var e = Y.one('.d1'),
		f = Y.one('.f1'),
		btn = Y.one('.btn1'),
	eclick = function(){
		f.addClass('c1');
	},
	eremove = function(){
		f.removeClass('c1');
	};
	var checkbox = Y.all('.b1 .checkbox'),
		navcheck = Y.all('.check1 .checkbox1'),
		navcheck2 = Y.all('.check2 .checkbox1'),
		navcheck3 = Y.all('.check3 .checkbox1'),
		navcheck4 = Y.all('.check4 .checkbox1'),
		select = Y.one('.b1 #select'),
		checkboxControl = function(){
			Y.Array.indexOf(checkbox.get('checked'), false) < 0 ? select.set('checked', true) : select.set('checked', false);
			var onecheckbox = Y.one('.check1 .checkbox');
			var twocheckbox = Y.one('.check2 .checkbox');
			var threecheckbox = Y.one('.check3 .checkbox');
			var fourcheckbox = Y.one('.check4 .checkbox');
			onecheckbox.get('checked') ? navcheck.set('checked', true) : navcheck.set('checked', false);
			twocheckbox.get('checked') ? navcheck2.set('checked', true) : navcheck2.set('checked', false);
			threecheckbox.get('checked') ? navcheck3.set('checked', true) : navcheck3.set('checked', false);
			fourcheckbox.get('checked') ? navcheck4.set('checked', true) : navcheck4.set('checked', false);
		},
		selectControl = function(){
			select.get('checked') ? checkbox.set('checked', true) : checkbox.set('checked', false);
			select.get('checked') ? navcheck.set('checked', true) : navcheck.set('checked', false);
			select.get('checked') ? navcheck2.set('checked', true) : navcheck2.set('checked', false);
			select.get('checked') ? navcheck3.set('checked', true) : navcheck3.set('checked', false);
			select.get('checked') ? navcheck4.set('checked', true) : navcheck4.set('checked', false);
		};

		checkbox.on('click', checkboxControl);
		select.on('click', selectControl);
		cll.on('click', removeclick);
		all.on('click', allclick);

	});
</script>