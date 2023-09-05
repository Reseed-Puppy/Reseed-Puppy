define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'system.crontab/index',
        add_url: 'system.crontab/add',
        edit_url: 'system.crontab/edit',
        delete_url: 'system.crontab/delete',
        export_url: 'system.crontab/export',
        modify_url: 'system.crontab/modify',
        flow_url: 'system.crontab/flow',
        reload_url: 'system.crontab/reload',
        start_url: 'system.crontab/start',
        ping_url: 'system.crontab/ping',
    };

    return {
        index: function () {
            var table = layui.table;

            function ping() {
                ea.request.get(
                    {
                        url: init.ping_url,
                        prefix: true
                    },
                    function (res) {
                        $('#crontab-status').html('定时任务 <span class="layui-badge layui-bg-green">运行中</span>');
                        table.reload(init.table_render_id);
                    },
                    function (res) {
                        $('#crontab-status').html('定时任务 <span class="layui-badge layui-bg-red">未启动</span> 请在项目根目录执行命令 <b>php think crontab start -d</b>');
                        table.reload(init.table_render_id);
                    }
                );
            }

            ping();
            // setInterval(ping, 60000);

            ea.table.render({
                init: init,
                toolbar: ['refresh', 'add', 'delete'],
                cellMinWidth: 100,
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'ID', sort: true, width: 80, search: false},
                    {field: 'title', title: '任务标题', minWidth: 180},
                    {field: 'download_name', title: '下载器名称', minWidth: 180},
                    // {field: 'type', title: '任务类型', selectList: typeOptions},
                    {field: 'frequency', title: '任务频率', minWidth: 180, search: false},
                    // {field: 'shell', title: '任务脚本', minWidth: 200, search: false},
                    // {field: 'remark', title: '任务备注', search: false},
                    {field: 'last_running_time', title: '上次执行时间', minWidth: 180, templet: ea.table.date, search: false},
                    {field: 'running_times', title: '已执行次数', search: false},
                    // {field: 'sort', title: '排序', sort: true, edit: 'text', search: false},
                    {field: 'status', title: '状态', templet: ea.table.switch, selectList: {0: '禁用', 1: '启用'}},
                    {field: 'create_time', title: '创建时间', minWidth: 180, sort: true, templet: ea.table.date, search: 'range'},
                    {
                        width: 300, title: '操作', fixed: 'right', templet: ea.table.tool, operat: [
                            [{
                                text: '立即执行',
                                url: init.start_url,
                                field: 'id',
                                method: 'request',
                                title: '确定执行该辅种任务吗？',
                                auth: 'start',
                                class: 'layui-btn layui-btn-xs layui-btn-warm'
                            }, {
                                text: '重启',
                                url: init.reload_url,
                                field: 'id',
                                method: 'request',
                                title: '确定重启吗？',
                                auth: 'reload',
                                class: 'layui-btn layui-btn-xs layui-btn-warm'
                            }, {
                                text: '日志',
                                url: init.flow_url,
                                field: 'id',
                                method: 'open',
                                auth: 'flow',
                                class: 'layui-btn layui-btn-xs layui-btn-normal',
                                extend: 'data-full="false"',
                            }],
                            'edit',
                            'delete']
                    }
                ]],
            });

            ea.listen();
        },
        add: function () {
            ea.listen();
        },
        edit: function () {
            ea.listen();
        },
        flow: function () {

            var intervalID,
                table = layui.table,
                form = layui.form,
                util = layui.util,
                init = {
                    table_elem: '#currentTable',
                    table_render_id: 'currentTableRenderId',
                    index_url: 'system.crontab/flow?sid=' + sid,
                };

            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cellMinWidth: 100,
                cols: [[
                    {field: 'id', title: 'ID', sort: true, width: 80, search: false},
                    {field: 'month', title: '日志月份', hide: true, search: 'time', timeType: 'month', searchValue: util.toDateString(new Date(), 'yyyy-MM')},
                    {
                        field: 'return_var', title: '运行结果', selectList: {0: '成功', 1: '失败'}, templet: function (d) {
                            return d.return_var === 0 ? '<span class="layui-badge layui-bg-green">成功</span>' : '<span class="layui-badge layui-bg-red">失败</span>';
                        }
                    },
                    // {field: 'command', title: '任务命令', search: false},
                    // {
                    //     field: 'running_time', title: '执行耗时', search: false, templet: function (d) {
                    //         return d.running_time + 's';
                    //     }
                    // },
                    {
                        field: 'output', title: '执行输出', search: false, templet: function (d) {
                            return d.output.replace(/\n/g, "<br/>");
                        }
                    },
                    {field: 'create_time', title: '执行时间', sort: true, templet: ea.table.date, search: 'range'}
                ]],
            });

            form.on('switch(monitor)', function (data) {
                if (data.elem.checked) {
                    intervalID = setInterval(function () {
                        table.reload(init.table_render_id);
                    }, 1000);
                } else {
                    clearInterval(intervalID);
                }

            });

            ea.listen();
        }
    }
})
