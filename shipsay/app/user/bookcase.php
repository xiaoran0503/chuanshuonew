<?php
// 包含必要的文件
require_once __DIR__ . '/../../include/function.php';

// 检查模板文件是否存在
function checkTemplateFileExists() {
    if (!file_exists(__THEME_DIR__ . '/user/tpl_bookcase.php')) {
        Url::ss_errpage();
    }
}

// 检查用户是否登录
function isUserLoggedIn() {
    global $db;
    if (isset($_COOKIE['ss_userid']) && isset($_COOKIE['ss_username']) && isset($_COOKIE['ss_password'])) {
        return User::ss_check_login($_COOKIE['ss_userid'], $_COOKIE['ss_password']);
    }
    return false;
}

// 过滤和转义用户输入
function sanitizeInput($input) {
    global $db;
    return mysqli_real_escape_string($db->getConnection(), $input);
}

// 获取书架信息
function getBookcaseInfo() {
    global $db, $dbarr, $rico_sql, $is_multiple, $is_acode;
    $userId = sanitizeInput($_COOKIE['ss_userid']);
    $sql = 'SELECT caseid,articleid,articlename,chapterid,chaptername FROM ' . $dbarr['pre'] . 'article_bookcase WHERE userid = "' . $userId . '" ORDER BY caseid DESC';
    try {
        $caseObj = $db->ss_query($sql);
        if (!$caseObj) {
            throw new Exception('数据库查询失败');
        }
        $caseArr = [];
        if ($caseObj->num_rows) {
            $k = 0;
            while ($rows = mysqli_fetch_assoc($caseObj)) {
                $articleId = sanitizeInput($rows['articleid']);
                $tmp = $db->ss_getrows($rico_sql . 'AND articleid = ' . $articleId);
                if (!empty($tmp[0])) {
                    $caseArr[$k]['img_url'] = $tmp[0]['img_url'];
                    $caseArr[$k]['info_url'] = $tmp[0]['info_url'];
                    $caseArr[$k]['index_url'] = $tmp[0]['index_url'];
                    $caseArr[$k]['lastchapter'] = $tmp[0]['lastchapter'];
                    $caseArr[$k]['last_url'] = $tmp[0]['last_url'];
                    $caseArr[$k]['lastupdate'] = $tmp[0]['lastupdate'];
                    $caseArr[$k]['author'] = $tmp[0]['author'];
                }
                if ($is_multiple) {
                    $rows['articleid'] = ss_newid($rows['articleid']);
                    $rows['chapterid'] = ss_newid($rows['chapterid']);
                }
                $caseArr[$k]['articleid'] = $rows['articleid'];
                $caseArr[$k]['articlename'] = $rows['articlename'];
                if ($is_acode) {
                    $rows['articleid'] = $tmp[0]['articlecode'];
                }
                $caseArr[$k]['case_url'] = Url::chapter_url($rows['articleid'], $rows['chapterid']);
                $caseArr[$k]['chaptername'] = $rows['chaptername'];
                $k++;
            }
        }
        return $caseArr;
    } catch (Exception $e) {
        die('获取书架信息失败: ' . $e->getMessage());
    }
}

// 主逻辑
checkTemplateFileExists();
if (isUserLoggedIn()) {
    $caseArr = getBookcaseInfo();
    require_once __THEME_DIR__ . '/user/tpl_bookcase.php';
} else {
    header('Location: /login/');
}
?>