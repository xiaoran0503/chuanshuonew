<?php
class Text
{
    static function strArr2p(array $strArr): string
    {
        $ret = '';
        foreach ($strArr as $v) {
            $tmpvar = preg_replace('/　{2,}|\s{2,}/is', ' ', $v);
            if (strlen($tmpvar) > 1) {
                $ret .= '<p>' . $tmpvar . '</p>';
            }
        }
        return $ret;
    }

    static function readpage_split(string $txt, int $page = 2, string $param = "\n"): array
    {
        $txt = htmlspecialchars($txt, ENT_NOQUOTES);
        $retarr = [];
        $all_lines = explode($param, $txt);
        $split_lines = ceil(count($all_lines) / $page);
        $tmparr = array_chunk($all_lines, $split_lines);
        for ($i = 0; $i < count($tmparr); $i++) {
            $retarr[$i] = self::strArr2p($tmparr[$i]);
        }
        return $retarr;
    }

    static function ss_txt2p(string $txt, string $param = "\n"): string
    {
        $arr = explode($param, $txt);
        return self::strArr2p($arr);
    }

    static function ss_txt2des(string $txt): string
    {
        $ret = htmlspecialchars($txt, ENT_NOQUOTES);
        $ret = preg_replace('/　{2,}|\s{2,}/is', ' ', $ret);
        $ret = mb_substr($ret, 0, 200, 'UTF-8');
        return $ret;
    }

    static function ss_lastupdate(int $time): string
    {
        global $is_ft;
        $ret = '';
        $time = intval($time);
        if ($time > time()) {
            $time = time();
        }
        $diff = time() - $time;
        if ($diff < 2 * 60) {
            $ret = '刚刚';
        } elseif ($diff < 60 * 60) {
            $ret = floor($diff / 60) . '分钟前';
        } elseif ($diff < 60 * 60 * 24) {
            $ret = floor($diff / (60 * 60)) . '小时前';
        } elseif ($diff < 60 * 60 * 24 * 30) {
            $ret = floor($diff / (60 * 60 * 24)) . '天前';
        } elseif ($diff < 60 * 60 * 24 * 365) {
            $ret = floor($diff / (60 * 60 * 24 * 30)) . '个月前';
        } else {
            $ret = date('Y-m-d', $time);
        }
        if ($is_ft) {
            $ret = Convert::jt2ft($ret);
        }
        return $ret;
    }

    static function ss_toutf8(string $str): string
    {
        if (mb_detect_encoding($str, 'UTF-8', true) === false) {
            $str = mb_convert_encoding($str, 'UTF-8', ['GBK', 'GB2312']);
        }
        return $str;
    }

    static function ss_get_contents(string $url): string
    {
        if (function_exists('curl_init')) {
            $ci = curl_init();
            curl_setopt($ci, CURLOPT_URL, $url);
            curl_setopt($ci, CURLOPT_REFERER, $url);
            curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ci, CURLOPT_TIMEOUT, 60);
            curl_setopt($ci, CURLOPT_HEADER, 0);
            curl_setopt($ci, CURLOPT_ENCODING, 'gzip,deflate');
            $useragent = ' Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
            curl_setopt($ci, CURLOPT_USERAGENT, $useragent);
            $ret = curl_exec($ci);
            $err = curl_error($ci);
            if ($ret === false || !empty($err)) {
                $ret = $err;
            }
            curl_close($ci);
        } else {
            $context = ["ssl" => ["verify_peer" => false, "verify_peer_name" => false]];
            $stream_context = stream_context_create($context);
            $ret = @file_get_contents("compress.zlib://" . $url, false, $stream_context);
        }
        return $ret;
    }

    static function ss_substr(string $str, int $int = 6): string
    {
        return @mb_substr($str, 0, $int, 'UTF-8');
    }

    static function ss_filter(string $str, string $filter_str): string
    {
        function ss_str2preg(string $str): string
        {
            $from = ['\\$\\$\\$\\$', '/', '\\.', '\'', '"'];
            $to = ['.*?', '\\/', '\\.', '\'', '\"'];
            $ret = preg_quote(trim($str));
            $ret = str_replace($from, $to, $ret);
            return $ret;
        }
        $filterlines = explode("\n", trim($filter_str));
        $from = [];
        $to = [];
        foreach ($filterlines as $v) {
            $tmparr = explode("♂", $v);
            $from[] = '/' . ss_str2preg($tmparr[0]) . '/is';
            $to[] = $tmparr[1];
        }
        return preg_replace($from, $to, $str);
    }
}
?>