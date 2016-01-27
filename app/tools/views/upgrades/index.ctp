<script type="text/javascript" src="/tools/js/jquery-1.8.2.min.js"></script>
<div class="upgrade">
	<div class="page_title"><h1>升级提示</h1></div>
	<div class="page_content">
			<?php
				$dbConfig = WWW_ROOT . 'data/database.php';
				$lockFile=WWW_ROOT.'data/install.lock';
				if(!file_exists($dbConfig)){
					echo "<p>文件" . $dbConfig . "不存在！ 提示：不要重命名原来的安装目录，下载最新的源码包，覆盖即可。" . "</p>";
				}else if(file_exists($lockFile)){ ?>
					<ul class="setStatusFile">
	                        	     <li>升级之前请先执行下面的命令：</li>
	                                 <li>windows: 打开命令行，执行<strong>del <?php echo $lockFile; ?></strong></li>
	                                 <li>linux: <strong>rm -rf <?php echo $lockFile; ?>;</strong></li>
	                    	     <li class="text-danger">我已经仔细阅读上面提示且完成上述工作，<a href="javascript:void(0);" onclick="upgrade_check()">继续升级</a></li>
                                   </ul>
			<?php
				}else{
			?>
					<div class="warnningContent">
						<p>升级有危险，请先备份数据库，以防万一。</p>
<pre>
1. 可以通过phpMyAdmin进行备份。
2. 使用mysql命令行的工具。
$> mysqldump -u <span class='text-danger'>username</span> -p <span class='text-danger'>dbname</span> > <span class='text-danger'>filename</span> 
要将上面红色的部分分别替换成对应的用户名和系统的数据库名。
比如： mysqldump -u root -p seevia > seevia.sql
</pre>
					</div>
					<div class="upgrade_action">
						<form action='/tools/upgrades/checkextension' method="POST">
							<input type="hidden" name="user_agree" value='1'>
							<input type="submit" value="确认升级" />
						</form>
					</div>
			<?php
				}
			?>
	</div>
</div>

<style type="text/css">
.upgrade .text-danger{color:red;}
.upgrade div.page_title h1{text-align:center;width:100%;margin:50px auto 30px;}
.upgrade div.page_content h1{text-align:center;width:100%;margin:50px auto 30px;}
.page_content .setStatusFile{width:50%;margin:0 auto;text-align:left;}
.page_content .setStatusFile li:first-child{list-style:none;margin:10px 0px;font-weight:600;}
.page_content .setStatusFile li:last-child{list-style:none;color:red;margin:10px 0px;}

.page_content .warnningContent{width:60%;margin:0 auto;text-align:left;}
.page_content .warnningContent pre{background-color:#f8f8f8;border:1px solid #000;padding:10px;border-radius:5px;}

.upgrade_action{text-align:center;margin:10px auto;padding:10px;}
.upgrade_action input[type='submit']{padding:5px 10px;cursor: pointer;}
</style>
<script type="text/javascript">
function upgrade_check(){
	$.ajax({
		url:'/tools/upgrades/upgrade_check/',
		type:'POST',
		data:{},
		dataType:'json',
		success:function(data){
			if(data.flag=='1'){
				window.location.reload();
			}
		}
	});
}
</script>