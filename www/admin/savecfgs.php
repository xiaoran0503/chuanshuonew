<?php if(session_status()!==PHP_SESSION_ACTIVE)session_start();
switch($_REQUEST['do'])
{
	case "base":$saveStr="<?php if (!defined('__ROOT_DIR__')) exit;\r\nerror_reporting(0);\r\ndate_default_timezone_set('Asia/ChongQing');\r\ninclude_once __ROOT_DIR__ . '/shipsay/version.php';\r\ndefine('SITE_NAME', '".$_POST['sitename']."');\r\n\$dbarr = [\r\n     'host' => '".$_POST['dbhost']."'\r\n    ,'port' => '".$_POST['dbport']."'\r\n    ,'name' => '".$_POST['dbname']."'\r\n    ,'user' => '".$_POST['dbuser']."'\r\n    ,'pass' => '".$_POST['dbpass']."'\r\n    ,'pconnect' => ".$_POST['db_pconnect']."\r\n];\r\n\$authcode = '".$_POST['authcode']."';\r\n\r\n\$sys_ver = '".$_POST['sys_ver']."';\r\n\$root_dir = '".str_replace('\\','/',$_POST['root_dir'])."';\r\n\$txt_url = '".$_POST['txt_url']."';              \r\n\$remote_img_url = '".$_POST['remote_img_url']."';\r\n\$local_img = ".$_POST['local_img'].";            \r\n\$is_attachment = ".$_POST['is_attachment'].";    \r\n\$att_url = '".$_POST['att_url']."';              \r\n\$site_url = '".$_POST['site_url']."';\r\n\$use_gzip = ".$_POST['use_gzip'].";\r\n\$enable_down = ".$_POST['enable_down'].";\r\n\$is_ft = ".$_POST['is_ft']."; \r\n\r\n\$theme_dir = '".$_POST['theme_dir']."';\r\n\$is_3in1 = ".$_POST['is_3in1'].";\r\n\$commend_ids = '".$_POST['commend_ids']."';\r\n\$category_per_page = ".$_POST['category_per_page'].";\r\n\$readpage_split_lines = ".$_POST['readpage_split_lines'].";\r\n\$vote_perday = ".$_POST['vote_perday'].";\r\n\$count_visit = ".$_POST['count_visit'].";\r\n\r\n\$fake_info_url = '".$_POST['fake_info_url']."';      \r\n\$fake_chapter_url = '".$_POST['fake_chapter_url']."';\r\n\$use_orderid = '".$_POST['use_orderid']."';\r\n\$fake_sort_url = '".$_POST['fake_sort_url']."';      \r\n\$fake_top = '".@$_POST['fake_top']."';        \r\n\$fake_recentread = '".$_POST['fake_recentread']."';\r\n\$fake_indexlist = '".$_POST['fake_indexlist']."';  \r\n\$per_indexlist = ".$_POST['per_indexlist'].";\r\n\r\n//分类设置\r\n".$_POST['sortarr']."\r\n\r\n//redis缓存设置\r\n\$use_redis = ".$_POST['use_redis'].";\r\n\$redisarr = [\r\n     'host' => '".$_POST['redishost']."' \r\n    ,'port' => '".$_POST['redisport']."' \r\n    ,'db' => '".$_POST['redisdb']."'\r\n    ,'pass' => '".$_POST['redispass']."'\r\n];\r\n\$home_cache_time = ".$_POST['home_cache_time'].";        \r\n\$info_cache_time = ".$_POST['info_cache_time'].";        \r\n\$category_cache_time = ".$_POST['category_cache_time'].";\r\n\$cache_time = ".$_POST['cache_time'].";                  \r\n\r\n//ID混淆\r\n\$is_multiple = ".$_POST['is_multiple'].";\r\n\$ss_newid = '".$_POST['ss_newid']."';\r\nfunction ss_newid(\$id){\r\n    return ".$_POST['ss_newid'].";\r\n}\r\n\$ss_sourceid = '".$_POST['ss_sourceid']."';\r\nfunction ss_sourceid(\$id){\r\n    return ".$_POST['ss_sourceid'].";\r\n}\r\n\r\n\$is_langtail = ".$_POST['is_langtail'].";\r\n\$langtail_catch_cycle = ".$_POST['langtail_catch_cycle'].";\r\n\$langtail_cache_time = ".$_POST['langtail_cache_time'].";\r\n\$fake_langtail_info = '".$_POST['fake_langtail_info']."';\r\n\$fake_langtail_indexlist = '".$_POST['fake_langtail_indexlist']."';\r\n\r\n";
	if(ss_writefile($_POST['config_file'],$saveStr))echo "200";
	break;
	case "report":$saveStr="<?php\r\n\$ShipSayReport['on'] = ".$_POST['report_on']."; \r\n\$ShipSayReport['delay'] = ".intval($_POST['report_delay'])."; \r\n";
	if(ss_writefile($_POST['config_file'],$saveStr))echo "200";
	break;
	case "filter":$saveStr="<?php\r\n\$ShipSayFilter['is_filter'] = ".$_POST['is_filter']."; \r\n\$ShipSayFilter['filter_ini'] = '\r\n".str_replace("'","\'",$_POST['filter_ini'])."\r\n';";
	if(ss_writefile($_POST['config_file'],$saveStr))echo "200";
	break;
	case "link":$saveStr="<?php\r\n\$ShipSayLink['is_link'] = ".$_POST['is_link']."; \r\n\$ShipSayLink['link_ini'] = ' \r\n".str_replace("'","\'",$_POST['link_ini'])."\r\n';";
	if(ss_writefile($_POST['config_file'],$saveStr))echo "200";
	break;
	case "article":$saveStr="<?php\r\n\$ShipSayRoot['folder'] = '".str_replace('\\','/',$_POST['root_folder'])."';      //服务器端硬盘根目录\r\n";
	if(ss_writefile($_POST['config_file'],$saveStr))echo "200";
	break;
	case "count":$saveStr="<?php\r\n\r\n\$count[1] = [\r\n    'enable' => ".$_POST['count1_enable'].",\r\n    'html' => '".str_replace("'","\'",$_POST['count1_html'])."'\r\n];\r\n\$count[2] = [\r\n    'enable' => ".$_POST['count2_enable'].",\r\n    'html' => '".str_replace("'","\'",$_POST['count2_html'])."'\r\n];\r\n\$count[3] = [\r\n    'enable' => ".$_POST['count3_enable'].",\r\n    'html' => '".str_replace("'","\'",$_POST['count3_html'])."'\r\n];\r\n";
	if(ss_writefile($_POST['config_file'],$saveStr))echo "200";
	break;
	case "search":$saveStr="<?php\r\n\r\n\$ShipSaySearch = [\r\n    'delay' => ".$_POST['delay']." \r\n    ,'limit' => ".$_POST['limit']."\r\n    ,'min_words' => ".$_POST['min_words']."\r\n    ,'cache_time' => ".$_POST['cache_time']."\r\n    ,'is_record' => ".intval($_POST['is_record'])."\r\n];\r\n";
	if(ss_writefile($_POST['config_file'],$saveStr))echo "200";
	break;
	case "app":$saveStr="<?php\r\n\r\n\$commend_ids = '".$_POST['commend_ids']."'; \r\n\$qrcode = '".$_POST['qrcode']."'; \r\n\$swipers = [\r\n//轮播图\r\n".trim($_POST['swipers'])."\r\n//轮播图结束\r\n];\r\n\$adsense = '".$_POST['adsense']."'; \r\n\r\n\$key = '".$_POST['key']."';\r\n\$auth_mode = ".$_POST['auth_mode']."; \r\n\$json_cache_time = ".$_POST['json_cache_time']."; \r\n\$uni_app_id = '".$_POST['uni_app_id']."'; \r\n\$current_ver = '".$_POST['current_ver']."'; \r\n\$update_note = '".$_POST['update_note']."'; \r\n\$download_url = '".$_POST['download_url']."'; \r\n\r\n";
	if(ss_writefile($_POST['config_file'],$saveStr))echo "200";
	break;
}
function ss_writefile($file_name,$data)
{
	if(!is_dir(dirname($file_name)))mkdir(dirname($file_name),0777,true);
	@chmod($file_name,511);
	return file_put_contents($file_name,$data);
}
?>