/**
 *        tabs初始化
 *        动态添加、关闭、刷新tabs的方法
 */
$.ajaxSetup({
    headers: {"X-Auth-Client": $.cookie('login_name'), "X-Auth-Token": $.cookie('token')},
    cache: false
});
$(function () {
    mainTabs = $('#MT').tabs({
        border: false,
        fit: true,
        plain: true,
        narrow: true,
        tools: [{
            iconCls: 'icon-clear',
            title: '关闭标签',
            handler: function () {
                removePanel();
            }
        }, {
            iconCls: 'icon-mini-refresh',
            title: '刷新',
            handler: function () {
                refresh();
            }
        },{
            iconCls: 'icon-large-clipart',
            title: '返回首页',
            handler: function () {
                location.href=Qrck.baseHtmlUrl+"pages/module/main.html";
            }
        }]
    });

    if (!Qrck.isLogin()) {
        window.location.href = Qrck.baseHtmlUrl;
    }
});

// Add Tabs 动态添加TABS,用的HREF方法
function addTab(title, url) {
    title = title+'<a hidden="hidden">123123</a>';
    if ($('#MT').tabs('exists', title)) {
        $('#MT').tabs('select', title);
    } else {
        $('#MT').tabs("add", {
            title: title,
            href: url,
            closable: true,
            cache: false
        });
    }
};
// Delete Tabs 动态删除TABS,用的HREF方法
function delTab(title) {
    $('#MT').tabs('onClose', title);
    //if ($('#MT').tabs('exists', title)) {
    //    $('#MT').tabs('select', title);
    //} else {
    //    $('#MT').tabs("add", {
    //        title: title,
    //        href: url,
    //        closable: true,
    //        cache: false
    //    });
    //}
};
// Remove Tabs 关闭TABS
function removePanel() {
    var index = mainTabs.tabs('getTabIndex', mainTabs.tabs('getSelected'));
    var tab = mainTabs.tabs('getTab', index);
    if (tab.panel('options').closable) {
        mainTabs.tabs('close', index);
        $('#mainLayout').layout('expand', 'west');
    } else {
        $.messager.alert('提示', tab.panel('options').title + '不可以被关闭！', 'error');
    }
};
// Refresh Tabs
function refresh() {
    var tab = $('#MT').tabs('getSelected');  // 取得选中的tab
    $('#MT').tabs('update', {
        tab: tab,
        options: {
            href: ''
        }
    });
};
//JSON 表单提交字符串转JSON
$.fn.serializeObjectToJson = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return $.toJSON(o);
};

// RoleToUser 角色与用户双表联动
function getDeatail(index, data) {
    var selectdata = data.id;
    if (selectdata) {
        $('#userlist').datagrid('reload', {
            id: selectdata
        });
    }
};
// tree 仅获取一级节点
$(function () {
    $.extend($.fn.tree.methods, {
        getLeafChildren: function (jq, params) {
            var nodes = [];
            $(params).next().children().children("div.tree-node").each(function () {
                nodes.push($(jq[0]).tree('getNode', this));
            });
            return nodes;
        }
    });
});
// 点击任务打开新tab开始在线编纂
function addEditTab(title) {
    var row = $('#editList').datagrid('getSelected');
    if (row == null) {
        $.messager.alert("提示", "请选择待编辑的任务!", "warning");
    } else {
        title = row.taskName;
        taskId = row.taskId;
        if ($('#MT').tabs('exists', title)) {
            $('#MT').tabs('select', title);
        } else {
            $('#MT').tabs("add", {
                title: title,
                href: 'pages/task/editPage.html',
                iconCls: 'icon-edit',
                closable: true,
                cache: false
            });
        }
    }
};
//时间格式化
function dataFormat(value, row, index) {
    var unixTimestamp = new Date(value);
    return unixTimestamp.toLocaleString();
}
//验证两次输入密码是否相同
$.extend($.fn.validatebox.defaults.rules, {
    equals: {
        validator: function (value, param) {
            return value == $(param[0]).val();
        },
        message: '两次输入的密码不一致，请重新输入！'
    }
});

/*跳转页面动画*/
function show(){
    $("#loading").fadeOut("normal", function(){
        $(this).remove();
    });
}
