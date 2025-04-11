<?php
// 开启严格模式，增强代码的健壮性
declare(strict_types=1);

// 检查请求参数中的操作类型
if (!isset($_REQUEST['do'])) {
    http_response_code(400);
    echo json_encode(['code' => 400, 'message' => '缺少操作类型参数'], JSON_UNESCAPED_UNICODE);
    exit;
}

$do = $_REQUEST['do'];

switch ($do) {
    case "report":
        require_once __ROOT_DIR__.'/shipsay/configs/report.ini.php';
        if (empty($ShipSayReport['on'])) {
            http_response_code(403);
            echo json_encode(['code' => 403, 'message' => '报错功能已关闭'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // 过滤并验证输入，防止 SQL 注入
        $articleid = filter_var($_REQUEST['articleid'] ?? 0, FILTER_VALIDATE_INT);
        $chapterid = filter_var($_REQUEST['chapterid'] ?? 0, FILTER_VALIDATE_INT);

        if ($articleid === false || $chapterid === false) {
            http_response_code(400);
            echo json_encode(['code' => 400, 'message' => '文章 ID 或章节 ID 无效'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($is_multiple) {
            $articleid = ss_sourceid($articleid);
            $chapterid = ss_sourceid($chapterid);
        }

        $articlename = $_REQUEST['articlename'] ?? '';
        $chaptername = $_REQUEST['chaptername'] ?? '';
        $repurl = $_REQUEST['repurl'] ?? '';
        $content = $_REQUEST['content'] ?? '';
        $ip = Ss::ss_get_ip();
        $reptime = date("U");

        // 使用预处理语句防止 SQL 注入
        $sql = 'INSERT INTO shipsay_article_report (articleid, chapterid, articlename, chaptername, repurl, content, ip, reptime) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $db->prepare($sql);
        $stmt->bind_param('iisssssi', $articleid, $chapterid, $articlename, $chaptername, $repurl, $content, $ip, $reptime);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(['code' => 200, 'message' => '提交成功'], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode(['code' => 500, 'message' => '提交失败，请稍后重试'], JSON_UNESCAPED_UNICODE);
        }
        $stmt->close();
        break;

    case "show":
        $sql = 'SELECT * FROM shipsay_article_report ORDER BY id DESC';
        $res = $db->query($sql);
        $data_arr = [];

        if ($res && $res->num_rows > 0) {
            while ($rows = $res->fetch_assoc()) {
                $data_arr[] = [
                    'id' => $rows['id'],
                    'articleid' => $rows['articleid'],
                    'chapterid' => $rows['chapterid'],
                    'articlename' => $rows['articlename'],
                    'chaptername' => $rows['chaptername'],
                    'repurl' => $rows['repurl'],
                    'content' => $rows['content'],
                    'ip' => $rows['ip'],
                    'reptime' => date('Y-m-d H:i:s', $rows['reptime'])
                ];
            }
        }

        $json = ["code" => 0, "count" => count($data_arr), "data" => $data_arr];
        http_response_code(200);
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        break;

    case "delete":
        if (is_array($_REQUEST['id'] ?? [])) {
            $ids = array_map('intval', $_REQUEST['id']);
            $idstr = implode(',', $ids);
            $sql = "DELETE FROM shipsay_article_report WHERE id IN ($idstr)";
        } else {
            $id = intval($_REQUEST['id'] ?? 0);
            $sql = "DELETE FROM shipsay_article_report WHERE id = $id";
        }

        if ($db->query($sql)) {
            http_response_code(200);
            echo json_encode(['code' => 200, 'message' => '删除成功'], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode(['code' => 500, 'message' => '删除失败，请稍后重试'], JSON_UNESCAPED_UNICODE);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(['code' => 400, 'message' => '无效的操作类型'], JSON_UNESCAPED_UNICODE);
        break;
}
?>