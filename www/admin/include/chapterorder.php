<?php
require_once 'function.php';

// 验证输入
if (empty($_REQUEST['fromid']) || (empty($_REQUEST['toid']) && $_REQUEST['toid']!== '0')) {
    http_response_code(400);
    die('没有选择章节');
}

$sourceid = filter_var($_REQUEST['sourceid'], FILTER_VALIDATE_INT);
if ($sourceid === false) {
    http_response_code(400);
    die('无效的 sourceid');
}

$tmparr = explode(',', $_REQUEST['fromid']);
if (count($tmparr) < 2) {
    http_response_code(400);
    die('无效的 fromid');
}

$fromid = filter_var($tmparr[0], FILTER_VALIDATE_INT);
if ($fromid === false) {
    http_response_code(400);
    die('无效的 fromid 中的第一个值');
}

$chapterid = filter_var($tmparr[1], FILTER_VALIDATE_INT);
if ($chapterid === false) {
    http_response_code(400);
    die('无效的 fromid 中的第二个值');
}

$toid = filter_var($_REQUEST['toid'], FILTER_VALIDATE_INT);
if ($toid === false) {
    http_response_code(400);
    die('无效的 toid');
}

try {
    // 开启事务
    $db->beginTransaction();

    if ($fromid < $toid) {
        $sql1 = 'UPDATE '.$dbarr['pre'].$db->get_cindex($sourceid).' 
                 SET chapterorder = chapterorder - 1 
                 WHERE articleid = :sourceid AND chapterorder > :fromid AND chapterorder <= :toid';
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindParam(':sourceid', $sourceid, PDO::PARAM_INT);
        $stmt1->bindParam(':fromid', $fromid, PDO::PARAM_INT);
        $stmt1->bindParam(':toid', $toid, PDO::PARAM_INT);
        $stmt1->execute();

        $sql2 = 'UPDATE '.$dbarr['pre'].$db->get_cindex($sourceid).' 
                 SET chapterorder = :toid 
                 WHERE chapterid = :chapterid';
        $stmt2 = $db->prepare($sql2);
        $stmt2->bindParam(':toid', $toid, PDO::PARAM_INT);
        $stmt2->bindParam(':chapterid', $chapterid, PDO::PARAM_INT);
        $stmt2->execute();
    } else {
        $sql1 = 'UPDATE '.$dbarr['pre'].$db->get_cindex($sourceid).' 
                 SET chapterorder = chapterorder + 1 
                 WHERE articleid = :sourceid AND chapterorder < :fromid AND chapterorder > :toid';
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindParam(':sourceid', $sourceid, PDO::PARAM_INT);
        $stmt1->bindParam(':fromid', $fromid, PDO::PARAM_INT);
        $stmt1->bindParam(':toid', $toid, PDO::PARAM_INT);
        $stmt1->execute();

        $newToId = $toid + 1;
        $sql2 = 'UPDATE '.$dbarr['pre'].$db->get_cindex($sourceid).' 
                 SET chapterorder = :newToId 
                 WHERE chapterid = :chapterid';
        $stmt2 = $db->prepare($sql2);
        $stmt2->bindParam(':newToId', $newToId, PDO::PARAM_INT);
        $stmt2->bindParam(':chapterid', $chapterid, PDO::PARAM_INT);
        $stmt2->execute();
    }

    // 获取最后一章的信息
    $lastid_sql = 'SELECT chapterid, chaptername 
                   FROM '.$dbarr['pre'].$db->get_cindex($sourceid).' 
                   WHERE articleid = :sourceid AND chaptertype = 0 
                   ORDER BY chapterorder DESC LIMIT 1';
    $stmt3 = $db->prepare($lastid_sql);
    $stmt3->bindParam(':sourceid', $sourceid, PDO::PARAM_INT);
    $stmt3->execute();
    $tmparr = $stmt3->fetch(PDO::FETCH_ASSOC);

    if ($tmparr) {
        $lastchapterid = $tmparr['chapterid'];
        $lastchapter = $tmparr['chaptername'];
    } else {
        $lastchapterid = 0;
        $lastchapter = '';
    }

    // 更新文章表的最后更新信息
    $articlesql = 'UPDATE '.$dbarr['pre'].'article_article 
                   SET lastupdate = :lastupdate, 
                       lastchapter = :lastchapter, 
                       lastchapterid = :lastchapterid 
                   WHERE articleid = :sourceid';
    $stmt4 = $db->prepare($articlesql);
    $lastupdate = time();
    $stmt4->bindParam(':lastupdate', $lastupdate, PDO::PARAM_INT);
    $stmt4->bindParam(':lastchapter', $lastchapter, PDO::PARAM_STR);
    $stmt4->bindParam(':lastchapterid', $lastchapterid, PDO::PARAM_INT);
    $stmt4->bindParam(':sourceid', $sourceid, PDO::PARAM_INT);
    $stmt4->execute();

    // 提交事务
    $db->commit();
    echo "200";
} catch (PDOException $e) {
    // 回滚事务
    $db->rollBack();
    http_response_code(500);
    die('chapter表排序失败: '.$e->getMessage());
}
?>