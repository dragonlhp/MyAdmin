var Title = '高级会员管理';
var Module = 'waitmember';

var PageUrl = '../../pages/';
var ModuleName = 'grade';
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
        title: '用户级别', field: 'level', width: '6%', align: 'left', sortable: true,
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
        title: '审核状态', field: 'type', width: '5%', align: 'center', sortable: true,
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

    {
        title: '状态', field: 'status', width: '5%', align: 'center', sortable: true,
        formatter: function (value) { //格式化数据
            if (value == 1) {
                return '禁用';
            } else if (value == 2) {
                return '启用'
            } else if (value == 3) {
                return '保留'
            }
        }
    },
    {title: '发布时间', field: 'created', width: '12%', align: 'center', sortable: true},
    {title: '更新时间', field: 'updated', width: '12%', align: 'center', sortable: true},
    {
        title: '操作', field: 'id', width: '9%', align: 'center',
        formatter: function (value, index) {

            return '<a href="javascript:void(0)" onclick="Smember.UpOpen(' + value + ',' + index.mtype + ')">审核</a>';
        }
    }
]];

/**
 * Created by Administrator on 2016/11/6 0006.
 *  模块：高级会员待审核模块
 */

$(function () {

    //分类列表
    $(DataGrid).datagrid({
        url: Qrck.baseApiUrl + PostModule + '/index?type=0',
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