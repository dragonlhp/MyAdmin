/**
 * Created by Administrator on 2016/11/6 0006.
 */
/**
 *  模块    ：编纂模块
 *  流程    ：创建任务
 */

$(function () {
    $('#admin_role_grid').datagrid({   //定位到Table标签，Table标签的ID是grid
        url: Qrck.baseApiUrl + 'admin/role/index?page_size=100',
        method: "get",
        iconCls: 'icon-view',
        height: 650,
        width: function () {
            return document.body.clientWidth * 0.9
        },
        nowrap: true,
        autoRowHeight: false,
        fit: true,
        fitColumns: true,
        striped: true,
        collapsible: true,
        pagination: true,
        pageSize: 100,
        pageList: [10, 20, 40],
        //rownumbers: true,
        selectOnCheck: false,
        checkOnSelect: false,
        singleSelect: true,
        sortName: 'id',    //根据某个字段给easyUI排序
        sortOrder: 'desc',
        remoteSort: false,
        idField: 'id',
        // queryParams: queryData,  //异步查询的参数
        frozenColumns: [[
            {field: 'ck', checkbox: true},   //选择
        ]],
        columns: [[
            {title: '名称', field: 'name', width: "30%", align: 'left'},
            {
                title: '操作', field: 'id', width: "10%", align: 'center'
                , formatter: function (value, row, index) {
                return '<a href="#" onclick="editRole(' + value + ')">编辑</a>';
            }

            }
        ]],

        toolbar: [{
            id: 'articleAdd',
            text: '添加',

            iconCls: 'icon-add',
            handler: function () {
                addRole();
            }
        }, '-', {
            id: 'adminUserDelete',
            text: '删除',
            iconCls: 'icon-remove',
            handler: function () {
                // 删除用户
                deleteRole();
            }
        }, '-', {
            id: 'adminUserReload',
            text: '刷新',
            iconCls: 'icon-reload',
            handler: function () {
                //实现刷新栏目中的数据
                $("#admin_role_grid").datagrid("reload");
            }
        },
        ],
    });
    $('#article_toolbar').appendTo('.datagrid-toolbar');


    //  登录账户验证
    $('#admin_role_add_login_name').validatebox({
        required: true,
        missingMessage: '请输入登录名'
    });
    $('#admin_role_add_name').validatebox({
        required: true,
        missingMessage: '请输入昵称'
    });
    //  登录密码验证
    $('#admin_role_add_pwd').validatebox({
        required: false,
        validType: 'length[3,8]',
        missingMessage: '请输入密码',
        invalidMessage: '密码长度不应低于3位或大于8位'
    });
});


/*
 *删除角色
 */
function deleteRole() {
    var rows = $("#admin_role_grid").datagrid("getChecked");
    if (rows.length > 0) {
        var ids = "";
        for (var i = 0; i < rows.length; i++) {
            ids += ids == "" ? rows[i].id : "," + rows[i].id;
        }
        var url = 'admin/role/delete';
        var parm = {ids: ids};
        Qrck.delete(url, parm, "", function () {
            $.messager.alert("提示信息", "操作成功！", "warning");
            $("#admin_role_grid").datagrid("reload");
        }, function () {
            $.messager.alert("提示", "操作失败！", "warning");
        });
    }
}


function addRole() {
    $("#admin_role_form_box")[0].reset();
    $('#admin_role_add_access').val("");
    $('#admin_role_add_access').combotree({
        url: Qrck.baseApiUrl + 'admin/menu/index',
        headers: {"X-Auth-Client": $.cookie('login_name'), "X-Auth-Token": $.cookie('token')},
        required: true,
        width: 450,
        method: "get",
        multiple: true,
        value: "",
        // onShowPanel:function(){
        //      $.messager.progress({
        //         text: '菜单获取中请稍后……',
        //     });
        // }
    });

    $("#admin_role_form").removeClass("hide");
    $('#admin_role_form').dialog({
        title: '添加角色',
        cache: false,
        width: 600,
        modal: true,
        inline: true,
        buttons: [{
            text: '保存',
            iconCls: 'icon-save',
            handler: function () {

                if ($('#admin_role_form_box').form('validate')) {
                    var data = $("#admin_role_form_box").serializeArray();
                    var url = 'admin/role/create';
                    Qrck.post(url, data, "", function () {
                        $.messager.alert("提示信息", "操作成功！", "warning");
                        $('#admin_role_form').dialog('close');
                        $("#admin_role_grid").datagrid("reload");
                    }, function () {
                        $.messager.alert("提示", "操作失败！", "warning");
                    });

                } else {
                    $.messager.show({
                        title: '提示',
                        msg: '请填写完整',
                        showType: 'slide'
                    });
                }
            }
        }, {
            text: '取消',
            iconCls: 'icon-cancel',
            handler: function () {
                $('#admin_role_form').dialog('close');
                $("#admin_role_form").addClass("hide");
            }
        }]
    });
}


function editRole(rid) {
    if (rid > 0) {
        $("#admin_role_form_box")[0].reset();
        $('#admin_role_add_access').val("");
        var url = "admin/role/view?id=" + rid;
        Qrck.get(url, {}, "", function (data) {
            $("#admin_role_add_name").val(data.name);

            $('#admin_role_add_access').combotree({
                url: Qrck.baseApiUrl + 'admin/menu/index',
                headers: {"X-Auth-Client": $.cookie('login_name'), "X-Auth-Token": $.cookie('token')},
                required: true,
                width: 450,
                method: "get",
                multiple: true,
                value: data.menu_ids,
            });
        }, function () {
            $.messager.alert("提示信息", "获取角色信息失败！", "warning");
        });


        $("#admin_role_form").removeClass("hide");
        $('#admin_role_form').dialog({
            title: '编辑角色',
            cache: false,
            width: 600,
            modal: true,
            inline: true,
            buttons: [{
                text: '保存',
                iconCls: 'icon-save',
                handler: function () {

                    if ($('#admin_role_form_box').form('validate')) {
                        var data = $("#admin_role_form_box").serializeArray();
                        var url = 'admin/role/update?id=' + rid;
                        Qrck.put(url, data, "", function () {
                            $.messager.alert("提示信息", "操作成功！", "warning");
                            $('#admin_role_form').dialog('close');
                            $("#admin_role_grid").datagrid("reload");
                        }, function () {
                            $.messager.alert("提示", "操作失败！", "warning");
                        });

                    } else {
                        $.messager.show({
                            title: '提示',
                            msg: '请填写完整',
                            showType: 'slide'
                        });
                    }
                }
            }, {
                text: '取消',
                iconCls: 'icon-cancel',
                handler: function () {
                    $('#admin_role_form').dialog('close');
                    $("#admin_role_form").addClass("hide");
                }
            }]
        });
    }
}
