Option = {
    bef_msg: '请稍后……',
    suc_msg: '操作成功!',
    err_msg: '操作失败!',
    method: 'get',

    pData: function (params) {
        if (typeof(params) == "object") {

            var be_fmsg = params.befTitle ? params.befTitle : this.bef_msg; //ajax加载提示信息
            var suc_msg = params.suc_msg ? params.suc_msg : this.bef_msg;   //ajax请求成功提示信息
            var err_msg = params.err_msg ? params.err_msg : this.bef_msg;   //ajaxq请求失败提示信息

            var be_fmsg_fn = isFun(params.be_fmsg_fn) ? params.be_fmsg_fn : function () {
            };
            var suc_msg_fn = isFun(params.be_fmsg_fn) ? params.be_fmsg_fn : function () {
            };
            var err_msg_fn = isFun(params.be_fmsg_fn) ? params.be_fmsg_fn : function () {
            };

            var Url = params.Url ? params.Url : ''; //请求地址
            var Data = params.Data ? params.Data : {}; //请求地址
            var method = params.method ? params.method : this.method; //ajax 请求类型

            Qrck.GetAjaxData(Url, method, Data,
                function () {
                    $.messager.progress({
                        text: be_fmsg
                    });
                }, function (data) {
                    $.messager.progress('close');
                    $.messager.alert({
                        title: '提示',
                        msg: suc_msg,
                        icon: 'info',
                        fn: suc_msg_fn
                    });
                    $(DataGrid).datagrid('load');
                }, function () {
                    $.messager.progress('close');
                    $.messager.alert({
                        title: '错误提示',
                        icon: 'warning',
                        msg: err_msg,
                        fn: err_msg_fn
                    });
                });
        }
    },
    AddOpen: function () {
        $(Form).removeAttr('hidden');
        NowType = 'add';
        $(Form).form('clear');
        var T = Title + '--添加';
        $(DiaLog).dialog(
            {
                title: T,
                inline: true,
                width: 900,
                height: 600,
                closed: false,
                cache: false,
                modal: true,
                buttons: [{
                    text: '保存',
                    iconCls: 'icon-save',
                    handler: function () {
                        Option.pData({
                            Url: PostModule + '/create',
                            method: 'post',
                            Data: $(Form).serializeArray(),
                            suc_msg_fn: function () {
                                $(DiaLog).dialog('close');
                            }
                        })
                    }
                    //handler: function () {
                    //
                    //    //var data = $(select).serializeArray();
                    //    Qrck.post(PostModule + '/create', $(Form).serializeArray(),
                    //        function () {
                    //            $.messager.progress({
                    //                text: '请稍后……'
                    //            });
                    //        }, function (data) {
                    //            $.messager.progress('close');
                    //            $.messager.alert({
                    //                title: '提示',
                    //                msg: '添加成功!',
                    //                icon: 'warning',
                    //                fn: function () {
                    //                    $(DiaLog).dialog('close');
                    //                }
                    //            });
                    //            $(DataGrid).datagrid('load');
                    //        }, function () {
                    //            $.messager.progress('close');
                    //            $.messager.alert({
                    //                title: '错误提示',
                    //                msg: '添加失败!查找一下哪里有问题....'
                    //            });
                    //        });
                    //}
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $(DiaLog).dialog('close');
                    }
                }]

            }
        );
    },
    UpOpen: function (value) {
        $(Form).removeAttr('hidden');
        var U = Title + '--修改';
        //获取编辑数据
        var is = Option.Is_Select(value);
        if (is == null) {
            $.messager.alert({
                title: '提示',
                msg: '请选择一行进行编辑!'
            });
        } else {
            $.ajax({
                type: 'get',
                url: Qrck.baseApiUrl + PostModule + '/view?id=' + is.id,
                success: function (data) {
                    $(Form).form('load', data);
                }
            });
            $(DiaLog).dialog({
                title: U,
                inline: true,
                width: 900,
                height: 600,
                closed: false,
                cache: false,
                modal: true,
                buttons: [{
                    text: '更新',
                    iconCls: 'icon-save',
                    handler: function () {
                        var is = Option.Is_Select();
                        Qrck.put(PostModule + '/update?id=' + is.id, $(Form).serializeArray(),
                            function () {
                                $.messager.progress({
                                    text: '请稍后……'
                                });
                            }, function () {
                                $.messager.progress('close');
                                $.messager.alert(
                                    '提示',
                                    '更新成功!',
                                    'warning',
                                    function () {
                                        $(DiaLog).dialog('close');
                                    }
                                );
                                $(DiaLog).dialog('close');
                                $(DataGrid).datagrid('load');
                            }, function () {
                                $.messager.progress('close');
                                $.messager.alert({
                                    msg: '更新失败!查找一下哪里有问题....'
                                });
                            });

                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $(DiaLog).dialog('close');
                    }
                }]
            });

        }
    },
    Reload: function () {

        $(DataGrid).datagrid('load');

    },
    Delete: function (value) {

        var sid = this.Is_Select(value);
        if (sid) {
            $.messager.confirm('确认对话框', '您确定想要删除这些数据吗？', function (r) {
                if (r) {
                    Qrck.delete(PostModule + '/status', {ids: sid.id, status: 9}, function () {
                    }, function (data) {
                        if (data.code = 200) {
                            $.messager.alert('提示', '删除成功!', 'warning', function () {
                                $(DataGrid).datagrid('load');
                            });
                            sid = null;
                            value = null;
                        }
                    }, function () {
                        $.messager.alert('警告', '删除失败!');
                        //console.log(data);
                        sid = null;
                        value = null;
                    });
                }
            });
        } else {
            $.messager.alert('警告', '必须选择一行才能删除!');
        }

    },
    getLan: function (select) {
        //设置语言
        $(select).combobox({
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
                },
                {
                    "id": 3,
                    "text": "删  除"
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
    //Getbsbdatatype
    getCapitlAmong: function (select) {
        Qrck.get('bs/capitalreq/getbsbdatatype', '', '', function (data) {
            $(select).combobox({
                data: data,
                prompt: '请选择类别....',
                valueField: 'id',
                textField: 'text',
                editable: false,
                width: 200,
                panelHeight: 75
            });
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
                    Qrck.delete(PostModule + '/status', {ids: is.id, status: sid}, function () {
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
    Is_Select: function (value) {
        var getids = $(DataGrid).datagrid('getChecked');
        var row = $(DataGrid).datagrid('getSelected');
        if (value) {
            return {id: value};
        } else if (row) {
            return row
        } else if (getids.length == 1) {

            return getids[0]
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
    getSelectStatus: function (select) {
        Qrck.post('attendance/log/getsubjct', {}, '', function (data) {
            var panelHeight = data.length;
            $(select).combobox({
                data: data,
                prompt: '请选择类别....',
                valueField: 'id',
                textField: 'text',
                editable: false,
                width: 200,
                panelHeight: panelHeight * 25,
                onChange: function () {
                    Option.SearchForm();
                }
            });
        });
        //工具栏筛选             Getsubjct


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
        var form = $(SearchForm).serializeArray();
        var each = [];
        form.push({name: 'keytype', value: SeatchType});
        $.each(form, function (k, v) {
            each[v.name] = v.value;
        });
        $(DataGrid).datagrid('load', each)
    }
};
