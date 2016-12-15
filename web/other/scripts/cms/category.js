var Title = '文章分类';
var Module = 'category';

var PageUrl = '../../pages/';
var ModuleName = Module;
var PostModule = 'cms/' + Module;

var TreeGrid = '#' + Module + 'Grid';
var ToolbarT = '#' + Module + 'Toolbar';
var DiaLog = '#' + Module + 'DiaLog';
var Form = '#' + Module + 'Form';


var data = [[
    {title: '栏目名称', field: 'text', width: '15%', align: 'left'},
    {
        title: '栏目语言', field: 'lan', width: "10%", align: 'center', sortable: true,
        formatter: function (value) { //格式化数据
            if (value == 1) {
                return '中文';
            } else if (value == 2) {
                return 'English'
            } else if (value == 3) {
                return '한 글'
            }
        }
    }, {
        title: '模块', field: 'module', width: "10%", align: 'center', sortable: true,
        formatter: function (value) { //格式化数据
            if (value == 1) {
                return '公告和咨询';
            } else if (value == 2) {
                return '文章'
            }
        }
    },
    {
        title: '显示类型', field: 'showtype', width: "10%", align: 'center', sortable: true,
        formatter: function (value) { //格式化数据
            if (value == 1) {
                return '列表显示';
            } else if (value == 2) {
                return '内容显示'
            } else if (value == 3) {
                return '链接跳转'
            }
        }
    },
    {title: '描述', field: 'descriptions', width: "14%", align: 'center', sortable: true},
    {title: '发布时间', field: 'created', width: "15%", align: 'center', sortable: true},
    {title: '更新时间', field: 'updated', width: "15%", align: 'center', sortable: true},
    {
        title: '操作', field: 'id', width: "10%", align: 'center',
        formatter: function (value, index) {

            if (index.pid != 0) {
                return '<a href="javascript:void(0)" onclick="OptionCate.UpOpen(' + value + ')">编辑</a>' +
                    '&nbsp;&nbsp;&nbsp;' +
                    '<a href="javascript:void(0)" onclick="OptionCate.Delete(' + value + ')">删除</a>';
            }


        }
    }
]];
$(function () {


//分类列表
    $(TreeGrid).treegrid({
        url: Qrck.baseApiUrl + PostModule,
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
        pagination: false,
        //pageSize: 30,
        //pageList: [30, 60, 90],
        sortName: 'id',
        sortOrder: 'asc',
        idField: 'id',
        treeField: 'text',
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
OptionCate = {


    AddOpen: function () {
        $(Form).removeAttr('hidden');
        //addTab('添加' + Title, PageUrl + ModuleName + '/add.html');
        NowType = 'add';
        $(Form).form('clear');
        var T = Title + '--添加';
        $(DiaLog).dialog(
            {
                title: T,
                inline: true,
                width: 400,
                height: 350,
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
                                $(Form).form('clear');
                                $(TreeGrid).treegrid('load');
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
        } else if (is.pid == 0) {
            $.messager.alert({
                title: '提示',
                msg: '顶级栏目不可修改编辑!'
            });
        } else {
            $(DiaLog).dialog({
                title: U,
                inline: true,
                width: 400,
                height: 350,
                closed: false,
                cache: false,
                modal: true,
                onOpen: function () {
                    Qrck.get(PostModule + '/view?id=' + is.id, '', '', function (data) {
                        $(Form).form('load', data);
                    });
                },
                buttons: [{
                    text: '更新',
                    iconCls: 'icon-save',
                    handler: function () {
                        var is = OptionCate.Is_Select(value);

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
                                $(TreeGrid).treegrid('load');
                            }, function () {
                                $.messager.progress('close');
                                $.messager.alert({
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

    Reload: function () {
        $(TreeGrid).treegrid('load');
    },
    Delete: function (value) {

        var sid = this.Is_Select(value);
        dump(sid);
        if (sid.pid == 0) {
            $.messager.alert('提示', '顶级栏目不可删除....');
        } else {

            $.messager.confirm('确认对话框', '您确定想要删除吗？', function (r) {
                if (r) {
                    if (sid !== null) {
                        Qrck.delete(PostModule + '/delete', {ids: sid.id, status: 9}, function () {
                        }, function (data) {
                            if (data.code == 200) {
                                $.messager.alert('提示', '删除成功!');
                                //$(TreeGrid).treegrid('load');
                            } else if (data.code == 1003) {
                                $.messager.alert('提示', '部分栏目删除失败!删除前请删除此栏目下的子栏目及文章!');

                            }
                            $(TreeGrid).treegrid('load');
                        }, function () {
                            $.messager.alert('警告', '删除失败!');
                        });
                    } else {
                        $.messager.alert('警告', '必须选择一行才能删除!');
                    }
                }
            });
        }
    },
    getComboBox: function (select, url, fun) {

        if (data) {
            Qrck.get(url, '', '', function (data) {
                $(select).combobox({
                    data: data,
                    prompt: '请选择....',
                    valueField: 'id',
                    textField: 'text',
                    editable: false,
                    width: 200,
                    panelHeight: 75,
                    onSelect: fun
                });
            });
        }
    },
    clearForm: function () {
        $(Form).form('clear');
    },
    //module
    getModule: function () {
        //设置语言
        $('.module').combobox({
            data: [
                {
                    "id": 1,
                    "text": "公告和咨询"
                },
                {
                    "id": 2,
                    "text": "文章"
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
    getLan: function () {
        //设置语言
        $('.Languages').combobox({
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
                }
            ],
            prompt: '请选择类别....',
            valueField: 'id',
            textField: 'text',
            editable: false,
            width: 200,
            panelHeight: 75,
            onChange: function (val) {
                if (TreeGrid) {
                    $(TreeGrid).treegrid('load', {
                        status: val
                    })
                }
            }
        });
    },
    TextSearch: function (select) {
        //搜索
        $(select).searchbox({
            prompt: '列表搜索...',
            width: 200,
            panelHeight: 75,
            searcher: function () {
                $(TreeGrid).treegrid('load', {
                    keyword: $(select).searchbox('getValue')
                })
            }
        });
    },
    getTree: function (select) {
        //分类查询树
        $(select).combotree({
            url: Qrck.baseApiUrl + PostModule + '/getcategory',
            method: 'get',
            multiple: true,
            prompt: '选择上级栏目...',
            width: 200,
            panelHeight: 200,
            onChange: function () {
                var Cids = $(select).combotree('getValues');	// 获取树对象
                var Cid = Cids.join(',');
                $(TreeGrid).treegrid('load', {
                    category_id: Cid
                })
            }
        });
    },
    getSelectTree: function (select) {
        //分类查询树
        $(select).combotree({
            url: Qrck.baseApiUrl + PostModule + '/getcategory',
            prompt: '选择上级栏目...',
            width: 200,
            panelHeight: 200,
            required: true,
            onShowPanel: function () {
                $(select).combotree({
                    reload: Qrck.baseApiUrl + PostModule + '/getcategory',
                });
            }

        });
    },
    Showtype: function (select) {
        //设置栏目类别
        $(select).combobox({
            data: [
                {
                    "id": 1,
                    "text": "列表显示"
                },
                {
                    "id": 2,
                    "text": "内容显示"
                },
                {
                    "id": 3,
                    "text": "链接跳转"
                }
            ],
            prompt: '选择栏目类别',
            valueField: 'id',
            textField: 'text',
            editable: false,
            width: 200,
            panelHeight: 75
        });
    },
    Is_Select: function (value) {
        var getids = $(TreeGrid).treegrid('getChecked');
        var row = $(TreeGrid).treegrid('getSelected');
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
    }
};
