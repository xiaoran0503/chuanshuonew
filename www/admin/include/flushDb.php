<?php if(isset($_REQUEST['flushDb']))
{
	require_once('function.php');
	$redis=new SsRedis($redisarr);
	if($redis->ss_flushDb())
	{
		echo "200";
	}
	;
}
elseif(isset($_REQUEST['flushHomePage']))
{
	$_file=str_replace('\\','/',dirname(dirname(__DIR__))).'/index_cache.html';
	if(is_file($_file))unlink($_file);
	$site_url=$_SERVER['SERVER_PORT']==443?'https://':'http://';
	$site_url.=$_SERVER['HTTP_HOST'];
	$content=file_get_contents($site_url);
	if(file_put_contents($_file,$content))
	{
		echo '200';
	}
}
?>