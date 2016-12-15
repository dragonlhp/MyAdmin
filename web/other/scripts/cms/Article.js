var Title = '文章管理';
var Module = 'article';

var PageUrl = '../../pages/';
var ModuleName = Module;
var PostModule = 'cms/' + Module;

var DataGrid = '#' + Module + 'Grid';
var ToolbarT = '#' + Module + 'Toolbar';
var DiaLog = '#' + Module + 'DiaLog';
var Form = '#' + Module + 'Form';
var SearchForm = "#" + Module + 'SearchForm';
var SeatchType = "";
var DataCategorys = null;
var data = [[

    {field: 'ck', checkbox: true},   //选择
    {title: '标题', field: 'title', width: '18%', align: 'left', sortable: true},
    //{title: '描述', field: 'description', width: "12%", align: 'center', sortable: true},
    //{title: '关键字', field: 'keywords', width: "6%", align: 'center', sortable: true},
    {title: '来源', field: 'source', width: "15%", align: 'center', sortable: true},
    {title: '点击数', field: 'd_num', width: "5%", align: 'center', sortable: true},
    {
        title: '分类', field: 'category_id', width: "10%", align: 'center',
        formatter: function (value) {
            return DataCategorys[value].text;
        }
    },
    {
        title: '状态', field: 'status', width: "7%", align: 'center', sortable: true,
        formatter: function (value) { //格式化数据
            if (value == 1) {
                return '待发布';
            } else if (value == 2) {
                return '已发布'
            }
        }
    },
    {title: '发布时间', field: 'created', width: "15%", align: 'center', sortable: true},
    {title: '更新时间', field: 'updated', width: "15%", align: 'center', sortable: true},
    {

        title: '操作', field: 'id', width: "11%", align: 'center', formatter: function (value, index) {

        return '<a href="javascript:void(0)" onclick="Option.UpOpen(' + value + ')">编辑</a>' +
            '&nbsp;&nbsp;&nbsp;' +
            '<a href="javascript:void(0)" onclick="Option.setStatus(9,' + value + ')">删除</a>';
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
        onBeforeLoad: function () {
            //获取分类数组

            Qrck.post('cms/category/arrcategory', {}, '', function (DataCategory) {

                DataCategorys = DataCategory;

            });
        },
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

Option = {
    /**
     * 创建文章编辑器
     * @constructor
     */
    CreateUE: function () {
        $('#contentedit').html(
            '<textarea id="editor_id" name="content" style="width:100%;height:300px;">111</textarea>'
        );

    },
    /**
     * 设置文章编辑器属性
     * @constructor
     */
    EditTool: function () {
        KindEditor.create('#editor_id', {
            resizeType: 1,
            allowFileManager: false,
            afterBlur: function () {
                this.sync();
            },
            items: [
                'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'cut', 'copy', 'paste',
                'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                'superscript', 'clearhtml', 'quickformat', 'selectall', '|', '/',
                'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
                'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak', 'anchor', 'link', 'unlink'
            ]
        });
    },
    /**
     * 打开新增页面表单
     * @constructor
     */
    AddOpen: function () {
        $(Form).removeAttr('hidden');
        var ue = this.CreateUE();
        NowType = 'add';
        $(Form).form('clear');
        var T = Title + '--添加';
        $(DiaLog).dialog(
            {
                onOpen: function () {
                    Option.EditTool();
                },
                onBeforeClose: function (event, ui) {
                    // 关闭Dialog前移除编辑器
                    KindEditor.remove('#editor_id');
                },
                title: T,
                inline: true,
                width: 900,
                height: 600,
                closed: false,
                cache: false,
                modal: true,
                buttons: [{
                    text: '保存',
                    iconCls: 'icon-save',
                    handler: function () {
                        var formdata = $(Form).serializeArray();

                        Qrck.post(PostModule + '/create', formdata,
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
                        Option.ClearUE(ue);
                    }
                }],
                onClose: function () {
                    Option.ClearUE(ue);
                }

            }
        );
    },
    UpOpen: function (value) {
        $(Form).removeAttr('hidden');
        var ue = this.CreateUE();
        var U = Title + '--修改';
        var loadData = null;
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
                    KindEditor.html('#editor_id', data.content);
                }
            });
            $(DiaLog).dialog({
                onOpen: function () {
                    Option.EditTool();
                },
                onBeforeClose: function (event, ui) {
                    // 关闭Dialog前移除编辑器
                    KindEditor.remove('#editor_id');
                },
                title: U,
                inline: true,
                width: 900,
                height: 600,
                closed: false,
                cache: false,
                modal: true,
                buttons: [{
                    text: '更新',
                    iconCls: 'icon-save',
                    handler: function () {
                        var is = Option.Is_Select(value);
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
                                    msg: '更新失败!查找一下哪里有问题....'
                                });


                            });

                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $(DiaLog).dialog('close');
                        Option.ClearUE(ue);
                    }
                }],
                onClose: function () {
                    Option.ClearUE(ue);
                }
            });

        }
    },
    Reload: function () {
        $(DataGrid).datagrid('load');
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
        //表单选择栏目类别
        $(select).combobox({
            data: [
                {
                    "id": 1,
                    "text": "待发布"
                },
                {
                    "id": 2,
                    "text": "已发布"
                }],
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
    getTree: function (select, url) {
        //分类查询树
        $(select).combotree({
            url: Qrck.baseApiUrl + url,
            method: 'get',
            multiple: true,
            prompt: '类型筛选...',
            width: 250,
            panelHeight: 200,
            onChange: function () {
                Option.SearchForm();
            }
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
        var Cids = $(".selectGetcategory").combotree('getValues');	// 获取树对象
        var Cid = Cids.join(',');
        var form = $(SearchForm).serializeArray();
        var each = [];
        form.push({name: 'keytype', value: SeatchType});
        form.push({name: 'category_id', value: Cid});
        $.each(form, function (k, v) {
            each[v.name] = v.value;
        });
        $(DataGrid).datagrid('load', each)
    },
    getTreeForm: function (select, url) {
        //分类查询树
        $(select).combotree({
            url: Qrck.baseApiUrl + url,
            method: 'get',
            prompt: '类型筛选...',
            width: 250,
            panelHeight: 200

        });
    },
    ClearUE: function (ue) {
        //彻底销毁编辑器,方便下次创建
        //ue.destroy();
        $('#contentedit').html('');
        ue = null;
    },
    /**
     * 操作Status状态值
     * @param sid
     * @param value
     */
    setStatus: function (sid, value) {
        var title = '';
        var is = this.Is_Select(value);
        if (sid == 9) {
            title = '你确定要删除吗?<br>删除后不可恢复!...';
        } else {
            title = '您确定想要修改发布状态吗?';
        }
        if (is) {
            $.messager.confirm('确认对话框', title, function (r) {
                if (r) {
                    var updata = {ids: is.id, status: sid};
                    Qrck.put(PostModule + '/status', updata, function () {
                    }, function (data) {
                        if (data.code = 200) {
                            $.messager.alert('提示', '操作成功!', 'warning', function () {
                                $(DataGrid).datagrid('load');
                            });
                        }
                    }, function () {
                        $.messager.alert('警告', '操作失败!');
                    });
                }
            });
        } else {
            $.messager.alert('警告', '必须选择一行才能操作!');
        }


    },
    /**
     * 获取列表选择值
     * @param value
     * @returns {*}
     * @constructor
     */
    Is_Select: function (value) {
        var getids = $(DataGrid).datagrid('getChecked');
        var row = $(DataGrid).datagrid('getSelected');
        if (value) {
            return {id: value};
        } else if (row) {
            return {id: row.id}
        } else if (getids.length == 1) {

            return {id: getids[0].id}
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
