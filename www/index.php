<?php define('__ROOT_DIR__',str_replace('\\','/',dirname(__DIR__)));
include_once(__ROOT_DIR__.'/shipsay/configs/config.ini.php');
$source_uri=$uri=$_SERVER['REQUEST_URI'];
if(strpos($uri,'?')!==false)$uri=substr($uri,0,strpos($uri,'?'));
require_once '../shipsay/class/router.php';
?>