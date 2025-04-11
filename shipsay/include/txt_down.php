<?php
// 严格类型声明，增强代码的健壮性
declare(strict_types=1);

// 过滤并验证输入，防止 SQL 注入和其他安全问题
$sourceid = filter_var($_REQUEST['articleid'] ?? 0, FILTER_VALIDATE_INT);
$articlename = filter_var($_REQUEST['articlename'] ?? '', FILTER_SANITIZE_STRING);

// 检查输入是否有效
if ($sourceid === false || empty($articlename)) {
    http_response_code(400);
    echo '无效的文章 ID 或文章名称';
    exit;
}

// 检查是否允许下载
if ($enable_down) {
    // 构建文本文件目录
    $txt_dir = $txt_url . '/' . intval($sourceid / 1000) . '/' . $sourceid;

    // 构建 SQL 查询语句，使用预处理语句或严格的类型转换来防止 SQL 注入
    $sql = 'SELECT chapterid, chaptername FROM ' . $dbarr['pre'] . $db->get_cindex($sourceid) . ' WHERE articleid = ' . $sourceid . ' AND chaptertype = 0 ORDER BY chapterorder ASC';

    // 执行 SQL 查询
    $res = $db->ss_query($sql);

    // 检查查询结果
    if ($res && $res->num_rows > 0) {
        // 设置响应头，指定文件类型和文件名
        header('Content-type: text/plain; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . urlencode('《' . $articlename . '》.txt') . '"');

        // 输出文章信息
        echo '《' . $articlename . '》来自: ' . $_SERVER['HTTP_REFERER'];

        // 逐章输出文本内容
        while ($rows = mysqli_fetch_assoc($res)) {
            // 输出章节标题
            echo "\n\n===" . Text::ss_toutf8($rows['chaptername']) . "===\n\n";

            // 读取章节文本文件
            $txt_file = $txt_dir . '/' . $rows['chapterid'] . '.txt';
            if (file_exists($txt_file)) {
                $txt_buffer = file_get_contents($txt_file);
                $txt_buffer = Text::ss_toutf8(trim($txt_buffer));
                echo $txt_buffer;
            } else {
                echo '章节文件不存在';
            }

            // 刷新输出缓冲区
            ob_flush();
            flush();
        }
    } else {
        // 没有找到相关章节
        http_response_code(404);
        echo '未找到相关章节';
    }
} else {
    // TXT 下载已关闭
    http_response_code(403);
    echo 'TXT下载已关闭';
}
?>