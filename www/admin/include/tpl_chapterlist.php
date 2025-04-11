<?php require_once 'function.php';
$sourceid=$_REQUEST['sourceid'];
$articlename=str_replace(["'",'\"'],["\'",'\"'],$_REQUEST['articlename']);
$sql='SELECT chapterid,chaptername,chaptertype,chapterorder FROM '.$dbarr['pre'].$db->get_cindex($sourceid).' WHERE articleid = '.$sourceid.' AND chaptertype = 0 ORDER BY chapterorder ASC';
$chapterarr=[];
if($res=$db->ss_query($sql))
{
	$k=0;
	while($rows=mysqli_fetch_assoc($res))
	{
		$chapterarr[$k]['chapterid']=$rows['chapterid'];
		$chapterarr[$k]['chaptername']=$rows['chaptername'];
		$chapterarr[$k]['chapterorder']=$rows['chapterorder'];
		$k++;
	}
}
else
{
	die('没有找到此编号的小说');
}
;
?>
<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <title>《<?=$articlename?>》章节管理</title>
   <meta name="renderer" content="webkit">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <link rel="stylesheet" href="/layui/css/layui.css" media="all">
   <script src="/layui/layui.js"></script>
   <script src="//cdn.staticfile.org/jquery/3.4.0/jquery.min.js"></script>
</head>

<body>


   <style>
       .grid-demo {
           border-bottom: 1px solid #e6e6e6;
           height: 40px;
           line-height: 40px;
           overflow: hidden;
       }

       a {
           color: #01AAED;
       }

       span:hover {
           cursor: pointer;
           color: #01AAED;
       }

       .layui-form-checkbox[lay-skin=primary] {
           padding-left: unset;
       }
   </style>
   <div class="layui-row layui-col-space5" style="width:960px; margin:0 auto;">

       <form class="layui-form layui-form-pane" action="javascript:;">
           <div>
               <div class="layui-col-md12" style="text-align:center;font-size:20px;padding:10px;border-bottom: 1px solid #e6e6e6;">
                   <div class="title">《<?=$articlename?>》 </div>
               </div>
               <?php foreach($chapterarr as $k=>$v): ?>
                   <div class="layui-col-md3 grid-demo">
                       <input type="checkbox" name="chapterids" value="<?=$v['chapterid']?>" lay-skin="primary" />
                       <span onclick="javascript:edit('<?=$v['chapterid']?>');" title="<?=$v['chapterid']?> <?=$v['chaptername']?>"><?=$v['chaptername']?></span>
                   </div>
               <?php endforeach ?>
           </div>

           <div style="clear:both;padding-top:10px;text-align:center">
               <a class="layui-btn layui-btn-sm layui-btn-normal" href="javascript:newchapter();">增加章节</a>
               <a class="layui-btn layui-btn-sm layui-btn-danger" href="javascript:clean();">清空章节</a>
               <a class="layui-btn layui-btn-sm" href="javascript:selectAll();">全部选中</a>
               <a class="layui-btn layui-btn-sm" href="javascript:disAll();">取消全选</a>
               <a class="layui-btn layui-btn-sm layui-btn-danger" href="javascript:del();">删除选中</a>
           </div>
           <!-- 排序 -->
           <div class="layui-form-item" style="clear:both;padding-top:20px;">
               <label class="layui-form-label">将章节</label>
               <div class="layui-input-block">
                   <select name="fromid" id="fromid">
                       <option value="">请选择要移动的章节</option>
                       <?php foreach($chapterarr as $k=>$v): ?>
                           <option value="<?=$v['chapterorder']?>,<?=$v['chapterid']?>"><?=$v['chaptername']?></option>
                       <?php endforeach ?>
                   </select>
               </div>
           </div>


           <div class="layui-form-item">
               <label class="layui-form-label">移到(之后)</label>
               <div class="layui-input-block">
                   <select name="toid" id="toid">
                       <option value="">选择章节的后面 ( 或最前面 )</option>
                       <option value="0">最前面</option>
                       <?php foreach($chapterarr as $k=>$v): ?>
                           <option value="<?=$v['chapterorder']?>"><?=$v['chaptername']?></option>
                       <?php endforeach ?>
                   </select>
               </div>
           </div>

           <div style="text-align:center; margin-bottom: 20px;">
               <button class="layui-btn sort-btn" onclick="javascript:sort();">提交排序</button>
           </div>
       </form>
   </div>


   <script>
       function selectAll() {
           $('.layui-form-checkbox').addClass('layui-form-checked');
           $("input[name='chapterids']").prop('checked', true);
       }

       function disAll() {
           $('.layui-form-checkbox').removeClass('layui-form-checked');
           $("input[name='chapterids']").prop('checked', false);
       }

       function del() {

           let ids = [];
           $(':checkbox[name=chapterids]:checked').each(function(i, v) {
               ids.push(v.value);
           })

           if (ids.length < 1) {
               layer.msg('没有选中任何章节');
               return false;
           }

           layer.confirm('确定要删除选中章节吗?', function() {
               $.ajax({
                   type: "POST",
                   url: 'chapterupdate.php',
                   data: {
                       "do": "delete",
                       "sourceid": '<?=$sourceid?>',
                       "ids": ids,
                   },
                   success: function(state) {
                       if (state == 200) {
                           layer.msg('删除完成');
                           location.reload();
                       } else {
                           layer.msg('删除过程有错误');
                       }
                   }
               })

           })
       }

       function clean() {

           layer.confirm('清空本书所有章节及附件？', function() {
               $.ajax({
                   type: "POST",
                   url: 'chapterupdate.php',
                   data: {
                       "do": "clean",
                       "sourceid": '<?=$sourceid?>'
                   },
                   success: function(state) {
                       if (state == 200) {
                           layer.msg('清空完成');
                           location.reload();
                       } else {
                           layer.msg('清空过程有错误');
                       }
                   }
               })

           })
       }

       function newchapter() {
           articlename = encodeURIComponent('<?=$articlename?>');
           layer.open({
               type: 2,
               title: '《<?=$articlename?>》',
               area: ['80%', '90%'],
               content: '../include/tpl_chapteredit.php?do=newchapter&sourceid=<?=$sourceid?>&articlename=' + articlename
           })
       }

       function edit(chapterid) {
           layer.open({
               type: 2,
               title: '《<?=$articlename?>》cid: ' + chapterid,
               area: ['80%', '90%'],
               content: '../include/tpl_chapteredit.php?do=edit&sourceid=<?=$sourceid?>&chapterid=' + chapterid + '&articlename=<?=$articlename?>'
           })
       }

       function sort() {
           $.ajax({
               type: "POST",
               url: 'chapterorder.php',
               data: {
                   "sourceid": '<?=$sourceid?>',
                   "fromid": $('#fromid').val(),
                   "toid": $('#toid').val()
               },
               success: function(state) {
                   if (state == 200) {
                       location.reload();
                   } else {
                       layer.msg('排序失败');
                   }
               }
           })
       }

       layui.use(['element', 'form'], function() {
           let element = layui.element;
           let form = layui.form;
           form.render();
       })
   </script>
</body>

</html>