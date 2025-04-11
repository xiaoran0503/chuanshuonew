<?php
// 开启严格错误报告模式，便于调试
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 引入必要的文件
require_once '../header.php';
require_once $config_file = __ROOT_DIR__ . '/shipsay/configs/config.ini.php';

// 生成安全的 token
$token = md5(base64_encode('ShipSayCMS' . time()));
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
            <div class="layui-tab-item layui-show"><?php require_once 'cfg_main.php'; ?></div>
            <div class="layui-tab-item"><?php require_once 'cfg_database.php'; ?></div>
            <div class="layui-tab-item"><?php require_once 'cfg_fake.php'; ?></div>
            <div class="layui-tab-item"><?php require_once 'cfg_sort.php'; ?></div>
            <div class="layui-tab-item"><?php require_once 'cfg_redis.php'; ?></div>
            <div class="layui-tab-item"><?php require_once 'cfg_adv.php'; ?></div>
            <div class="layui-tab-item"><?php include_once 'cfg_langtail.php'; ?></div>
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
        // 对用户输入进行转义，防止 XSS 攻击
        function escapeHtml(unsafe) {
            return unsafe
               .replace(/&/g, "&amp;")
               .replace(/</g, "&lt;")
               .replace(/>/g, "&gt;")
               .replace(/"/g, "&quot;")
               .replace(/'/g, "&#039;");
        }

        // 收集表单数据
        let data = {
            "do": "base",
            "sitename": escapeHtml($("input[name='sitename']").val()),
            "sys_ver": escapeHtml($("select[name='sys_ver']").val()),
            "root_dir": escapeHtml($("input[name='root_dir']").val()),
            "txt_url": escapeHtml($("input[name='txt_url']").val()),
            "remote_img_url": escapeHtml($("input[name='remote_img_url']").val()),
            "local_img": escapeHtml($("input[name='local_img']:checked").val()),
            "is_attachment": $("input[name='is_attachment']").is(':checked') ? 1 : 0,
            "att_url": escapeHtml($("input[name='att_url']").val()),
            "site_url": escapeHtml($("input[name='site_url']").val()),
            "use_gzip": $("input[name='use_gzip']").is(':checked') ? 1 : 0,
            "enable_down": $("input[name='enable_down']").is(':checked') ? 1 : 0,
            "is_ft": $("input[name='is_ft']").is(':checked') ? 1 : 0,
            "theme_dir": escapeHtml($("select[name='theme_dir']").val()),
            "is_3in1": $("input[name='is_3in1']").is(':checked') ? 1 : 0,
            "commend_ids": escapeHtml($("input[name='commend_ids']").val()),
            "category_per_page": escapeHtml($("input[name='category_per_page']").val()),
            "readpage_split_lines": escapeHtml($("input[name='readpage_split_lines']").val()),
            "vote_perday": escapeHtml($("input[name='vote_perday']").val()),
            "count_visit": $("input[name='count_visit']").is(':checked') ? 1 : 0,
            "fake_info_url": escapeHtml($("input[name='fake_info_url']").val()),
            "fake_chapter_url": escapeHtml($("input[name='fake_chapter_url']").val()),
            "use_orderid": $("input[name='use_orderid']").is(':checked') ? 1 : 0,
            "fake_sort_url": escapeHtml($("input[name='fake_sort_url']").val()),
            "fake_top": escapeHtml($("input[name='fake_top']").val()),
            "fake_recentread": escapeHtml($("input[name='fake_recentread']").val()),
            "fake_indexlist": escapeHtml($("input[name='fake_indexlist']").val()),
            "per_indexlist": escapeHtml($("input[name='per_indexlist']").val()),
            "dbhost": escapeHtml($("input[name='dbhost']").val()),
            "dbport": escapeHtml($("input[name='dbport']").val()),
            "dbname": escapeHtml($("input[name='dbname']").val()),
            "dbuser": escapeHtml($("input[name='dbuser']").val()),
            "dbpass": escapeHtml($("input[name='dbpass']").val()),
            "db_pconnect": $("input[name='db_pconnect']").is(':checked') ? 1 : 0,
            "authcode": escapeHtml($("input[name='authcode']").val()),
            "sortarr": escapeHtml($("#sortarr").val()),
            "use_redis": $("input[name='use_redis']").is(':checked') ? 1 : 0,
            "redishost": escapeHtml($("input[name='redishost']").val()),
            "redisport": escapeHtml($("input[name='redisport']").val()),
            "redispass": escapeHtml($("input[name='redispass']").val()),
            "redisdb": escapeHtml($("input[name='redisdb']").val()),
            "home_cache_time": escapeHtml($("input[name='home_cache_time']").val()),
            "info_cache_time": escapeHtml($("input[name='info_cache_time']").val()),
            "category_cache_time": escapeHtml($("input[name='category_cache_time']").val()),
            "cache_time": escapeHtml($("input[name='cache_time']").val()),
            "is_multiple": $("input[name='is_multiple']").is(':checked') ? 1 : 0,
            "ss_newid": escapeHtml($("input[name='ss_newid']").val()),
            "ss_sourceid": escapeHtml($("input[name='ss_sourceid']").val()),
            "is_langtail": $("input[name='is_langtail']").is(':checked') ? 1 : 0,
            "langtail_catch_cycle": escapeHtml($("input[name='langtail_catch_cycle']").val()),
            "langtail_cache_time": escapeHtml($("input[name='langtail_cache_time']").val()),
            "fake_langtail_info": escapeHtml($("input[name='fake_langtail_info']").val()),
            "fake_langtail_indexlist": escapeHtml($("input[name='fake_langtail_indexlist']").val()),
            "token": '<?= $token ?>',
            "config_file": '<?= htmlspecialchars($config_file, ENT_QUOTES, 'UTF-8') ?>'
        };

        $.ajax({
            type: "post",
            url: "/<?= htmlspecialchars($admin_url, ENT_QUOTES, 'UTF-8') ?>/savecfgs.php",
            data: data,
            async: true,
            success: function(state) {
                layer.msg(state == 200 ? '保存成功' : '保存失败,请检查配置(base)');
            }
        });
    });

    layui.use(['element', 'form'], function() {
        let element = layui.element;
        let form = layui.form;
        form.render();
    });
</script>

</body>

</html>