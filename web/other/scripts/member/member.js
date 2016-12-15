var Title = '会员管理';
var Module = 'member';

var PageUrl = '../../pages/';
var ModuleName = Module;
var PostModule = 'member/' + Module;

var DataGrid = '#' + Module + 'Grid';
var ToolbarT = '#' + Module + 'Toolbar';
var DiaLog = '#' + Module + 'DiaLog';
var Form = '#' + Module + 'Form';


var data = [[

    {field: 'ck', checkbox: true},

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
    {title: '电子邮箱', field: 'email', width: '15%', align: 'left', sortable: true},
    {
        title: '邮箱状态', field: 'email_status', width: '5%', align: 'left', sortable: true,
        formatter: function (value) { //格式化数据
            if (value == 1) {
                return '待激活';
            } else if (value == 2) {
                return '已激活'
            }
        }
    },
    {
        title: '用户级别', field: 'level', width: '5%', align: 'left', sortable: true,
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
    {title: '发布时间', field: 'created', width: '13%', align: 'center', sortable: true},
    {title: '更新时间', field: 'updated', width: '13%', align: 'center', sortable: true},
    {
        title: '操作', field: 'id', width: '10%', align: 'center',
        formatter: function (value) {

            return '<a href="javascript:void(0)" onclick="member.UpOpen(' + value + ')">编辑</a>' +
                '&nbsp;&nbsp;&nbsp;' +
                '<a href="javascript:void(0)" onclick="member.Delete(' + value + ')">删除</a>';
        }
    }
]];