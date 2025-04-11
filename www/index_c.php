<?php define('__ROOT_DIR__',str_replace('\\','/',dirname(__DIR__)));
include_once(__ROOT_DIR__.'/shipsay/configs/config.ini.php');
if(!empty($cache_homepage))
{
	$_file=__ROOT_DIR__.'/www/index_cache.html';
	if(!file_exists($_file)||$cache_homepage_period<time()-filemtime($_file))
	{
		$site_url=$_SERVER['SERVER_PORT']==443?'https://':'http://';
		$site_url.=$_SERVER['HTTP_HOST'];
		$content=file_get_contents($site_url);
		file_put_contents($_file,$content);
	}
}
?>