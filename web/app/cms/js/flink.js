var Title = '友情链接';
var Module = 'flink';

var PageUrl = '../../pages/';
var ModuleName = Module;
var PostModule = 'cms/' + Module;

var DataGrid = '#' + Module + 'Grid';
var ToolbarT = '#' + Module + 'Toolbar';
var DiaLog = '#' + Module + 'DiaLog';
var Form = '#' + Module + 'Form';
var SearchForm = "#" + Module + 'SearchForm';
var SeatchType = "name";

var columns = [[
    {field: 'ck', checkbox: true},

    {title: '名称', field: 'name', width: '15%', align: 'left', sortable: true},
    {title: '地址', field: 'url', width: '20%', align: 'left', sortable: true},
    {title: '图片', field: 'img', width: '16%', align: 'left', sortable: true},
    {title: '排序', field: 'rank', width: '3%', align: 'left', sortable: true},
    {
        title: '状态', field: 'status', width: '5%', align: 'center', sortable: true,
        formatter: function (value) { //格式化数据
            if (value == 1) {
                return '待发布';
            } else if (value == 2) {
                return '已发布'
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

//
//Option = {
//
//    AddOpen: function () {
//        $(Form).removeAttr('hidden');
//        NowType = 'add';
//        $(Form).form('clear');
//        var T = Title + '--添加';
//        $(DiaLog).dialog(
//            {
//                title: T,
//                inline: true,
//                width: 400,
//                height: 300,
//                closed: false,
//                cache: false,
//                modal: true,
//                buttons: [{
//                    text: '保存',
//                    iconCls: 'icon-save',
//                    handler: function () {
//
//                        Qrck.form(Form, PostModule + '/create', 'post', $(Form).serializeArray(),
//                            function () {
//                                $.messager.progress({
//                                    text: '请稍后……'
//                                });
//                            }, function (data) {
//                                $.messager.progress('close');
//                                $.messager.alert({
//                                    title: '提示',
//                                    msg: '添加成功!'
//                                });
//                                $(Form).form('clear');
//                                $('#File1').val('');   //清除文件上传控件的记录
//                                $(DiaLog).dialog('close');
//                                $(DataGrid).datagrid('load');
//                            }, function () {
//                                $.messager.progress('close');
//                                $.messager.alert({
//                                    title: '错误提示',
//                                    msg: '添加失败!查找一下哪里有问题....'
//                                });
//                            });
//                    }
//                }, {
//                    text: '取消',
//                    iconCls: 'icon-cancel',
//                    handler: function () {
//                        $(DiaLog).dialog('close');
//                        $('#File1').val('');   //清除文件上传控件的记录
//                    }
//                }]
//
//            }
//        );
//    },
//    UpOpen: function (value) {
//        $(Form).removeAttr('hidden');
//        var U = Title + '--修改';
//        //获取编辑数据
//        var is = this.Is_Select(value);
//        if (is == null) {
//            $.messager.alert({
//                title: '提示',
//                msg: '请选择一行进行编辑!'
//            });
//        } else {
//            $(DiaLog).dialog({
//                title: U,
//                inline: true,
//                width: 400,
//                height: 300,
//                closed: false,
//                cache: false,
//                modal: true,
//                onOpen: function () {
//                    Qrck.get(PostModule + '/view?id=' + is.id, '', '', function (data) {
//                        $(Form).form('load', data);
//                    });
//                },
//                buttons: [{
//                    text: '更新',
//                    iconCls: 'icon-save',
//                    handler: function () {
//                        var is = Option.Is_Select();
//                        Qrck.form(Form, PostModule + '/update?id=' + is.id, 'put', $(Form).serializeArray(),
//                            function () {
//                                $.messager.progress({
//                                    text: '请稍后……'
//
//                                });
//                            }, function () {
//                                $.messager.progress('close');
//                                $.messager.alert({
//                                    title: '提示',
//                                    msg: '更新成功!'
//
//                                });
//                                $('#File1').val(''); //清除文件上传控件的记录
//                                $(DiaLog).dialog('close');
//                                $(DataGrid).datagrid('load');
//                            }, function () {
//                                $.messager.progress('close');
//                                $.messager.alert({
//                                    title: '错误提示',
//                                    msg: '更新失败!查找一下哪里有问题....'
//                                });
//                            });
//
//                    }
//                }, {
//                    text: '取消',
//                    iconCls: 'icon-cancel',
//                    handler: function () {
//                        $(DiaLog).dialog('close');
//                        $('#File1').val('');    //清除文件上传控件的记录
//                    }
//                }]
//            });
//
//        }
//    },
//    Reload: function () {
//
//        $(DataGrid).datagrid('load');
//
//    },
//    getLan: function (select) {
//        //设置语言
//        $(select).combobox({
//            data: [
//                {
//                    "id": 1,
//                    "text": "中 文"
//                },
//                {
//                    "id": 2,
//                    "text": "English"
//                },
//                {
//                    "id": 3,
//                    "text": "한 글"
//                }
//            ],
//            prompt: '选择栏目语言',
//            valueField: 'id',
//            textField: 'text',
//            editable: false,
//            width: 200,
//            panelHeight: 75
//        });
//    },
//    getStatus: function (select) {
//        //设置栏目类别
//        $(select).combobox({
//            data: [
//                {
//                    "id": 1,
//                    "text": "待发布"
//                },
//                {
//                    "id": 2,
//                    "text": "已发布"
//                },
//                {
//                    "id": 3,
//                    "text": "删  除"
//                }
//            ],
//            prompt: '请选择类别....',
//            valueField: 'id',
//            textField: 'text',
//            editable: false,
//            width: 200,
//            panelHeight: 75
//        });
//    },
//    getComboBox: function (select, url, onSelect, success) {
//        Qrck.get(url, '', '', function (data) {
//            $(select).combobox({
//                data: data,
//                prompt: '请选择....',
//                valueField: 'id',
//                textField: 'text',
//                editable: false,
//                width: 200,
//                panelHeight: 75,
//                onSelect: onSelect,
//                onLoadSuccess: success
//            });
//        });
//    },
//    setStatus: function (sid) {
//
//        var is = this.Is_Select();
//
//        if (is) {
//            $.messager.confirm('确认对话框', '您确定想要修改发布状态吗？', function (r) {
//                if (r) {
//                    Qrck.delete(PostModule + '/status', {ids: is, status: sid}, function () {
//                    }, function (data) {
//                        if (data.code = 200) {
//
//
//                            $.messager.alert('提示', '修改成功!', 'warning', function () {
//                                $(DataGrid).datagrid('load');
//                            });
//                        }
//                    }, function () {
//                        $.messager.alert('警告', '修改失败!');
//                    });
//                }
//            });
//        } else {
//            $.messager.alert('警告', '必须选择一行才能操作!');
//        }
//    },
//    Is_Select: function (value) {
//        var getids = $(DataGrid).datagrid('getChecked');
//        var row = $(DataGrid).datagrid('getSelected');
//        if (value) {
//            return {id: value};
//        } else if (row) {
//            return row
//        } else if (getids.length == 1) {
//
//            return getids[0]
//        } else if (getids.length > 1) {
//            var getLen = '';
//            $.each(getids, function (k, v) {
//                if (k == 0) {
//                    getLen += v.id;
//                } else {
//                    getLen += "," + v.id;
//                }
//            });
//            return {id: getLen};
//        }
//    },
//
//    getSelectStatus: function (select) {
//        //工具栏筛选
//        $(select).combobox({
//            data: [
//                {
//                    "id": 1,
//                    "text": "待发布"
//                },
//                {
//                    "id": 2,
//                    "text": "已发布"
//                }
//            ],
//            prompt: '请选择类别....',
//            valueField: 'id',
//            textField: 'text',
//            editable: false,
//            width: 200,
//            panelHeight: 75,
//            onChange: function () {
//                Option.SearchForm();
//            }
//        });
//    },
//    TextSearch: function (select) {
//        //搜索
//        $(select).searchbox({
//            prompt: '关键搜索...',
//            width: 200,
//            panelHeight: 55,
//            searcher: function () {
//                Option.SearchForm();
//            }
//        });
//    },
//    SearchForm: function () {
//        //文章分类树选择结果
//        var form = $(SearchForm).serializeArray();
//        var each = [];
//        form.push({name: 'keytype', value: SeatchType});
//        $.each(form, function (k, v) {
//            each[v.name] = v.value;
//        });
//        $(DataGrid).datagrid('load', each)
//    }
//};
