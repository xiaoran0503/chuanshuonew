<?php
// 初始化语言长尾词数组
$langtailrows = [];

// 构建 SQL 查询语句，使用 intval 确保 $sourceid 为整数，防止 SQL 注入
$langsql = 'SELECT * FROM shipsay_article_langtail WHERE sourceid=' . intval($sourceid) . ' ORDER BY uptime DESC';

// 检查 Redis 缓存
if (isset($redis) && ($cachedData = $redis->ss_get($langsql))) {
    $langtailrows = $cachedData;
} else {
    // 执行 SQL 查询
    $langres = $db->ss_query($langsql);
    if ($langres && $langres->num_rows > 0) {
        // 遍历查询结果
        while ($row = mysqli_fetch_assoc($langres)) {
            $langtailrows[] = [
                'langname' => $row['langname'],
                'uptime' => $row['uptime'],
                'info_url' => Url::info_url($is_multiple ? ss_newid($row['langid']) : $row['langid'], true),
                'index_url' => Url::index_url($is_multiple ? ss_newid($row['langid']) : $row['langid'], 1, true)
            ];
        }

        // 检查是否需要更新数据
        if (time() - intval($langtailrows[0]['uptime']) >= intval($langtail_catch_cycle) * 24 * 3600) {
            $upsql = get_upsql(intval($sourceid), $sourcename);
            $db->ss_query($upsql);
        }

        // 更新 Redis 缓存
        if (isset($redis)) {
            $redis->ss_setex($langsql, $langtail_cache_time, $langtailrows);
        }
    } else {
        // 执行更新 SQL
        $upsql = get_upsql(intval($sourceid), $sourcename);
        $db->ss_query($upsql);

        // 再次查询更新后的数据
        $first_res = $db->ss_query($langsql);
        if ($first_res && $first_res->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($first_res)) {
                $langtailrows[] = [
                    'langname' => $row['langname'],
                    'uptime' => $row['uptime'],
                    'info_url' => Url::info_url($is_multiple ? ss_newid($row['langid']) : $row['langid'], true),
                    'index_url' => Url::index_url($is_multiple ? ss_newid($row['langid']) : $row['langid'], 1, true)
                ];
            }

            // 更新 Redis 缓存
            if (isset($redis)) {
                $redis->ss_setex($langsql, $langtail_cache_time, $langtailrows);
            }
        }
    }
}

/**
 * 生成更新 SQL 语句
 *
 * @param int    $sourceid    源 ID
 * @param string $sourcename  源名称
 * @return string 更新 SQL 语句
 */
function get_upsql($sourceid, $sourcename)
{
    $upsql = '';
    // 构建百度建议 API URL，使用 urlencode 对 $sourcename 进行编码，防止 URL 注入
    $langapi = 'http://suggestion.baidu.com/su?wd=' . urlencode($sourcename);
    $langhtml = Text::ss_get_contents($langapi);
    $langhtml = mb_convert_encoding($langhtml, 'UTF-8', 'GBK');

    // 使用正则表达式提取建议词
    if (preg_match('#s:\[(.+?)\]#', $langhtml, $matches)) {
        $langstr = str_replace('"', '', $matches[1]);
        $langarr = explode(',', $langstr);
        $uptime = time();

        $upsql = "INSERT IGNORE INTO shipsay_article_langtail (sourceid,langname,sourcename,uptime) VALUES ";
        $values = [];
        foreach ($langarr as $v) {
            $v = trim($v);
            // 使用 mysqli_real_escape_string 对值进行转义，防止 SQL 注入
            global $db;
            $escapedV = mysqli_real_escape_string($db->getConnection(), $v);
            $escapedSourcename = mysqli_real_escape_string($db->getConnection(), $sourcename);
            $values[] = "('$sourceid', '$escapedV', '$escapedSourcename', '$uptime')";
        }
        $upsql .= implode(',', $values);
    }

    return $upsql;
}
?>