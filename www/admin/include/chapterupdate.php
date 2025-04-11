<?php
require_once 'function.php';

// 过滤和验证输入
function filterAndValidateInput($input, $filterType = FILTER_DEFAULT) {
    return filter_var($input, $filterType);
}

switch ($_REQUEST['do']) {
    case 'update':
        $sourceid = filterAndValidateInput($_REQUEST['sourceid'], FILTER_VALIDATE_INT);
        $chapterid = filterAndValidateInput($_REQUEST['chapterid'], FILTER_VALIDATE_INT);
        $chaptername = filterAndValidateInput($_REQUEST['chaptername']);
        $content = filterAndValidateInput($_REQUEST['content']);
        $words = mb_strlen($content);

        $sql = 'UPDATE ' . $dbarr['pre'] . $db->get_cindex($sourceid) . ' 
                SET chaptername = :chaptername, ' . $dbarr['words'] . ' = :words 
                WHERE chapterid = :chapterid';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':chaptername', $chaptername, PDO::PARAM_STR);
        $stmt->bindParam(':words', $words, PDO::PARAM_INT);
        $stmt->bindParam(':chapterid', $chapterid, PDO::PARAM_INT);

        if (ss_writefile($_REQUEST['chapter_file'], $content) && $stmt->execute()) {
            echo "200";
        }
        break;

    case 'delete':
        $sourceid = filterAndValidateInput($_REQUEST['sourceid'], FILTER_VALIDATE_INT);
        $chapterids = $_REQUEST['ids'];
        $err = false;

        foreach ($chapterids as $k => $v) {
            $chapter_id = filterAndValidateInput($v, FILTER_VALIDATE_INT);
            $chapter_file = $root_dir . '/files/article/txt/' . intval($sourceid / 1000) . '/' . $sourceid . '/' . $chapter_id . '.txt';
            $sql = 'DELETE FROM ' . $dbarr['pre'] . $db->get_cindex($sourceid) . ' WHERE chapterid = :chapterid';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':chapterid', $chapter_id, PDO::PARAM_INT);

            if (!($stmt->execute() && @unlink($chapter_file))) {
                $err = true;
            }
        }

        $lastid_sql = 'SELECT chapterid, chaptername 
                       FROM ' . $dbarr['pre'] . $db->get_cindex($sourceid) . ' 
                       WHERE articleid = :sourceid AND chaptertype = 0 
                       ORDER BY chapterorder DESC LIMIT 1';
        $stmt = $db->prepare($lastid_sql);
        $stmt->bindParam(':sourceid', $sourceid, PDO::PARAM_INT);
        $stmt->execute();
        $tmparr = $stmt->fetch(PDO::FETCH_ASSOC);

        $lastchapterid = $tmparr ? $tmparr['chapterid'] : 0;
        $lastchapter = $tmparr ? $tmparr['chaptername'] : '';

        $articlesql = 'UPDATE ' . $dbarr['pre'] . 'article_article 
                       SET lastupdate = :lastupdate, 
                           lastchapter = :lastchapter, 
                           lastchapterid = :lastchapterid, 
                           chapters = chapters - :count 
                       WHERE articleid = :sourceid';
        $stmt = $db->prepare($articlesql);
        $lastupdate = time();
        $stmt->bindParam(':lastupdate', $lastupdate, PDO::PARAM_INT);
        $stmt->bindParam(':lastchapter', $lastchapter, PDO::PARAM_STR);
        $stmt->bindParam(':lastchapterid', $lastchapterid, PDO::PARAM_INT);
        $stmt->bindParam(':count', count($chapterids), PDO::PARAM_INT);
        $stmt->bindParam(':sourceid', $sourceid, PDO::PARAM_INT);

        if ($stmt->execute() && !$err) {
            echo "200";
        }
        break;

    case 'clean':
        $sourceid = filterAndValidateInput($_REQUEST['sourceid'], FILTER_VALIDATE_INT);
        $err = false;

        $txt_folder = $root_dir . '/files/article/txt/' . intval($sourceid / 1000) . '/' . $sourceid;
        $att_folder = $root_dir . '/files/article/attachement/' . intval($sourceid / 1000) . '/' . $sourceid;
        Ss::ss_delfolder($txt_folder);
        Ss::ss_delfolder($att_folder);

        $sql = 'DELETE FROM ' . $dbarr['pre'] . $db->get_cindex($sourceid) . ' WHERE articleid = :sourceid';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':sourceid', $sourceid, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            die('删除chapter表的相关内容失败');
        }

        $lastid_sql = 'SELECT chapterid, chaptername 
                       FROM ' . $dbarr['pre'] . $db->get_cindex($sourceid) . ' 
                       WHERE articleid = :sourceid AND chaptertype = 0 
                       ORDER BY chapterorder DESC LIMIT 1';
        $stmt = $db->prepare($lastid_sql);
        $stmt->bindParam(':sourceid', $sourceid, PDO::PARAM_INT);
        $stmt->execute();
        $tmparr = $stmt->fetch(PDO::FETCH_ASSOC);

        $lastchapterid = $tmparr ? $tmparr['chapterid'] : 0;
        $lastchapter = $tmparr ? $tmparr['chaptername'] : '';

        $articlesql = 'UPDATE ' . $dbarr['pre'] . 'article_article 
                       SET ' . $dbarr['words'] . ' = 0, 
                           lastchapter = :lastchapter, 
                           lastchapterid = :lastchapterid 
                       WHERE articleid = :sourceid';
        $stmt = $db->prepare($articlesql);
        $stmt->bindParam(':lastchapter', $lastchapter, PDO::PARAM_STR);
        $stmt->bindParam(':lastchapterid', $lastchapterid, PDO::PARAM_INT);
        $stmt->bindParam(':sourceid', $sourceid, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "200";
        }
        break;

    case 'newchapter':
        $sourceid = filterAndValidateInput($_REQUEST['sourceid'], FILTER_VALIDATE_INT);
        $chaptername = filterAndValidateInput($_REQUEST['chaptername']);
        $content = filterAndValidateInput($_REQUEST['content']);
        $words = strlen($content);
        $wordstr = $sys_ver < 2.4 ? 'size' : 'words';

        $sql = 'SELECT MAX(chapterorder) as maxid 
                FROM ' . $dbarr['pre'] . $db->get_cindex($sourceid) . ' 
                WHERE articleid = :sourceid';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':sourceid', $sourceid, PDO::PARAM_INT);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res) {
            $chapterorder = intval($res['maxid']) + 1;
        } else {
            die('没有该小说数据');
        }

        $articlename = filterAndValidateInput($_REQUEST['articlename']);
        $insertsql = 'INSERT INTO ' . $dbarr['pre'] . $db->get_cindex($sourceid) . ' 
                      (articleid, articlename, chaptername, postdate, lastupdate, chapterorder, ' . $wordstr . ', chaptertype, posterid, poster) 
                      VALUES (:sourceid, :articlename, :chaptername, :postdate, :lastupdate, :chapterorder, :words, 0, 1, "admin")';
        $stmt = $db->prepare($insertsql);
        $postdate = time();
        $lastupdate = time();
        $stmt->bindParam(':sourceid', $sourceid, PDO::PARAM_INT);
        $stmt->bindParam(':articlename', $articlename, PDO::PARAM_STR);
        $stmt->bindParam(':chaptername', $chaptername, PDO::PARAM_STR);
        $stmt->bindParam(':postdate', $postdate, PDO::PARAM_INT);
        $stmt->bindParam(':lastupdate', $lastupdate, PDO::PARAM_INT);
        $stmt->bindParam(':chapterorder', $chapterorder, PDO::PARAM_INT);
        $stmt->bindParam(':words', $words, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            die('chapter表写入失败');
        }

        $chapteridsql = 'SELECT chapterid 
                         FROM ' . $dbarr['pre'] . $db->get_cindex($sourceid) . ' 
                         WHERE articleid = :sourceid AND chaptertype = 0 
                         ORDER BY chapterorder DESC LIMIT 1';
        $stmt = $db->prepare($chapteridsql);
        $stmt->bindParam(':sourceid', $sourceid, PDO::PARAM_INT);
        $stmt->execute();
        $chapterid = $stmt->fetch(PDO::FETCH_ASSOC)['chapterid'];

        $chapter_file = $root_dir . '/files/article/txt/' . intval($sourceid / 1000) . '/' . $sourceid . '/' . $chapterid . '.txt';
        $articlesql = 'UPDATE ' . $dbarr['pre'] . 'article_article 
                       SET lastupdate = :lastupdate, 
                           ' . $wordstr . ' = ' . $wordstr . ' + :words, 
                           lastchapter = :chaptername, 
                           lastchapterid = :chapterid, 
                           chapters = chapters + 1 
                       WHERE articleid = :sourceid';
        $stmt = $db->prepare($articlesql);
        $lastupdate = time();
        $stmt->bindParam(':lastupdate', $lastupdate, PDO::PARAM_INT);
        $stmt->bindParam(':words', $words, PDO::PARAM_INT);
        $stmt->bindParam(':chaptername', $chaptername, PDO::PARAM_STR);
        $stmt->bindParam(':chapterid', $chapterid, PDO::PARAM_INT);
        $stmt->bindParam(':sourceid', $sourceid, PDO::PARAM_INT);

        if (ss_writefile($chapter_file, $content) && $stmt->execute()) {
            echo "200";
        }
        break;
}
?>