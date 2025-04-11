<?php
// 引入必要的文件
require_once __ROOT_DIR__ . '/shipsay/configs/filter.ini.php';
require_once __ROOT_DIR__ . '/shipsay/include/function.php';

// 安全过滤用户输入，防止 SQL 注入
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// 封装数据库查询逻辑，处理 Redis 缓存
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

// 初始化变量
$now_pid = 1;
$articleid = $sourceid = sanitizeInput($matches[1]);

// 处理文章编码
if ($is_acode) {
    $sql = 'SELECT articleid FROM ' . $dbarr['pre'] . 'article_article WHERE articlecode = "' . $sourceid . '"';
    $sourceid = executeQuery($sql, $db, $redis)['articleid'];
}

$chapterid = $sourcecid = sanitizeInput($matches[2]);
if (isset($matches[3])) {
    $now_pid = str_replace('_', '', sanitizeInput($matches[3]));
}

// 处理多模式情况
if ($is_multiple) {
    $sourceid = ss_sourceid($sourceid);
    $sourcecid = ss_sourceid($sourcecid);
}

// 初始化页面相关变量
$max_pid = 1;
$prevpage_url = '';
$nextpage_url = '';
$subaid = intval($sourceid / 1000);

// 查询文章信息
$sql = $rico_sql . 'AND articleid = ' . $sourceid;
$infoarr = executeQuery($sql, $db, $redis, $cache_time, true);

// 检查查询结果
if (!is_array($infoarr)) {
    Url::ss_errpage();
}

// 获取文章相关信息
$articlename = $infoarr[0]['articlename'];
$author = $infoarr[0]['author'];
$author_url = $infoarr[0]['author_url'];
$img_url = $infoarr[0]['img_url'];
$sortid = $infoarr[0]['sortid'];
$sortname = $infoarr[0]['sortname'];
$isfull = $infoarr[0]['isfull'];
$keywords = $infoarr[0]['keywords'];
$words_w = $infoarr[0]['words_w'];
$intro_des = $infoarr[0]['intro_des'];
$intro_p = $infoarr[0]['intro_p'];

// 获取章节信息
if (isset($redis) && $redis->ss_get($uri)) {
    $ret = $redis->ss_get($uri);
    $chapterids = $ret['chapterids'];
    $chaptername = $ret['chaptername'];
    $chapterwords = $ret['chapterwords'];
    $lastupdate = $ret['lastupdate'];
} else {
    $sql = 'SELECT chapterid,chapterorder,chaptername,' . $dbarr['words'] . ',lastupdate FROM ' . $dbarr['pre'] . $db->get_cindex($sourceid) . ' WHERE articleid = ' . $sourceid . ' AND chaptertype = 0 ORDER BY chapterorder ASC';
    $res = executeQuery($sql, $db, $redis, $cache_time, true);

    if (empty($res)) {
        Url::ss_errpage();
    }

    $chapterids = [];
    foreach ($res as $row) {
        $_compare_id = $use_orderid ? $row['chapterorder'] : $row['chapterid'];
        $chapterids[] = $_compare_id;
        if ($sourcecid == $_compare_id) {
            $txt_sourceid = $row['chapterid'];
            $chaptername = Text::ss_toutf8($row['chaptername']);
            if ($is_ft) {
                $chaptername = Convert::jt2ft($chaptername);
            }
            $chapterwords = round($row[$dbarr['words']] / 2);
            $lastupdate = $row['lastupdate'];
        }
    }

    if (isset($redis)) {
        $ret = [
            'chapterids' => $chapterids,
            'chaptername' => $chaptername,
            'chapterwords' => $chapterwords,
            'lastupdate' => $lastupdate
        ];
        $redis->ss_setex($uri, $cache_time, $ret);
    }
}

// 生成文章相关链接
$info_url = Url::info_url($articleid);
$index_url = Url::index_url($articleid);

// 获取上一章和下一章的 ID
$pre_cid = 0;
$next_cid = 0;
$chapters = count($chapterids);
$offset = array_search($sourcecid, $chapterids);
if ($offset === false) {
    Url::ss_errpage();
}
$pre_cid = ($offset == 0) ? 0 : $chapterids[$offset - 1];
$next_cid = ($offset == $chapters - 1) ? 0 : $chapterids[$offset + 1];

// 生成上一章和下一章的链接
if ($pre_cid == 0) {
    $pre_url = $info_url;
} else {
    $tmpvar = $is_multiple ? ss_newid($pre_cid) : $pre_cid;
    $pre_url = Url::chapter_url($articleid, $tmpvar);
}

if ($next_cid == 0) {
    $next_url = $info_url;
} else {
    $tmpvar = $is_multiple ? ss_newid($next_cid) : $next_cid;
    $next_url = Url::chapter_url($articleid, $tmpvar);
}

// 获取章节文件路径
if ($use_orderid) {
    $txtfile = $txt_url . '/' . $subaid . '/' . $sourceid . '/' . $txt_sourceid . '.txt';
} else {
    $txtfile = $txt_url . '/' . $subaid . '/' . $sourceid . '/' . $sourcecid . '.txt';
}

// 获取章节内容
$content = is_dir($txt_url) ? file_get_contents($txtfile) : Text::ss_get_contents($txtfile);
$content = Text::ss_toutf8($content);
$content = preg_replace('#<br\s*/?>#isU', "\r\n", $content);

// 敏感词过滤
if ($ShipSayFilter['is_filter']) {
    function ss_str2preg($str) {
        $from = ['\\$\\$\\$\\$', '/', '\\.', '\'', '"'];
        $to = ['.*?', '\\/', '\\.', '\'', '\"'];
        $str = preg_quote(trim($str));
        $str = str_replace($from, $to, $str);
        return $str;
    }

    $filterlines = explode("\n", trim($ShipSayFilter['filter_ini']));
    $from = [];
    $to = [];
    foreach ($filterlines as $v) {
        $tmparr = explode("♂", $v);
        $from[] = '/' . ss_str2preg($tmparr[0]) . '/is';
        $to[] = $tmparr[1];
    }
    $content = preg_replace($from, $to, $content);
}

// 分页处理
if ($readpage_split_lines < count(explode("\n", $content))) {
    $content_arr = Text::readpage_split($content);
    $max_pid = count($content_arr);
    if ($now_pid > $max_pid) {
        $now_pid = $max_pid;
    }
    if ($now_pid < 1) {
        $now_pid = 1;
    }
    $rico_content = $content_arr[$now_pid - 1];

    if ($now_pid > 1) {
        if ($now_pid == 2) {
            $prevpage_url = Url::chapter_url($articleid, $chapterid);
        } else {
            $prevpage_url = Url::chapter_url($articleid, $chapterid, ($now_pid - 1));
        }
    }

    if ($now_pid < $max_pid) {
        $nextpage_url = Url::chapter_url($articleid, $chapterid, ($now_pid + 1));
    }
} else {
    $rico_content = Text::ss_txt2p($content);
}

// 繁体转换
if ($is_ft) {
    $rico_content = Convert::jt2ft($rico_content);
}

// 生成章节描述
$reader_des = mb_substr(preg_replace('/<\/?p>/is', '', $rico_content), 0, 200);

// 处理附件
if ($is_attachment && !empty($att_url) && $now_pid == $max_pid) {
    $sql = 'SELECT attachment FROM ' . $dbarr['pre'] . $db->get_cindex($sourceid) . ' WHERE chapterid = ' . $sourcecid;
    $res = executeQuery($sql, $db, $redis, $cache_time);

    $att_url .= '/' . $subaid . '/' . $sourceid . '/' . $sourcecid;
    $attHtml = '';
    $regex = '/"postfix";s:3:"(.+?)".+?"attachid";i:(\d+?);/i';
    preg_match_all($regex, $res['attachment'], $atts);
    foreach ($atts[2] as $k => $v) {
        $attHtml .= '<img class="ss-image-content" src="' . $att_url . '/' . $v . '.' . $atts[1][$k] . '"/>';
    }
    $rico_content .= $attHtml;
}

// 处理章节内容缺失的情况
if (strlen($rico_content) <= 0) {
    if (!isset($chaptername)) {
        $chaptername = $chapterwords = $lastupdate = '';
    }
    $rico_content = '章节内容缺失或章节不存在！请稍后重新尝试！';
}

// 输出 JSON 或引入模板文件
if (isset($_REQUEST['json'])) {
    echo json_encode($rico_content, JSON_UNESCAPED_UNICODE);
} else {
    header('Last-Modified: ' . date('D, d M Y H:i:s', $lastupdate - 8 * 60 * 60) . ' GMT');
    require_once __THEME_DIR__ . '/tpl_reader.php';
}