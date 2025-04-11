function gotop(){$('body,html').animate({scrollTop:0},600);}
function gofooter(){$('body,html').animate({scrollTop:$(document).height()},600);}
function menu_toggle() {
    $('nav, .search').slideToggle();
    $('#menu-btn').text() == '菜单' ? $('#menu-btn').text('关闭') : $('#menu-btn').text('菜单')
}
function setEcho(){$("img.lazy").lazyload({effect : "fadeIn"})}
function search(){
    document.write('<form class="flex" name="t_frmsearch" method="post" action="/search/"><input id="searchkey" type="text" name="searchkey" class="search_input" placeholder="书名或作者,请您少字也别错字" autocomplete="off"><button type="submit" name="Submit" class="search_btn" title="搜索"> 搜 索 </button></form>');
}