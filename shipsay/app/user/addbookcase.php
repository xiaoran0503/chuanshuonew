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

// 处理添加到书架的逻辑
function handleAddToBookcase() {
    global $db, $dbarr, $is_multiple;
    $userid = $_COOKIE['ss_userid'];
    $source_aid = $is_multiple ? ss_sourceid($_REQUEST['articleid']) : $_REQUEST['articleid'];
    $source_cid = isset($_REQUEST['chapterid']) ? ($is_multiple ? ss_sourceid($_REQUEST['chapterid']) : $_REQUEST['chapterid']) : 0;
    $articlename = isset($_REQUEST['articlename']) ? sanitizeInput($_REQUEST['articlename']) : '';
    $chaptername = isset($_REQUEST['chaptername']) ? sanitizeInput($_REQUEST['chaptername']) : '';

    try {
        // 检查书籍是否已经在书架中
        $res = $db->ss_getone('SELECT caseid,articlename FROM ' . $dbarr['pre'] . 'article_bookcase WHERE articleid = "' . $source_aid . '" AND userid = "' . $userid . '"');

        if ($res) {
            // 更新书架记录
            $upsql = 'UPDATE ' . $dbarr['pre'] . 'article_bookcase SET 
                      chapterid = "' . $source_cid . '",
                      chaptername = "' . $chaptername . '" 
                      WHERE articleid = "' . $source_aid . '" AND userid = "' . $userid . '"';
        } else {
            // 插入新的书架记录
            $upsql = 'INSERT INTO ' . $dbarr['pre'] . 'article_bookcase 
                      (articleid,articlename,userid,username,chapterid,chaptername) 
                      VALUES 
                      ("' . $source_aid . '","' . $articlename . '","' . $userid . '","' . sanitizeInput($_COOKIE['ss_username']) . '","' . $source_cid . '","' . $chaptername . '")';
            // 增加书籍的收藏数
            $db->ss_query('UPDATE ' . $dbarr['pre'] . 'article_article SET goodnum = goodnum + 1 WHERE articleid = ' . $source_aid);
        }

        // 执行 SQL 语句
        if ($db->ss_query($upsql)) {
            die('添加成功');
        } else {
            throw new Exception('数据库操作失败');
        }
    } catch (Exception $e) {
        die('添加失败: ' . $e->getMessage());
    }
}

// 主逻辑
if (isUserLoggedIn()) {
    handleAddToBookcase();
} else {
    header('Location: /login/');
}
?>