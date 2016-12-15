var Title = '高级会员管理';
var Module = 'grade';

var PageUrl = '../../pages/';
var ModuleName = Module;
var PostModule = 'member/' + 'seniormember';

var DataGrid = '#' + Module + 'Grid';
var ToolbarT = '#' + Module + 'Toolbar';
var DiaLog = '#' + Module + 'DiaLog';
var Form = '#' + Module + 'Form';

var data = [[

    //{field: 'ck', checkbox: true},

    {title: '登录名', field: 'login_name', width: '10%', align: 'left', sortable: true},
    {title: '昵称', field: 'nick_name', width: '10%', align: 'left', sortable: true},
    {
        title: '用户类型', field: 'mtype', width: '10%', align: 'left', sortable: true,
        formatter: function (value) { //格式化数据
            if (value == 1) {
                return '创业企业';
            } else if (value == 2) {
                return '孵化器'
            } else if (value == 3) {
                return '服务机构'
            } else if (value == 4) {
                return '个人'
            }
        }
    },
    {title: '电子邮箱', field: 'email', width: '20%', align: 'left', sortable: true},
    {
        title: '用户级别', field: 'level', width: '7%', align: 'left', sortable: true,
        formatter: function (value) { //格式化数据
            if (value == 1) {
                return '一般会员';
            } else if (value == 2) {
                return '高级会员'
            }
        }
    },
    //{title: '用户权限', field: 'access', width: '15%', align: 'left', sortable: true},

    {
        title: '审核状态', field: 'type', width: '7%', align: 'center', sortable: true,
        formatter: function (value) { //格式化数据
            if (value == 1) {
                return '待审核';
            } else if (value == 2) {
                return '审核成功'
            } else if (value == 3) {
                return '退回修改'
            } else if (value == 4) {
                return '条件不符合'
            }
        }
    },
    {title: '发布时间', field: 'created', width: '13%', align: 'center', sortable: true},
    {title: '更新时间', field: 'updated', width: '13%', align: 'center', sortable: true},
    {
        title: '操作', field: 'id', width: '9%', align: 'center',
        formatter: function (value, index) {


            return '<a href="javascript:void(0)" onclick="Smember.UpOpen(' + value + ',' + index.mtype + ')">审核</a>';

        }
    }
]];

$(function () {

    //分类列表
    $(DataGrid).datagrid({
        url: Qrck.baseApiUrl + PostModule + '/index?type=1',
        method: 'get',
        iconCls: 'icon-view',
        height: 650,
        width: function () {
            return document.body.clientWidth * 0.9
        },
        nowrap: true,
        autoRowHeight: false,
        selectOnCheck: false,
        checkOnSelect: false,
        fit: true,
        fitColumns: true,
        striped: true,
        //ctrlSelect:true,
        singleSelect: true,
        collapsible: true,
        rownumbers: true,
        remoteSort: false,
        pagination: true,
        pageSize: 30,
        pageList: [30, 60, 90],
        sortName: 'id',
        sortOrder: 'desc',
        idField: 'id',
        columns: data,
        toolbar: ToolbarT,
        onLoadSuccess: function () {
            $(DataGrid).datagrid('clearSelections');
            $(DataGrid).datagrid('clearChecked');
        },
        onSelect: function () {
            $(DataGrid).datagrid('clearChecked');
        },
        onCheck: function () {
            $(DataGrid).datagrid('clearSelections');
        },
        onCheckAll: function () {
            $(DataGrid).datagrid('clearSelections');
        }

    });


});


function IS_SELECT(value, index) {
    var Types = [
        'company',
        'incubator',
        'service'
    ];
    $(DataGrid).datagrid('selectRecord', value);
    var grid = $(DataGrid).datagrid('getChecked');
    var row = $(DataGrid).datagrid('getSelected');
    var is = {};
    is.id = row ? row.id : grid[0] ? grid[0].id : value;
    is.mtype = index ? Types[index - 1] : grid[0] ? Types[grid[0].mtype - 1] : Types[row.mtype - 1];
    if (is == null) {
        $.messager.alert({
            title: '提示',
            msg: '请先选择一行!'
        });
    }
    return is;


}
Smember = {

    UpOpen: function (value, index) {
        var is = IS_SELECT(value, index);

        if (is) {

            $(DiaLog).dialog({
                href: PageUrl + ModuleName + '/update_' + is.mtype + '.html',
                title: Title + '--审核',
                inline: true,
                width: 1000,
                height: 600,
                maximized: true,
                closed: false,
                cache: false,
                modal: true,
                buttons: [{
                    text: '提交审核',
                    iconCls: 'icon-save',
                    handler: function () {
                        var upload = $('#SeniorApprovalForm').serializeArray();

                        //修改会员状态
                        var level = (upload[3].value == 2) ? 2 : 1;
                        var is = IS_SELECT(value, index);
                        Qrck.get(PostModule + '/setlevel', {id: is.id, level: level});
                        //提交审核日志
                        Qrck.put(PostModule + '/seniorapproval', upload,
                            function () {
                                $.messager.progress({
                                    text: '请稍后……'

                                });
                            }, function () {
                                $.messager.progress('close');
                                $.messager.alert({
                                    title: '提示',
                                    msg: '提交成功!',
                                    fn: function () {
                                        $(this).dialog('close');

                                    }

                                });

                                $(DataGrid).datagrid('load');
                            }, function () {
                                $.messager.progress('close');
                                $.messager.alert({
                                    title: '错误提示',
                                    msg: '更新失败!查找一下哪里有问题....',
                                    fn: function () {
                                        $(this).dialog('close');

                                    }
                                });
                            });
                        is = null;
                        $(DataGrid).datagrid('load');


                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {

                        $(this).dialog('close');
                        $(this).val('');
                    }
                }],
                onClose: function () {
                    $(this).dialog('destroy');
                    $(this).val('');
                }
            });

        }
        is = null;

    },
    Reload: function () {

        $(DataGrid).datagrid('load');
        $(DiaLog).val('');

    },


    clearForm: function (select) {
        $(select).form('clear');
    },


    getStatus: function (select) {
        //设置栏目类别
        $(select).combobox({
            data: [
                {
                    "id": 1,
                    "text": "待发布"
                },
                {
                    "id": 2,
                    "text": "已发布"
                }
            ],
            prompt: '请选择类别....',
            valueField: 'id',
            textField: 'text',
            editable: false,
            width: 200,
            panelHeight: 75
        });
    },
    getComboBox: function (select, url, onSelect, success) {
        Qrck.get(url, '', '', function (data) {
            $(select).combobox({
                data: data,
                prompt: '请选择....',
                valueField: 'id',
                textField: 'text',
                editable: false,
                width: 200,
                panelHeight: 75,
                onSelect: onSelect,
                onLoadSuccess: success
            });
        });
    },
    setStatus: function (sid) {

        var is = this.Is_Select();

        if (is) {
            $.messager.confirm('确认对话框', '您确定想要修改发布状态吗？', function (r) {
                if (r) {
                    Qrck.delete(PostModule + '/status', {ids: is, status: sid}, function () {
                    }, function (data) {
                        if (data.code = 200) {


                            $.messager.alert('提示', '修改成功!', 'warning', function () {
                                $(DataGrid).datagrid('load');
                            });
                        }
                    }, function () {
                        $.messager.alert('警告', '修改失败!');
                    });
                }
            });
        } else {
            $.messager.alert('警告', '必须选择一行才能操作!');
        }
    },
    Is_Select: function (value) {
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
    },
    TextSearch: function (select) {
        //搜索
        $(select).searchbox({
            prompt: '关键搜索...',
            width: 200,
            panelHeight: 55,
            searcher: function () {
                $(DataGrid).datagrid('load', {
                    keyword: $(select).searchbox('getValue'),
                    keytype: 'title,keywords,description'
                })
            }
        });
    },
};

