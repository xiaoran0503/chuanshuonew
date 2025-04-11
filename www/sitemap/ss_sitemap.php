<?php
/**
 * 船说 sitemap自动生成工具 V3.7.2
 * 
 * 自动读取链接格式,ID转换规则等,访问时;显示标准的XML格式的网址
 * 
 * 提交为xml后缀文件,一次提交,永久受益.  
 * 
 * 访问路径: http://你的域名/sitemap/sitemap.xml
 * 
 */

$per_page = 5000; // 一个索引文件,多少本


/***** 以下代码请勿修改. *******/
header("Cache-Control: no-store, no-cache, must-revalidate");date_default_timezone_set('Asia/Chongqing');set_time_limit(300);define('__ROOT_DIR__',dirname(dirname(__DIR__)));require_once __ROOT_DIR__.'/shipsay/configs/config.ini.php';spl_autoload_register('ss_autoload');if(!empty($authcode))$dbarr['host']=$authcode;$is_sortid = strpos($fake_sort_url, '{sortid}') !== false ? true : false;$articlecode_str=$sys_ver<2.4?'':'articlecode,';$dbarr=array_merge(['pre'=>$sys_ver<5?'jieqi_':'shipsay_','words'=>$sys_ver<2.4?'size':'words','sortarr'=>$sortarr,'is_multiple'=>$is_multiple],$dbarr);$db=new Db($dbarr);$xml='<?xml version="1.0"  encoding="UTF-8" ?>'."\r\n";if(empty($site_url)){$site_url=$_SERVER['SERVER_PORT']==443?'https://':'http://';$site_url.=$_SERVER['HTTP_HOST'];}if(isset($_GET['page'])){$xml.='<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
      http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'."\r\n";if(intval($_GET['page'])==0){$static_url[]=$site_url;foreach(Sort::ss_sorthead()as $v){$static_url[]=$site_url.$v['sorturl'];}$currentdate=date('Y-m-d').'T'.date('H:i:s');for($i=0;$i<count($static_url);$i++){$xml.='<url>'."\r\n";$xml.='  <loc>'.$static_url[$i].'</loc>'."\r\n";$xml.='  <lastmod>'.date('Y-m-d H:i:s').'</lastmod>'."\r\n";$xml.='  <changefreq>always</changefreq>'."\r\n";$i==0?$xml.='  <priority>1.00</priority>':$xml.='  <priority>0.95</priority>';$xml.="\r\n".'</url>'."\r\n";}}else{$start_offset=(intval($_GET['page'])-1)*$per_page;$sql='SELECT '.$articlecode_str.'articleid,lastupdate FROM '.$dbarr['pre'].'article_article WHERE '.$dbarr['words'].' > 0 ORDER BY lastupdate DESC LIMIT '.$start_offset.','.$per_page;$res_article=$db->ss_query($sql);$k=0;while($row=mysqli_fetch_array($res_article)){if(strpos($fake_info_url,'{acode}')!==false){$article_arr[$k]['articleid']=$row['articlecode'];}else{$article_arr[$k]['articleid']=$is_multiple?ss_newid($row['articleid']):$row['articleid'];}$article_arr[$k]['lastupdate']=$row['lastupdate'];$k++;}foreach($article_arr as $v){$xml.='<url>'."\r\n";if($is_3in1){$xml.='  <loc>'.$site_url.Url::index_url($v['articleid']).'</loc>'."\r\n";}else{$xml.='  <loc>'.$site_url.Url::info_url($v['articleid']).'</loc>'."\r\n";}$xml.='  <lastmod>'.date('Y-m-d H:i:s',$v['lastupdate']).'</lastmod>'."\r\n";$xml.='  <priority>0.70</priority>'."\r\n";$xml.='</url>'."\r\n";}}$xml.='</urlset>';}else{$totalsql='SELECT COUNT(articleid) as allbooks FROM '.$dbarr['pre'].'article_article WHERE '.$dbarr['words'].' > 0';$allrows=$db->ss_getone($totalsql)['allbooks'];$allpage=ceil($allrows/$per_page);$xml.='<sitemapindex>'."\r\n";for($i=0;$i<=$allpage;$i++){$xml.='<sitemap>'."\r\n";$xml.='  <loc>'.$site_url.'/sitemap/sitemap_'.$i.'.xml</loc>';$xml.="\r\n".'</sitemap>'."\r\n";}$xml.='</sitemapindex>';}header('Content-type: text/xml');ob_clean();echo $xml;function ss_autoload($classname){if(!class_exists($classname))require __ROOT_DIR__.'/shipsay/class/'.$classname.'.php';}