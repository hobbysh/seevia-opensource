lastScrollY = 0;
var graySrc = false;
var InterTime = 1;
var maxWidth = -1;
var minWidth = -128;
var numInter = 8;
var BigInter;
var SmallInter;
var o = null;
var i = 0;
kuzhan = function(id, _top, _right) {
    var me = id.charAt ? document.getElementById(id) : id, d1 = document.body, d2 = document.documentElement;
    d1.style.height = d2.style.height = '100%'; 
    me.style.top = _top ? _top + 'px' : 0; 
    me.style.right = '0px' ; 
    //me.style.right = _right + "px"; 
    $(me).find('div.services').hide();
    me.style.position = 'absolute';
    me.style.display = 'block';
//    if(navigator.userAgent.indexOf("iPad") != -1){  
//	me.style.display = 'none';   }
	 var system ={
	   win : false,
	   mac : false,
	   xll : false
	   };
	//检测平台
   var p = navigator.platform;
   system.win = p.indexOf("Win") == 0;
   system.mac = p.indexOf("Mac") == 0;
   system.x11 = (p == "X11") || (p.indexOf("Linux") == 0);
   //跳转语句
   if(!(system.win||system.mac||system.xll)){
   	me.style.display = 'none';  
   }else{
   me.style.display = 'block';  
   }
    setInterval(function() { me.style.top = parseInt(me.style.top) + (Math.max(d1.scrollTop, d2.scrollTop) + _top - parseInt(me.style.top)) * 0.1 + 'px'; }, 10 + parseInt(Math.random() * 20));
    return arguments.callee;
};
$(document).ready(function() {


    o = document.getElementById("kuzhan");
    i = parseInt(o.style.left);

    kuzhan('kuzhan', 390, -128);
});

function Big() {
    if (parseInt(o.style.right) < maxWidth) {
        i = parseInt(o.style.right);
        i += numInter;
        //o.style.right = i + "px";
        if (i == maxWidth)
            clearInterval(BigInter);
    }
    $(o).find('div.services').show();
    if (!graySrc) {
        $(o).find("img").each(function() {
            $(this).attr("src", $(this).attr("Original"));
        });
        graySrc = true;
    }
}
function toBig() {
    clearInterval(SmallInter);
    clearInterval(BigInter);
    BigInter = setInterval(Big, InterTime);
}
function Small() {
    if (parseInt(o.style.right) > minWidth) {
        i = parseInt(o.style.right);
        i -= numInter;
       // o.style.right = i + "px";
        if (i == minWidth)
            clearInterval(SmallInter);
    }
    $(o).find('div.services').hide();
}
function toSmall() {
    clearInterval(SmallInter);
    clearInterval(BigInter);
    SmallInter = setInterval(Small, InterTime);

}