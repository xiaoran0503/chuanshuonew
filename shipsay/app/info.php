<?php
// 过滤和转义输入数据，防止 SQL 注入
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// 封装数据库查询逻辑，处理 Redis 缓存
function executeQuery($sql, $db, $redis, $cacheTime = null, $isArray = false) {
    if (isset($redis)) {
        $cacheKey = md5($sql);
        $result = $redis->ss_get($cacheKey);
        if ($result) {
            return $isArray? unserialize($result) : $result;
        }
        $result = $isArray? $db->ss_getrows($sql) : $db->ss_getone($sql);
        if ($cacheTime) {
            $redis->ss_setex($cacheKey, $cacheTime, $isArray? serialize($result) : $result);
        }
        return $result;
    }
    return $isArray? $db->ss_getrows($sql) : $db->ss_getone($sql);
}

// 获取文章相关 ID
$articleid = $sourceid = sanitizeInput($matches[1]);
$index_url = Url::index_url($articleid);

// 检查模板文件是否存在
if (!file_exists(__THEME_DIR__ . '/tpl_info.php')) {
    header('Location:' . $index_url);
    exit;
}

// 处理多模式和编码情况
if ($is_multiple) {
    $sourceid = ss_sourceid($articleid);
}
if ($is_acode) {
    $sql = $rico_sql . 'AND articlecode = "' . $sourceid . '"';
} else {
    $sql = $rico_sql . 'AND articleid = ' . $sourceid;
}

// 查询文章信息
$infoarr = executeQuery($sql, $db, $redis, $info_cache_time, true);

// 检查查询结果
if (!is_array($infoarr)) {
    Url::ss_errpage();
}

// 处理编码情况
if ($is_acode) {
    $sourceid = $infoarr[0]['articleid'];
}

$articlename = $sourcename = $infoarr[0]['articlename'];

// 处理长尾词
if ($is_langtail === 1) {
    if ($is_ft) {
        $sourcename = Convert::jt2ft($sourcename, 1);
    }
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
$score = $ratenum > 0? sprintf("%.1f ", $ratesum / $ratenum) : '0.0';

// 查询章节信息
$sql = 'SELECT chapterid,chaptername,lastupdate,chaptertype,chapterorder FROM ' . $dbarr['pre'] . $db->get_cindex($sourceid) . ' WHERE articleid = ' . $sourceid . ' AND chaptertype = 0 ORDER BY chapterorder ASC';
$chapterrows = executeQuery($sql, $db, $redis, $info_cache_time, true);

// 处理章节信息
if ($chapterrows) {
    foreach ($chapterrows as $k => &$rows) {
        $cid = $use_orderid? $rows['chapterorder'] : $rows['chapterid'];
        if ($is_multiple) {
            $cid = ss_newid($cid);
        }
        $rows['chaptertype'] = $rows['chaptertype'];
        $rows['lastupdate'] = $rows['lastupdate'];
        $rows['cid_url'] = Url::chapter_url($articleid, $cid);
        $rows['cname'] = Text::ss_toutf8($rows['chaptername']);
        if ($is_ft) {
            $rows['cname'] = Convert::jt2ft($rows['cname']);
        }
    }
    unset($rows);
}

// 获取章节相关信息
$first_url = $chapterrows[0]['cid_url'];
$chapters = count($chapterrows);
$lastupdate_stamp = $chapterrows[$chapters - 1]['lastupdate'];
$lastupdate = date('Y-m-d H:i:s', $lastupdate_stamp);
$lastupdate_cn = Text::ss_lastupdate($lastupdate_stamp);
$lastchapter = $chapterrows[$chapters - 1]['cname'];
$last_url = $chapterrows[$chapters - 1]['cid_url'];
$lastarr = array_reverse(array_slice($chapterrows, -12, 12));

// 处理访问计数
if ($count_visit) {
    require_once __ROOT_DIR__ . '/shipsay/include/articlevisit.php';
}

// 设置响应头
header('Last-Modified: ' . date('D, d M Y H:i:s', $lastupdate_stamp - 8 * 60 * 60) . ' GMT');

// 引入模板文件
require_once __THEME_DIR__ . '/tpl_info.php';
?>