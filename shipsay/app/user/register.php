<?php
// 封装检查模板文件是否存在的函数
function checkTemplateFile() {
    if (!file_exists(__THEME_DIR__ . '/user/tpl_register.php')) {
        Url::ss_errpage();
    }
}

// 过滤和转义用户输入
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// 检查用户名是否合法
function isValidUsername($username) {
    return !preg_match('/^\s*$|^c:\\con\\con$|[@%,;:\.\|\*\"\'\\\\\/\s\t\<\>\&]|　/is', $username);
}

// 检查密码长度
function isValidPassword($password) {
    return strlen($password) >= 6;
}

// 检查邮箱格式
function isValidEmail($email) {
    return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i", $email);
}

// 设置用户登录的 cookie
function setUserCookies($userarr) {
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
}

// 主逻辑
checkTemplateFile();

if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'register' && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['email'])) {
    try {
        $username = sanitizeInput($_REQUEST['username']);
        $password = sanitizeInput($_REQUEST['password']);
        $email = sanitizeInput($_REQUEST['email']);

        if (!isValidUsername($username)) {
            throw new Exception('用户名含有非法字符');
        }
        if (!isValidPassword($password)) {
            throw new Exception('密码长度至少6位');
        }
        if (!isValidEmail($email)) {
            throw new Exception('邮箱格式错误');
        }

        $userarr = User::ss_new_user($username, $password, $email);

        if (is_array($userarr)) {
            setUserCookies($userarr);
            header('Location: /bookcase/');
            exit;
        } else {
            throw new Exception($userarr);
        }
    } catch (Exception $e) {
        echo '<script>alert("' . $e->getMessage() . '");history.go(-1);</script>';
    }
} else {
    require_once __THEME_DIR__ . '/user/tpl_register.php';
}
?>