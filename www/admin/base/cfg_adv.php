<form class="layui-form layui-form-pane" method="POST" action="javascript:;">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>高级功能</legend>
    </fieldset>
    <!-- 静态首页控制
    <div class="layui-form-item">
        <label class="layui-form-label">静态首页</label>
        <div class="layui-input-inline switch-wd">
            <input type="checkbox" name="cache_homepage" lay-skin="switch" lay-filter="cache_homepage" lay-text="ON|OFF" <?php if($cache_homepage==1)echo ' checked' ?>>
        </div>

        <label class="layui-form-label" style="width:120px;">生成周期(秒)</label>
        <div class="layui-input-inline switch-wd">
            <input type="text" name="cache_homepage_period" autocomplete="off" value="<?=$cache_homepage_period?:1800?>" class="layui-input">
        </div>
        <button class="layui-btn" id="flushHomePage" style="float:left;">立即生成</button>
    </div>
    -->
    <div class="layui-form-item">
        <label class="layui-form-label">Gzip压缩</label>
        <div class="layui-input-inline switch-wd">
            <input type="checkbox" name="use_gzip" lay-skin="switch" lay-filter="use_gzip" lay-text="ON|OFF" <?php if($use_gzip==1)echo ' checked' ?>>
        </div>
        <div class="layui-form-mid layui-word-aux">通过压缩网页大幅提升访问速度,但会轻微损耗CPU性能.</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">TXT下载</label>
        <div class="layui-input-inline switch-wd">
            <input type="checkbox" name="enable_down" lay-skin="switch" lay-text="ON|OFF" <?php if($enable_down==1)echo ' checked' ?>>
        </div>
        <div class="layui-form-mid layui-word-aux">此功能会消耗大量资源, 请慎重选择. </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">繁体版</label>
        <div class="layui-input-inline switch-wd">
            <input type="checkbox" name="is_ft" lay-skin="switch" lay-text="ON|OFF" <?php if($is_ft==1)echo ' checked' ?>>
        </div>
        <div class="layui-form-mid layui-word-aux">网页内核及源代码均为繁体 <b style="color:#FF5722">( 此功能需单独购买 )</b></div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">强制域名</label>
        <div class="layui-input-inline" style="width:400px;">
            <input type="text" name="site_url" autocomplete="off" value="<?php echo htmlspecialchars($site_url, ENT_QUOTES, 'UTF-8'); ?>" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">使用反代服务器时可能会用到</div>
    </div>

    <fieldset class="layui-elem-field layui-field-title">
        <legend>小说ID转换</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">转换开关</label>
        <div class="layui-input-inline switch-wd">
            <input type="checkbox" name="is_multiple" lay-skin="switch" lay-text="ON|OFF" <?php if($is_multiple==1)echo ' checked' ?>>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">转换算法</label>
        <div class="layui-input-inline">
            <input type="text" name="ss_newid" value="<?php echo htmlspecialchars($ss_newid, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">本站的小说ID加5, 写作: $id+5</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">还原算法</label>
        <div class="layui-input-inline">
            <input type="text" name="ss_sourceid" value="<?php echo htmlspecialchars($ss_sourceid, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">将刚才加5的ID还原, 写作: $id-5</span></div>
    </div>

    <blockquote class="layui-elem-quote layui-text">
        1.支持php所有的算术运算法,如: 加 + , 减 -, 乘 *, 除 / <br>
        2.转换后的数必须为大于0的整数<br>
        3.章节ID也会按照同样的算法进行转换
    </blockquote>

</form>

<script>
    // 处理重新生成静态首页的点击事件
    $('#flushHomePage').on('click', function() {
        layer.confirm('重新生成静态首页?', function() {
            // 发送 AJAX 请求
            $.ajax({
                type: 'GET',
                url: '../include/flushDb.php',
                data: {
                    'flushHomePage': 'flushHomePage'
                },
                success: function(state) {
                    // 显示生成结果的消息
                    layer.msg(state === '200' ? '生成完成' : '生成失败');
                },
                error: function() {
                    // 处理请求失败的情况
                    layer.msg('请求失败，请稍后重试');
                }
            });
        });
    });
</script>