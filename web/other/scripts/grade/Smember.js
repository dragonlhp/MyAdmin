
Smember = {

    UpOpen: function (value, index) {
        var is = this.Is_Select(value, index);
        if (is) {
            $(DiaLog).dialog({
                href: PageUrl + ModuleName + '/update_' + is.mtype + '.html',
                title: Title + '--审核',
                inline: true,
                width: 1000,
                height: 600,
                maximized: true,
                closed: false,
                cache: false,
                modal: true,
                buttons: [{
                    text: '提交审核',
                    iconCls: 'icon-save',
                    handler: function () {
                        var upload = $('.SeniorApprovalForm').serializeArray();
                        //修改会员状态
                        var level = (upload[3].value == 2) ? 2 : 1;
                        var is = Smember.Is_Select(value, index);
                        Qrck.get(PostModule + '/setlevel', {id: is.id, level: level});
                        //提交审核日志
                        Qrck.put(PostModule + '/seniorapproval', upload,
                            function () {
                                $.messager.progress({
                                    text: '请稍后……'

                                });
                            }, function () {
                                $.messager.progress('close');
                                $.messager.alert({
                                    title: '提示',
                                    msg: '提交成功!',
                                    fn: function () {
                                        $(DiaLog).dialog('close');
                                    }
                                });

                                $(DataGrid).datagrid('load');
                            }, function () {
                                $.messager.progress('close');
                                $.messager.alert({
                                    title: '错误提示',
                                    msg: '更新失败!查找一下哪里有问题....',
                                    fn: function () {
                                        $(DiaLog).dialog('close');
                                    }
                                });
                            });
                        is = null;
                        $(DataGrid).datagrid('load');
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $(DiaLog).dialog('close');
                        $(DiaLog).val('');
                    }
                }],
                onClose: function () {
                    $(DiaLog).val('');
                }
            });

        }
        is = null;
    },
    Is_Select: function (value, index) {
        var Types = [
            'company',
            'incubator',
            'service'
        ];

        var getids = $(DataGrid).datagrid('getChecked');
        var row = $(DataGrid).datagrid('getSelected');
        if (value) {
            return {id: value, mtype: Types[index - 1]};
        } else if (row) {
            return {id: row.id, mtype: Types[row.mtype - 1]}
        } else if (getids.length == 1) {

            return {id: getids[0].id, mtype: Types[getids[0].mtype - 1]}
        } else if (getids.length > 1) {
            var getLen = '';
            $.each(getids, function (k, v) {
                if (k == 0) {
                    getLen += v.id;
                } else {
                    getLen += "," + v.id;
                }
            });
            return {id: getLen, mtype: Types[getids[0].mtype - 1]};
        }


    },
    Reload: function () {

        $(DataGrid).datagrid('load');
        $(DiaLog).val('');

    },


    clearForm: function (select) {
        $(select).form('clear');
    },


    getStatus: function (select) {
        //设置栏目类别
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
    setStatus: function (sid) {

        var is = this.Is_Select();

        if (is) {
            $.messager.confirm('确认对话框', '您确定想要修改发布状态吗？', function (r) {
                if (r) {
                    Qrck.delete(PostModule + '/status', {ids: is, status: sid}, function () {
                    }, function (data) {
                        if (data.code = 200) {


                            $.messager.alert('提示', '修改成功!', 'warning', function () {
                                $(DataGrid).datagrid('load');
                            });
                        }
                    }, function () {
                        $.messager.alert('警告', '修改失败!');
                    });
                }
            });
        } else {
            $.messager.alert('警告', '必须选择一行才能操作!');
        }
    },
    TextSearch: function (select) {
        //搜索
        $(select).searchbox({
            prompt: '关键搜索...',
            width: 200,
            panelHeight: 55,
            searcher: function () {
                $(DataGrid).datagrid('load', {
                    keyword: $(select).searchbox('getValue'),
                    keytype: 'title,keywords,description'
                })
            }
        });
    }
};