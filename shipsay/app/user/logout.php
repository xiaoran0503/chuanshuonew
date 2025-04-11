<?php
// 封装设置 cookie 的函数，增强代码可读性和可维护性
function clearUserCookie($cookieName) {
    $cookieOptions = [
        'expires' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'httponly' => true,
        'samesite' => 'Lax'
    ];
    // 设置 cookie 并检查是否成功
    if (!setcookie($cookieName, null, $cookieOptions)) {
        die("清除 $cookieName 失败，请检查设置。");
    }
}

// 定义要清除的 cookie 数组
$cookiesToClear = ['ss_userid', 'ss_username', 'ss_password', 'ss_groupid'];

// 遍历数组清除每个 cookie
foreach ($cookiesToClear as $cookie) {
    clearUserCookie($cookie);
}

// 重定向到首页
header('Location: /');
exit;
?>