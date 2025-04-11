<?php
require_once 'function.php';

// 验证 'do' 参数
if (!isset($_REQUEST['do'])) {
    http_response_code(400);
    echo json_encode(["code" => 400, "message" => "Missing 'do' parameter"], JSON_UNESCAPED_UNICODE);
    exit;
}

$do = $_REQUEST['do'];

switch ($do) {
    case "show":
        // 验证和过滤 'page' 和 'limit' 参数
        $page = isset($_REQUEST['page']) ? filter_var($_REQUEST['page'], FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]) : 1;
        $limit = isset($_REQUEST['limit']) ? filter_var($_REQUEST['limit'], FILTER_VALIDATE_INT, ['options' => ['default' => 10, 'min_range' => 1]]) : 10;

        // 计算总记录数
        $countSql = 'SELECT COUNT(*) FROM shipsay_article_search';
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
        $dataSql = "SELECT * FROM shipsay_article_search ORDER BY searchid DESC LIMIT $offset, $limit";
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
                'searchid' => $row['searchid'],
                'keywords' => $row['keywords'],
                'results' => $row['results'],
                'searchsite' => $row['searchsite'],
                'searchtime' => date('Y-m-d H:i:s', $row['searchtime'])
            ];
        }

        // 返回 JSON 数据
        $json = ["code" => 0, "count" => $totalRecords, "data" => $dataArr];
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        break;

    case "delete":
        // 验证和过滤 'searchid' 参数
        if (!isset($_REQUEST['searchid']) ||!is_array($_REQUEST['searchid'])) {
            http_response_code(400);
            echo json_encode(["code" => 400, "message" => "Invalid 'searchid' parameter"], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $searchIds = array_map('intval', $_REQUEST['searchid']);
        $idStr = implode(',', $searchIds);

        // 构建并执行删除语句
        $deleteSql = "DELETE FROM shipsay_article_search WHERE searchid IN ($idStr)";
        if ($db->ss_query($deleteSql)) {
            echo "200";
        } else {
            http_response_code(500);
            echo json_encode(["code" => 500, "message" => "Failed to delete records"], JSON_UNESCAPED_UNICODE);
        }
        break;

    case "delAll":
        // 构建并执行清空表语句
        $truncateSql = 'TRUNCATE TABLE shipsay_article_search';
        if ($db->ss_query($truncateSql)) {
            echo "200";
        } else {
            http_response_code(500);
            echo json_encode(["code" => 500, "message" => "Failed to truncate table"], JSON_UNESCAPED_UNICODE);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(["code" => 400, "message" => "Invalid 'do' value"], JSON_UNESCAPED_UNICODE);
        break;
}
?>