<?php $sourceid=intval($_REQUEST['articleid']);
$articlename=$_REQUEST['articlename'];
if($enable_down)
{
	$txt_dir=$txt_url.'/'.intval($sourceid/1000).'/'.$sourceid;
	$sql='SELECT chapterid,chaptername FROM '.$dbarr['pre'].$db->get_cindex($sourceid).' WHERE articleid = '.$sourceid.' AND chaptertype = 0 ORDER BY chapterorder ASC';
	$res=$db->ss_query($sql);
	if($res->num_rows)
	{
		header('Content-type: applictation/txt');
		header('Content-Disposition: attachment; filename=《'.$articlename.'》.txt');
		echo '《'.$articlename.'》来自: '.$_SERVER['HTTP_REFERER'];
		while($rows=mysqli_fetch_assoc($res))
		{
			echo "\n\n===".Text::ss_toutf8($rows['chaptername'])."===\n\n";
			$txt_buffer=file_get_contents($txt_dir.'/'.$rows['chapterid'].'.txt');
			$txt_buffer=Text::ss_toutf8(trim($txt_buffer));
			echo $txt_buffer;
			ob_flush();
			flush();
		}
	}
}
else
{
	echo 'TXT下载已关闭';
}
?>