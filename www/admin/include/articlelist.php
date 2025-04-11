<?php
// 开启严格错误报告模式，便于调试
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 引入必要的文件
require_once 'function.php';

// 定义允许的排序字段
$allowedOrders = ['lastupdate', 'allvisit', 'monthvisit', 'weekvisit', 'dayvisit', 'allvote', 'monthvote', 'weekvote', 'dayvote'];

// 获取排序字段，默认按最后更新时间排序
$orderstr = in_array($_REQUEST['order'] ?? '', $allowedOrders) ? $_REQUEST['order'] : 'lastupdate';

// 获取当前页码，默认第一页
$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;

// 获取分类 ID 条件
$sortidstr = isset($_REQUEST['sortid']) ? ' AND sortid = :sortid' : '';

// 获取完本状态条件
$fullstr = isset($_REQUEST['fullflag']) ? ' AND fullflag > 0 ' : '';

// 获取显示状态条件
$displaystr = isset($_REQUEST['display']) ? ' AND display = 1 ' : '';

// 获取每页显示数量，默认 10 条
$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 10;

// 初始化搜索条件
$search_str = '';
$searchParams = [];

// 处理搜索请求
if ($_REQUEST['do'] === 'search') {
    $searchkey = trim($_REQUEST['searchkey']);
    $search_str = ' AND (articlename LIKE :searchkey OR author LIKE :searchkey)';
    $searchParams[':searchkey'] = "%$searchkey%";
}

// 构建统计总记录数的 SQL 查询
$countSql = "SELECT COUNT(*) FROM {$dbarr['pre']}article_article WHERE {$dbarr['words']} >= 0 $displaystr$sortidstr$fullstr$search_str";
$countStmt = $db->prepare($countSql);

// 绑定搜索参数
if (!empty($searchParams)) {
    foreach ($searchParams as $key => $value) {
        $countStmt->bindValue($key, $value);
    }
}

// 绑定分类 ID 参数
if (isset($_REQUEST['sortid'])) {
    $countStmt->bindValue(':sortid', $_REQUEST['sortid']);
}

// 执行统计查询
$countStmt->execute();
$rows = $countStmt->fetch(PDO::FETCH_NUM);
$allpage = ceil($rows[0] / $limit);

// 确保当前页码在有效范围内
if ($page > $allpage) {
    $page = $allpage;
}

// 构建获取文章列表的 SQL 查询
$listSql = "$rico_sql$displaystr$sortidstr$fullstr$search_str ORDER BY $orderstr DESC LIMIT :offset, :limit";
$listStmt = $db->prepare($listSql);

// 绑定搜索参数
if (!empty($searchParams)) {
    foreach ($searchParams as $key => $value) {
        $listStmt->bindValue($key, $value);
    }
}

// 绑定分类 ID 参数
if (isset($_REQUEST['sortid'])) {
    $listStmt->bindValue(':sortid', $_REQUEST['sortid']);
}

// 绑定偏移量和每页显示数量
$offset = ($page - 1) * $limit;
$listStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$listStmt->bindValue(':limit', $limit, PDO::PARAM_INT);

// 执行文章列表查询
$listStmt->execute();
$retarr = $listStmt->fetchAll(PDO::FETCH_ASSOC);

// 处理文章列表数据
foreach ($retarr as &$v) {
    $v['sourceid'] = $is_multiple ? ss_sourceid($v['articleid']) : $v['articleid'];
    $v['lastupdate_cn'] = date('Y-m-d H:i:s', $v['lastupdate']);
}
unset($v);

// 构建 JSON 响应数据
$json = [
    'code' => 0,
    'count' => $rows[0],
    'data' => $retarr
];

// 输出 JSON 响应
header('Content-Type: application/json; charset=utf-8');
echo json_encode($json, JSON_UNESCAPED_UNICODE);
?>