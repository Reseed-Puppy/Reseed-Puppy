define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'site.config/index',
        add_url: 'site.config/add',
        edit_url: 'site.config/edit',
        delete_url: 'site.config/delete',
        export_url: 'site.config/export',
        modify_url: 'site.config/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},                    {field: 'id', title: 'id'},                    {field: 'siteName', title: '站点名称'},                    {field: 'siteUrl', title: '站点地址'},                    {field: 'apiUrl', title: '站点接口地址'},                    {field: 'passkey', title: '站点passkey'},                    {field: 'status', search: 'select', selectList: ["禁用","启用"], title: '状态', templet: ea.table.switch},                    {field: 'create_time', title: '创建时间'},                    {width: 250, title: '操作', templet: ea.table.tool},
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