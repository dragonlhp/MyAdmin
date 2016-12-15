var Title = '资金需求';
var Module = 'capital';
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

    {title: '企业名称', field: 'company', width: '12%', align: 'left', sortable: true},

    //[{"id":18,"text":"0-100W"},{"id":19,"text":"100-500w"},{"id":20,"text":"500-1000w"},{"id":21,"text":"1000以上"}]
    {
        title: '资金需求量(万)', field: 'capital_req', width: '6%', align: 'left', sortable: true,
        formatter: function (value) {
            if (value == 18) {
                return '0-100W';
            } else if (value == 19) {
                return '100-500w'
            } else if (value == 20) {
                return '500-1000w'
            } else if (value == 21) {
                return '1000以上'
            }
        }
    },
    {title: '贷款用途', field: 'useing', width: '10%', align: 'left', sortable: true},
    {title: '法人代表', field: 'legal', width: '5%', align: 'left', sortable: true},
    //{title: '成立日期', field: 'reg_date', width: '15%', align: 'left', sortable: true},
    //{title: '注册资本(万元)', field: 'captial', width: '15%', align: 'left', sortable: true},
    //{title: '工商注册地', field: 'reg_address', width: '15%', align: 'left', sortable: true},
    //{title: '所属行业', field: 'industry', width: '15%', align: 'left', sortable: true},
    //{title: '专利情况', field: 'patent', width: '15%', align: 'left', sortable: true},
    //{title: '上年度销售收入(万元)', field: 'pre_income', width: '15%', align: 'left', sortable: true},
    //{title: '上年度净利润(万元)', field: 'pre_profit', width: '15%', align: 'left', sortable: true},
    //{title: '截止上年度银行贷款(万元)', field: 'pre_loan', width: '15%', align: 'left', sortable: true},
    //{title: '资金供应方', field: 'money_supply', width: '15%', align: 'left', sortable: true},
    //{title: '担保公司', field: 'bonding_company', width: '15%', align: 'left', sortable: true},
    //{title: '贷款品种', field: 'loan_type', width: '15%', align: 'left', sortable: true},
    //{title: '贷款时间', field: 'loan_date', width: '15%', align: 'left', sortable: true},
    //{title: '还款时间', field: 'return_date', width: '15%', align: 'left', sortable: true},
    //{title: '利率', field: 'rate', width: '15%', align: 'left', sortable: true},
    //{title: '备注', field: 'note', width: '15%', align: 'left', sortable: true},


    {title: '联系人', field: 'link_name', width: '5%', align: 'left', sortable: true},
    {title: '联系方式', field: 'link_tel', width: '7%', align: 'left', sortable: true},
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
    {title: '发布时间', field: 'created', width: '11%', align: 'center', sortable: true},
    {title: '更新时间', field: 'updated', width: '11%', align: 'center', sortable: true},
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

