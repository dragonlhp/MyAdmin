var Title = '专利供应';
var Module = 'patent';
var ModuleType = 'sup';

var PageUrl = '../../pages/';
var ModuleName = Module + '/' + ModuleType;
var PostModule = 'bs/' + Module + ModuleType;

var DataGrid = '#' + Module + ModuleType + 'Grid';
var ToolbarT = '#' + Module + ModuleType + 'Toolbar';
var DiaLog = '#' + Module + ModuleType + 'DiaLog';
var Form = '#' + Module + ModuleType + 'Form';
var SearchForm = "#" + Module + ModuleType + 'SearchForm';
var SeatchType = "name";

var columns = [[
    {field: 'ck', checkbox: true},

    {title: '专利名称', field: 'name', width: '10%', align: 'left', sortable: true},
    {title: '专利类型', field: 'ptype', width: '5%', align: 'left', sortable: true},
    {title: '专利方', field: 'sup_name', width: '10%', align: 'left', sortable: true},
    {title: '国家及地区', field: 'areas', width: '7%', align: 'left', sortable: true},
    {title: '拟交易价格', field: 'price', width: '6%', align: 'left', sortable: true},

    //以下为公共字段
    {title: '联系人', field: 'link_name', width: '4%', align: 'left', sortable: true},
    {title: '联系方式', field: 'link_tel', width: '8%', align: 'left', sortable: true},
    // {title: '所有者', field: 'owner', align: 'left', sortable: true},
    {
        title: '所有者类型', field: 'owner_type', align: 'left', sortable: true,
        formatter: function (value) {
            if (value == 1) {
                return '管理员';
            } else if (value == 2) {
                return '会员'
            }
        }
    },
    {
        title: '语言', field: 'lan', width: '5%', align: 'left', sortable: true,
        formatter: function (value) {
            if (value == 1) {
                return '中文';
            } else if (value == 2) {
                return 'English'
            } else if (value == 3) {
                return '한 글'
            }
        }
    },
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
    {title: '发布时间', field: 'created', width: '12%', align: 'center', sortable: true},
    {title: '更新时间', field: 'updated', width: '12%', align: 'center', sortable: true},
    {
        title: '操作', field: 'id', width: '7%', align: 'center',
        formatter: function (value) {

            return '<a href="javascript:void(0)" onclick="Option.UpOpen(' + value + ')" id="Edit">编辑</a>' +
                '&nbsp;&nbsp;&nbsp;' +
                '<a href="javascript:void(0)" onclick="Option.Delete(' + value + ')">删除</a>';
        }
    }
]];


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

Option.getLan('.lan');
Option.getStatus('.status');

$('.easyui-textbox').textbox({
    width: 300
});
$('.easyui-datebox').datebox({
    width: 200
});
