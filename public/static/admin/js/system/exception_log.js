define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'system.exception_log/index',
    };

    return {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cellMinWidth: 80,
                cols: [[
                    {field: 'id', title: 'ID', search: false},
                    {field: 'message', title: '异常消息'},
                    {field: 'code', title: '异常代码'},
                    {field: 'file', title: '异常文件'},
                    {field: 'line', title: '异常行号'},
                    {field: 'trace', title: '异常追踪', hide: true, search: false},
                    {field: 'url', title: '请求url'},
                    {field: 'method', title: '请求方法'},
                    {field: 'param', title: '请求参数', search: false},
                    {field: 'ip', title: '请求IP'},
                    {field: 'header', title: '请求头', hide: true, search: false},
                    {field: 'create_time', title: '创建时间', search: 'range'},
                ]],
            });

            ea.listen();
        }
    };
});