/**
 * Created by Administrator on 2016/11/6 0006.
 */
/**
 *  模块    ：编纂模块
 *  流程    ：创建任务
 */

$(function () {
    $('#db_expert_grid').datagrid({   //定位到Table标签，Table标签的ID是grid
        url: Qrck.baseApiUrl+'db/expert/index',   //指向后台的Action来获取当前菜单的信息的Json格式的数据
        method:"get",
        iconCls: 'icon-view',
        height: 650,
        width: function () {
            return document.body.clientWidth * 0.9
        },
        nowrap: true,
        autoRowHeight: false,
        fit: true,
        fitColumns: true,
        striped: true,
        collapsible: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 20, 40],
        selectOnCheck:false,
        checkOnSelect: false,
        singleSelect:true,
        //rownumbers: true,
        sortName: 'id',    //根据某个字段给easyUI排序
        sortOrder: 'desc',
        remoteSort: false,
        idField: 'id',
        // queryParams: queryData,  //异步查询的参数
        frozenColumns: [[
            {field: 'ck', checkbox: true},   //选择
        ]],
        columns: [[
            {title: '单位名称', field: 'org_name', width: "15%", align: 'left'},
            {title: '专家姓名', field: 'name', width: "10%", align: 'left'},
            {title: '职务/学历', field: 'position', width: '10%', align: 'left'},
            {
                title: '类别', field: 'ctype', formatter: function (value) { //格式化数据
                if (value == 1) {
                    return '国内';
                } else if (value == 2) {
                    return '国际'
                } 
            }, width: "8%", align: 'center'
            },
            {title: '联系电话', field: 'tel', width: "10%", align: 'left'},
            {
                title: '状态', field: 'status', formatter: function (value) { //格式化数据
                if (value == 1) {
                    return '启用';
                } else if (value == 2) {
                    return '禁用'
                } 
            }, width: "8%", align: 'center'
            },
            {title: '发布时间', field: 'created', sortable: true, width: "15%", align: 'center'},
            {title: '更新时间', field: 'updated', width: "15%", align: 'center'},
            {title: '操作', field: 'id', width: "8%", align: 'center'
                ,formatter: function(value,row,index){
                    return '<a href="#" onclick="editBox('+value+')">编辑</a>';
                }
            }
        ]],

        toolbar:'#db_expert_toolbar',
        onDblClickRow: function (rowIndex, rowData) {
            $('#db_expert_grid').datagrid('uncheckAll');
            $('#db_expert_grid').datagrid('checkRow', rowIndex);
            ShowEditOrViewDialog();
        },
        onLoadSuccess: function () {
            $('#db_expert_grid').datagrid('clearSelections');
            $('#db_expert_grid').datagrid('clearChecked');
        },
        onSelect: function () {
            $('#db_expert_grid').datagrid('clearChecked');
        },
        onCheck: function () {
            $('#db_expert_grid').datagrid('clearSelections');
        }
    });


    $(".db_expert_search_keyword").searchbox({
            prompt: '关键搜索...',
            width: 200,
            panelHeight: 55,
            searcher: function () {
               searchData();
            }
    });

    $(".db_expert_search_status").combobox({
            data: [
                 {
                    "id": "",
                    "text": "所有"
                },
                {
                    "id": 1,
                    "text": "启用"
                },
                {
                    "id": 2,
                    "text": "禁用"
                }
            ],
            prompt: '请选择状态....',
            valueField: 'id',
            textField: 'text',
            editable: false,
            width: 200,
            panelHeight: 75,
            onChange: function () {
                searchData();
            }
    });

    //  登录账户验证
    $('#db_export_form_org_name').validatebox({
        required: true,
        missingMessage: '请输入单位名称'
    });
     $('#db_export_form_name').validatebox({
        required: true,
        missingMessage: '请输入专家名称'
    });
});


function searchData(){
    var k=$(".db_expert_search_keyword").val();
    var s=$('.db_expert_search_status').combobox('getValue');
    $("#db_expert_grid").datagrid("load",{keyword:k,status:s});
}


function reloadData(){
    $("#db_expert_grid").datagrid("reload");
}
/*
 *修改用户的状态
 */
function editStatus(sflag){
    var rows=$("#db_expert_grid").datagrid("getChecked");
    if(rows.length > 0){
            var ids="";
            for(var i=0;i<rows.length;i++){
                ids+=ids==""?rows[i].id:","+rows[i].id;
            }
            var url='db/expert/status';
            var parm={ids:ids,status:sflag};
            Qrck.put(url,parm,"",function(){
                    $.messager.alert("提示信息","操作成功！","warning"); 
                    $("#db_expert_grid").datagrid("reload");
            },function(){
                    $.messager.alert("提示","操作失败！","warning"); 
            });   
    }else{
             $.messager.alert("提示","请选择至少一行","warning"); 
    }
}


function addBox(){
    $("#db_expert_form_box")[0].reset();
    $("#db_expert_form").removeClass("hide");
    $('#db_expert_form').dialog({
        title: '添加专家',
        cache: false,
        width: 600,
        modal : true,
        inline : true,
        buttons: [{
            text: '保存',
            iconCls: 'icon-save',
            handler: function(){

                if($('#db_expert_form_box').form('validate')){
                    var data=$("#db_expert_form_box").serializeArray();
                    var url='db/expert/create';
                    Qrck.post(url,data,"",function(){
                            $.messager.alert("提示信息","操作成功！","warning"); 
                            $('#db_expert_form').dialog('close');
                            $("#db_expert_grid").datagrid("reload");
                    },function(){
                            $.messager.alert("提示","操作失败！","warning"); 
                    });   
                    
                }else{
                    $.messager.show({
                        title : '提示',
                        msg : '请填写完整',
                        showType : 'slide'
                    }); 
                }
            }
        },{
            text: '取消',
            iconCls: 'icon-cancel',
            handler : function(){
                $('#db_expert_form').dialog('close');
                $("#db_expert_form").addClass("hide");
            }
        }]
    });
}


function editBox(uid){
    if(uid >0){
        $("#db_expert_form_box")[0].reset();
        var url="db/expert/view?id="+uid;
        Qrck.get(url,{},"",function(data){
                $("#db_export_form_org_name").val(data.org_name);
                $("#db_export_form_name").val(data.name);
                $("#db_export_form_ctype input").removeAttr("checked");
                $("#db_export_form_ctype input[value='"+data.ctype+"']").prop("checked", "checked");

                $("#db_export_form_position").val(data.position);
                $("#db_export_form_tel").val(data.tel);


                $("#db_expert_form").removeClass("hide");
                $('#db_expert_form').dialog({
                    title: '编辑专家',
                    cache: false,
                    width: 600,
                    modal : true,
                    inline : true,
                    buttons: [{
                        text: '保存',
                        iconCls: 'icon-save',
                        handler: function(){

                            if($('#db_expert_form_box').form('validate')){
                                var data=$("#db_expert_form_box").serializeArray();
                                var url='db/expert/update?id='+uid;
                                Qrck.put(url,data,"",function(){
                                        $.messager.alert("提示信息","操作成功！","warning"); 
                                        $('#db_expert_form').dialog('close');
                                        $("#db_expert_grid").datagrid("reload");
                                },function(){
                                        $.messager.alert("提示","操作失败！","warning"); 
                                });   
                                
                            }else{
                                $.messager.show({
                                    title : '提示',
                                    msg : '请填写完整',
                                    showType : 'slide'
                                }); 
                            }
                        }
                    },{
                        text: '取消',
                        iconCls: 'icon-cancel',
                        handler : function(){
                            $('#db_expert_form').dialog('close');
                            $("#db_expert_form").addClass("hide");
                        }
                    }]
                });

        },function(){
               $.messager.alert("提示信息","获取用户信息失败！","warning"); 
        });   

        
    }
}