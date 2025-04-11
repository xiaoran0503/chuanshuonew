<form class="layui-form layui-form-pane" method="POST" action="javascript:;">
    <!-- 长尾词设置标题 -->
    <fieldset class="layui-elem-field layui-field-title">
        <legend>长尾词设置</legend>
    </fieldset>

    <!-- 总开关 -->
    <div class="layui-form-item">
        <label class="layui-form-label">总开关</label>
        <div class="layui-input-inline switch-wd">
            <!-- 使用 htmlspecialchars 转义输出，防止 XSS 攻击 -->
            <input type="checkbox" name="is_langtail" lay-skin="switch" lay-filter="is_langtail" lay-text="ON|OFF" <?php echo ($is_langtail == 1)? 'checked' : '';?>>
        </div>
    </div>

    <!-- 抓取周期 -->
    <div class="layui-form-item">
        <label class="layui-form-label">抓取周期</label>
        <div class="layui-input-inline">
            <!-- 使用 htmlspecialchars 转义输出，防止 XSS 攻击 -->
            <input type="text" name="langtail_catch_cycle" value="<?php echo htmlspecialchars($langtail_catch_cycle?: 7, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">( 天 ) , 从百度下拉框中重新抓取的周期 </div>
    </div>

    <!-- 缓存时间 -->
    <div class="layui-form-item">
        <label class="layui-form-label">缓存时间</label>
        <div class="layui-input-inline">
            <!-- 使用 htmlspecialchars 转义输出，防止 XSS 攻击 -->
            <input type="text" name="langtail_cache_time" value="<?php echo htmlspecialchars($langtail_cache_time?: 0, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">( 秒 ) , 依赖 Redis , 0=不缓存 </div>
    </div>

    <!-- 长尾信息页 -->
    <div class="layui-form-item">
        <label class="layui-form-label">长尾信息页</label>
        <div class="layui-input-inline" style="width:300px;">
            <!-- 使用 htmlspecialchars 转义输出，防止 XSS 攻击 -->
            <input type="text" name="fake_langtail_info" value="<?php echo htmlspecialchars($fake_langtail_info, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux"> <b style="color:#FF5722">{aid}</b> 小说ID , <b style="color:#FF5722">{subaid}</b>小说子序号(可选)</div>
    </div>

    <!-- 长尾目录页 -->
    <div class="layui-form-item">
        <label class="layui-form-label">长尾目录页</label>
        <div class="layui-input-inline" style="width:300px;">
            <!-- 使用 htmlspecialchars 转义输出，防止 XSS 攻击 -->
            <input type="text" name="fake_langtail_indexlist" value="<?php echo htmlspecialchars($fake_langtail_indexlist, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux"><b style="color:#FF5722">{aid}</b> 小说ID , <b style="color:#FF5722">{subaid}</b>小说子序号(可选) , <b style="color:#FF5722">{pid}</b>翻页码(必填) </div>
    </div>

    <!-- 注意事项 -->
    <blockquote class="layui-elem-quote layui-text">
        注意: 长尾词小说的路径不支持拼音路径 <br>
    </blockquote>

</form>