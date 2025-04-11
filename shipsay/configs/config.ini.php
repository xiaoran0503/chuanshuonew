<?php
// 安全检查：确保文件在正确的环境中被包含
if (!defined('__ROOT_DIR__')) {
    exit;
}

// 设置错误报告级别
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

// 设置时区
date_default_timezone_set('Asia/Chongqing');

// 包含版本信息文件
require_once __ROOT_DIR__ . '/shipsay/version.php';

// 定义网站名称
define('SITE_NAME', '笔趣阁8800');

// 数据库配置
$dbarr = [
    'host' => '127.0.0.1',
    'port' => '3306',
    'name' => 'admin1_biquge880',
    'user' => 'admin1_biquge880',
    'pass' => 'HWwkw76pLPjtAZTW',
    'pconnect' => false // 使用布尔值代替 0 和 1
];

// 认证码
$authcode = '';

// 系统版本
$sys_ver = '1.7';

// 文件路径配置
$root_dir = '';
$txt_url = '/www/wwwroot/admin1.biquge8800.com/files/article/txt';
$remote_img_url = 'https://admin1.biquge8800.com/files/article/image';
$local_img = true; // 使用布尔值代替 0 和 1
$is_attachment = false; // 使用布尔值代替 0 和 1
$att_url = '';
$site_url = '';

// 压缩和下载配置
$use_gzip = true; // 使用布尔值代替 0 和 1
$enable_down = false; // 使用布尔值代替 0 和 1

// 繁体设置
$is_ft = false; // 使用布尔值代替 0 和 1

// 主题目录
$theme_dir = 'qula';

// 三合一设置
$is_3in1 = false; // 使用布尔值代替 0 和 1

// 推荐 ID
$commend_ids = [1, 2, 3, 4, 5, 6];

// 分页和阅读设置
$category_per_page = 10;
$readpage_split_lines = 20;
$vote_perday = 3;
$count_visit = false; // 使用布尔值代替 0 和 1

// 伪 URL 配置
$fake_info_url = '/book/{aid}/';
$fake_chapter_url = '/read/{aid}/{cid}.html';
$use_orderid = '0';
$fake_sort_url = '/list/{sortcode}_{pid}.html';
$fake_top = '/top.html';
$fake_recentread = '/history.html';
$fake_indexlist = '/index_{aid}/{pid}/';
$per_indexlist = 50;

// 分类设置
$sortarr = [
    1 => ['code' => 'xuanhuanxiaoshuo', 'caption' => '玄幻小说'],
    2 => ['code' => 'xiuzhenxiaoshuo', 'caption' => '修真小说'],
    3 => ['code' => 'dushixiaoshuo', 'caption' => '都市小说'],
    4 => ['code' => 'chuanyuexiaoshuo', 'caption' => '穿越小说'],
    5 => ['code' => 'wangyouxiaoshuo', 'caption' => '网游小说'],
    6 => ['code' => 'lishixiaoshuo', 'caption' => '历史小说'],
    7 => ['code' => 'kehuanxiaoshuo', 'caption' => '科幻小说'],
    8 => ['code' => 'nvshengxiaoshuo', 'caption' => '女生小说'],
    9 => ['code' => 'qitaxiaoshuo', 'caption' => '其他小说']
];

// Redis 缓存设置
$use_redis = false; // 使用布尔值代替 0 和 1
$redisarr = [
    'host' => '127.0.0.1',
    'port' => '6379',
    'db' => 0, // 使用整数代替字符串
    'pass' => ''
];

// 缓存时间设置
$home_cache_time = 600;
$info_cache_time = 600;
$category_cache_time = 600;
$cache_time = 600;

// ID 混淆
$is_multiple = false; // 使用布尔值代替 0 和 1

// 定义 ID 转换函数
$ss_newid = function ($id) {
    return $id + 5;
};

$ss_sourceid = function ($id) {
    return $id - 5;
};

// 繁体缓存设置
$is_langtail = true; // 使用布尔值代替 0 和 1
$langtail_catch_cycle = 7;
$langtail_cache_time = 0;
$fake_langtail_info = '/books/{aid}/';
$fake_langtail_indexlist = '/indexs/{aid}/{pid}/';