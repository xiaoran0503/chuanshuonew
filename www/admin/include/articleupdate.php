<?php require_once 'function.php';
switch($_REQUEST['do'])
{
	case 'upcover':if($coverfile=$_FILES['file']['tmp_name'])
	{
		$sourceid=intval($_REQUEST['sourceid']);
		$cover_dir=$root_dir.'/files/article/image/'.intval($sourceid/1000).'/'.$sourceid;
		if(!is_dir($cover_dir))mkdir($cover_dir,0777,true);
		$filename=$cover_dir.'/'.$sourceid.'s.jpg';
		if(move_uploaded_file($coverfile,$filename))
		{
			$sql='SELECT imgflag FROM '.$dbarr['pre'].'article_article WHERE articleid = '.$sourceid;
			$res=$db->ss_getone($sql);
			if($res['imgflag']!=9)
			{
				$db->ss_query('UPDATE '.$dbarr['pre'].'article_article SET imgflag = 9 WHERE articleid = '.$sourceid);
			}
			;
			echo "200";
		}
		else
		{
			echo "100";
		}
	}
	break;
	case 'update':if($sys_ver<2.4)
	{
		$sql='UPDATE '.$dbarr['pre'].'article_article SET 
           articlename = "'.$_REQUEST['articlename'].'"
           ,author = "'.$_REQUEST['author'].'"
           ,keywords = "'.$_REQUEST['keywords'].'"
           ,sortid = "'.$_REQUEST['sortid'].'"
           ,fullflag = "'.$_REQUEST['fullflag'].'"
           ,display = "'.$_REQUEST['display'].'"
           ,intro = "'.$_REQUEST['intro'].'"
       WHERE articleid = '.$_REQUEST['sourceid'];
	}
	else
	{
		$sql='UPDATE '.$dbarr['pre'].'article_article SET 
           articlename = "'.$_REQUEST['articlename'].'"
           ,articlecode = "'.$_REQUEST['articlecode'].'"
           ,author = "'.$_REQUEST['author'].'"
           ,backupname = "'.$_REQUEST['backupname'].'"
           ,keywords = "'.$_REQUEST['keywords'].'"
           ,sortid = "'.$_REQUEST['sortid'].'"
           ,fullflag = "'.$_REQUEST['fullflag'].'"
           ,display = "'.$_REQUEST['display'].'"
           ,intro = "'.$_REQUEST['intro'].'"
       WHERE articleid = '.$_REQUEST['sourceid'];
	}
	if($db->ss_query($sql))echo "200";
	break;
	case 'delete':$err=false;
	if(is_array($_REQUEST['sourceid']))
	{
		$sourceids=$_REQUEST['sourceid'];
	}
	else
	{
		$sourceids[]=$_REQUEST['sourceid'];
	}
	foreach($sourceids as $k=>$v)
	{
		$sourceid=$v;
		$sqlarticle='DELETE FROM '.$dbarr['pre'].'article_article WHERE articleid = '.$sourceid;
		$sqlchapter='DELETE FROM '.$dbarr['pre'].$db->get_cindex($sourceid).' WHERE articleid = '.$sourceid;
		if(!($db->ss_query($sqlarticle))||!($db->ss_query($sqlchapter)))$err=true;
		$subaid=intval($sourceid/1000);
		$txt_folder=$root_dir.'/files/article/txt/'.$subaid.'/'.$sourceid;
		$img_folder=$root_dir.'/files/article/image/'.$subaid.'/'.$sourceid;
		$att_folder=$root_dir.'/files/article/attachement/'.$subaid.'/'.$sourceid;
		Ss::ss_delfolder($txt_folder);
		Ss::ss_delfolder($img_folder);
		Ss::ss_delfolder($att_folder);
	}
	if(!$err)echo "200";
	break;
	case 'newarticle':if(!$articlename=$_REQUEST['articlename'])die('小说名不能为空');
	if($sys_ver<2.4)
	{
		$author=$_REQUEST['author'];
		$sortid=$_REQUEST['sortid'];
		$fullflag=$_REQUEST['fullflag'];
		$display=$_REQUEST['display'];
		$intro=$_REQUEST['intro'];
		$keywords=$_REQUEST['keywords'];
		$sql='INSERT INTO '.$dbarr['pre'].'article_article 
               (articlename, author, sortid, fullflag, display, intro, keywords, postdate, lastupdate, posterid, poster) VALUES 
               ("'.$articlename.'","'.$author.'","'.$sortid.'","'.$fullflag.'","'.$display.'","'.$intro.'","'.$keywords.'","'.date("U").'","'.date("U").'","1","admin")';
	}
	else
	{
		$articlecode=$_REQUEST['articlecode'];
		$author=$_REQUEST['author'];
		$backupname=$_REQUEST['backupname'];
		$sortid=$_REQUEST['sortid'];
		$fullflag=$_REQUEST['fullflag'];
		$display=$_REQUEST['display'];
		$intro=$_REQUEST['intro'];
		$keywords=$_REQUEST['keywords'];
		$sql='INSERT INTO '.$dbarr['pre'].'article_article 
               (articlename, articlecode, author, backupname, sortid, fullflag, display, intro, keywords, postdate, lastupdate, posterid, poster) VALUES 
               ("'.$articlename.'","'.$articlecode.'","'.$author.'","'.$backupname.'","'.$sortid.'","'.$fullflag.'","'.$display.'","'.$intro.'","'.$keywords.'","'.date("U").'","'.date("U").'","1","admin")';
	}
	if($db->ss_query($sql))echo "200";
	break;
}
?>