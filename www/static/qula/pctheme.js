function Go(a){window.location=a}
function shezhi(){
    document.writeln('')
}

function yuedu(){
    function a(){
        var a=-parseInt($(".mlfy_main").css("width"))/2-60,b=a+70+"px";$(".mlf11y_main_l").css("margin-left",a+"px"),$(".mlfy_main_r").css("margin-right",a+"px"),$(".mlfy_main_sz").css("margin-left",b)
    }
    function b(){$(".mlfy_main_sz").removeClass("hover"),$(".mlfy_main_l i").removeClass("hover")}
    function c(){
        var a,b,c;void 0!=$.cookie("xszjsz")&&(a=$.cookie("xszjsz").split(","),$("body").removeClass().addClass(a[0]),b=a[0].substring(2,3)-1,$(".mlfy_main_sz.b2 ul li i").eq(b).addClass("hover").siblings().removeClass("hover"),c=a[1].substring(2,3)-1,$(".mlfy_main_sz.b2 ul li .zt").eq(c).addClass("hover").siblings().removeClass("hover"),$("#mlfy_main_text").removeClass().addClass(a[1]),$(".mlfy_main_sz.b2 ul li .dxc").text(a[2]),$("#mlfy_main_text").css("font-size",a[2]+"px"),$(".mlfy_main_sz.b2 ul li .kdc").text(a[3]),$(".bar,.mlfy_main,.mlfy_add,.mlfy_page").css("width",a[3]+"px"),e=$.inArray(a[3],d))
    }
    var d,e,f,g,h,i,j,k;
    !function(a){
        "function"==typeof define&&define.amd?define(["jquery"],a):a("object"==typeof exports?require("jquery"):jQuery)
    }(function(a){
            function b(a){
                return h.raw?a:encodeURIComponent(a)
            }
    function c(a){return h.raw?a:decodeURIComponent(a)}
    function d(a){return b(h.json?JSON.stringify(a):String(a))}
    function e(a){0===a.indexOf('"')&&(a=a.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{return a=decodeURIComponent(a.replace(g," ")),h.json?JSON.parse(a):a}catch(a){}}
    function f(b,c){var d=h.raw?b:e(b);return a.isFunction(c)?c(d):d}
    var g=/\+/g,h=a.cookie=function(e,g,i){var j,k,l,m,n,o,p,q,r;if(void 0!==g&&!a.isFunction(g))return i=a.extend({},h.defaults,i),"number"==typeof i.expires&&(j=i.expires,k=i.expires=new Date,k.setTime(+k+864e5*j)),document.cookie=[b(e),"=",d(g),i.expires?"; expires="+i.expires.toUTCString():"",i.path?"; path="+i.path:"",i.domain?"; domain="+i.domain:"",i.secure?"; secure":""].join("");for(l=e?void 0:{},m=document.cookie?document.cookie.split("; "):[],n=0,o=m.length;o>n;n++){
        if(p=m[n].split("="),q=c(p.shift()),r=p.join("="),e&&e===q){l=f(r,g);break}
    e||void 0===(r=f(r))||(l[q]=r)
    }
    return l};
    h.defaults={},a.removeCookie=function(b,c){return void 0!==a.cookie(b)&&(a.cookie(b,"",a.extend({},c,{expires:-1})),!a.cookie(b))}}),d=["640","800","990","1200","1400"],e=2,void 0!=$.cookie("xszjsz")&&(f=$.cookie("xszjsz").split(","),g=$.inArray(f[3],d),e=g),c(),a(),$(".szk").click(function(){$(".mlfy_main_sz,.szk").addClass("hover").siblings(".mlfy_main_sz").removeClass("hover")}),h=["bg1","bg2","bg3","bg4","bg5","bg6","bg7","bg8"],$(".mlfy_main_sz.b2 ul li i").click(function(){$(this).addClass("hover").siblings().removeClass("hover");var a=$(this).index()-1;$("body").removeClass().addClass(h[a])}),i=["zt1","zt2","zt3","zt4","zt5","zt6"],$(".mlfy_main_sz.b2 ul li .zt").click(function(){$(this).addClass("hover").siblings().removeClass("hover");var a=$(this).index()-1;$("#mlfy_main_text").removeClass().addClass(i[a])}),$(".mlfy_main_sz.b2 ul li .dxl").click(function(){var a=parseInt($(".mlfy_main_sz.b2 ul li .dxc").text());a>12&&(a-=2,$(".mlfy_main_sz.b2 ul li .dxc").text(a),$("#mlfy_main_text").css("font-size",a))}),$(".mlfy_main_sz.b2 ul li .dxr").click(function(){var a=parseInt($(".mlfy_main_sz.b2 ul li .dxc").text());48>a&&(a+=2,$(".mlfy_main_sz.b2 ul li .dxc").text(a),$("#mlfy_main_text").css("font-size",a))}),$(".mlfy_main_sz.b2 ul li .kdl").click(function(){e>0&&(e-=1,$(".bar,.mlfy_main,.mlfy_add,.mlfy_page").css("width",d[e]+"px"),$(".kdc").text(d[e]),a())}),$(".mlfy_main_sz.b2 ul li .kdr").click(function(){4>e&&(e+=1,$(".bar,.mlfy_main,.mlfy_add,.mlfy_page").css("width",d[e]+"px"),$(".kdc").text(d[e]),a())}),$(".mlfy_main_sz.b2 ul li .yd").click(function(){$(this).addClass("hover").siblings().removeClass("hover")}),$(".mlfy_main_sz.b2 ul li .zd").click(function(){"开启"==$(this).text()?($(this).text("关闭").animate({left:"0px"}),$(this).parent().removeClass("on").addClass("off")):($(this).text("开启").animate({left:"20px"}),$(this).parent().removeClass("off").addClass("on"))}),$(".mlfy_main_sz.b2 .red-btn").click(function(){$.cookie("xszjsz",null,{expires:7,path:"/"});var a=[];a.push($("body").attr("class")),a.push($("#mlfy_main_text").attr("class")),a.push($(".mlfy_main_sz.b2 ul li .dxc").text()),a.push($(".mlfy_main_sz.b2 ul li .kdc").text()),a.push($("#zd_bg").attr("class")),$.cookie("xszjsz",a.join(","),{expires:7,path:"/"}),b()}),$(".mlfy_main_sz.b2 .grey-btn,.close").click(function(){void 0==$.cookie("xszjsz")?($("body").removeClass().addClass("bg6"),$(".mlfy_main_sz.b2 ul li i").eq(0).addClass("hover").siblings().removeClass("hover"),$(".mlfy_main_sz.b2 ul li .zt").eq(0).addClass("hover").siblings().removeClass("hover"),$("#mlfy_main_text").removeClass(),$(".mlfy_main_sz.b2 ul li .dxc").text("20"),$("#mlfy_main_text").css("font-size","20px"),$(".bar,.mlfy_main,.mlfy_add,.mlfy_page").css("width","990px"),$(".kdc").text("990"),e=2,b(),a()):(b(),c(),a())}),j=$(".mlfy_add a").eq(2).attr("href"),$(".mlfy_main_r .a1").attr("href",j+"#l3"),k=document.getElementById("TextContent").innerHTML,k=k.replace(new RegExp("&nbsp;&nbsp;&nbsp;&nbsp;","gi"),"<p>").replace(new RegExp("<br><br>","gi"),"</p>").replace(new RegExp("<br>\n<br>","gi"),"</p>"),document.getElementById("TextContent").innerHTML=k
}
