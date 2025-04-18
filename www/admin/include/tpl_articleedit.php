<?php
require_once 'function.php';

// 验证和过滤 sourceid 参数
if (isset($_REQUEST['sourceid'])) {
    $sourceid = filter_var($_REQUEST['sourceid'], FILTER_VALIDATE_INT);
    if ($sourceid === false) {
        die('无效的小说编号');
    }
    $sql = $rico_sql . ' AND articleid = ' . $sourceid;
    if (!$res = $db->ss_getone($sql)) {
        die('没有此编号的小说');
    }
    $sortname = $sortarr[$res['sortid']]['caption'];
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>ShipSay</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/layui/css/layui.css" media="all">
    <script src="/layui/layui.js"></script>
    <script src="//cdn.staticfile.org/jquery/3.4.0/jquery.min.js"></script>
    <style>
       .readonly {
            color: gray;
            font-style: italic;
        }
    </style>
</head>

<body>

    <form class="layui-form layui-form-pane" action="javascript:;" style="margin-top: 20px;">
        <div class="layui-form-item" style="margin-right:15px;">
            <label class="layui-form-label">小说名称</label>
            <div class="layui-input-block">
                <input type="text" name="articlename" value="<?= htmlspecialchars($res['articlename']?? '', ENT_QUOTES, 'UTF-8') ?>" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item" style="margin-right:15px;">
            <label class="layui-form-label">关键字</label>
            <div class="layui-input-block">
                <input type="text" name="keywords" value="<?= htmlspecialchars($res['keywords']?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="英文逗号(,)隔开,不要有空格" autocomplete="off" class="layui-input">
            </div>
        </div>
        <?php if ($sys_ver >= 2.4): ?>
            <div class="layui-form-item" style="margin-right:15px;">
                <label class="layui-form-label">副标题</label>
                <div class="layui-input-block">
                    <input type="text" name="backupname" value="<?= htmlspecialchars($res['backupname']?? '', ENT_QUOTES, 'UTF-8') ?>" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item" style="margin-right:15px">
                <label class="layui-form-label">拼音名</label>
                <div class="layui-input-block">
                    <input type="text" name="articlecode" value="<?= htmlspecialchars($res['articlecode']?? '', ENT_QUOTES, 'UTF-8') ?>" autocomplete="off" class="layui-input">
                </div>
            </div>
        <?php endif ?>

        <div class="layui-form-item" style="margin-right:15px">
            <label class="layui-form-label">作者</label>
            <div class="layui-input-inline">
                <input type="text" name="author" value="<?= htmlspecialchars($res['author']?? '', ENT_QUOTES, 'UTF-8') ?>" autocomplete="off" class="layui-input">
            </div>
            <!-- <?php if ($sys_ver >= 2.4): ?>

                <label class="layui-form-label">来源</label>
                <div class="layui-input-inline">
                    <input type="text" name="source" value="<?= htmlspecialchars($res['source']?? '', ENT_QUOTES, 'UTF-8') ?>" autocomplete="off" class="layui-input">
                </div>
            <?php endif ?> -->
        </div>


        <div class="layui-form-item">
            <label class="layui-form-label">分类</label>
            <div class="layui-input-inline">
                <select name="sortid">
                    <?php foreach ($sortarr as $k => $v): ?>
                        <option value="<?= $k ?>" <?= ($k == ($res['sortid']?? null))? ' selected' : '' ?>>
                            <?= htmlspecialchars($v['caption'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <?php if (isset($sourceid)): ?>
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="upload-btn" style="margin-left: 100px;">更换封面</button><span class="layui-word-aux">限jpg格式</span>
                    <img src="<?= htmlspecialchars(Url::get_img_url($sourceid, $res['imgflag']?? 0), ENT_QUOTES, 'UTF-8') ?>" class="layui-upload-img" id="cover" style="width:80px;height:100px;position: absolute;left:330px;">
                </div>
            <?php endif; ?>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                <input type="radio" name="fullflag" value="0" title="连载" <?= ($res['fullflag']?? 0)!= 1? ' checked' : '' ?>>
                <input type="radio" name="fullflag" value="1" title="完本" <?= ($res['fullflag']?? 0) == 1? ' checked' : '' ?>>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">审核</label>
            <div class="layui-input-inline">
                <input type="radio" name="display" value="0" title="已审" <?= ($res['display']?? 0)!= 1? ' checked' : '' ?>>
                <input type="radio" name="display" value="1" title="下架" <?= ($res['display']?? 0) == 1? ' checked' : '' ?>>
            </div>
        </div>


        <div class="layui-form-item layui-form-text" style="margin-right:15px;">
            <label class="layui-form-label">内容简介</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea" name="intro" style="height: 250px;"><?= htmlspecialchars($res['intro']?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <button class="layui-btn" id="<?= htmlspecialchars($_REQUEST['do']?? '', ENT_QUOTES, 'UTF-8') ?>">保 存</button>
        </div>

        <script>
            $('#edit').on('click', function() {
                $.ajax({
                    type: "POST",
                    url: "articleupdate.php?do=update",
                    data: {
                        "sourceid": '<?= $sourceid?? '' ?>',
                        "articlename": $('input[name="articlename"]').val(),
                        "articlecode": $('input[name="articlecode"]').val() || '',
                        "backupname": $('input[name="backupname"]').val() || '',
                        "source": $('input[name="source"]').val() || '',
                        "author": $('input[name="author"]').val(),
                        "keywords": $('input[name="keywords"]').val(),
                        "sortid": $('select[name="sortid"]').val(),
                        "fullflag": $('input[name="fullflag"]:checked').val(),
                        "display": $('input[name="display"]:checked').val(),
                        "intro": $('textarea[name="intro"]').val()
                    },
                    async: true,
                    success: function(state) {
                        layer.msg(state == 200? "保存完成" : "保存失败,请检查配置");
                    }
                });
            });

            // 新增文章
            $('#newarticle').on('click', function() {
                $.ajax({
                    type: "POST",
                    url: "articleupdate.php?do=newarticle",
                    data: {
                        "articlename": $('input[name="articlename"]').val(),
                        "articlecode": $('input[name="articlecode"]').val() || '',
                        "backupname": $('input[name="backupname"]').val() || '',
                        "source": $('input[name="source"]').val() || '',
                        "author": $('input[name="author"]').val(),
                        "keywords": $('input[name="keywords"]').val(),
                        "sortid": $('select[name="sortid"]').val(),
                        "fullflag": $('input[name="fullflag"]:checked').val(),
                        "display": $('input[name="display"]:checked').val(),
                        "intro": $('textarea[name="intro"]').val()
                    },
                    async: true,
                    success: function(state) {
                        layer.msg(state == 200? "保存完成" : "新增失败,请检查配置", function() {
                            // let index = parent.layer.getFrameIndex(window.name); // 关闭当前窗口
                            // parent.layer.close(index);
                        });
                    }
                });
            });


            layui.use('upload', function() {
                let upload = layui.upload;

                let uploadInst = upload.render({
                    elem: '#upload-btn',
                    url: 'articleupdate.php?do=upcover&sourceid=<?= $sourceid?? '' ?>',
                    exts: 'jpg',
                    before: function(obj) {
                        // 预读本地文件示例，不支持ie8
                        obj.preview(function(index, file, result) {
                            $('#cover').attr('src', result); // 图片链接（base64）
                        });
                    },
                    done: function(res) {
                        console.log(res);
                        return layer.msg(res == '200'? '封面更新成功' : '封面更新失败');
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