/**
 * Created by Administrator on 2016/11/6 0006.
 */
/**
 *  模块    ：编纂模块
 *  流程    ：创建任务
 */

$(function () {
    $('#admin_setting_grid').datagrid({   //定位到Table标签，Table标签的ID是grid
        url: Qrck.baseApiUrl + 'admin/setting/index',
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
            {title: '内容', field: 'vdata', width: "30%", align: 'left'},

            {
                title: '操作', field: 'id', width: "10%", align: 'center'
                , formatter: function (value, row, index) {
                return '<a href="#" onclick="editRole(' + value + ')">编辑</a>';
            }

            }
        ]],

        toolbar: [{
            id: 'adminSettingAdd',
            text: '添加',

            iconCls: 'icon-add',
            handler: function () {
                addRole();
            }
        }, '-', {
            id: 'adminSettingDelete',
            text: '删除',
            iconCls: 'icon-remove',
            handler: function () {
                // 删除用户
                deleteRole();
            }
        }, '-', {
            id: 'adminSettingReload',
            text: '刷新',
            iconCls: 'icon-reload',
            handler: function () {
                //实现刷新栏目中的数据
                $("#admin_setting_grid").datagrid("reload");
            }
        },
        ],
    });
    $('#article_toolbar').appendTo('.datagrid-toolbar');

    //$('#admin_setting_add_name').textbox({
    //    width : 200,
    //});
});


/*
 *删除设置项
 */
function deleteRole() {
    var rows = $("#admin_setting_grid").datagrid("getChecked");
    if (rows.length > 0) {
        var ids = "";
        for (var i = 0; i < rows.length; i++) {
            ids += ids == "" ? rows[i].id : "," + rows[i].id;
        }
        var url = 'admin/setting/delete';
        var parm = {ids: ids};
        Qrck.delete(url, parm, "", function () {
            $.messager.alert("提示信息", "操作成功！", "warning");
            $("#admin_setting_grid").datagrid("reload");
        }, function () {
            $.messager.alert("提示", "操作失败！", "warning");
        });
    }
}

/*
*
* 添加设置项
 */

function addRole() {
    $("#admin_setting_form_box")[0].reset();
    $('#tr').show()
    //$.messager.show({
    //    title: '提示',
    //    msg: '请填写完整',
    //    showType: 'slide'
    //});

    $("#admin_setting_form").removeClass("hide");
    $('#admin_setting_form').dialog({
        title: '添加设置项',
        cache: false,
        width: 600,
        modal: true,
        inline: true,
        buttons: [{
            text: '保存',
            iconCls: 'icon-save',
            handler: function () {

                if ($('#admin_setting_form_box').form('validate'))
                {
                    var name =  $('#admin_setting_add_name').val();
                    var vdata =  $('#admin_setting_add_vdata').val();
                    var code = $('#admin_setting_code').val();
                    var url= 'admin/setting/create';

                    Qrck.post(url, {vdata:vdata,name:name,code:code}, "", function () {

                        $.messager.alert("提示信息", "操作成功！", "warning");
                        $('#admin_setting_form').dialog('close');
                        $("#admin_setting_grid").datagrid("reload");
                    }, function () {
                        $.messager.alert("提示", "操作失败！", "warning");
                    });
                }
                else {
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
                $('#admin_setting_form').dialog('close');
                $("#admin_setting_form").addClass("hide");
            }
        }]
    });
}


function editRole(rid) {

    $("#tr").hide();
    if (rid > 0) {

        $("#admin_setting_form_box")[0].reset();
        $('#admin_setting_add_access').val("");
        var url = "admin/setting/view?id=" + rid;
        Qrck.get(url, {}, "", function (data) {
            $("#admin_setting_add_name").val(data.name);
            $("#admin_setting_add_vdata").val(data.vdata);

        }, function () {
            $.messager.alert("提示信息", "获取角色信息失败！", "warning");
        });


        $("#admin_setting_form").removeClass("hide");
        $('#admin_setting_form').dialog({
            title: '编辑',
            cache: false,
            width: 600,
            modal: true,
            inline: true,
            buttons: [{
                text: '保存',
                iconCls: 'icon-save',
                handler: function () {

                    if ($('#admin_setting_form_box').form('validate'))
                    {
                        var id = Is_Select();
                        var name =  $('#admin_setting_add_name').val();
                        var vdata =  $('#admin_setting_add_vdata').val();
                        var code = $('#admin_setting_code').val();
                        var url= 'admin/setting/update?id='+id;

                        Qrck.put(url, {vdata:vdata,name:name,code:code}, "", function () {

                            $.messager.alert("提示信息", "操作成功！", "warning");
                            $('#admin_setting_form').dialog('close');
                            $("#admin_setting_grid").datagrid("reload");
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
                    $('#admin_setting_form').dialog('close');
                    $("#admin_setting_form").addClass("hide");
                }
            }]
        });
    }

}
function Is_Select (value) {
  var DataGrid = "#admin_setting_grid";
    if (value !== null) {
        $(DataGrid).datagrid('selectRecord', value);
    }
    var row = $(DataGrid).datagrid('getSelected');
    var getids = $(DataGrid).datagrid('getChecked');
    var ids = [];
    var is = '';
    $.each(getids, function (index, item) {
        ids.push(item.id);
    });
    if (ids.length !== 0) {
        is = ids.join(",");
    } else {
        is = row.id;
    }
    return is;
}
