<?php require_once '../header.php';
require_once $config_file=__ROOT_DIR__.'/shipsay/configs/report.ini.php';
?>
<!-- 内容主体区域 -->
 <div class="layui-body">
       <form class="layui-form layui-form-pane" method="POST" action="javascript:;">
           <fieldset class="layui-elem-field layui-field-title"><legend>本站报错设置</legend></fieldset>
           <div class="layui-form-item">
               <label class="layui-form-label" style="width:120px;">开关</label>
               <div class="layui-input-inline" style="width: 80px;">
                   <input type="checkbox" name="report_on" lay-skin="switch" lay-text="ON|OFF" <?php if($ShipSayReport['on'])echo ' checked' ?>>
               </div>

               <label class="layui-form-label" style="width:120px;">报错频率 (秒)</label>
               <div class="layui-input-inline" style="width: 80px;">
                   <input type="text" name="report_delay" value="<?=$ShipSayReport['delay']?>" autocomplete="off" class="layui-input">
               </div>
           </div>
           
           <!-- 报错列表 -->
           <fieldset class="layui-elem-field layui-field-title"><legend>报错数据( 全网 )</legend></fieldset>
           <table class="layui-hide" id="reportList" lay-filter="reportList"></table>
       </form>
 </div>
<!-- /内容主体区域 -->
 <div class="layui-footer">
   <button class="layui-btn save-btn-report">保存设置</button>
   <span class="layui-layout-right layui-word-aux" style="margin-right: 10px;">&copy; 船说CMS</span>
 </div>
 
</div> <!-- /header -->

<script type="text/html" id="toolbarDemo"><!-- 表头模板 -->
   <div class="layui-btn-container">
       <button class="layui-btn layui-btn-sm" lay-event="delCheckData">删除选中数据</button>
   </div>
</script>

<script type="text/html" id="barDemo">
   <a class="layui-btn layui-btn-xs" lay-event="repair">修复</a>
   <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>

<script>
layui.use('table',function(){
   let table = layui.table;
   table.render({
       elem: '#reportList'
       ,url:'../include/report.php?do=show'
       ,toolbar: '#toolbarDemo' //开启头部工具栏，并为其绑定左侧模板
       ,limit: 20
       ,defaultToolbar: ['filter', 'exports', 'print']
       // ,title: '报错汇总表'
       ,cols: [[
       // {field:'id', title:'ID', width:50, fixed: 'left'}
       {type: 'checkbox', fixed: 'left'}
       ,{fixed: 'right', title:'操作', toolbar: '#barDemo', width:120}
       ,{field:'articlename,chaptername', title:'小说及章节', sort: true, templet: function(d){
           return '《'+ d.articlename +'》'+ d.chaptername;
       }}
       ,{field:'repurl', title:'报错网址', sort: true, templet: function(d){
           return '<a href="'+d.repurl+'" target="_blank" class="layui-table-link">'+d.repurl+'</a>';
       }}
       ,{field:'ip', title:'用户IP', width:140, sort: true}
       ,{field:'reptime', title:'时间', width:160}
       ]]
       ,page: true
   });

   //头工具栏事件
   table.on('toolbar(reportList)', function(obj){
       let checkStatus = table.checkStatus(obj.config.id);
       let data = checkStatus.data;
       switch(obj.event) {
           case 'delCheckData':
               layer.confirm('确定要删除选中数据吗？', function(){
                   let ids = [];
                   $.each(data, function(index, value){
                       ids.push(value.id);
                   })

                   $.ajax({
                       type : "POST",
                       url : "../include/report.php",
                       data : {
                           "do" : "delete",
                           "id" : ids
                       },
                       success : function(state){
                           layer.msg(state == 200 ? '删除成功' : '删除失败,请检查配置');
                       }
                   })
                   table.reload('reportList');
               })
           break;
       }
   })

   //监听行工具事件
   table.on('tool(reportList)', function(obj){
       let data = obj.data;
       switch(obj.event) {
           case 'del':
               layer.confirm('删除当前数据？', function(index){
                   let ids = [];
                   ids.push(data.id);
                   $.ajax({
                       type : "POST",
                       url : "../include/report.php",
                       data : {
                           "do" : "delete",
                           "id" : ids
                       },
                       success : function(state) {
                           layer.msg(state == 200 ? '删除成功' : '删除失败,请检查配置');
                       }
                   })
                   obj.del();
                   layer.close(index);
               });
           break;

           case 'repair':
               layer.open({
                   type: 2,
                   title: '《'+data.articlename+ '》',
                   area: ['80%', '90%'],
                   content: '../include/tpl_chapteredit.php?do=edit&sourceid='+data.articleid+'&chapterid='+data.chapterid+'&chaptername='+data.chaptername
               })
           break;
       }

   })

})

$('.save-btn-report').on('click',function(){
   $.ajax({
       type: "POST",
       url: "/<?=$admin_url?>/savecfgs.php",
       data: {
           "do" : "report",
           "report_delay" : $("input[name='report_delay']").val(),
           "report_on" : $("input[name='report_on']").is(':checked') ? true : false,
           "config_file" : "<?=$config_file?>"
       },
       async: true,
       success: function(state){
           layer.msg(state == 200 ? '保存成功' : '保存失败,请检查配置');
       }
   })
})
</script>
</body>
</html>