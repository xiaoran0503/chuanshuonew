<form class="layui-form layui-form-pane" method="POST" action="javascript:;">

   <fieldset class="layui-elem-field layui-field-title">
       <legend>App设置</legend>
   </fieldset>
   <div class="layui-form-item">
       <label class="layui-form-label">封推小说ID</label>
       <div class="layui-input-block">
           <input type="text" name="commend_ids" value="<?=$commend_ids?>" autocomplete="off" autocomplete="off" class="layui-input">
       </div>

   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">二维码图片</label>
       <div class="layui-input-inline" style="width:400px;">
           <input type="text" name="qrcode" autocomplete="off" placeholder="http://shipsay.com/img/qrcode.png" value="<?=$qrcode?>" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">显示在'我的'页面中的二维码图片(打赏)地址, 建议尺寸 200x200</div>
   </div>

   <fieldset class="layui-elem-field layui-field-title">
       <legend>首页轮播图</legend>
   </fieldset>

   <div class="layui-form-item layui-form-text">
       <textarea class="swipers layui-textarea" style="min-height:100px"><?=$swipers?></textarea>
   </div>
   <blockquote class="layui-elem-quote layui-text">
       不使用轮播图请留空. 格式: <b style="color:#FF5722">["aid"=>小说id,"img_url"=>'图片地址'],</b> 一行一个, 注意最后有逗号. 建议图片尺寸比例3:1, 如:600x200, 900x300
   </blockquote>

   <!-- <fieldset class="layui-elem-field layui-field-title">
       <legend>广告代码 ( 预留 ) </legend>
   </fieldset>

   <div class="layui-form-item layui-form-text">
       <textarea class="adsense layui-textarea" style="min-height:100px"><?=$adsense?></textarea>
   </div> -->


   <fieldset class="layui-elem-field layui-field-title">
       <legend>App 升级配置</legend>
   </fieldset>
   <div class="layui-form-item">
       <label class="layui-form-label">Uni-Appid</label>
       <div class="layui-input-inline">
           <input type="text" name="uni_app_id" autocomplete="off" placeholder="__UNI__0000000" value="<?=$uni_app_id?>" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">当前应用在Uni-App的ID号</div>
   </div>
   <div class="layui-form-item">
       <label class="layui-form-label">版本号</label>
       <div class="layui-input-inline">
           <input type="text" name="current_ver" autocomplete="off" value="<?=$current_ver?>" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">当前应用升级后的版本号</div>
   </div>
   <div class="layui-form-item">
       <label class="layui-form-label">升级描述</label>
       <div class="layui-input-inline" style="width:400px;">
           <input type="text" name="update_note" autocomplete="off" value="<?=$update_note?>" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">显示在升级弹出窗口的文字</div>
   </div>
   <div class="layui-form-item">
       <label class="layui-form-label">下载地址</label>
       <div class="layui-input-inline" style="width:400px;">
           <input type="text" name="download_url" autocomplete="off" placeholder="http://shipsay.com/release/ssApp.apk" value="<?=$download_url?>" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">安装包或更新包的App下载地址</div>
   </div>

</form>