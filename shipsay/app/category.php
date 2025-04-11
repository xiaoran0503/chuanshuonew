<?php
// 初始化变量
$page = 1;
$sortid = -1;
$sortidstr = '';
$fullstr = '';
$fullflag = false;
$sortname = '全部小说';
$sortcode = '';
$pinyin_fake_sort_url = $fake_sort_url;
$orgin_uri = $uri;

// 过滤和转义用户输入
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// 封装数据库查询逻辑
function getQueryResult($sql, $db, $redis, $cacheTime = null) {
    if (isset($redis) && $redis->ss_get($sql)) {
        return $redis->ss_get($sql);
    }
    $result = $db->ss_getone($sql);
    if (isset($redis) && $cacheTime) {
        $redis->ss_setex($sql, $cacheTime, $result);
    }
    return $result;
}

// 处理 URI 中的数字匹配
if (preg_match('/\d+/i', $pinyin_fake_sort_url, $_keys)) {
    $uri = preg_replace('/' . $_keys[0] . '/', '', $uri, 1);
    $pinyin_fake_sort_url = str_replace($_keys[0], '', $pinyin_fake_sort_url);
}

// 处理全本标识
if (strpos($orgin_uri, $fake_fullstr) !== false) {
    $fullstr = ' AND fullflag = 1 ';
    $fullflag = true;
    $full_url = str_replace('/' . $fake_fullstr, '', $orgin_uri);
} else {
    $full_url = '/' . $fake_fullstr . $orgin_uri;
}

// 生成分类信息
foreach ($sortarr as $k => $v) {
    $tmpvar = $is_sortid ? Url::category_url($k) : Url::category_url($v['code']);
    if ($fullflag) {
        $sortcategory[$k]['sorturl'] = '/' . $fake_fullstr . $tmpvar;
    } else {
        $sortcategory[$k]['sorturl'] = $tmpvar;
    }
    $v['caption'] = Text::ss_toutf8($v['caption']);
    if ($is_ft) {
        $v['caption'] = Convert::jt2ft($v['caption']);
    }
    $sortcategory[$k]['sortname'] = $v['caption'];
    $sortcategory[$k]['sortname_2'] = @mb_substr($v['caption'], 0, 2);
}

// 解析 sortid 和 page
if ($is_sortid) {
    $tmpint = preg_match_all('/\d+/', $uri, $matches);
    if ($tmpint >= 2) {
        $sortid = intval($matches[0][0]);
        $sortname = $sortarr[$sortid]['caption'];
        $sortname = Text::ss_toutf8($sortname);
        if ($matches[0][1]) {
            $page = intval($matches[0][1]);
        }
        $sortidstr = 'AND sortid = ' . $sortid;
    } elseif ($tmpint == 1) {
        $page = intval($matches[0][0]);
    }
} else {
    preg_match('/\d+/', $uri, $matches);
    if ($matches) {
        $page = intval($matches[0]);
    }
    $new_uri = preg_replace('/{sortcode}(.+)/i', '{sortcode}', $pinyin_fake_sort_url);
    $from = ['{sortcode}', '/', '.'];
    $to = ['([a-z-]+)', '\/', '\.'];
    $pregstr = '/' . str_replace($from, $to, $new_uri) . '/i';
    preg_match($pregstr, $uri, $matches);
    if ($matches) {
        $sortcode = $matches[1];
        foreach ($sortarr as $k => $v) {
            if ($v['code'] == $sortcode) {
                $sortid = $k;
                $sortname = Text::ss_toutf8($v['caption']);
                break;
            }
        }
        $sortidstr = 'AND sortid=' . $sortid;
    }
}

// 统计书籍总数
$sql = 'SELECT COUNT(articleid) as allbooks FROM ' . $dbarr['pre'] . 'article_article WHERE ' . $dbarr['words'] . ' > 0 ' . $sortidstr . $fullstr;
$allbooks = getQueryResult($sql, $db, $redis, $category_cache_time)['allbooks'];

$allpage = ceil($allbooks / $category_per_page);
if ($page > $allpage) {
    $page = $allpage;
}

// 查询当前页书籍列表
$sql = $rico_sql . $sortidstr . $fullstr . ' ORDER BY lastupdate DESC LIMIT ' . (($page - 1) * $category_per_page) . ',' . $category_per_page;
if (isset($redis) &&!isset($_REQUEST['nocache'])) {
    $retarr = $redis->ss_redis_getrows($sql, $category_cache_time, 1);
} else {
    $retarr = $db->ss_getrows($sql);
}

if ($page == 1) {
    $orgin_uri = str_replace(['{sortid}', '{sortcode}', '{pid}'], [$sortid, $sortcode, 1], $fake_sort_url);
}

// 替换 URI 中的最后一个数字
function replace_last_num($new_page_num, $uri) {
    return preg_replace('/(\d+)(\D*?)$/i', $new_page_num . '${2}', $uri);
}

// 替换 URI 中的所有数字
function replace_all_num($new_page_num) {
    global $fake_sort_url, $fullflag, $fake_fullstr;
    $ret = preg_replace('/({sortid}|{sortcode}).*{pid}/i', $new_page_num, $fake_sort_url);
    if ($fullflag) {
        $ret = '/' . $fake_fullstr . $ret;
    }
    return $ret;
}

$jump_page = Page::ss_jumppage($allpage, $page);
$jump_html = '';
$jump_html_wap = '';

if ($sortid == -1) {
    $first_page_url = replace_all_num(1);
    $last_page_url = replace_all_num($allpage);
    $pre_url = $page > 1 ? replace_all_num($page - 1) : 'javascript:void(0);';
    $next_url = $page < $allpage ? replace_all_num($page + 1) : 'javascript:void(0);';
    $jump_html.= '<a href="' . replace_all_num(1) . '">1</a>';
    $jump_html.= '<a href="' . $pre_url . '">&lt;&lt;</a>';
    $jump_html_wap.= '<a class="index-container-btn" href="' . $pre_url . '">上一页</a>';
    $jump_html_wap.= '<select id="indexselect" onchange="self.location.href=options[selectedIndex].value">';
    foreach ($jump_page as $v) {
        if ($page == $v) {
            $jump_html.= '<strong>' . $v . '</strong>';
            $jump_html_wap.= '<option selected="selected" value="' . replace_all_num($v) . '">';
        } else {
            $jump_html.= '<a href="' . replace_all_num($v) . '">' . $v . '</a>';
            $jump_html_wap.= '<option value="' . replace_all_num($v) . '">';
        }
        $jump_html_wap.= '第' . $v . '页</option>';
    }
    $jump_html.= '<a href="' . $next_url . '">&gt;&gt;</a>';
    $jump_html.= '<a href="' . replace_all_num($allpage) . '">' . $allpage . '</a>';
    $jump_html_wap.= '</select><a class="index-container-btn" href="' . $next_url . '">下一页</a>';
} else {
    $first_page_url = replace_last_num(1, $orgin_uri);
    $last_page_url = replace_last_num($allpage, $orgin_uri);
    $pre_url = $page > 1 ? replace_last_num($page - 1, $orgin_uri) : 'javascript:void(0);';
    $next_url = $page < $allpage ? replace_last_num($page + 1, $orgin_uri) : 'javascript:void(0);';
    $jump_html.= '<a href="' . replace_last_num(1, $orgin_uri) . '">1</a>';
    $jump_html.= '<a href="' . $pre_url . '">&lt;&lt;</a>';
    $jump_html_wap.= '<a class="index-container-btn" href="' . $pre_url . '">上一页</a>';
    $jump_html_wap.= '<select id="indexselect" onchange="self.location.href=options[selectedIndex].value">';
    foreach ($jump_page as $v) {
        if ($page == $v) {
            $jump_html.= '<strong>' . $v . '</strong>';
            $jump_html_wap.= '<option selected="selected" value="' . replace_last_num($v, $orgin_uri) . '">';
        } else {
            $jump_html.= '<a href="' . replace_last_num($v, $orgin_uri) . '">' . $v . '</a>';
            $jump_html_wap.= '<option value="' . replace_last_num($v, $orgin_uri) . '">';
        }
        $jump_html_wap.= '第' . $v . '页</option>';
    }
    $jump_html.= '<a href="' . $next_url . '">&gt;&gt;</a>';
    $jump_html.= '<a href="' . replace_last_num($allpage, $orgin_uri) . '">' . $allpage . '</a>';
    $jump_html_wap.= '</select><a class="index-container-btn" href="' . $next_url . '">下一页</a>';
}

// 查询按发布日期排序的书籍列表
$sql = $rico_sql . $sortidstr . ' ORDER BY postdate DESC LIMIT ' . $category_per_page;
if (isset($redis)) {
    $sort_postdate = $redis->ss_redis_getrows($sql, $category_cache_time);
} else {
    $sort_postdate = $db->ss_getrows($sql);
}

// 处理单页加载
if ($is_oneload && $page > 1) {
    if ($retarr) {
        echo json_encode($retarr, JSON_UNESCAPED_UNICODE);
    } else {
        echo '';
    }
} else {
    require_once __THEME_DIR__ . '/tpl_category.php';
}
?>