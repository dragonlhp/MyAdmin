var Title = '在线互动';
var Module = 'faq';

var PageUrl = '../../pages/';
var ModuleName = Module;
var PostModule = 'bs/' + Module;

var DataGrid = '#' + Module + 'Grid';
var ToolbarT = '#' + Module + 'Toolbar';
var DiaLog = '#' + Module + 'DiaLog';
var Form = '#' + Module + 'Form';
var SearchForm = "#" + Module + 'SearchForm';
var SeatchType = "content";

var columns = [[
    {field: 'ck', checkbox: true},

    {
        title: '内容', field: 'content', width: '34%', align: 'left', sortable: true,
        formatter: function (value, index) {
            //substring
            if (index.content !== null) {
                var ret = index.content;
                if (ret.length > 15) {
                    return ret.substring(0, 15) + '.....';
                } else {
                    return ret;
                }

            }
        }
    },
    {
        title: '问题类别', field: 'qtype', width: '10%', align: 'center', sortable: true,
        formatter: function (value) { //格式化数据
            if (value == 1) {
                return '载体';
            } else if (value == 2) {
                return '资金'
            } else if (value == 3) {
                return '技术'
            } else if (value == 4) {
                return '专利'
            } else if (value == 5) {
                return '产品'
            } else {
                return '未选择'
            }
        }
    },
    {
        title: '状态', field: 'status', width: '10%', align: 'center', sortable: true,
        formatter: function (value) { //格式化数据
            if (value == 1) {
                return '待发布';
            } else if (value == 2) {
                return '已发布'
            }
        }
    },
    {
        title: '语言', field: 'lan', width: '5%', align: 'center', sortable: true,
        formatter: function (value) { //格式化数据
            if (value == 1) {
                return '中文';
            } else if (value == 2) {
                return 'English'
            } else if (value == 3) {
                return '한 글'
            }
        }
    },
    {title: '发布时间', field: 'created', width: '14%', align: 'center', sortable: true},
    {title: '更新时间', field: 'updated', width: '14%', align: 'center', sortable: true},
    {
        title: '操作', field: 'id', width: '10%', align: 'center',
        formatter: function (value) {

            return '<a href="javascript:void(0)" onclick="Option.UpOpen(' + value + ')" id="Edit">编辑</a>' +
                '&nbsp;&nbsp;&nbsp;' +
                '<a href="javascript:void(0)" onclick="Option.Delete(' + value + ')">删除</a>';
        }
    }
]];

/**
 * Created by Administrator on 2016/11/6 0006.
 */
/**
 *  模块：产品需求模块
 */

$(function () {

    //分类列表
    $(DataGrid).datagrid({
        url: Qrck.baseApiUrl + PostModule + '/index',
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
        columns: columns,

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


    $('.easyui-textbox').textbox({
        width: 200
    });

});


Option = {

    AddOpen: function () {
        $(Form).removeAttr('hidden');
        NowType = 'add';
        $(Form).form('clear');
        var T = Title + '--添加';
        $(DiaLog).dialog(
            {
                title: T,
                inline: true,
                width: 400,
                height: 300,
                closed: false,
                cache: false,
                modal: true,
                buttons: [{
                    text: '保存',
                    iconCls: 'icon-save',
                    handler: function () {

                        //var data = $(select).serializeArray();
                        Qrck.post(PostModule + '/create', $(Form).serializeArray(),
                            function () {
                                $.messager.progress({
                                    text: '请稍后……'
                                });
                            }, function (data) {
                                $.messager.progress('close');
                                $.messager.alert({
                                    title: '提示',
                                    msg: '添加成功!'
                                });
                                $(DiaLog).dialog('close');
                                $(DataGrid).datagrid('load');
                            }, function () {
                                $.messager.progress('close');
                                $.messager.alert({
                                    title: '错误提示',
                                    msg: '添加失败!查找一下哪里有问题....'
                                });
                            });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $(DiaLog).dialog('close');
                    }
                }]

            }
        );
    },
    UpOpen: function (value) {
        $(Form).removeAttr('hidden');
        var U = Title + '--修改';
        //获取编辑数据

        var is = this.Is_Select(value);
        if (is == null) {
            $.messager.alert({
                title: '提示',
                msg: '请选择一行进行编辑!'
            });
        } else {
            $.ajax({
                type: 'get',
                url: Qrck.baseApiUrl + PostModule + '/view?id=' + is.id,
                success: function (data) {
                    $(Form).form('load', data);
                }
            });
            $(DiaLog).dialog({
                title: U,
                inline: true,
                width: 400,
                height: 300,
                closed: false,
                cache: false,
                modal: true,
                buttons: [{
                    text: '更新',
                    iconCls: 'icon-save',
                    handler: function () {
                        var is = Option.Is_Select();
                        Qrck.put(PostModule + '/update?id=' + is.id, $(Form).serializeArray(),
                            function () {
                                $.messager.progress({
                                    text: '请稍后……'

                                });
                            }, function () {
                                $.messager.progress('close');
                                $.messager.alert({
                                    title: '提示',
                                    msg: '更新成功!'

                                });
                                $(DiaLog).dialog('close');
                                $(DataGrid).datagrid('load');
                            }, function () {
                                $.messager.progress('close');
                                $.messager.alert({
                                    title: '错误提示',
                                    msg: '更新失败!查找一下哪里有问题....'
                                });
                            });

                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $(DiaLog).dialog('close');
                    }
                }]
            });

        }
    },
    getLan: function (select) {
        //设置语言
        $(select).combobox({
            data: [
                {
                    "id": 1,
                    "text": "中 文"
                },
                {
                    "id": 2,
                    "text": "English"
                },
                {
                    "id": 3,
                    "text": "한 글"
                }
            ],
            prompt: '选择栏目语言',
            valueField: 'id',
            textField: 'text',
            editable: false,
            width: 200,
            panelHeight: 75
        });
    },
    Reload: function () {

        $(DataGrid).datagrid('load');

    },
    Delete: function (value) {
        if (value !== null) {
            $(DataGrid).datagrid('selectRecord', value);
        }

        var row = $(DataGrid).datagrid('getSelected');
        var getids = $(DataGrid).datagrid('getChecked');
        var ids = [];
        var sid = '';
        $.each(getids, function (index, item) {
            ids.push(item.id);
        });
        if (ids.length !== 0) {
            sid = ids.join(",");
        } else {
            sid = row.id;
        }

        if (sid) {
            $.messager.confirm('确认对话框', '您确定想要删除这些数据吗？', function (r) {
                if (r) {
                    Qrck.delete(PostModule + '/status?ids=' + sid, {ids: sid, status: 9}, function () {
                    }, function (data) {
                        if (data.code = 200) {
                            $.messager.alert('提示', '删除成功!', 'warning', function () {
                                $(DataGrid).datagrid('load');
                            });
                        }
                    }, function () {
                        $.messager.alert('警告', '删除失败!');
                        //console.log(data);
                    });
                }
            });
        } else {
            $.messager.alert('警告', '必须选择一行才能删除!');
        }
    },
    updateForm: function (select) {
        var row = $(DataGrid).datagrid('getSelected');
        var data = $(select).serializeArray();
        var getids = $(DataGrid).datagrid('getChecked');
        var is = getids ? getids[0] : null;
        is = is ? is : row;
        Qrck.put(PostModule + '/update?id=' + is.id, data,
            function () {
                $.messager.progress({
                    text: '请稍后……'

                });
            }, function () {
                $.messager.progress('close');
                $.messager.alert({
                    title: '提示',
                    msg: '更新成功!'
                });
                $(DataGrid).datagrid('load');
                $(select).form('clear');
            }, function () {
                $.messager.progress('close');
                $.messager.alert({
                    msg: '更新失败!查找一下哪里有问题....'
                });
            });
    },
    addForm: function (select) {

        var data = $(select).serializeArray();

        Qrck.post(PostModule + '/create', data,
            function () {
                $.messager.progress({
                    text: '请稍后……'

                });
            }, function (data) {
                $.messager.progress('close');
                $.messager.alert({
                    title: '提示',
                    msg: '添加成功!'
                });
                $(select).form('clear');
                $(DataGrid).datagrid('load');
            }, function () {
                $.messager.progress('close');
                $.messager.alert({
                    title: '错误提示',
                    msg: '添加失败!查找一下哪里有问题....'
                });
            });
    },
    clearForm: function (select) {
        $(select).form('clear');
    },
    getLan: function (select) {
        //设置语言
        $(select).combobox({
            data: [
                {
                    "id": 1,
                    "text": "中 文"
                },
                {
                    "id": 2,
                    "text": "English"
                },
                {
                    "id": 3,
                    "text": "한 글"
                }
            ],
            prompt: '选择栏目语言',
            valueField: 'id',
            textField: 'text',
            editable: false,
            width: 200,
            panelHeight: 75
        });
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
                },
                {
                    "id": 3,
                    "text": "删  除"
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
                    Qrck.put(PostModule + '/status', {ids: is.id, status: sid}, function () {
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
        var getids = $(DataGrid).datagrid('getChecked');
        var row = $(DataGrid).datagrid('getSelected');
        if (value) {
            return {id: value};
        } else if (row) {
            return row
        } else if (getids.length == 1) {

            return getids[0]
        } else if (getids.length > 1) {
            var getLen = '';
            $.each(getids, function (k, v) {
                if (k == 0) {
                    getLen += v.id;
                } else {
                    getLen += "," + v.id;
                }
            });
            return {id: getLen};
        }
    },
    SelectQType: function (select) {
        $(select).combobox({
            data: [
                {
                    "id": 1,
                    "text": "载体"
                },
                {
                    "id": 2,
                    "text": "资金"
                },
                {
                    "id": 3,
                    "text": "技术"
                },
                {
                    "id": 4,
                    "text": "专利"
                },
                {
                    "id": 5,
                    "text": "产品"
                }
            ],
            prompt: '请选择类别....',
            valueField: 'id',
            textField: 'text',
            editable: false,
            width: 200,
            panelHeight: 125
        });
    },
    getSelectStatus: function (select) {
        //工具栏筛选
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
            panelHeight: 75,
            onChange: function () {
                Option.SearchForm();
            }
        });
    },
    TextSearch: function (select) {
        //搜索
        $(select).searchbox({
            prompt: '关键搜索...',
            width: 200,
            panelHeight: 55,
            searcher: function () {
                Option.SearchForm();
            }
        });
    },
    SearchForm: function () {
        //文章分类树选择结果
        var form = $(SearchForm).serializeArray();
        var each = [];
        form.push({name: 'keytype', value: SeatchType});
        $.each(form, function (k, v) {
            each[v.name] = v.value;
        });
        $(DataGrid).datagrid('load', each)
    }
};
