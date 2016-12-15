var Title = '优惠券';
var Module = 'coupon';

var PageUrl = '../../pages/';
var ModuleName = Module;
var PostModule = 'coupon/' + Module;

var DataGrid = '#' + Module + 'Grid';
var ToolbarT = '#' + Module + 'Toolbar';
var SeatchType = "";
var data = [[
    {field: 'ck', checkbox: true},

    {title: '申请单位', field: 'req_company', width: '15%', align: 'left', sortable: true},
    {
        title: '服务机构', field: 'service_id', width: '15%', align: 'left', sortable: true,
        formatter: function (value, index) {
            var name;
            Qrck.get('coupon/coupon/getservice?service_id=' + value, '', '', function (data) {
                name = data.name;

            });
            return name;

        }
    },
    {title: '合同金额', field: 'contract_price', width: '5%', align: 'left', sortable: true},
    {title: '服务折扣', field: 'discounts', width: '5%', align: 'left', sortable: true},
    {title: '联系人', field: 'link_name', width: '10%', align: 'left', sortable: true},
    {title: '联系方式', field: 'link_tel',width: '10%', align: 'left', sortable: true},
    {
        title: '状态', field: 'status', width: '5%', align: 'center', sortable: true,
        formatter: function (value) { //格式化数据
            if (value == 1) {
                return '待审核';
            } else if (value == 2) {
                return '通过';
            } else if (value == 3) {
                return '拒绝';
            }
        }

    },
    {title: '发布时间', field: 'created', width: '11%', align: 'center', sortable: true},
    {title: '更新时间', field: 'updated', width: '11%', align: 'center', sortable: true},
    {
        title: '操作', field: 'id', width: '10%', align: 'center',
        formatter: function (value, index) {
            if (index.status == 1) {
                return '<a href="javascript:void(0)" onclick="Option.Pass(' + 2 + ',' + value + ')">通过</a>' +
                    '&nbsp;&nbsp;' +
                    '<a href="javascript:void(0)" onclick="Option.Pass(' + 3 + ',' + value + ')">拒绝</a>' + '&nbsp;&nbsp;' +
                    '<a href="javascript:void(0)" onclick="Option.Pass(' + 9 + ',' + value + ')">删除</a>';
            }
            else if (index.status == 2) {
                return '<a href="javascript:void(0)" onclick="Option.Pass(' + 1 + ',' + value + ')">取消通过</a>';
            } else {
                return '<a href="javascript:void(0)" onclick="Option.Pass(' + 1 + ',' + value + ')">取消拒绝</a>';
            }
        }
    }
]];

$(function () {

    //分类列表
    $(DataGrid).datagrid({
        url: Qrck.baseApiUrl + PostModule + '/clist',
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

    $('.easyui-textbox').textbox({
        width: 200
    });

});

Option = {
    Reload: function () {

        $(DataGrid).datagrid('load');

    },
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
    },
    Pass: function (status, value) {
        var sid = Option.Is_Select(value);
        if (sid !== null) {
            $.messager.confirm('确认对话框', '请确定当前操作？', function (r) {
                if (r) {
                    Qrck.put(PostModule + '/status', {ids: sid.id, status: status}, function () {
                    }, function (data) {
                        if (data.code = 200) {
                            $.messager.alert('提示', '操作成功!');
                            $(DataGrid).datagrid('load');
                        }
                    }, function () {
                        $.messager.alert('警告', '操作失败!');
                    });

                }
            });
        }
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
        var Cid = Cids.join(',');
        var form = $(SearchForm).serializeArray();
        var each = [];
        form.push({name: 'keytype', value: SeatchType});
        form.push({name: 'category_id', value: Cid});
        $.each(form, function (k, v) {
            each[v.name] = v.value;
        });
        $(DataGrid).datagrid('load', each)
    }
};