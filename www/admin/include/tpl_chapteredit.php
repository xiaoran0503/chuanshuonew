<?php require_once 'function.php';
$articlename=str_replace(["'",'\"'],["\'",'\"'],$_REQUEST['articlename']);
$sourceid=$_REQUEST['sourceid'];
switch($_REQUEST['do'])
{
	case 'edit':$chapterid=$_REQUEST['chapterid'];
	if(is_dir($root_dir))
	{
		$chapter_file=$root_dir.'/files/article/txt/'.intval($sourceid/1000).'/'.$sourceid.'/'.$chapterid.'.txt';
		$txt=@file_get_contents($chapter_file);
		$readonly=0;
	}
	else
	{
		$txt=Text::ss_get_contents($txt_url.'/'.intval($sourceid/1000).'/'.$sourceid.'/'.$chapterid.'.txt');
		$readonly=1;
	}
	$sql='SELECT chaptername FROM '.$dbarr['pre'].$db->get_cindex($sourceid).' WHERE chapterid = '.$chapterid;
	$chaptername=$db->ss_getone($sql)['chaptername'];
	break;
}
?>

<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <title>船说CMS</title>
   <meta name="renderer" content="webkit">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <link rel="stylesheet" href="/layui/css/layui.css" media="all">
   <script src="/layui/layui.js"></script>
   <script src="//cdn.staticfile.org/jquery/3.4.0/jquery.min.js"></script>
</head>

<body>

   <form class="layui-form layui-form-pane" action="javascript:;" style="margin-top:20px;">
       <div class="layui-form-item" style="margin-right:15px;">
           <label class="layui-form-label">章节名</label>
           <div class="layui-input-block">
               <input type="text" name="chaptername" value="<?=$chaptername?>" autocomplete="off" class="layui-input">
           </div>
       </div>


       <div class="layui-form-item layui-form-text" style="margin-right:15px;">
           <label class="layui-form-label">章节内容 ( <span id="count"></span> 字 )<?php if($readonly)echo ' <b style="color:#FF5722;">只读模式</b>';
?></label>
           <div class="layui-input-block">
               <textarea class="layui-textarea" name="chapter-content" id="content" style="height: 480px;" <?php if($readonly)echo ' readonly' ?>><?=Text::ss_toutf8($txt)?></textarea>
           </div>
       </div>

       <div class="layui-form-item">
           <button class="layui-btn" id="<?=$_REQUEST['do']?>">保存</button>
       </div>
   </form>
   <script>
       $('#edit').on('click', function() {
           $.ajax({
               type: "POST",
               url: "chapterupdate.php?do=update",
               data: {
                   "sourceid": '<?=$sourceid?>',
                   "chapter_file": '<?=$chapter_file?>',
                   "chapterid": '<?=$chapterid?>',
                   "chaptername": $('input[name="chaptername"]').val(),
                   "content": $('textarea[name="chapter-content"]').val()
               },
               async: true,
               success: function(state) {
                   layer.msg(state == 200 ? "保存完成" : "保存失败,请检查配置");
               }
           })
       })


       $('#newchapter').on('click', function() {
           $.ajax({
               type: "POST",
               url: "chapterupdate.php?do=newchapter",
               data: {
                   "sourceid": '<?=$sourceid?>',
                   "articlename": '<?=$articlename?>',
                   "chaptername": $('input[name="chaptername"]').val(),
                   "content": $('textarea[name="chapter-content"]').val()
               },
               async: true,
               success: function(state) {
                   layer.msg(state == 200 ? "新增成功,请稍候..." : "新增失败,请检查配置", function() {
                       window.parent.location.reload(); //刷新父页面
                       let index = parent.layer.getFrameIndex(window.name); //关闭当前页面
                       parent.layer.close(index);
                   });
               }
           })
       })

       layui.use(['element', 'form'], function() {
           let element = layui.element;
           let form = layui.form;
           form.render();
       })

       $('#count').text($('#content').val().length);
       $('#content').on('keyup', function() {
           $('#count').text($('#content').val().length);
       })
   </script>
</body>

</html>