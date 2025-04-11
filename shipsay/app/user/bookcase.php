<?php
if(!file_exists(__THEME_DIR__.'/user/tpl_bookcase.php'))Url::ss_errpage();
if(isset($_COOKIE['ss_userid'])&&isset($_COOKIE['ss_username'])&&isset($_COOKIE['ss_password']))
{
	if(User::ss_check_login($_COOKIE['ss_userid'],$_COOKIE['ss_password']))
	{
		$sql='SELECT caseid,articleid,articlename,chapterid,chaptername FROM '.$dbarr['pre'].'article_bookcase WHERE userid = "'.$_COOKIE['ss_userid'].'" ORDER BY caseid DESC';
		$caseObj=$db->ss_query($sql);
		$caseArr=array();
		if($caseObj->num_rows)
		{
			$k=0;
			while($rows=mysqli_fetch_assoc($caseObj))
			{
				$tmp=$db->ss_getrows($rico_sql.'AND articleid = '.$rows['articleid']);
				$caseArr[$k]['img_url']=$tmp[0]['img_url'];
				$caseArr[$k]['info_url']=$tmp[0]['info_url'];
				$caseArr[$k]['index_url']=$tmp[0]['index_url'];
				$caseArr[$k]['lastchapter']=$tmp[0]['lastchapter'];
				$caseArr[$k]['last_url']=$tmp[0]['last_url'];
				$caseArr[$k]['lastupdate']=$tmp[0]['lastupdate'];
				$caseArr[$k]['author']=$tmp[0]['author'];
				if($is_multiple)
				{
					$rows['articleid']=ss_newid($rows['articleid']);
					$rows['chapterid']=ss_newid($rows['chapterid']);
				}
				$caseArr[$k]['articleid']=$rows['articleid'];
				$caseArr[$k]['articlename']=$rows['articlename'];
				if($is_acode)$rows['articleid']=$tmp[0]['articlecode'];
				$caseArr[$k]['case_url']=Url::chapter_url($rows['articleid'],$rows['chapterid']);
				$caseArr[$k]['chaptername']=$rows['chaptername'];
				$k++;
			}
		}
		require_once __THEME_DIR__.'/user/tpl_bookcase.php';
	}
	else
	{
		header('Location: /login/');
	}
}
else
{
	header('Location: /login/');
}
?>