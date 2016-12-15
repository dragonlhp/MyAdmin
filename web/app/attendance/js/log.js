var Title = '资金需求';
var Module = 'log';


var PageUrl = '../../pages/';
var ModuleName = Module + '/';
var PostModule = 'attendance/' + Module;

var DataGrid = '#' + Module + 'Grid';
var ToolbarT = '#' + Module + 'Toolbar';
var DiaLog = '#' + Module + 'DiaLog';
var Form = '#' + Module + 'Form';
var SearchForm = "#" + Module + 'SearchForm';
var SeatchType = "username";
var fitColumns = [[]];
var columns = [[
    {field: 'ck', checkbox: true},
    {title: '系统编号', field: 'aid', width: '10%', align: 'center'},
    {title: '流水编号', field: 'id', width: '10%', align: 'center'},
    {title: '设备编号', field: 'type', width: '10%', align: 'center'},
    {title: '用户编号', field: 'type', width: '10%', align: 'center'},
    {title: '姓名', field: 'username', width: '10%', align: 'center'},
    {title: '所属部门', field: 'subjct', width: '10%', align: 'center'},
    {title: '操作时间', field: 'date', width: '10%', align: 'center'},
    {title: '年', field: 'year', width: '10%', align: 'center'},
    {title: '月', field: 'month', width: '10%', align: 'center'},
    {title: '日', field: 'day', width: '10%', align: 'center'},
    {title: '时间', field: 'time', width: '10%', align: 'center'},

    {
        title: '操作', field: 'id', width: '10%', align: 'center',
        formatter: function (value) {

            return '<a href="javascript:void(0)" onclick="Option.UpOpen(' + value + ')">编辑</a>' +
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
    fitColumns: fitColumns,

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


//
//Option.getLan('.lan');
//Option.getStatus('.status');
//
//$('.easyui-textbox').textbox({
//    width: 300
//});
//$('.easyui-datebox').datebox({
//    width: 200
//});

