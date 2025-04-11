<?php
switch($_REQUEST['do'])
{
	case "report":require_once __ROOT_DIR__.'/shipsay/configs/report.ini.php';
	if(empty($ShipSayReport['on']))die('报错功能已关闭');
	$articleid=intval($_REQUEST['articleid']);
	$chapterid=intval($_REQUEST['chapterid']);
	if($is_multiple)
	{
		$articleid=ss_sourceid($articleid);
		$chapterid=ss_sourceid($chapterid);
	}
	$articlename=empty($_REQUEST['articlename'])?'':$_REQUEST['articlename'];
	$chaptername=empty($_REQUEST['chaptername'])?'':$_REQUEST['chaptername'];
	$repurl=$_REQUEST['repurl'];
	$content=empty($_REQUEST['content'])?'':$_REQUEST['content'];
	$ip=Ss::ss_get_ip();
	$reptime=date("U");
	$sql='INSERT INTO shipsay_article_report (articleid,chapterid,articlename,chaptername,repurl,content,ip,reptime) VALUES ("'.$articleid.'","'.$chapterid.'","'.$articlename.'","'.$chaptername.'","'.$repurl.'","'.$content.'","'.$ip.'" , "'.$reptime.'")';
	if($db->ss_query($sql))echo "200";
	break;
	case "show":$sql='SELECT * FROM shipsay_article_report ORDER BY id DESC';
	$res=$db->ss_query($sql);
	$data_arr=[];
	if($res->num_rows)
	{
		$k=0;
		while($rows=mysqli_fetch_assoc($res))
		{
			$data_arr[$k]['id']=$rows['id'];
			$data_arr[$k]['articleid']=$rows['articleid'];
			$data_arr[$k]['chapterid']=$rows['chapterid'];
			$data_arr[$k]['articlename']=$rows['articlename'];
			$data_arr[$k]['chaptername']=$rows['chaptername'];
			$data_arr[$k]['repurl']=$rows['repurl'];
			$data_arr[$k]['content']=$rows['content'];
			$data_arr[$k]['ip']=$rows['ip'];
			$data_arr[$k]['reptime']=Date('Y-m-d H:i:s',$rows['reptime']);
			$k++;
		}
	}
	$json=["code"=>0,"count"=>count($data_arr),"data"=>$data_arr];
	echo json_encode($json,JSON_UNESCAPED_UNICODE);
	break;
	case "delete":if(is_array($_REQUEST['id']))
	{
		$idstr='';
		foreach($_REQUEST['id']as $k=>$v)
		{
			if($k==0)
			{
				$idstr.=$v;
			}
			else
			{
				$idstr.=','.$v;
			}
		}
		$sql=' DELETE FROM shipsay_article_report WHERE id IN ('.$idstr.' ) ';
	}
	else
	{
		$sql='DELETE FROM shipsay_article_report WHERE id = '.$_REQUEST['id'];
	}
	if($db->ss_query($sql))echo "200";
	break;
}
?>