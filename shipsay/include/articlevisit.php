<?php $cookie_time=strtotime('tomorrow');
if(empty($_REQUEST['vote']))
{
	if(empty($_COOKIE["articlevisited"]))
	{
		$visitsql='SELECT lastvisit FROM '.$dbarr['pre'].'article_article WHERE articleid = '.$sourceid;
		$lastvisit=$db->ss_getone($visitsql)['lastvisit'];
		$retsql=ss_add_or_up($lastvisit,'visit');
		$sql='UPDATE '.$dbarr['pre'].'article_article  '.$retsql.'  WHERE articleid='.$sourceid;
		if($db->ss_query($sql))
		{
			setcookie("articlevisited",1,$cookie_time,'/');
		}
		else
		{
			die('写入访问数失败');
		}
	}
}
else
{
	if(empty($_COOKIE["articlevote"])||$_COOKIE["articlevote"]<$vote_perday)
	{
		$sourceid=$is_multiple?ss_sourceid($_REQUEST['articleid']):$_REQUEST['articleid'];
		$votesql='SELECT lastvote FROM '.$dbarr['pre'].'article_article WHERE articleid = '.$sourceid;
		$lastvote=$db->ss_getone($votesql)['lastvote'];
		$retsql=ss_add_or_up($lastvote,'vote');
		$sql='UPDATE '.$dbarr['pre'].'article_article  '.$retsql.'  WHERE articleid='.$sourceid;
		if($db->ss_query($sql))
		{
			if(empty($_COOKIE["articlevote"]))
			{
				setcookie("articlevote",1,$cookie_time,'/');
			}
			else
			{
				setcookie("articlevote",$_COOKIE["articlevote"]+1,$cookie_time,'/');
			}
			echo '200';
		}
		else
		{
			die('写入推荐数失败');
		}
	}
}
function ss_add_or_up($time,$column)
{
	$time=intval($time);
	$flagY=date('Y',$time)==date('Y',time())?true:false;
	$flagM=date('m',$time)==date('m',time())?true:false;
	$flagW=date('W',$time)==date('W',time())?true:false;
	$flagD=date('d',$time)==date('d',time())?true:false;
	$allstr='all'.$column.'=all'.$column.'+1';
	$monthstr='month'.$column.'=';
	$monthstr.=($flagY&&$flagM)?'month'.$column.' + 1':'1';
	$weekstr='week'.$column.'=';
	$weekstr.=($flagY&&$flagM&&$flagW)?'week'.$column.' + 1':'1';
	$daystr='day'.$column.'=';
	$daystr.=($flagY&&$flagM&&$flagW&&$flagD)?'day'.$column.' + 1':'1';
	$retsql='SET last'.$column.'='.time().','.$daystr.','.$weekstr.','.$monthstr.','.$allstr;
	return $retsql;
}
?>