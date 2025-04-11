<?php $host=$_REQUEST['dbhost'];
$port=$_REQUEST['dbport'];
$user=$_REQUEST['dbuser'];
$pass=$_REQUEST['dbpass'];
$name=$_REQUEST['dbname']?:'ShipSayDb';
$conn=mysqli_connect($host.':'.$port,$user,$pass,$name)or die('连接失败, 请检查配置');
echo "连接成功, 数据库版本 : ".mysqli_get_server_info($conn);
?>