<form class="layui-form layui-form-pane" method="POST" action="javascript:;">

    <fieldset class="layui-elem-field layui-field-title"><legend>数据库连接设置</legend></fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">数据库版本</label>
        <div class="layui-input-inline">
            <select name="sys_ver">
                <!-- <option value="">选择数据库版本</option> -->
                <option value="6.0" <?php echo ($sys_ver == 6.0)? 'selected' : '';?>>船说 ( 分表版 )</option>
                <option value="2.5" <?php echo ($sys_ver == 2.5)? 'selected' : '';?>>船说 ( 优化版 )</option>
                <option value="1.7" <?php echo ($sys_ver == 1.7)? 'selected' : '';?>>杰奇 1.x</option>
                <option value="2.4" <?php echo ($sys_ver == 2.4)? 'selected' : '';?>>杰奇 2.x</option>
                <option value="3.0" <?php echo ($sys_ver == 3.0)? 'selected' : '';?>>杰奇 3.x</option>
            </select>
        </div>
        <div class="layui-form-mid layui-word-aux"><button class="layui-btn layui-btn-normal layui-btn-xs" onclick='javascript:testdb();'>测试连接</button></div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">数据库 IP</label>
        <div class="layui-input-inline">
            <input type="text" name="dbhost" value="<?php echo htmlspecialchars($dbarr['host'], ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">本机: 127.0.0.1</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">端口</label>
        <div class="layui-input-inline">
            <input type="text" name="dbport" value="<?php echo htmlspecialchars($dbarr['port'], ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">默认: 3306</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">数据库名</label>
        <div class="layui-input-inline">
            <input type="text" name="dbname" value="<?php echo htmlspecialchars($dbarr['name'], ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-inline">
            <input type="text" name="dbuser" value="<?php echo htmlspecialchars($dbarr['user'], ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-inline">
            <input type="password" name="dbpass" value="<?php echo htmlspecialchars($dbarr['pass'], ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" class="layui-input">
        </div>
        <button class="layui-btn layui-btn-disabled dispass" style="cursor:pointer" onclick='javascript:dispass();'>显示</button>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">持久连接</label>
        <div class="layui-input-inline switch-wd">
            <input type="checkbox" name="db_pconnect" lay-skin="switch" lay-filter="db_pconnect" lay-text="ON|OFF" <?php echo (!empty($dbarr['pconnect']))? 'checked' : '';?>>
        </div>
        <div class="layui-form-mid layui-word-aux">默认关闭 ( 建议 ), 具体请百度</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">授权码</label>
        <div class="layui-input-inline" style="width:100%;max-width: 550px;">
            <input type="text" name="authcode" placeholder="解密版无需填写，无限制多多支持www.huaitui.com" value="<?php echo htmlspecialchars($authcode, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" class="layui-input">
        </div>
    </div>
</form>

<blockquote class="layui-elem-quote layui-text">
 远程数据库需: 1. 开启远程访问权限;  2.放行对应端口
</blockquote>
<script>
    // 切换密码显示状态的函数
    function dispass(){
        if($('input[name="dbpass"]').attr('type') === 'password') {
            $('input[name="dbpass"]').attr('type', 'text');
            $('.dispass').text('隐藏');
        } else {
            $('input[name="dbpass"]').attr('type', 'password');
            $('.dispass').text('显示');
        }
    }

    // 测试数据库连接的函数
    function testdb(){
        $.ajax({
            type: "POST",
            url: "../include/testDb.php",
            data: {
                "dbhost": $("input[name='dbhost']").val(), 
                "dbport": $("input[name='dbport']").val(), 
                "dbuser": $("input[name='dbuser']").val(), 
                "dbpass": $("input[name='dbpass']").val(), 
                "dbname": $("input[name='dbname']").val()
            },
            success: function(info) {
                layer.msg(info);
            },
            error: function() {
                layer.msg('请求失败，请检查网络或服务器配置');
            }
        });
    }
</script>