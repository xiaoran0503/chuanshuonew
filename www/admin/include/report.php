<?php
require_once 'function.php';

// 验证请求中的 'do' 参数是否合法
if (!isset($_REQUEST['do'])) {
    http_response_code(400);
    echo json_encode(["code" => 400, "message" => "Missing 'do' parameter"], JSON_UNESCAPED_UNICODE);
    exit;
}

$do = $_REQUEST['do'];

switch ($do) {
    case "show":
        // 验证并获取分页参数
        $page = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
        $limit = isset($_REQUEST['limit']) ? (int)$_REQUEST['limit'] : 10;

        // 计算总记录数
        $countSql = 'SELECT COUNT(*) FROM shipsay_article_report';
        $countResult = $db->ss_query($countSql);
        if (!$countResult) {
            http_response_code(500);
            echo json_encode(["code" => 500, "message" => "Failed to get total record count"], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $allRows = mysqli_fetch_array($countResult);
        $totalRecords = $allRows[0];
        $totalPages = ceil($totalRecords / $limit);

        // 确保页码在有效范围内
        if ($page > $totalPages) {
            $page = $totalPages;
        }

        // 构建并执行数据查询语句
        $offset = ($page - 1) * $limit;
        $dataSql = "SELECT * FROM shipsay_article_report ORDER BY id DESC LIMIT $offset, $limit";
        $dataResult = $db->ss_query($dataSql);
        if (!$dataResult) {
            http_response_code(500);
            echo json_encode(["code" => 500, "message" => "Failed to fetch data"], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // 处理查询结果
        $dataArr = [];
        while ($row = mysqli_fetch_assoc($dataResult)) {
            $dataArr[] = [
                'id' => $row['id'],
                'articleid' => $row['articleid'],
                'chapterid' => $row['chapterid'],
                'articlename' => $row['articlename'],
                'chaptername' => $row['chaptername'],
                'repurl' => $row['repurl'],
                'content' => $row['content'],
                'ip' => $row['ip'],
                'reptime' => date('Y-m-d H:i:s', $row['reptime'])
            ];
        }

        // 返回 JSON 数据
        $json = ["code" => 0, "count" => $totalRecords, "data" => $dataArr];
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        break;

    case "delete":
        // 验证并处理要删除的 ID 数组
        if (!isset($_REQUEST['id']) ||!is_array($_REQUEST['id'])) {
            http_response_code(400);
            echo json_encode(["code" => 400, "message" => "Invalid 'id' parameter"], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $ids = array_map('intval', $_REQUEST['id']);
        $idStr = implode(',', $ids);

        // 构建并执行删除语句
        $deleteSql = "DELETE FROM shipsay_article_report WHERE id IN ($idStr)";
        if ($db->ss_query($deleteSql)) {
            echo "200";
        } else {
            http_response_code(500);
            echo json_encode(["code" => 500, "message" => "Failed to delete records"], JSON_UNESCAPED_UNICODE);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(["code" => 400, "message" => "Invalid 'do' value"], JSON_UNESCAPED_UNICODE);
        break;
}
?>