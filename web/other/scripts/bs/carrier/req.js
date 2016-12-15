var Title = '载体需求';
var Module = 'carrier';
var ModuleType = 'req';

var PageUrl = '../../pages/';
var ModuleName = Module + '/' + ModuleType;
var PostModule = 'bs/' + Module + ModuleType;

var DataGrid = '#' + Module + ModuleType + 'Grid';
var ToolbarT = '#' + Module + ModuleType + 'Toolbar';
var DiaLog = '#' + Module + ModuleType + 'DiaLog';
var Form = '#' + Module + ModuleType + 'Form';
var SearchForm = "#" + Module + ModuleType + 'SearchForm';
var SeatchType = "company";

var columns = [[
    {field: 'ck', checkbox: true},

    {title: '企业名称', field: 'company', width: '15%', align: 'left', sortable: true},
    {
        title: '面积需求', field: 'area_reqs', width: '10%', align: 'left', sortable: true,
        formatter: function (value) {
            // [{"id":14,"text":"0-100"},{"id":15,"text":"100-500"},{"id":16,"text":"500-1000"},{"id":17,"text":"1000以上"}]
            if (value == 14) {
                return '0-100(平米)';
            } else if (value == 15) {
                return '100-500(平米)'
            } else if (value == 16) {
                return '500-1000(平米)'
            } else if (value == 17) {
                return '1000(平米)以上'
            }
        }
    },
    {
        title: '期望价格', field: 'price', width: '10%', align: 'left', sortable: true,
        formatter: function (value) {
            //[{"id":22,"text":"1-30"},{"id":23,"text":"30-50"},{"id":24,"text":"50以上"}]
            if (value == 22) {
                return '1-30';
            } else if (value == 23) {
                return '30-50'
            } else if (value == 24) {
                return '50以上'
            }
        }
    },
    //{title: '区域', field: 'country', width: '15%', align: 'left', sortable: true},
    //{title: '载体类型', field: 'carrier_type_id', width: '15%', align: 'left', sortable: true},
    //{title: '期望物管费(元)', field: 'mprice', width: '15%', align: 'left', sortable: true},
    //{title: '优惠信息', field: 'coupon', width: '15%', align: 'left', sortable: true},
    //
    //{title: '其他', field: 'other', width: '15%', align: 'left', sortable: true},

    {title: '联系人', field: 'link_name', width: '5%', align: 'left', sortable: true},
    {title: '联系方式', field: 'link_tel', width: '10%', align: 'left', sortable: true},
    {title: '所有者', field: 'owner', align: 'left', sortable: true},
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
    {title: '更新时间', field: 'updated', width: '13%', align: 'center', sortable: true},
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
Option.getComboBox('.price', 'bs/carrierreq/getbsbdatatype?type=5');  //期望价格
Option.getComboBox('.area_reqs', 'bs/carrierreq/getbsbdatatype?type=3');  //面积需求
Option.getComboBox('.carrier_type_id', 'bs/carrierreq/getbsbdatatype?type=2');  //载体类型