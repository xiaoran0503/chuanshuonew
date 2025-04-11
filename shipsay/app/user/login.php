<?php
// 检查模板文件是否存在
function checkTemplateFile() {
    if (!file_exists(__THEME_DIR__ . '/user/tpl_login.php')) {
        Url::ss_errpage();
    }
}

// 过滤和转义用户输入
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// 处理登录逻辑
function handleLogin() {
    try {
        $username = sanitizeInput($_POST['username']);
        $password = sanitizeInput($_POST['password']);

        $userarr = User::ss_login_do($username, $password);

        if (is_array($userarr)) {
            $cookietime = 60 * 60 * 24 * 365;
            $cookieOptions = [
                'expires' => time() + $cookietime,
                'path' => '/',
                'domain' => '',
                'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                'httponly' => true,
                'samesite' => 'Lax'
            ];

            setcookie('ss_userid', $userarr['ss_userid'], $cookieOptions);
            setcookie('ss_username', $userarr['ss_username'], $cookieOptions);
            setcookie('ss_password', $userarr['ss_password'], $cookieOptions);
            setcookie('ss_groupid', $userarr['ss_groupid'], $cookieOptions);

            header('Location: /bookcase/');
            exit;
        } else {
            echo '<script>alert("登陆失败");history.go(-1);</script>';
        }
    } catch (Exception $e) {
        echo '<script>alert("发生错误: ' . $e->getMessage() . '");history.go(-1);</script>';
    }
}

// 主逻辑
checkTemplateFile();

if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'login' && !empty($_POST['username']) && !empty($_POST['password'])) {
    handleLogin();
} else {
    require_once __THEME_DIR__ . '/user/tpl_login.php';
}
?>