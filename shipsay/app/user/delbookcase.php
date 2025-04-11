<?php
// 包含必要的文件
require_once __DIR__ . '/../../include/function.php';

// 检查用户是否登录
function isUserLoggedIn() {
    global $db;
    if (isset($_REQUEST['articleid']) && isset($_COOKIE['ss_userid']) && isset($_COOKIE['ss_username']) && isset($_COOKIE['ss_password'])) {
        $userid = $_COOKIE['ss_userid'];
        return User::ss_check_login($userid, $_COOKIE['ss_password']);
    }
    return false;
}

// 过滤和转义用户输入
function sanitizeInput($input) {
    global $db;
    return mysqli_real_escape_string($db->getConnection(), $input);
}

// 处理删除书架记录的逻辑
function handleDeleteFromBookcase() {
    global $db, $dbarr, $is_multiple;
    $userid = sanitizeInput($_COOKIE['ss_userid']);
    $source_aid = $is_multiple ? ss_sourceid(sanitizeInput($_REQUEST['articleid'])) : sanitizeInput($_REQUEST['articleid']);

    try {
        // 减少书籍的收藏数
        $decreaseSql = 'UPDATE ' . $dbarr['pre'] . 'article_article SET goodnum = goodnum - 1 WHERE articleid = ' . $source_aid;
        if (!$db->ss_query($decreaseSql)) {
            throw new Exception('更新书籍收藏数失败');
        }

        // 删除书架记录
        $deleteSql = 'DELETE FROM ' . $dbarr['pre'] . 'article_bookcase WHERE articleid = "' . $source_aid . '" AND userid = "' . $userid . '"';
        if ($db->ss_query($deleteSql)) {
            die('删除成功');
        } else {
            throw new Exception('删除书架记录失败');
        }
    } catch (Exception $e) {
        die('删除失败: ' . $e->getMessage());
    }
}

// 主逻辑
if (isUserLoggedIn()) {
    handleDeleteFromBookcase();
} else {
    header('Location: /login/');
}
?>