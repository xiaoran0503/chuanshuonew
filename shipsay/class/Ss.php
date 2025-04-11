<?php
class Ss
{
    // 获取客户端 IP 地址
    static function ss_get_ip()
    {
        $ip = false;
        // 检查 HTTP_CLIENT_IP 头
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        // 检查 HTTP_X_FORWARDED_FOR 头
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) {
                array_unshift($ips, $ip);
                $ip = false;
            }
            foreach ($ips as $single_ip) {
                // 过滤掉内网 IP
                if (!preg_match('/^(10|172\.16|192\.168)\./i', $single_ip)) {
                    $ip = $single_ip;
                    break;
                }
            }
        }
        // 如果没有找到合适的 IP，使用 REMOTE_ADDR
        return $ip ?: $_SERVER['REMOTE_ADDR'];
    }

    // 删除指定目录及其所有内容
    static function ss_delfolder($dirname)
    {
        $dirname = trim($dirname);
        // 打开目录
        if (!is_dir($dirname)) {
            return false;
        }
        $handle = opendir($dirname);
        if ($handle === false) {
            return false;
        }
        // 遍历目录中的文件和子目录
        while (($file = readdir($handle)) !== false) {
            if ($file != '.' && $file != '..') {
                $path = $dirname . DIRECTORY_SEPARATOR . $file;
                if (is_dir($path)) {
                    // 递归删除子目录
                    self::ss_delfolder($path);
                } else {
                    // 删除文件
                    if (!unlink($path)) {
                        closedir($handle);
                        return false;
                    }
                }
            }
        }
        closedir($handle);
        // 删除目录
        return rmdir($dirname);
    }

    // 检测是否为移动设备
    static function is_mobile()
    {
        // 检查常见的移动设备标识头
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        if (isset($_SERVER['HTTP_CLIENT']) && 'PhoneClient' == $_SERVER['HTTP_CLIENT']) {
            return true;
        }
        if (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], 'wap')) {
            return true;
        }
        // 检查 User-Agent 头
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = [
                'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-',
                'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu',
                'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi',
                'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'
            ];
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        // 检查 HTTP_ACCEPT 头
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml')!== false) &&
                (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false ||
                    (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))
            ) {
                return true;
            }
        }
        return false;
    }

    // 检测是否为搜索引擎机器人
    static function is_robot()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
            // 检查 User-Agent 中是否包含机器人标识
            return preg_match('/spider|sogou|soso|bing|google/i', $agent);
        }
        return false;
    }
}
?>