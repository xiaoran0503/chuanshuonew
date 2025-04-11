<?php
// 定义常量和变量
define('FAKE_FULLSTR', 'quanben');
define('FAKE_RANKSTR', 'rank');
define('FAKE_TAG', '/tag/{tag}/{pid}.html');

// 设置字符编码
header("Content-type: text/html; charset=utf-8");

// 定义主题目录
define('__THEME_DIR__', __ROOT_DIR__ . '/themes/' . $theme_dir);

// 定义 SITE_URL
if (!defined('SITE_URL')) {
    define('SITE_URL', $_SERVER['HTTP_HOST']);
}

// 开启 Gzip 压缩
if ($use_gzip &&!headers_sent() && extension_loaded("zlib") && strstr($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip")) {
    ini_set('zlib.output_compression', 'On');
    ini_set('zlib.output_compression_level', '9');
}

// 自动加载类
spl_autoload_register('ss_autoload');

// 设置 site_url
if (empty($site_url)) {
    $site_url = $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
    $site_url .= $_SERVER['HTTP_HOST'];
}

// 显示版本信息
if (isset($_REQUEST['show_shipsay_version'])) {
    print_r($ShipSayVersion);
    die();
}

$use_code = 0;
$year = date('Y');
$is_sortid = strpos($fake_sort_url, '{sortid}')!== false;
$is_acode = strpos($fake_info_url, '{acode}')!== false;

// 检查 Redis 扩展
if ($use_redis) {
    if (!extension_loaded('redis')) {
        handle_error('php的"Redis扩展"未正确安装');
    } else {
        $redis = new SsRedis($redisarr);
    }
}

// 合并数据库配置
$dbarr = array_merge([
    'pre' => $sys_ver < 5 ? 'jieqi_' : 'shipsay_',
    'words' => $sys_ver < 2.4 ? 'size' : 'words',
    'is_multiple' => $is_multiple,
    'sortarr' => $sortarr
], $dbarr);

// 创建数据库对象
$db = new Db($dbarr);

// 文章代码字符串
$articlecode_str = $sys_ver < 2.4 ? '' : 'articlecode,backupname,ratenum,ratesum,';

// 通用 SQL 查询语句
$rico_sql = 'SELECT ' . $articlecode_str . $dbarr['words'] . ',articleid,articlename,intro,author,sortid,fullflag,display,lastupdate,imgflag,allvisit,allvote,goodnum,keywords,lastchapter,lastchapterid FROM ' . $dbarr['pre'] . 'article_article WHERE display <> 1 AND ' . $dbarr['words'] . ' >= 0 ';

// 设置是否一次性加载
if (!isset($is_oneload)) {
    $is_oneload = 0;
}

// 全部书籍 URL
$allbooks_url = preg_replace('/({sortid}|{sortcode}).*/i', '', $fake_sort_url);
$full_allbooks_url = '/' . FAKE_FULLSTR . $allbooks_url;

// 路由处理
if (handle_json_request($source_uri)) {
    exit;
}

if ($uri == '/') {
    require_once __ROOT_DIR__ . '/shipsay/app/home.php';
    exit;
}

if (handle_category_request($uri, $allbooks_url, $fake_sort_url)) {
    exit;
}

if (handle_tag_request($uri, $fake_tag)) {
    exit;
}

if (handle_top_request($uri, $fake_top)) {
    exit;
}

if (handle_rank_request($uri)) {
    exit;
}

if (handle_search_request($uri)) {
    exit;
}

if (handle_author_request($uri)) {
    exit;
}

if (handle_recentread_request($uri, $fake_recentread)) {
    exit;
}

if (handle_api_request($uri)) {
    exit;
}

if (handle_user_request($uri, ['/login', '/logout', '/register', '/addbookcase', '/delbookcase', '/bookcase'])) {
    exit;
}

if (handle_reader_request($uri, $fake_chapter_url)) {
    exit;
}

if (handle_info_request($uri, $fake_info_url)) {
    exit;
}

if (handle_indexlist_request($uri, $fake_indexlist)) {
    exit;
}

if ($is_langtail === 1) {
    if (handle_info_langtail_request($uri, $fake_langtail_info)) {
        exit;
    }
    if (handle_indexlist_langtail_request($uri, $fake_langtail_indexlist)) {
        exit;
    }
}

// 错误页面
Url::ss_errpage();

/**
 * 处理 JSON 请求
 * @param string $source_uri 请求的 URI
 * @return bool 是否处理成功
 */
function handle_json_request($source_uri) {
    if (preg_match('/^\/json\/([\s\S]+)\.php/i', $source_uri, $match_json)) {
        require_once __ROOT_DIR__ . '/shipsay/json/ss_json_api.php';
        return true;
    }
    return false;
}

/**
 * 处理分类请求
 * @param string $uri 请求的 URI
 * @param string $allbooks_url 全部书籍 URL
 * @param string $fake_sort_url 分类 URL 模板
 * @return bool 是否处理成功
 */
function handle_category_request($uri, $allbooks_url, $fake_sort_url) {
    if (strpos($uri, $allbooks_url)!== false || preg_match(Url::sort2real($fake_sort_url), $uri)) {
        require_once __ROOT_DIR__ . '/shipsay/app/category.php';
        return true;
    }
    return false;
}

/**
 * 处理标签请求
 * @param string $uri 请求的 URI
 * @param string $fake_tag 标签 URL 模板
 * @return bool 是否处理成功
 */
function handle_tag_request($uri, $fake_tag) {
    $tag_first_page = preg_replace('/{tag}.*$/i', '', $fake_tag);
    if (preg_match(Url::tag2real($fake_tag), urldecode($uri), $matches) || strpos($uri, $tag_first_page) === 0) {
        require_once __ROOT_DIR__ . '/shipsay/app/tag.php';
        return true;
    }
    return false;
}

/**
 * 处理排行榜请求
 * @param string $uri 请求的 URI
 * @param string $fake_top 排行榜 URL 模板
 * @return bool 是否处理成功
 */
function handle_top_request($uri, $fake_top) {
    if (decide_uri($uri, $fake_top)) {
        require_once __ROOT_DIR__ . '/shipsay/app/top.php';
        return true;
    }
    return false;
}

/**
 * 处理排名请求
 * @param string $uri 请求的 URI
 * @return bool 是否处理成功
 */
function handle_rank_request($uri) {
    if (preg_match('/\/' . FAKE_RANKSTR . '\/?([^\/]*)\/?/i', $uri, $matches)) {
        require_once __ROOT_DIR__ . '/shipsay/app/rank.php';
        return true;
    }
    return false;
}

/**
 * 处理搜索请求
 * @param string $uri 请求的 URI
 * @return bool 是否处理成功
 */
function handle_search_request($uri) {
    if (decide_uri($uri, '/search')) {
        require_once __ROOT_DIR__ . '/shipsay/app/search.php';
        return true;
    }
    return false;
}

/**
 * 处理作者请求
 * @param string $uri 请求的 URI
 * @return bool 是否处理成功
 */
function handle_author_request($uri) {
    if (preg_match('/^\/author\/(.+?)\/?$/i', $uri, $matches)) {
        require_once __ROOT_DIR__ . '/shipsay/app/author.php';
        return true;
    }
    return false;
}

/**
 * 处理最近阅读请求
 * @param string $uri 请求的 URI
 * @param string $fake_recentread 最近阅读 URL 模板
 * @return bool 是否处理成功
 */
function handle_recentread_request($uri, $fake_recentread) {
    if (decide_uri($uri, $fake_recentread)) {
        require_once __ROOT_DIR__ . '/shipsay/app/recentread.php';
        return true;
    }
    return false;
}

/**
 * 处理 API 请求
 * @param string $uri 请求的 URI
 * @return bool 是否处理成功
 */
function handle_api_request($uri) {
    if (preg_match('/^\/api\/(.+?)\.php/i', $uri, $matches)) {
        require_once __ROOT_DIR__ . '/shipsay/include/' . $matches[1] . '.php';
        return true;
    }
    return false;
}

/**
 * 处理用户请求
 * @param string $uri 请求的 URI
 * @param array $user_paths 用户相关路径数组
 * @return bool 是否处理成功
 */
function handle_user_request($uri, $user_paths) {
    foreach ($user_paths as $path) {
        if (decide_uri($uri, $path)) {
            require_once __ROOT_DIR__ . '/shipsay/app/user/' . substr($path, 1) . '.php';
            return true;
        }
    }
    return false;
}

/**
 * 处理阅读请求
 * @param string $uri 请求的 URI
 * @param string $fake_chapter_url 章节 URL 模板
 * @return bool 是否处理成功
 */
function handle_reader_request($uri, $fake_chapter_url) {
    if (preg_match(Url::fake2real($fake_chapter_url), $uri, $matches)) {
        require_once __ROOT_DIR__ . '/shipsay/app/reader.php';
        return true;
    }
    return false;
}

/**
 * 处理信息请求
 * @param string $uri 请求的 URI
 * @param string $fake_info_url 信息 URL 模板
 * @return bool 是否处理成功
 */
function handle_info_request($uri, $fake_info_url) {
    if (strpos($uri,'search') === false && preg_match(Url::fake2real($fake_info_url), $uri, $matches)) {
        require_once __ROOT_DIR__ . '/shipsay/app/info.php';
        return true;
    }
    return false;
}

/**
 * 处理目录列表请求
 * @param string $uri 请求的 URI
 * @param string $fake_indexlist 目录列表 URL 模板
 * @return bool 是否处理成功
 */
function handle_indexlist_request($uri, $fake_indexlist) {
    if (preg_match(Url::indexlist2real($fake_indexlist), $uri, $matches)) {
        require_once __ROOT_DIR__ . '/shipsay/app/indexlist.php';
        return true;
    }
    return false;
}

/**
 * 处理繁体信息请求
 * @param string $uri 请求的 URI
 * @param string $fake_langtail_info 繁体信息 URL 模板
 * @return bool 是否处理成功
 */
function handle_info_langtail_request($uri, $fake_langtail_info) {
    if (strpos($uri,'search') === false && preg_match(Url::fake2real($fake_langtail_info), $uri, $matches)) {
        require_once __ROOT_DIR__ . '/shipsay/app/info_langtail.php';
        return true;
    }
    return false;
}

/**
 * 处理繁体目录列表请求
 * @param string $uri 请求的 URI
 * @param string $fake_langtail_indexlist 繁体目录列表 URL 模板
 * @return bool 是否处理成功
 */
function handle_indexlist_langtail_request($uri, $fake_langtail_indexlist) {
    if (preg_match(Url::indexlist2real($fake_langtail_indexlist), $uri, $matches)) {
        require_once __ROOT_DIR__ . '/shipsay/app/indexlist_langtail.php';
        return true;
    }
    return false;
}

/**
 * 判断 URI 是否匹配
 * @param string $uri 请求的 URI
 * @param string $fake_url 匹配的 URL
 * @return bool 是否匹配
 */
function decide_uri($uri, $fake_url) {
    return rtrim($uri, '/') === rtrim($fake_url, '/');
}

/**
 * 自动加载类
 * @param string $classname 类名
 */
function ss_autoload($classname) {
    if (!class_exists($classname)) {
        require __ROOT_DIR__ . '/shipsay/class/' . $classname . '.php';
    }
}

/**
 * 处理错误信息
 * @param string $message 错误信息
 */
function handle_error($message) {
    die('<p style="color: red;">' . $message . '</p>');
}
?>