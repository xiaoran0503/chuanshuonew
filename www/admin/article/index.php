<?php require_once '../header.php';
?>
<!-- 内容主体区域 -->
<div class="layui-body">
    <form class="layui-form layui-form-pane" method="POST" action="javascript:;">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>删除小说：只有在<b style="color:#FF5722">源站后台</b>才能彻底删除txt,封面,附件。</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">关键字</label>
            <div class="layui-input-inline">
                <input type="text" name="searchkey" placeholder="书名或作者" autocomplete="off" class="layui-input">
            </div>
            <a class="layui-btn search-btn-article" style="margin-right:50px;">搜索</a>
            <a class="layui-btn display-btn">已下架</a>
            <button class="layui-btn newarticle-btn">新增小说</button>
        </div>

        <!-- 表格 -->
        <table class="layui-hide" id="articlelist" lay-filter="articlelist"></table>
    </form>
</div>


</div> <!-- /header -->

<!-- 控制部分 -->
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="manage">章节</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>

<script type="text/html" id="toolbarDemo">
    <!-- 表头模板 -->
    <div class="layui-btn-container">
        <button class="layui-btn layui-btn-sm" lay-event="delCheckData">删除选中小说</button>
    </div>
</script>

<script type="text/html" id="tpl-articleinfo">
    <a href="{{d.info_url}}" class="layui-table-link" target="_blank">{{d.articlename}}</a>
</script>

<script>
    layui.use('table', function() {
        let table = layui.table;

        // 定义表格列配置
        const tableCols = [
            [
                { type: 'checkbox', fixed: 'left' },
                { fixed: 'right', title: '操作', toolbar: '#barDemo', width: 165 },
                { field: 'articlename', title: '小说名称', sort: true, templet: '#tpl-articleinfo' },
                { field: 'lastchapter', title: '最新章节', sort: true },
                { field: 'author', title: '作者', sort: true, width: 120 },
                { field: 'sortname', title: '分类', sort: true, width: 120 },
                { field: 'isfull', title: '状态', sort: true, width: 80 },
                { field: 'display', title: '审核', sort: true, width: 80 },
                { field: 'allvisit', title: '点击', sort: true, width: 80 },
                { field: 'words_w', title: '万字', sort: true, width: 80 },
                { field: 'lastupdate_cn', title: '更新', sort: true }
            ]
        ];

        // 定义表格渲染函数
        function renderTable(url) {
            table.render({
                elem: '#articlelist',
                toolbar: '#toolbarDemo',
                limit: 20,
                defaultToolbar: ['filter', 'exports', 'print'],
                url: url,
                cols: tableCols,
                page: true
            });
        }

        // 头工具栏事件
        table.on('toolbar(articlelist)', function(obj) {
            let checkStatus = table.checkStatus(obj.config.id);
            let data = checkStatus.data;
            switch (obj.event) {
                case 'delCheckData':
                    layer.confirm('确定要删除选中的小说吗？', function() {
                        let ids = [];
                        $.each(data, function(index, value) {
                            ids.push(value.sourceid);
                        });

                        $.ajax({
                            type: "POST",
                            url: "../include/articleupdate.php?do=delete",
                            data: {
                                "sourceid": ids
                            },
                            success: function(state) {
                                layer.msg(state == 200 ? '删除成功' : '删除过程中发现错误,请检查');
                            }
                        });
                        table.reload('articlelist');
                    });
                    break;
            }
        });

        // 渲染初始表格数据
        renderTable('../include/articlelist.php?do=show');

        // 监听行内按钮事件
        table.on('tool(articlelist)', function(obj) {
            let data = obj.data;
            switch (obj.event) {
                case 'edit': // 编辑按钮
                    layer.open({
                        type: 2,
                        title: data.sourceid,
                        area: ['80%', '90%'],
                        content: '../include/tpl_articleedit.php?do=edit&sourceid=' + data.sourceid
                    });
                    break;

                case 'manage': // 管理(章节)按钮
                    layer.open({
                        type: 2,
                        title: data.sourceid,
                        area: ['80%', '90%'],
                        content: '../include/tpl_chapterlist.php?sourceid=' + data.sourceid + '&articlename=' + data.articlename
                    });
                    break;

                case 'del': // 删除按钮
                    layer.confirm('删除《' + data.articlename + '》', function(index) {
                        $.ajax({
                            type: "POST",
                            url: "../include/articleupdate.php?do=delete",
                            data: {
                                "sourceid": data.sourceid
                            },
                            success: function(state) {
                                layer.msg(state == 200 ? '删除完成' : '删除失败,请检查配置');
                            }
                        });
                        obj.del();
                        layer.close(index);
                    });
                    break;
            }
        });

        // 搜索按钮点击事件
        $('.search-btn-article').on('click', function() {
            let searchkey = $("input[name='searchkey']").val();
            // 对搜索关键字进行转义，防止 XSS 攻击
            searchkey = encodeURIComponent(searchkey.replace(/[<>"']/g, function(match) {
                switch (match) {
                    case '<': return '%3C';
                    case '>': return '%3E';
                    case '"': return '%22';
                    case "'": return '%27';
                    default: return match;
                }
            }));
            renderTable('../include/articlelist.php?do=search&searchkey=' + searchkey);
        });

        // 审核/下架按钮点击事件
        $('.display-btn').on('click', function() {
            renderTable('../include/articlelist.php?display=1');
        });

        // 新增小说按钮点击事件
        $('.newarticle-btn').on('click', function() {
            layer.open({
                type: 2,
                title: '新增小说',
                area: ['80%', '90%'],
                content: '../include/tpl_articleedit.php?do=newarticle'
            });
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