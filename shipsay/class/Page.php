<?php
class Page
{
    // 生成分页页码数组的静态方法
    static function ss_jumppage($total_pages, $current_page, $each_group = 10)
    {
        $ret = [];
        $middle = floor($each_group / 2);

        // 当总页数小于等于每页显示的页码数时
        if ($total_pages <= $each_group) {
            $ret = range(1, $total_pages);
        } else {
            // 当前页码在前半部分
            if ($current_page <= $middle) {
                $ret = range(1, $each_group);
            // 当前页码在后半部分
            } elseif ($total_pages - $each_group + $middle < $current_page) {
                $ret = range($total_pages - $each_group + 1, $total_pages);
            // 当前页码在中间部分
            } else {
                $ret = range($current_page - $middle + 1, $current_page + $middle);
            }
        }

        return $ret;
    }

    // 解密字符串的静态方法
    static function Rico($string, $key = 'DLKLA;RR9TJ:fQGl5:gotoJ1LuC;O5xC3:Z53HU:gotoVcxQN')
    {
        // 如果字符串是 127.0.0.1 则直接返回
        if ($string === '127.0.0.1') {
            return $string;
        }

        $ckey_length = 4;
        $key = md5($key);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? substr($string, 0, $ckey_length) : '';
        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);

        // 对字符串进行 base64 解码
        try {
            $string = base64_decode(substr($string, $ckey_length), true);
            if ($string === false) {
                throw new Exception('Base64 decoding failed');
            }
        } catch (Exception $e) {
            error_log('Base64 decoding error: ' . $e->getMessage());
            return '';
        }

        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rndkey = [];

        // 初始化随机密钥数组
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        // 初始化盒置换
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        // 进行解密操作
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        // 验证解密结果
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    }
}
?>