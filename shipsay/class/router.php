<?php $fake_fullstr='quanben';
$fake_rankstr='rank';
$fake_tag='/tag/{tag}/{pid}.html';
if(!empty($authcode))$dbarr['host']=$authcode;
header("Content-type: text/html; charset=utf-8");
define('__THEME_DIR__',__ROOT_DIR__.'/themes/'.$theme_dir);
if(!defined('SITE_URL'))define('SITE_URL',$_SERVER['HTTP_HOST']);
if($use_gzip&&!headers_sent()&&extension_loaded("zlib")&&strstr($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip"))
{
	ini_set('zlib.output_compression','On');
	ini_set('zlib.output_compression_level','9');
}
spl_autoload_register('ss_autoload');
if(empty($site_url))
{
	$site_url=$_SERVER['SERVER_PORT']==443?'https://':'http://';
	$site_url.=$_SERVER['HTTP_HOST'];
}
if(isset($_REQUEST['show_shipsay_version']))
{
	print_r($ShipSayVersion);
	die();
}
$use_code=0;
$year=date('Y');
$is_sortid=strpos($fake_sort_url,'{sortid}')!==false?true:false;
$is_acode=strpos($fake_info_url,'{acode}')!==false?true:false;
if($use_redis)
{
	if(!extension_loaded('redis'))
	{
		die('php的"Redis扩展"未正确安装');
	}
	else
	{
		$redis=new SsRedis($redisarr);
	}
}
$dbarr=array_merge(['pre'=>$sys_ver<5?'jieqi_':'shipsay_','words'=>$sys_ver<2.4?'size':'words','is_multiple'=>$is_multiple,'sortarr'=>$sortarr],$dbarr);
$db=new Db($dbarr);
$articlecode_str=$sys_ver<2.4?'':'articlecode,backupname,ratenum,ratesum,';
$rico_sql='SELECT '.$articlecode_str.$dbarr['words'].',articleid,articlename,intro,author,sortid,fullflag,display,lastupdate,imgflag,allvisit,allvote,goodnum,keywords,lastchapter,lastchapterid FROM '.$dbarr['pre'].'article_article WHERE display <> 1 AND '.$dbarr['words'].' >= 0 ';
if(!isset($is_oneload))$is_oneload=0;
$allbooks_url=preg_replace('/({sortid}|{sortcode}).*/i','',$fake_sort_url);
$full_allbooks_url='/'.$fake_fullstr.$allbooks_url;
if(preg_match('/^\/json\/([\s\S]+)\.php/i',$source_uri,$match_json))
{
	require_once __ROOT_DIR__.'/shipsay/json/ss_json_api.php';
	exit;
}
if($uri=='/')
{
	require_once __ROOT_DIR__.'/shipsay/app/home.php';
	exit;
}
if(strpos($uri,$allbooks_url)!==false||preg_match(Url::sort2real($fake_sort_url),$uri))
{
	require_once __ROOT_DIR__.'/shipsay/app/category.php';
	exit;
}
$tag_first_page=preg_replace('/{tag}.*$/i','',$fake_tag);
if(preg_match(Url::tag2real($fake_tag),urldecode($uri),$matches)||strpos($uri,$tag_first_page)===0)
{
	require_once __ROOT_DIR__.'/shipsay/app/tag.php';
	exit;
}
if(decide_uri($uri,$fake_top))
{
	require_once __ROOT_DIR__.'/shipsay/app/top.php';
	exit;
}
if(preg_match('/\/'.$fake_rankstr.'\/?([^\/]*)\/?/i',$uri,$matches))
{
	require_once __ROOT_DIR__.'/shipsay/app/rank.php';
	exit;
}
if(decide_uri($uri,'/search'))
{
	require_once __ROOT_DIR__.'/shipsay/app/search.php';
	exit;
}
if(preg_match('/^\/author\/(.+?)\/?$/i',$uri,$matches))
{
	require_once __ROOT_DIR__.'/shipsay/app/author.php';
	exit;
}
if(decide_uri($uri,$fake_recentread))
{
	require_once __ROOT_DIR__.'/shipsay/app/recentread.php';
	exit;
}
if(preg_match('/^\/api\/(.+?)\.php/i',$uri,$matches))
{
	require_once __ROOT_DIR__.'/shipsay/include/'.$matches[1].'.php';
	exit;
}
if(decide_uri($uri,'/login'))
{
	require_once __ROOT_DIR__.'/shipsay/app/user/login.php';
	exit;
}
if(decide_uri($uri,'/logout'))
{
	require_once __ROOT_DIR__.'/shipsay/app/user/logout.php';
	exit;
}
if(decide_uri($uri,'/register'))
{
	require_once __ROOT_DIR__.'/shipsay/app/user/register.php';
	exit;
}
if(decide_uri($uri,'/addbookcase'))
{
	require_once __ROOT_DIR__.'/shipsay/app/user/addbookcase.php';
	exit;
}
if(decide_uri($uri,'/delbookcase'))
{
	require_once __ROOT_DIR__.'/shipsay/app/user/delbookcase.php';
	exit;
}
if(decide_uri($uri,'/bookcase'))
{
	require_once __ROOT_DIR__.'/shipsay/app/user/bookcase.php';
	exit;
}
if(preg_match(Url::fake2real($fake_chapter_url),$uri,$matches))
{
	require_once __ROOT_DIR__.'/shipsay/app/reader.php';
	exit;
}
if(strpos($uri,'search')===false&&preg_match(Url::fake2real($fake_info_url),$uri,$matches))
{
	require_once __ROOT_DIR__.'/shipsay/app/info.php';
	exit;
}
if(preg_match(Url::indexlist2real($fake_indexlist),$uri,$matches))
{
	require_once __ROOT_DIR__.'/shipsay/app/indexlist.php';
	exit;
}
if($is_langtail===1)
{
	if(strpos($uri,'search')===false&&preg_match(Url::fake2real($fake_langtail_info),$uri,$matches))
	{
		require_once __ROOT_DIR__.'/shipsay/app/info_langtail.php';
		exit;
	}
	if(preg_match(Url::indexlist2real($fake_langtail_indexlist),$uri,$matches))
	{
		require_once __ROOT_DIR__.'/shipsay/app/indexlist_langtail.php';
		exit;
	}
}
Url::ss_errpage();
function decide_uri($uri,$fake_url)
{
	return rtrim($uri,'/')===rtrim($fake_url,'/');
}
function ss_autoload($classname)
{
	if(!class_exists($classname))require __ROOT_DIR__.'/shipsay/class/'.$classname.'.php';
}
?>