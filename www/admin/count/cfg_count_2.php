<form class="layui-form layui-form-pane" method="POST" action="javascript:;">
   <fieldset class="layui-elem-field layui-field-title"><legend>网站统计代码②</legend></fieldset>

   <div class="layui-form-item">
       <label class="layui-form-label">统计②开关</label>
       <div class="layui-input-inline"  style="width:100px;">
           <input type="checkbox" name="count2_enable" lay-skin="switch" lay-filter="count2_enable" lay-text="ON|OFF"<?php if($count[2]['enable']==1)echo ' checked';
?>>
       </div>
   </div>

   <div class="layui-form-item layui-form-text">
       <label class="layui-form-label">统计代码直接粘贴, 无需转换</label>
       <div class="layui-input-block">
           <textarea id="count2_html" class="layui-textarea" style="min-height:500px"><?=trim($count[2]['html'])?></textarea>
       </div>
   </div>

</form>