<?php require_once 'function.php';
switch($_REQUEST['do'])
{
	case "show":$page=isset($_REQUEST['page'])?intval($_REQUEST['page']):1;
	$limit=isset($_REQUEST['limit'])?intval($_REQUEST['limit']):10;
	$sql='SELECT COUNT(*) FROM shipsay_article_report';
	$allrows=mysqli_fetch_array($db->ss_query($sql));
	$allpage=ceil($allrows[0]/$limit);
	if($page>$allpage)$page=$allpage;
	$sql='SELECT * FROM shipsay_article_report ORDER BY id DESC LIMIT '.(($page-1)*$limit).','.$limit;
	$data_arr=[];
	if($res=$db->ss_query($sql))
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
	$json=["code"=>0,"count"=>$allrows[0],"data"=>$data_arr];
	echo json_encode($json,JSON_UNESCAPED_UNICODE);
	break;
	case "delete":$idstr=implode(',',$_REQUEST['id']);
	$sql=' DELETE FROM shipsay_article_report WHERE id IN ('.$idstr.' ) ';
	if($db->ss_query($sql))echo "200";
	break;
}
?>