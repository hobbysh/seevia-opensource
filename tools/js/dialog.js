(function($) { 
    $.fn.lee_dialog = function(param){    
    if(typeof param.dialog=='undefined') return; 
    var dialog = param.dialog; 
    var close = param.close || '.close'; 
    var speed = 400; 
    
    var opa=dialog==".dialog_likeit"?0.01:0.35;
    
    var margin_left = '-'+parseInt($(dialog).width()/2)+'px'; 
    var margin_top = '-'+parseInt($(dialog).height()/2)+'px'; 
        var _this = null;
           
    //var bg = '<div class="lee_dialog_bg" style="width:100%;height:'+$(document).height()+'px;background:#000;opacity:'+opa+';filter:alpha(opacity=70);position:absolute;left:0;top:0;z-index:2147483600;display:none;"></div>'; 

    $(dialog).css({'position':'fixed','margin-left':margin_left,'margin-top':margin_top,'left':'50%','top':'50%','display':'none','z-index':2147483601}); 
   
    //$('body').append(bg); 
  	
        $(this).each(function(){ 
  		
        _this = $(this); 
  
        _this.click(function(){ 
            if(!$(dialog).is(':visible')){ 
                //$('.lee_dialog_bg').fadeIn(parseInt(speed/2)); 
                
                $(dialog).css({'top':'50%','opacity':1}); 
                //$(dialog).animate({top:'50%',opacity:1},speed); 
                $(dialog).show();
                $('.lee_dialog_bg').css("display","block");
                $("#forget_error").css("display","none");
            } 
        }); 
  
        $(dialog+' '+close).click(function(){ 
            //$(dialog).animate({top:'65%',opacity:0},speed,false,function(){$(this).hide().css('top','50%');$('.lee_dialog_bg').fadeOut(parseInt(speed/2));});
            $(dialog).hide();
            $("#forget_error").css("display","none");
            $('.lee_dialog_bg').css("display","none");
        }); 
  
  
  
  
    }); 
    } 
    
})(jQuery);
 