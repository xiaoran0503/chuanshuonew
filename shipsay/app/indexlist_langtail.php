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
$langtail_articleid = $langtail_sourceid = sanitizeInput($matches[1]);
$info_url = Url::info_url($langtail_articleid, true);
$index_url = Url::index_url($langtail_articleid, 1, true);

// 检查模板文件是否存在
if (!file_exists(__THEME_DIR__ . '/tpl_indexlist.php')) {
    header('Location:' . $info_url);
    exit;
}

// 处理多模式下的 sourceid
if ($is_multiple) {
    $langtail_sourceid = ss_sourceid($langtail_sourceid);
}

$subaid = intval($langtail_articleid / 1000);
$pid = isset($matches[2])? sanitizeInput($matches[2]) : 1;
$per_page = $per_indexlist?: 100;

// 查询长尾词相关信息
$langtail_sql = 'SELECT sourceid,langname,sourcename FROM shipsay_article_langtail WHERE langid = ' . $langtail_sourceid;
$lang_res = executeQuery($langtail_sql, $db, $redis);

$articleid = $sourceid = $lang_res['sourceid'];
if ($is_multiple) {
    $articleid = ss_newid($articleid);
}
$articlename = $lang_res['langname'];
$sourcename = $lang_res['sourcename'];

// 查询文章信息
$sql = $rico_sql . 'AND articleid = ' . $sourceid;
$infoarr = executeQuery($sql, $db, $redis, $info_cache_time, true);

// 检查查询结果
if (!is_array($infoarr)) {
    Url::ss_errpage();
}

// 处理长尾词
if ($is_langtail === 1) {
    include_once __ROOT_DIR__ . '/shipsay/include/langtail.php';
}

// 获取文章其他信息
$author = $infoarr[0]['author'];
$author_url = $infoarr[0]['author_url'];
$keywords = $infoarr[0]['keywords'];
$img_url = $infoarr[0]['img_url'];
$sortid = $infoarr[0]['sortid'];
$sortname = $infoarr[0]['sortname'];
$isfull = $infoarr[0]['isfull'];
$words_w = $infoarr[0]['words_w'];
$intro_des = $infoarr[0]['intro_des'];
$intro_p = $infoarr[0]['intro_p'];
$allvisit = $infoarr[0]['allvisit'];
$goodnum = $infoarr[0]['goodnum'];

// 查询章节信息
$sql = 'SELECT chapterid,chapterorder,chaptername,chaptertype,lastupdate FROM ' . $dbarr['pre'] . $db->get_cindex($sourceid) . ' WHERE articleid = ' . $sourceid . ' AND chaptertype = 0 ORDER BY chapterorder ASC';
$chapterrows = executeQuery($sql, $db, $redis, $info_cache_time, true);

// 处理章节信息
if ($chapterrows) {
    foreach ($chapterrows as $k => &$rows) {
        $rows['chaptertype'] = $rows['chaptertype'];
        $rows['lastupdate'] = $rows['lastupdate'];
        $rows['cname'] = Text::ss_toutf8($rows['chaptername']);
        if ($is_ft) {
            $rows['cname'] = Convert::jt2ft($rows['cname']);
        }
        if ($is_multiple) {
            $rows['chapterid'] = ss_newid($rows['chapterid']);
        }
        if ($use_orderid) {
            $rows['chapterid'] = $rows['chapterorder'];
        }
        $rows['cid_url'] = Url::chapter_url($articleid, $rows['chapterid']);
    }
    unset($rows);
}

// 获取章节相关信息
$first_url = $chapterrows[0]['cid_url'];
$chapters = count($chapterrows);
$lastupdate = date('Y-m-d H:i:s', $chapterrows[$chapters - 1]['lastupdate']);
$lastupdate_cn = Text::ss_lastupdate($chapterrows[$chapters - 1]['lastupdate']);
$lastchapter = $chapterrows[$chapters - 1]['cname'];
$last_url = $chapterrows[$chapters - 1]['cid_url'];
$lastarr = array_reverse(array_slice($chapterrows, -12, 12));

// 分页处理
$rico_arr = array_chunk($chapterrows, $per_page);
if ($pid > count($rico_arr)) {
    $pid = count($rico_arr);
}
$list_arr = $rico_arr[$pid - 1];

// 生成页码导航
$htmltitle = '';
if ($pid > 1) {
    $htmltitle = '<a class="index-container-btn" href="' . Url::index_url($langtail_articleid, ($pid - 1), true) . '">上一页</a>';
} else {
    $htmltitle = '<a class="index-container-btn disabled-btn" href="javascript:void(0);">没有了</a>';
}
$htmltitle.= '<select id="indexselect" onchange="self.location.href=options[selectedIndex].value">';
for ($i = 1; $i <= count($rico_arr); $i++) {
    $end = $i * $per_page > $chapters? $chapters : $i * $per_page;
    $htmltitle.= '<option value="' . Url::index_url($langtail_articleid, $i, true) . '"';
    if ($i == $pid) {
        $htmltitle.= ' selected="selected"';
    }
    $htmltitle.= '> ' . (($i - 1) * $per_page + 1) . ' - ' . $end. '章</option>';
}
$htmltitle.= '</select>';
if ($pid < count($rico_arr)) {
    $htmltitle.= '<a class="index-container-btn" href="' . Url::index_url($langtail_articleid, ($pid + 1), true) . '">下一页</a>';
} else {
    $htmltitle.= '<a class="index-container-btn disabled-btn" href="javascript:void(0);">没有了</a>';
}

// 处理访问计数
require_once __ROOT_DIR__ . '/shipsay/include/articlevisit.php';

// 设置响应头
header('Last-Modified: ' . date('D, d M Y H:i:s', $chapterrows[$chapters - 1]['lastupdate'] - 8 * 60 * 60). ' GMT');

// 引入模板文件
require_once __THEME_DIR__ . '/tpl_indexlist.php';
?>