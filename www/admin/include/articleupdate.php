<?php
require_once 'function.php';

// 封装数据库更新操作
function executeQuery($db, $sql) {
    try {
        $stmt = $db->prepare($sql);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage());
        return false;
    }
}

switch ($_REQUEST['do']) {
    case 'upcover':
        if ($coverfile = $_FILES['file']['tmp_name']) {
            $sourceid = intval($_REQUEST['sourceid']);
            $cover_dir = $root_dir . '/files/article/image/' . intval($sourceid / 1000) . '/' . $sourceid;

            if (!is_dir($cover_dir)) {
                mkdir($cover_dir, 0777, true);
            }

            $filename = $cover_dir . '/' . $sourceid . 's.jpg';

            if (move_uploaded_file($coverfile, $filename)) {
                $sql = 'SELECT imgflag FROM ' . $dbarr['pre'] . 'article_article WHERE articleid = :sourceid';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':sourceid', $sourceid, PDO::PARAM_INT);
                $stmt->execute();
                $res = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($res && $res['imgflag'] != 9) {
                    $updateSql = 'UPDATE ' . $dbarr['pre'] . 'article_article SET imgflag = 9 WHERE articleid = :sourceid';
                    if (executeQuery($db, $updateSql)) {
                        echo "200";
                    } else {
                        echo "100";
                    }
                } else {
                    echo "200";
                }
            } else {
                echo "100";
            }
        }
        break;
    case 'update':
        $sourceid = intval($_REQUEST['sourceid']);
        $articlename = filter_var($_REQUEST['articlename'], FILTER_SANITIZE_STRING);
        $author = filter_var($_REQUEST['author'], FILTER_SANITIZE_STRING);
        $keywords = filter_var($_REQUEST['keywords'], FILTER_SANITIZE_STRING);
        $sortid = intval($_REQUEST['sortid']);
        $fullflag = intval($_REQUEST['fullflag']);
        $display = intval($_REQUEST['display']);
        $intro = filter_var($_REQUEST['intro'], FILTER_SANITIZE_STRING);

        if ($sys_ver < 2.4) {
            $sql = 'UPDATE ' . $dbarr['pre'] . 'article_article 
                    SET articlename = :articlename,
                        author = :author,
                        keywords = :keywords,
                        sortid = :sortid,
                        fullflag = :fullflag,
                        display = :display,
                        intro = :intro
                    WHERE articleid = :sourceid';
        } else {
            $articlecode = filter_var($_REQUEST['articlecode'], FILTER_SANITIZE_STRING);
            $backupname = filter_var($_REQUEST['backupname'], FILTER_SANITIZE_STRING);
            $sql = 'UPDATE ' . $dbarr['pre'] . 'article_article 
                    SET articlename = :articlename,
                        articlecode = :articlecode,
                        author = :author,
                        backupname = :backupname,
                        keywords = :keywords,
                        sortid = :sortid,
                        fullflag = :fullflag,
                        display = :display,
                        intro = :intro
                    WHERE articleid = :sourceid';
        }

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':articlename', $articlename, PDO::PARAM_STR);
        $stmt->bindParam(':author', $author, PDO::PARAM_STR);
        $stmt->bindParam(':keywords', $keywords, PDO::PARAM_STR);
        $stmt->bindParam(':sortid', $sortid, PDO::PARAM_INT);
        $stmt->bindParam(':fullflag', $fullflag, PDO::PARAM_INT);
        $stmt->bindParam(':display', $display, PDO::PARAM_INT);
        $stmt->bindParam(':intro', $intro, PDO::PARAM_STR);
        $stmt->bindParam(':sourceid', $sourceid, PDO::PARAM_INT);

        if (isset($articlecode)) {
            $stmt->bindParam(':articlecode', $articlecode, PDO::PARAM_STR);
        }
        if (isset($backupname)) {
            $stmt->bindParam(':backupname', $backupname, PDO::PARAM_STR);
        }

        if ($stmt->execute()) {
            echo "200";
        }
        break;
    case 'delete':
        $err = false;
        if (is_array($_REQUEST['sourceid'])) {
            $sourceids = $_REQUEST['sourceid'];
        } else {
            $sourceids[] = $_REQUEST['sourceid'];
        }

        foreach ($sourceids as $sourceid) {
            $sourceid = intval($sourceid);
            $sqlarticle = 'DELETE FROM ' . $dbarr['pre'] . 'article_article WHERE articleid = :sourceid';
            $sqlchapter = 'DELETE FROM ' . $dbarr['pre'] . $db->get_cindex($sourceid) . ' WHERE articleid = :sourceid';

            if (!executeQuery($db, $sqlarticle) || !executeQuery($db, $sqlchapter)) {
                $err = true;
            }

            $subaid = intval($sourceid / 1000);
            $txt_folder = $root_dir . '/files/article/txt/' . $subaid . '/' . $sourceid;
            $img_folder = $root_dir . '/files/article/image/' . $subaid . '/' . $sourceid;
            $att_folder = $root_dir . '/files/article/attachement/' . $subaid . '/' . $sourceid;

            Ss::ss_delfolder($txt_folder);
            Ss::ss_delfolder($img_folder);
            Ss::ss_delfolder($att_folder);
        }

        if (!$err) {
            echo "200";
        }
        break;
    case 'newarticle':
        $articlename = filter_var($_REQUEST['articlename'], FILTER_SANITIZE_STRING);
        if (!$articlename) {
            die('小说名不能为空');
        }

        $author = filter_var($_REQUEST['author'], FILTER_SANITIZE_STRING);
        $sortid = intval($_REQUEST['sortid']);
        $fullflag = intval($_REQUEST['fullflag']);
        $display = intval($_REQUEST['display']);
        $intro = filter_var($_REQUEST['intro'], FILTER_SANITIZE_STRING);
        $keywords = filter_var($_REQUEST['keywords'], FILTER_SANITIZE_STRING);

        if ($sys_ver < 2.4) {
            $sql = 'INSERT INTO ' . $dbarr['pre'] . 'article_article 
                    (articlename, author, sortid, fullflag, display, intro, keywords, postdate, lastupdate, posterid, poster) 
                    VALUES 
                    (:articlename, :author, :sortid, :fullflag, :display, :intro, :keywords, :postdate, :lastupdate, :posterid, :poster)';
        } else {
            $articlecode = filter_var($_REQUEST['articlecode'], FILTER_SANITIZE_STRING);
            $backupname = filter_var($_REQUEST['backupname'], FILTER_SANITIZE_STRING);
            $sql = 'INSERT INTO ' . $dbarr['pre'] . 'article_article 
                    (articlename, articlecode, author, backupname, sortid, fullflag, display, intro, keywords, postdate, lastupdate, posterid, poster) 
                    VALUES 
                    (:articlename, :articlecode, :author, :backupname, :sortid, :fullflag, :display, :intro, :keywords, :postdate, :lastupdate, :posterid, :poster)';
        }

        $postdate = $lastupdate = time();
        $posterid = 1;
        $poster = 'admin';

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':articlename', $articlename, PDO::PARAM_STR);
        $stmt->bindParam(':author', $author, PDO::PARAM_STR);
        $stmt->bindParam(':sortid', $sortid, PDO::PARAM_INT);
        $stmt->bindParam(':fullflag', $fullflag, PDO::PARAM_INT);
        $stmt->bindParam(':display', $display, PDO::PARAM_INT);
        $stmt->bindParam(':intro', $intro, PDO::PARAM_STR);
        $stmt->bindParam(':keywords', $keywords, PDO::PARAM_STR);
        $stmt->bindParam(':postdate', $postdate, PDO::PARAM_INT);
        $stmt->bindParam(':lastupdate', $lastupdate, PDO::PARAM_INT);
        $stmt->bindParam(':posterid', $posterid, PDO::PARAM_INT);
        $stmt->bindParam(':poster', $poster, PDO::PARAM_STR);

        if (isset($articlecode)) {
            $stmt->bindParam(':articlecode', $articlecode, PDO::PARAM_STR);
        }
        if (isset($backupname)) {
            $stmt->bindParam(':backupname', $backupname, PDO::PARAM_STR);
        }

        if ($stmt->execute()) {
            echo "200";
        }
        break;
}
?>