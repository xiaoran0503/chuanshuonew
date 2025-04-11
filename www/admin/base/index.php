<?php error_reporting(0);
require_once '../header.php';
require_once $config_file=__ROOT_DIR__.'/shipsay/configs/config.ini.php';
?>

<div class="layui-body">
   <!-- 内容主体区域 -->
   <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief" style="margin-top: 6px;">
       <ul class="layui-tab-title">
           <li class="layui-this">基础设置</li>
           <li>数据库</li>
           <li>URL设置</li>
           <li>小说分类</li>
           <li>Redis缓存</li>
           <li>高级功能</li>
           <li>长尾词 (收费)</li>
       </ul>
       <div class="layui-tab-content" style="height: 100px;">
           <div class="layui-tab-item layui-show"><?php require_once 'cfg_main.php' ?></div>
           <div class="layui-tab-item"><?php require_once 'cfg_database.php' ?></div>
           <div class="layui-tab-item"><?php require_once 'cfg_fake.php' ?></div>
           <div class="layui-tab-item"><?php require_once 'cfg_sort.php' ?></div>
           <div class="layui-tab-item"><?php require_once 'cfg_redis.php' ?></div>
           <div class="layui-tab-item"><?php require_once 'cfg_adv.php' ?></div>
           <div class="layui-tab-item"><?php include_once 'cfg_langtail.php' ?></div>
       </div>
   </div>
   <!-- /内容主体区域 -->
</div>
<div class="layui-footer">
   <button class="layui-btn save-btn-base">保存设置</button><span class="layui-word-aux">所有设置均为英文半角,路径结尾不加 /</span>
   <span class="layui-layout-right layui-word-aux" style="margin-right: 10px;">&copy; 船说CMS</span>
</div>
</div> <!-- /header -->

<script>
   $('.save-btn-base').on('click', function() {
       $.ajax({
           type: "post",
           url: "/<?=$admin_url?>/savecfgs.php",
           data: {
               "do": "base",
               "sitename": $("input[name='sitename']").val(),
               "sys_ver": $("select[name='sys_ver']").val(),
               "root_dir": $("input[name='root_dir']").val(),
               "txt_url": $("input[name='txt_url']").val(),
               "remote_img_url": $("input[name='remote_img_url']").val(),
               "local_img": $("input[name='local_img']:checked").val(),
               "is_attachment": $("input[name='is_attachment']").is(':checked') ? 1 : 0,
               "att_url": $("input[name='att_url']").val(),
               "site_url": $("input[name='site_url']").val(),
               "use_gzip": $("input[name='use_gzip']").is(':checked') ? 1 : 0,
               "enable_down": $("input[name='enable_down']").is(':checked') ? 1 : 0,
               "is_ft": $("input[name='is_ft']").is(':checked') ? 1 : 0,
               /*静态首页控制
               "cache_homepage": $("input[name='cache_homepage']").is(':checked') ? 1 : 0,
               "cache_homepage_period": $("input[name='cache_homepage_period']").val(),
               */
               "theme_dir": $("select[name='theme_dir']").val(),
               "is_3in1": $("input[name='is_3in1']").is(':checked') ? 1 : 0,
               "commend_ids": $("input[name='commend_ids']").val(),
               "category_per_page": $("input[name='category_per_page']").val(),
               "readpage_split_lines": $("input[name='readpage_split_lines']").val(),
               "vote_perday": $("input[name='vote_perday']").val(),
               "count_visit": $("input[name='count_visit']").is(':checked') ? 1 : 0,

               "fake_info_url": $("input[name='fake_info_url']").val(),
               "fake_chapter_url": $("input[name='fake_chapter_url']").val(),
               "use_orderid": $("input[name='use_orderid']").is(':checked') ? 1 : 0,

               "fake_sort_url": $("input[name='fake_sort_url']").val(),
               "fake_top": $("input[name='fake_top']").val(),
               "fake_recentread": $("input[name='fake_recentread']").val(),
               "fake_indexlist": $("input[name='fake_indexlist']").val(),
               "per_indexlist": $("input[name='per_indexlist']").val(),

               "dbhost": $("input[name='dbhost']").val(),
               "dbport": $("input[name='dbport']").val(),
               "dbname": $("input[name='dbname']").val(),
               "dbuser": $("input[name='dbuser']").val(),
               "dbpass": $("input[name='dbpass']").val(),
               "db_pconnect": $("input[name='db_pconnect']").is(':checked') ? 1 : 0,
               "authcode": $("input[name='authcode']").val(),

               "sortarr": $("#sortarr").val(),

               "use_redis": $("input[name='use_redis']").is(':checked') ? 1 : 0,
               "redishost": $("input[name='redishost']").val(),
               "redisport": $("input[name='redisport']").val(),
               "redispass": $("input[name='redispass']").val(),
               "redisdb": $("input[name='redisdb']").val(),

               "home_cache_time": $("input[name='home_cache_time']").val(),
               "info_cache_time": $("input[name='info_cache_time']").val(),
               "category_cache_time": $("input[name='category_cache_time']").val(),
               "cache_time": $("input[name='cache_time']").val(),

               "is_multiple": $("input[name='is_multiple']").is(':checked') ? 1 : 0,
               "ss_newid": $("input[name='ss_newid']").val(),
               "ss_sourceid": $("input[name='ss_sourceid']").val(),

               "is_langtail": $("input[name='is_langtail']").is(':checked') ? 1 : 0,
               "langtail_catch_cycle": $("input[name='langtail_catch_cycle']").val(),
               "langtail_cache_time": $("input[name='langtail_cache_time']").val(),
               "fake_langtail_info": $("input[name='fake_langtail_info']").val(),
               "fake_langtail_indexlist": $("input[name='fake_langtail_indexlist']").val(),

               "token": '<?=md5(base64_encode('ShipSayCMS'.time()))?>',
               "config_file": '<?=$config_file?>',

           },
           async: true,
           success: function(state) {
               layer.msg(state == 200 ? '保存成功' : '保存失败,请检查配置(base)');
           },

       })
   })

   layui.use(['element', 'form'], function() {
       let element = layui.element;
       let form = layui.form;
       form.render();
   })
</script>

</body>

</html>