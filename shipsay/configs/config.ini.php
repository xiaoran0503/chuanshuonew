<?php if (!defined('__ROOT_DIR__')) exit;
error_reporting(0);
date_default_timezone_set('Asia/ChongQing');
include_once __ROOT_DIR__ . '/shipsay/version.php';
define('SITE_NAME', '笔趣阁8800');
$dbarr = [
     'host' => '127.0.0.1'
    ,'port' => '3306'
    ,'name' => 'admin1_biquge880'
    ,'user' => 'admin1_biquge880'
    ,'pass' => 'HWwkw76pLPjtAZTW'
    ,'pconnect' => 0
];
$authcode = '';

$sys_ver = '1.7';
$root_dir = '';
$txt_url = '/www/wwwroot/admin1.biquge8800.com/files/article/txt';              
$remote_img_url = 'https://admin1.biquge8800.com/files/article/image';
$local_img = 1;            
$is_attachment = 0;    
$att_url = '';              
$site_url = '';
$use_gzip = 1;
$enable_down = 0;
$is_ft = 0; 

$theme_dir = 'qula';
$is_3in1 = 0;
$commend_ids = '1, 2, 3, 4, 5, 6';
$category_per_page = 10;
$readpage_split_lines = 20;
$vote_perday = 3;
$count_visit = 0;

$fake_info_url = '/book/{aid}/';      
$fake_chapter_url = '/read/{aid}/{cid}.html';
$use_orderid = '0';
$fake_sort_url = '/list/{sortcode}_{pid}.html';      
$fake_top = '/top.html';        
$fake_recentread = '/history.html';
$fake_indexlist = '/index_{aid}/{pid}/';  
$per_indexlist = 50;

//分类设置
$sortarr[1] = ['code' => 'xuanhuanxiaoshuo', 'caption' => '玄幻小说'];
$sortarr[2] = ['code' => 'xiuzhenxiaoshuo', 'caption' => '修真小说'];
$sortarr[3] = ['code' => 'dushixiaoshuo', 'caption' => '都市小说'];
$sortarr[4] = ['code' => 'chuanyuexiaoshuo', 'caption' => '穿越小说'];
$sortarr[5] = ['code' => 'wangyouxiaoshuo', 'caption' => '网游小说'];
$sortarr[6] = ['code' => 'lishixiaoshuo', 'caption' => '历史小说'];
$sortarr[7] = ['code' => 'kehuanxiaoshuo', 'caption' => '科幻小说'];
$sortarr[8] = ['code' => 'nvshengxiaoshuo', 'caption' => '女生小说'];
$sortarr[9] = ['code' => 'qitaxiaoshuo', 'caption' => '其他小说'];

//redis缓存设置
$use_redis = 0;
$redisarr = [
     'host' => '127.0.0.1' 
    ,'port' => '6379' 
    ,'db' => '0'
    ,'pass' => ''
];
$home_cache_time = 600;        
$info_cache_time = 600;        
$category_cache_time = 600;
$cache_time = 600;                  

//ID混淆
$is_multiple = 0;
$ss_newid = '$id + 5';
function ss_newid($id){
    return $id + 5;
}
$ss_sourceid = '$id - 5';
function ss_sourceid($id){
    return $id - 5;
}

$is_langtail = 1;
$langtail_catch_cycle = 7;
$langtail_cache_time = 0;
$fake_langtail_info = '/books/{aid}/';
$fake_langtail_indexlist = '/indexs/{aid}/{pid}/';

