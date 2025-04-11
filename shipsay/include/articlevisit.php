<?php
// 计算明天的时间戳，增强代码可读性
$cookie_time = strtotime('tomorrow');

// 检查请求中是否包含投票参数
if (!isset($_REQUEST['vote'])) {
    // 检查是否有文章访问的 cookie
    if (!isset($_COOKIE["articlevisited"])) {
        // 确保 $sourceid 安全，避免 SQL 注入
        $sourceid = (int)$sourceid;
        $visitsql = "SELECT lastvisit FROM {$dbarr['pre']}article_article WHERE articleid = $sourceid";
        $lastvisitResult = $db->ss_getone($visitsql);
        if ($lastvisitResult) {
            $lastvisit = (int)$lastvisitResult['lastvisit'];
            $retsql = ss_add_or_up($lastvisit, 'visit');
            $sql = "UPDATE {$dbarr['pre']}article_article $retsql WHERE articleid = $sourceid";
            if ($db->ss_query($sql)) {
                // 设置文章已访问的 cookie
                setcookie("articlevisited", 1, $cookie_time, '/');
            } else {
                // 输出错误信息
                die('写入访问数失败');
            }
        }
    }
} else {
    // 检查投票 cookie 是否存在或是否达到每日投票上限
    if (!isset($_COOKIE["articlevote"]) || (int)$_COOKIE["articlevote"] < $vote_perday) {
        // 处理文章 ID，确保安全
        $sourceid = $is_multiple ? (int)ss_sourceid($_REQUEST['articleid']) : (int)$_REQUEST['articleid'];
        $votesql = "SELECT lastvote FROM {$dbarr['pre']}article_article WHERE articleid = $sourceid";
        $lastvoteResult = $db->ss_getone($votesql);
        if ($lastvoteResult) {
            $lastvote = (int)$lastvoteResult['lastvote'];
            $retsql = ss_add_or_up($lastvote, 'vote');
            $sql = "UPDATE {$dbarr['pre']}article_article $retsql WHERE articleid = $sourceid";
            if ($db->ss_query($sql)) {
                if (!isset($_COOKIE["articlevote"])) {
                    // 首次投票，设置投票 cookie 为 1
                    setcookie("articlevote", 1, $cookie_time, '/');
                } else {
                    // 非首次投票，增加投票次数
                    setcookie("articlevote", (int)$_COOKIE["articlevote"] + 1, $cookie_time, '/');
                }
                // 输出成功信息
                echo '200';
            } else {
                // 输出错误信息
                die('写入推荐数失败');
            }
        }
    }
}

/**
 * 根据时间和统计类型更新统计数据
 *
 * @param int    $time   上次统计时间
 * @param string $column 统计类型（visit 或 vote）
 * @return string 更新语句
 */
function ss_add_or_up($time, $column)
{
    $time = (int)$time;
    $currentTime = time();
    // 检查时间是否在同一时间周期内
    $flagY = date('Y', $time) === date('Y', $currentTime);
    $flagM = date('m', $time) === date('m', $currentTime);
    $flagW = date('W', $time) === date('W', $currentTime);
    $flagD = date('d', $time) === date('d', $currentTime);

    // 生成更新语句
    $allstr = "all{$column} = all{$column} + 1";
    $monthstr = "month{$column} = " . ($flagY && $flagM ? "month{$column} + 1" : '1');
    $weekstr = "week{$column} = " . ($flagY && $flagM && $flagW ? "week{$column} + 1" : '1');
    $daystr = "day{$column} = " . ($flagY && $flagM && $flagW && $flagD ? "day{$column} + 1" : '1');

    return "SET last{$column} = $currentTime, $daystr, $weekstr, $monthstr, $allstr";
}
?>