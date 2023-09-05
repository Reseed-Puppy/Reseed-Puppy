define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'download.config/index',
        add_url: 'download.config/add',
        edit_url: 'download.config/edit',
        delete_url: 'download.config/delete',
        export_url: 'download.config/export',
        modify_url: 'download.config/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},                    {field: 'id', title: 'id'},                    {field: 'name', title: '下载器名称'},                    {field: 'type', search: 'select', selectList: {"1":"qb","2":"tr"}, title: '下载器类型'},                    {field: 'url', title: '下载器地址'},                    {field: 'port', title: '下载器端口'},                    {field: 'username', title: '下载器用户名'},                    {field: 'password', title: '下载器密码'},                    {field: 'dir', title: '下载器映射目录'},                    {field: 'skiphash', search: 'select', selectList: {"1":"否","2":"是"}, title: '跳过hash校验'},                    {field: 'isaction', search: 'select', selectList: {"1":"否","2":"是"}, title: '自动开始'},                    {field: 'create_time', title: '创建时间'},                    {width: 250, title: '操作', templet: ea.table.tool},
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
    };
    return Controller;
});