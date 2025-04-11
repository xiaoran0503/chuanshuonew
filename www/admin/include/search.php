<?php require_once 'function.php';
switch($_REQUEST['do'])
{
	case "show":$page=isset($_REQUEST['page'])?intval($_REQUEST['page']):1;
	$limit=isset($_REQUEST['limit'])?intval($_REQUEST['limit']):10;
	$sql='SELECT COUNT(*) FROM shipsay_article_search';
	$allrows=mysqli_fetch_array($db->ss_query($sql));
	$allpage=ceil($allrows[0]/$limit);
	if($page>$allpage)$page=$allpage;
	$sql='SELECT * FROM shipsay_article_search ORDER BY searchid DESC LIMIT '.(($page-1)*$limit).','.$limit;
	$data_arr=[];
	if($res=$db->ss_query($sql))
	{
		$k=0;
		while($rows=mysqli_fetch_assoc($res))
		{
			$data_arr[$k]['searchid']=$rows['searchid'];
			$data_arr[$k]['keywords']=$rows['keywords'];
			$data_arr[$k]['results']=$rows['results'];
			$data_arr[$k]['searchsite']=$rows['searchsite'];
			$data_arr[$k]['searchtime']=Date('Y-m-d H:i:s',$rows['searchtime']);
			$k++;
		}
	}
	$json=["code"=>0,"count"=>$allrows[0],"data"=>$data_arr];
	echo json_encode($json,JSON_UNESCAPED_UNICODE);
	break;
	case "delete":$idstr=implode(',',$_REQUEST['searchid']);
	$sql=' DELETE FROM shipsay_article_search WHERE searchid IN ('.$idstr.' ) ';
	if($db->ss_query($sql))echo "200";
	break;
	case "delAll":$sql='TRUNCATE TABLE shipsay_article_search';
	if($db->ss_query($sql))echo "200";
	break;
}
?>