<?php
// 引入必要的函数和配置文件
require_once __ROOT_DIR__ . '/shipsay/include/function.php';

// 安全过滤用户输入，防止 SQL 注入
$langtail_articleid = $langtail_sourceid = sanitizeInput($matches[1]);

// 获取首页 URL
$index_url = Url::index_url($langtail_articleid, 1, true);

// 检查模板文件是否存在，若不存在则重定向到首页
if (!file_exists(__THEME_DIR__ . '/tpl_info.php')) {
    header('Location:' . $index_url);
    exit;
}

// 处理多模式情况
if ($is_multiple) {
    $langtail_sourceid = ss_sourceid($langtail_sourceid);
}

// 查询长尾词信息
$langtail_sql = "SELECT sourceid, langname, sourcename FROM shipsay_article_langtail WHERE langid = $langtail_sourceid";
$lang_res = executeQuery($langtail_sql, $db, $redis, null, false);

// 检查查询结果
if (!$lang_res) {
    Url::ss_errpage();
}

// 获取文章相关信息
$articleid = $sourceid = $lang_res['sourceid'];
$articlename = $lang_res['langname'];
$sourcename = $lang_res['sourcename'];

// 查询文章详细信息
$sql = $rico_sql . " AND articleid = $sourceid";
$infoarr = executeQuery($sql, $db, $redis, $info_cache_time, true);

// 检查文章详细信息查询结果
if (!is_array($infoarr)) {
    Url::ss_errpage();
}

// 处理编码情况
if ($is_acode) {
    $sourceid = $infoarr[0]['articleid'];
}

// 处理长尾词逻辑
if ($is_langtail === 1) {
    include_once __ROOT_DIR__ . '/shipsay/include/langtail.php';
}

// 获取文章其他信息
$author = $infoarr[0]['author'];
$author_arr = explode(',', $author);
$author_url = $infoarr[0]['author_url'];
$keywords = $infoarr[0]['keywords'];
$keywords_arr = explode(',', $keywords);
$img_url = $infoarr[0]['img_url'];
$sortid = $infoarr[0]['sortid'];
$sortname = $infoarr[0]['sortname'];
$isfull = $infoarr[0]['isfull'];
$words_w = $infoarr[0]['words_w'];
$intro_des = $infoarr[0]['intro_des'];
$intro_p = $infoarr[0]['intro_p'];
$allvisit = $infoarr[0]['allvisit'];
$goodnum = $infoarr[0]['goodnum'];
$ratenum = $infoarr[0]['ratenum'];
$ratesum = $infoarr[0]['ratesum'];
$score = $ratenum > 0 ? sprintf("%.1f ", $ratesum / $ratenum) : '0.0';

// 查询章节信息
$sql = "SELECT chapterid, chaptername, lastupdate, chaptertype, chapterorder FROM {$dbarr['pre']}{$db->get_cindex($sourceid)} WHERE articleid = $sourceid AND chaptertype = 0 ORDER BY chapterorder ASC";
$chapterrows = executeQuery($sql, $db, $redis, $info_cache_time, true);

// 检查章节信息查询结果
if (empty($chapterrows)) {
    $chapterrows = [];
}

// 处理章节信息
foreach ($chapterrows as &$row) {
    $cid = $use_orderid ? $row['chapterorder'] : $row['chapterid'];
    if ($is_multiple) {
        $cid = ss_newid($cid);
    }
    $row['chaptertype'] = $row['chaptertype'];
    $row['lastupdate'] = $row['lastupdate'];
    $row['cid_url'] = Url::chapter_url($articleid, $cid);
    $row['cname'] = Text::ss_toutf8($row['chaptername']);
    if ($is_ft) {
        $row['cname'] = Convert::jt2ft($row['cname']);
    }
}
unset($row);

// 获取章节相关信息
if (!empty($chapterrows)) {
    $first_url = $chapterrows[0]['cid_url'];
    $chapters = count($chapterrows);
    $lastupdate_stamp = $chapterrows[$chapters - 1]['lastupdate'];
    $lastupdate = date('Y-m-d H:i:s', $lastupdate_stamp);
    $lastupdate_cn = Text::ss_lastupdate($lastupdate_stamp);
    $lastchapter = $chapterrows[$chapters - 1]['cname'];
    $last_url = $chapterrows[$chapters - 1]['cid_url'];
    $lastarr = array_reverse(array_slice($chapterrows, -12, 12));
} else {
    $first_url = '';
    $chapters = 0;
    $lastupdate_stamp = 0;
    $lastupdate = '';
    $lastupdate_cn = '';
    $lastchapter = '';
    $last_url = '';
    $lastarr = [];
}

// 处理文章访问计数
require_once __ROOT_DIR__ . '/shipsay/include/articlevisit.php';

// 设置响应头
if ($lastupdate_stamp > 0) {
    header('Last-Modified: ' . date('D, d M Y H:i:s', $lastupdate_stamp - 8 * 60 * 60) . ' GMT');
}

// 引入模板文件
require_once __THEME_DIR__ . '/tpl_info.php';

/**
 * 安全过滤用户输入，防止 SQL 注入
 *
 * @param string $input 用户输入
 * @return string 过滤后的输入
 */
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * 执行数据库查询，并处理 Redis 缓存
 *
 * @param string $sql 查询语句
 * @param object $db 数据库对象
 * @param object|null $redis Redis 对象
 * @param int|null $cacheTime 缓存时间
 * @param bool $isArray 是否返回数组结果
 * @return mixed 查询结果
 */
function executeQuery($sql, $db, $redis, $cacheTime = null, $isArray = false) {
    if (isset($redis)) {
        $cacheKey = md5($sql);
        $result = $redis->ss_get($cacheKey);
        if ($result) {
            return $result;
        }
    }

    if ($isArray) {
        $result = $db->ss_getrows($sql);
    } else {
        $result = $db->ss_getone($sql);
    }

    if (isset($redis) && $cacheTime) {
        $redis->ss_setex($cacheKey, $cacheTime, $result);
    }

    return $result;
}