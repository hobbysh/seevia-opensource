<?php
    if(!empty($open_wechat_info)){
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $url=urlencode(iconv("gbk","UTF-8",$url));
        $wechat_signature=file_get_contents($server_host.'/opens/signature/?page='.$url);
        
        $wechat_navigations=array();
        if(isset($navigations['T'])){
            foreach($navigations['T'] as $v){
                $wechat_navigations[]=$v['NavigationI18n']['name'];
            }
        }
?>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
    var wechat_shop_description="<?php echo $configs['shop_description']; ?>";
    var wechat_navigations_description="<?php echo implode(" ",$wechat_navigations);  ?>";
    
    var wechat_appId="<?php echo isset($open_wechat_info['app_id'])?$open_wechat_info['app_id']:''; ?>";
    var wechat_signature="<?php echo $wechat_signature; ?>";
    if(typeof(wechat_shareTitle)=="undefined"){var wechat_shareTitle="<?php echo $configs['shop_title']; ?>";}
	if(typeof(wechat_imgUrl)=="undefined"){var wechat_imgUrl="<?php echo $server_host.$configs['shop_logo']; ?>";}
	if(typeof(wechat_lineLink)=="undefined"){var wechat_lineLink="<?php echo $server_host; ?>";}
	if(typeof(wechat_descContent)=="undefined"||wechat_descContent==""){
        if(window.location.href=="<?php echo $server_host; ?>/"){
            var wechat_descContent=wechat_shop_description!=''?wechat_shop_description:wechat_navigations_description;
        }else{
            var wechat_descContent=wechat_navigations_description;
        }
    }
	
    if(wechat_imgUrl=="<?php echo $server_host; ?>"){
        wechat_imgUrl="<?php echo $server_host.$configs['shop_logo']; ?>"
    }
    if(wechat_descContent.length>150){
        wechat_descContent=wechat_descContent.substr(0,150)+"...";
    }
    wx.config({
        debug: false,//这里是开启测试，如果设置为true，则打开每个步骤，都会有提示，是否成功或者失败
        appId: wechat_appId,
        timestamp: "<?php echo strtotime(date('Y-m-d')) ?>",//这个一定要与上面的php代码里的一样。
        nonceStr: "<?php echo strtotime(date('Y-m-d')) ?>",//这个一定要与上面的php代码里的一样。
        signature: wechat_signature,
        jsApiList: [
          // 所有要调用的 API 都要加到这个列表中
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'chooseWXPay'
        ]
    });
            
    wx.ready(function(){
        //分享到朋友圈
        wx.onMenuShareTimeline({
            title: wechat_shareTitle, // 分享标题
            link: wechat_lineLink, // 分享链接
            imgUrl: wechat_imgUrl, // 分享图标
            success: function () { 
                // 用户确认分享后执行的回调函数
            },
            cancel: function () { 
                // 用户取消分享后执行的回调函数
            }
        });
        
        //分享给朋友
        wx.onMenuShareAppMessage({
            title: wechat_shareTitle, // 分享标题
            desc: wechat_descContent, // 分享描述
            link: wechat_lineLink, // 分享链接
            imgUrl: wechat_imgUrl, // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () { 
                // 用户取消分享后执行的回调函数
            }
        });
        
        //分享到QQ
        wx.onMenuShareQQ({
            title: wechat_shareTitle, // 分享标题
            desc: wechat_descContent, // 分享描述
            link: wechat_lineLink, // 分享链接
            imgUrl: wechat_imgUrl, // 分享图标
            success: function () { 
               // 用户确认分享后执行的回调函数
            },
            cancel: function () { 
               // 用户取消分享后执行的回调函数
            }
        });
        
        //分享到腾讯微博
        wx.onMenuShareWeibo({
            title: wechat_shareTitle, // 分享标题
            desc: wechat_descContent, // 分享描述
            link: wechat_lineLink, // 分享链接
            imgUrl: wechat_imgUrl, // 分享图标
            success: function () { 
               // 用户确认分享后执行的回调函数
            },
            cancel: function () { 
                // 用户取消分享后执行的回调函数
            }
        });
    });
</script>
<?php } ?>