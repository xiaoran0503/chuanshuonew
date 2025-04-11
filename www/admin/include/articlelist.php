<?php require_once 'function.php';
$orders=array('lastupdate','allvisit','monthvisit','weekvisit','dayvisit','allvote','monthvote','weekvote','dayvote');
$orderstr=isset($_REQUEST['order'])&&in_array($orders,$_REQUEST['order'])?$_REQUEST['order']:'lastupdate';
$page=isset($_REQUEST['page'])?intval($_REQUEST['page']):1;
$sortidstr=isset($_REQUEST['sortid'])?' AND sortid = '.$_REQUEST['sortid']:'';
$fullstr=isset($_REQUEST['fullflag'])?' AND fullflag > 0 ':'';
$displaystr=isset($_REQUEST['display'])?' AND display = 1 ':'';
$limit=isset($_REQUEST['limit'])?intval($_REQUEST['limit']):10;
$search_str='';
if($_REQUEST['do']=='search')
{
	$searchkey=trim($_REQUEST['searchkey']);
	$search_str=' AND (articlename LIKE "%'.$searchkey.'%" OR author LIKE "%'.$searchkey.'%")';
}
$sql='SELECT COUNT(*) FROM '.$dbarr['pre'].'article_article WHERE '.$dbarr['words'].' >= 0 '.$displaystr.$sortidstr.$fullstr.$search_str;
$rows=mysqli_fetch_array($db->ss_query($sql));
$allpage=ceil($rows[0]/$limit);
if($page>$allpage)$page=$allpage;
$sql=$rico_sql.$displaystr.$sortidstr.$fullstr.$search_str.' ORDER BY '.$orderstr.' DESC LIMIT '.(($page-1)*$limit).','.$limit;
$retarr=$db->ss_getrows($sql);
foreach($retarr as $k=>$v)
{
	$retarr[$k]['sourceid']=$is_multiple?ss_sourceid($v['articleid']):$v['articleid'];
	$retarr[$k]['lastupdate_cn']=date('Y-m-d H:i:s',$retarr[$k]['lastupdate']);
}
$json=["code"=>0,"count"=>$rows[0],"data"=>$retarr];
echo json_encode($json,JSON_UNESCAPED_UNICODE);
?>