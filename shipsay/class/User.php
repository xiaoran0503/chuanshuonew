<?php
class User
{
    /**
     * 检查用户是否登录
     *
     * @param string $cookie_userid 用户ID
     * @param string $cookie_pass 用户密码
     * @return bool 如果登录返回true，否则返回false
     */
    static function ss_check_login($cookie_userid, $cookie_pass)
    {
        global $dbarr, $db;
        $sql = 'SELECT uid FROM ' . $dbarr['pre'] . 'system_users WHERE uid = "' . $cookie_userid . '" AND pass = "' . $cookie_pass . '"';
        return (bool)$db->ss_getone($sql);
    }

    /**
     * 用户登录处理
     *
     * @param string $username 用户名
     * @param string $password 用户密码
     * @return array|string 登录成功返回用户信息数组，失败返回错误信息
     */
    static function ss_login_do($username, $password)
    {
        global $dbarr, $db;
        $low_version = ($dbarr['words'] == 'size');
        $sql = $low_version 
            ? 'SELECT uid,uname,pass,groupid FROM ' . $dbarr['pre'] . 'system_users WHERE uname = "' . $username . '" AND pass = "' . md5($password) . '"'
            : 'SELECT uid,uname,salt,pass,groupid FROM ' . $dbarr['pre'] . 'system_users WHERE uname = "' . $username . '"';

        $resarr = $db->ss_getone($sql);
        $islogin = $low_version 
            ? (bool)$resarr
            : ($resarr && $resarr['pass'] === md5(md5($password).$resarr['salt']));

        if ($islogin) {
            return [
                'ss_userid' => $resarr['uid'],
                'ss_username' => $resarr['uname'],
                'ss_password' => $resarr['pass'],
                'ss_groupid' => $resarr['groupid']
            ];
        }
        return 'login failed';
    }

    /**
     * 创建新用户
     *
     * @param string $username 用户名
     * @param string $password 用户密码
     * @param string $email 用户邮箱
     * @return array|string 注册成功返回用户信息数组，失败返回错误信息
     */
    static function ss_new_user($username, $password, $email)
    {
        global $dbarr, $db;
        if ($db->ss_getone('SELECT uname FROM ' . $dbarr['pre'] . 'system_users WHERE uname = "' . $username . '"')) {
            return '用户名已注册';
        }
        if ($db->ss_getone('SELECT uname FROM ' . $dbarr['pre'] . 'system_users WHERE email = "' . $email . '"')) {
            return '邮箱已注册';
        }

        $low_version = ($dbarr['words'] == 'size');
        if ($low_version) {
            $sql = 'INSERT INTO ' . $dbarr['pre'] . 'system_users (uname,name,pass,groupid,regdate,email) VALUES ("' . $username . '","' . $username . '","' . md5($password) . '","3","' . date("U") . '","' . $email . '")';
        } else {
            $salt = substr(md5(uniqid(rand(), true)), -16);
            $tmppass = md5(md5($password).$salt);
            $sql = 'INSERT INTO ' . $dbarr['pre'] . 'system_users (uname,name,pass,groupid,regdate,email,salt) VALUES ("' . $username . '","' . $username . '","' . $tmppass . '","3","' . date("U") . '","' . $email . '","' . $salt . '")';
        }
        $db->ss_query($sql);
        return self::ss_login_do($username, $password);
    }

    /**
     * 修改用户密码
     *
     * @param int $uid 用户ID
     * @param string $newpass 新密码
     * @return string 修改成功返回"200"，失败返回空
     */
    static function ss_change_pass($uid, $newpass)
    {
        global $dbarr, $db;
        $low_version = ($dbarr['words'] == 'size');
        if ($low_version) {
            $sql = 'UPDATE ' . $dbarr['pre'] . 'system_users SET pass = "' . md5($newpass) . '" WHERE uid = ' . $uid;
        } else {
            $salt = substr(md5(uniqid(rand(), true)), -16);
            $pass = md5(md5($newpass).$salt);
            $sql = 'UPDATE ' . $dbarr['pre'] . 'system_users SET pass = "' . $pass . '", salt = "' . $salt . '" WHERE uid = ' . $uid;
        }
        return $db->ss_query($sql) ? "200" : '';
    }
}
?>