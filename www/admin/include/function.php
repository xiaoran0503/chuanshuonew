<?php
// 设置错误报告级别，建议在生产环境中根据实际情况调整
error_reporting(E_ALL & ~E_NOTICE);
// 定义根目录常量
define('__ROOT_DIR__', dirname(dirname(dirname(__DIR__))));
// 设置时区
date_default_timezone_set('Asia/Chongqing');
// 启动会话
ss_session();

// 自动加载类函数
function ss_autoload($classname)
{
    // 检查类是否已存在，若不存在则引入类文件
    if (!class_exists($classname)) {
        $classFilePath = __ROOT_DIR__ . '/shipsay/class/' . $classname . '.php';
        if (file_exists($classFilePath)) {
            require $classFilePath;
        }
    }
}
// 注册自动加载函数
spl_autoload_register('ss_autoload');

// 引入配置文件
require_once __ROOT_DIR__ . '/shipsay/configs/config.ini.php';

// 处理数据库主机配置
if (!empty($authcode)) {
    $dbarr['host'] = $authcode;
}

// 检查 URL 中是否包含 {acode}
$is_acode = strpos($fake_info_url, '{acode}')!== false;

// 合并数据库配置数组
$dbarr = array_merge([
    'pre' => $sys_ver < 5? 'jieqi_' : 'shipsay_',
    'words' => $sys_ver < 2.4? 'size' : 'words',
    'sortarr' => $sortarr,
    'is_multiple' => $is_multiple
], $dbarr);

// 创建数据库实例
$db = new Db($dbarr);

// 处理文章代码相关字符串
$articlecode_str = $sys_ver < 2.4? '' : 'articlecode,backupname,ratenum,ratesum,';

// 构建 SQL 查询语句
$rico_sql = sprintf('SELECT %s%s, articleid, articlename, intro, author, sortid, fullflag, display, lastupdate, imgflag, allvisit, allvote, goodnum, keywords, lastchapter, lastchapterid FROM %sarticle_article WHERE %s >= 0 ',
    $articlecode_str,
    $dbarr['words'],
    $dbarr['pre'],
    $dbarr['words']
);

// 写入文件函数
function ss_writefile($file_name, $data)
{
    // 检查文件目录是否存在，若不存在则创建
    $dir = dirname($file_name);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    // 设置文件权限
    @chmod($file_name, 0644);
    // 写入文件并返回结果
    return file_put_contents($file_name, $data);
}

// 启动会话函数
function ss_session()
{
    // 检查会话是否已启动，若未启动则启动会话
    if (session_status()!== PHP_SESSION_ACTIVE) {
        session_start();
    }
}
?>