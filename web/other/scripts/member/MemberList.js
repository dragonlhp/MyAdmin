/**
 * Created by Administrator on 2016/11/6 0006.
 */
/**
 *  模块：产品需求模块
 */

$(function () {

    //分类列表
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

member = {


    AddOpen: function () {

        addTab('添加' + Title, PageUrl + ModuleName + '/add.html');

    },


    UpOpen: function (value) {

        $(DataGrid).datagrid('selectRecord', value);

        var getids = $(DataGrid).datagrid('getChecked');

        var row = $(DataGrid).datagrid('getSelected');

        if (row !== null) {
            addTab(Title + ' -- 修改', PageUrl + ModuleName + '/update.html');
        } else if (getids.length == 1) {
            $(DataGrid).datagrid('selectRecord', getids[0].id);
            addTab(Title, PageUrl + ModuleName + '/update.html');
        } else {
            $.messager.alert('警告', '必须选择一行才能编辑!');
        }


    },
    Reload: function () {

        $(DataGrid).datagrid('load');

    },
    Delete: function (value) {
        if (value !== null) {
            $(DataGrid).datagrid('selectRecord', value);
        }

        var row = $(DataGrid).datagrid('getSelected');
        var getids = $(DataGrid).datagrid('getChecked');
        var ids = [];
        var sid = '';
        $.each(getids, function (index, item) {
            ids.push(item.id);
        });
        if (ids.length !== 0) {
            sid = ids.join(",");
        } else {
            sid = row.id;
        }

        if (sid !== null) {
            Qrck.delete(PostModule + '/status', {ids: sid, status: 9}, function () {
            }, function (data) {
                if (data.code = 200) {
                    $.messager.alert('提示', '删除成功!');
                    $(DataGrid).datagrid('load');
                }
            }, function () {
                $.messager.alert('警告', '删除失败!');
                //console.log(data);
            });
        } else {
            $.messager.alert('警告', '必须选择一行才能删除!');
        }
    },

    updateForm: function (select) {
        var row = $(DataGrid).datagrid('getSelected');

        var data = $(select).serializeArray();

        Qrck.put(PostModule + '/update?id=' + row.id, data,
            function () {
                $.messager.progress({
                    text: '请稍后……'

                });
            }, function () {
                $.messager.confirm("提示", "更新成功!", function () {
                    $.messager.progress('close');
                    removePanel();

                });
                $(select).form('clear');
            }, function () {
                $.messager.progress('close');
                $.messager.alert({
                    msg: '更新失败!查找一下哪里有问题....'
                });
            });
    },
    MemberForm: function (select, url, messager) {
        var row = $(DataGrid).datagrid('getSelected');

        var data = $(select).serializeArray();


        if (messager) {
            $.messager.confirm('确认对话框', '你确定要提交修改吗？', function (r) {
                if (r) {


                    Qrck.post(url + '?id=' + row.id, data,
                        function () {
                            $.messager.progress({
                                text: '请稍后……'

                            });
                        }, function () {
                            $.messager.confirm("提示", "更新成功!", function () {
                                $.messager.progress('close');


                            });
                        }, function () {
                            $.messager.progress('close');
                            $.messager.alert({
                                title: '提示',
                                msg: '更新失败!查找一下哪里有问题....'
                            });
                        });


                } else {
                    return false;
                }
            });
        } else {

            Qrck.post(url + '?id=' + row.id, data,
                function () {
                    $.messager.progress({
                        text: '请稍后……'

                    });
                }, function () {
                    $.messager.confirm("提示", "更新成功!", function () {
                        $.messager.progress('close');


                    });
                }, function (data) {
                    dump(data);
                    var error = data ? data : '查找一下哪里有问题....';
                    $.messager.progress('close');
                    $.messager.alert({
                        title: '提示',
                        msg: '更新失败!' + error
                    })
                    ;
                });
        }

    },
    MemberPwdForm: function (select, url, messager, error) {
        var row = $(DataGrid).datagrid('getSelected');

        var data = $(select).serializeArray();
        var msg = messager ? messager : '';
        var err = error ? error : '查找一下哪里有问题....';

        if (data[0].value == data[1].value) { //判断密码是否一致
            $.messager.confirm('确认对话框', '你确定要提交修改吗？', function (r) {
                if (r) {


                    Qrck.post(url + '?id=' + row.id, data,
                        function () {
                            $.messager.progress({
                                text: '请稍后……'

                            });
                        }, function () {
                            $.messager.confirm("提示", "更新成功!" + msg, function () {
                                $.messager.progress('close');


                            });
                        }, function () {
                            $.messager.progress('close');
                            $.messager.alert({
                                title: '提示',
                                msg: '更新失败!' + err
                            });
                        });


                } else {
                    return false;
                }
            });
        } else {
            $.messager.alert({
                title: '提示',
                msg: '两次输入密码不一致请重新输入....'
            });
        }


    },
    ajaxForm: function () {

    },
    //addForm: function (select) {
    //
    //    var data = $(select).serializeArray();
    //    console.log(data);
    //    Qrck.post( PostModule + '/create', data,
    //        function () {
    //            $.messager.progress({
    //                text: '请稍后……'
    //
    //            });
    //        }, function (data) {
    //            console.log(data);
    //            $.messager.progress('close');
    //            $.messager.alert({
    //                msg: '添加成功!'
    //            });
    //            $("#adds").form('clear');
    //        }, function () {
    //            $.messager.progress('close');
    //            $.messager.alert({
    //                msg: '添加失败!查找一下哪里有问题....'
    //            });
    //        });
    //},
    clearForm: function (select) {
        $(select).form('clear');
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
    getStatus: function () {
        //设置栏目类别
        $('.Status').combobox({
            data: [
                {
                    "id": 1,
                    "text": "禁用"
                },
                {
                    "id": 2,
                    "text": "启用"
                },
                {
                    "id": 3,
                    "text": "保留"
                },
                {
                    "id": 9,
                    "text": "删除"
                }
            ],
            prompt: '请选择....',
            valueField: 'id',
            textField: 'text',
            editable: false,
            width: 200,
            panelHeight: 100
        });
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
    getLevel: function () {
        //设置会员级别
        $('.level').combobox({
            data: [
                {
                    "id": 1,
                    "text": "一般会员"
                },
                {
                    "id": 2,
                    "text": "高级会员"
                }
            ],
            prompt: '请选择....',
            valueField: 'id',
            textField: 'text',
            editable: false,
            width: 200,
            panelHeight: 75
        });
    },
    getEmail: function () {
        $('.email').combobox({
            data: [
                {
                    "id": 1,
                    "text": "待激活"
                },
                {
                    "id": 2,
                    "text": "已激活"
                }
            ],
            prompt: '请选择....',
            valueField: 'id',
            textField: 'text',
            editable: false,
            width: 200,
            panelHeight: 75
        });
    },
    getMtype: function () {
        $('.mtype').combobox({ //1：创业企业 2：孵化器 3：服务机构  4 ：个人
            data: [
                {
                    "id": 1,
                    "text": "创业企业"
                },
                {
                    "id": 2,
                    "text": "孵化器"
                },
                {
                    "id": 3,
                    "text": "服务机构"
                },
                {
                    "id": 4,
                    "text": "个人"
                }
            ],
            prompt: '请选择....',
            valueField: 'id',
            textField: 'text',
            editable: false,
            width: 200,
            panelHeight: 75
        });
    },
    //搜索
    TextSearch: function (select) {
        //搜索
        $(select).searchbox({
            prompt: '搜索...',
            width: 200,
            panelHeight: 75,
            searcher: function () {
                $(DataGrid).datagrid('load', {
                    keyword: $(select).searchbox('getValue')
                })
            }
        });
    },
};


