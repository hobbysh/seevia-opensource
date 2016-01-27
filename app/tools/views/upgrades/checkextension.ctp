<script type="text/javascript" src="/tools/js/jquery-1.8.2.min.js"></script>
<div class="upgrade">
	<div class="page_title"><h1>检查版本</h1></div>
	<div class="page_content">
		<table>
			<tr>
				<th>当前版本</th>
				<td><?php echo $version_config; ?></td>
			</tr>
			<tr>
				<th>最新版本</th>
				<td><?php echo Version; ?></td>
			</tr>
			<tr>
				<th></th>
				<td><?php if(empty($version_config)){
						echo "当前版本不确定";
					}else if($version_config==Version){
						echo "当前已是最新版本";
					}else{ ?>
						<input type="button" value="升级" onclick="upgrade_action('<?php echo $version_config; ?>')" />
					<?php } ?></td>
			</tr>
		</table>
	</div>
</div>
<style type="text/css">
.upgrade div.page_title h1{text-align:center;width:100%;margin:50px auto 30px;}
.upgrade div.page_content h1{text-align:center;width:100%;margin:50px auto 30px;}

.page_content table{width:50%;margin:0 auto;}
.page_content table th{width:30%;text-align:right;}
.page_content table td{text-align:left;}
.page_content table th,.page_content table td{padding:5px;}
</style>

<script type="text/javascript">
function upgrade_action(version){
	if(version.trim()==""){
		return false;
	}
	$.ajax({
		url:'/tools/upgrades/upgrade_action/',
		type:'POST',
		data:{'web_version':version},
		dataType:'json',
		success:function(data){
			if(data.flag=='1'){
				alert(data.message);
				window.location.href="http://"+window.location.host;
			}else{
				alert(data.message);
			}
		}
	});
}
</script>
