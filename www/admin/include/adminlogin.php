<?php
// 开启严格错误报告模式，便于调试
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 引入必要的文件
require_once 'function.php';

// 启动会话
session_start();

// 销毁当前会话数据
session_unset();
session_destroy();

// 对用户输入进行过滤和验证
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

// 检查用户名和密码是否为空
if (empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => '用户名和密码不能为空']);
    exit;
}

// 调用登录方法
$userarr = User::ss_login_do($username, $password);

// 检查登录结果
if (is_array($userarr) && $userarr['ss_groupid'] === 2) {
    // 重新启动会话
    session_start();

    // 存储用户信息到会话中
    $_SESSION['ss_userid'] = $userarr['ss_userid'];
    $_SESSION['ss_username'] = $userarr['ss_username'];
    $_SESSION['ss_password'] = $userarr['ss_password'];
    $_SESSION['ss_groupid'] = $userarr['ss_groupid'];

    // 输出成功信息
    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => '登录成功']);
} else {
    // 输出失败信息
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => '用户名或密码错误']);
}
?>