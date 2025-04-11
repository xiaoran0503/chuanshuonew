<?php
// 引入必要的函数文件
require_once 'function.php';

// 检查会话是否已启动，如果未启动则启动会话
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// 检查是否为 POST 请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 过滤并验证密码输入
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    if (!$password) {
        // 密码为空，输出错误信息
        http_response_code(400);
        echo '密码不能为空';
        exit;
    }

    // 获取用户 ID
    $uid = $_SESSION['ss_userid'] ?? null;
    if (!$uid) {
        // 用户未登录，输出错误信息
        http_response_code(401);
        echo '用户未登录';
        exit;
    }

    try {
        // 调用修改密码的方法
        if (User::ss_change_pass($uid, $password) === '200') {
            // 修改成功，输出 200
            echo '200';
        } else {
            // 修改失败，输出错误信息
            http_response_code(500);
            echo '修改密码失败';
        }
    } catch (Exception $e) {
        // 捕获异常，输出错误信息
        http_response_code(500);
        echo '发生错误: '. $e->getMessage();
    }
} else {
    // 不是 POST 请求，输出错误信息
    http_response_code(405);
    echo '只允许 POST 请求';
}
?>