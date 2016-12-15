/**
 * Created by Administrator on 2016/11/6 0006.
 */
/**
 *  模块    ：编纂模块
 *  流程    ：创建任务
 */

$(function () {
    $('#db_service_grid').datagrid({   //定位到Table标签，Table标签的ID是grid
        url: Qrck.baseApiUrl+'db/service/index',   //指向后台的Action来获取当前菜单的信息的Json格式的数据
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
            {title: '公司名称', field: 'name', width: "10%", align: 'left'},
            {title: '服务内容', field: 'sname', width: "15%", align: 'left'},
            {title: '业务内容', field: 'content', width: "10%", align: 'left'},
            {title: '联系人', field: 'link_name', width: '10%', align: 'left'},
            {title: '联系电话', field: 'tel', width: "8%", align: 'left'},
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

        toolbar:'#db_service_toolbar',
        onDblClickRow: function (rowIndex, rowData) {
            $('#db_service_grid').datagrid('uncheckAll');
            $('#db_service_grid').datagrid('checkRow', rowIndex);
            ShowEditOrViewDialog();
        },
        onLoadSuccess: function () {
            $('#db_service_grid').datagrid('clearSelections');
            $('#db_service_grid').datagrid('clearChecked');
        },
        onSelect: function () {
            $('#db_service_grid').datagrid('clearChecked');
        },
        onCheck: function () {
            $('#db_service_grid').datagrid('clearSelections');
        }
    });


    $(".db_service_search_keyword").searchbox({
            prompt: '关键搜索...',
            width: 200,
            panelHeight: 55,
            searcher: function () {
               searchData();
            }
    });


    //  登录账户验证
    $('#db_service_form_name').validatebox({
        required: true,
        missingMessage: '请输入公司名称'
    });
     $('#db_service_form_content').validatebox({
        required: true,
        missingMessage: '请输入业务内容'
    });

     
});


function initServiceContent(id){
    var url='db/service/getcontent';
    Qrck.get(url,{sid:id},"",function(msg){
        var content="";
        for(var i=0;i<msg.length;i++){
            if(msg[i].status ==0){
                content+='<label><input type="checkbox" checked="checked" name="ctype[]" value="'+msg[i].id+'" />&nbsp;'+msg[i].name+'&nbsp;</label>&nbsp;';
            }else{
                 content+='<label><input type="checkbox" name="ctype[]" value="'+msg[i].id+'" />&nbsp;'+msg[i].name+'&nbsp;</label>&nbsp;';
            }
        }
        $("#db_service_form_ctype").html(content);
    });
}


function searchData(){
    var k=$(".db_service_search_keyword").val();
    $("#db_service_grid").datagrid("load",{keyword:k});
}


function reloadData(){
    $("#db_service_grid").datagrid("reload");
}
/*
 *修改用户的状态
 */
function editStatus(sflag){
    var rows=$("#db_service_grid").datagrid("getChecked");
    if(rows.length > 0){
            var ids="";
            for(var i=0;i<rows.length;i++){
                ids+=ids==""?rows[i].id:","+rows[i].id;
            }
            var url='db/service/status';
            var parm={ids:ids,status:sflag};
            Qrck.put(url,parm,"",function(){
                    $.messager.alert("提示信息","操作成功！","warning"); 
                    $("#db_service_grid").datagrid("reload");
            },function(){
                    $.messager.alert("提示","操作失败！","warning"); 
            });   
    }else{
             $.messager.alert("提示","请选择至少一行","warning"); 
    }
}


function addBox(){
    $("#db_service_form_box")[0].reset();
    initServiceContent(0);
    $("#db_service_form").removeClass("hide");
    $('#db_service_form').dialog({
        title: '添加服务机构',
        cache: false,
        width: 700,
        modal : true,
        inline : true,
        buttons: [{
            text: '保存',
            iconCls: 'icon-save',
            handler: function(){

                if($('#db_service_form_box').form('validate')){
                    var data=$("#db_service_form_box").serializeArray();
                    var url='db/service/create';
                    Qrck.post(url,data,"",function(){
                            $.messager.alert("提示信息","操作成功！","warning"); 
                            $('#db_service_form').dialog('close');
                            $("#db_service_grid").datagrid("reload");
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
                $('#db_service_form').dialog('close');
                $("#db_service_form").addClass("hide");
            }
        }]
    });
}


function editBox(uid){
    if(uid >0){
        $("#db_service_form_box")[0].reset();

        var url="db/service/view?id="+uid;
        Qrck.get(url,{},"",function(data){

                initServiceContent(uid);

                $("#db_service_form_name").val(data.name);
                $("#db_service_form_content").val(data.content);
                $("#db_service_form_link_name").val(data.link_name);
                $("#db_service_form_tel").val(data.tel);


                $("#db_service_form").removeClass("hide");
                $('#db_service_form').dialog({
                    title: '编辑服务机构',
                    cache: false,
                    width: 700,
                    modal : true,
                    inline : true,
                    buttons: [{
                        text: '保存',
                        iconCls: 'icon-save',
                        handler: function(){

                            if($('#db_service_form_box').form('validate')){
                                var data=$("#db_service_form_box").serializeArray();
                                var url='db/service/update?id='+uid;
                                Qrck.put(url,data,"",function(){
                                        $.messager.alert("提示信息","操作成功！","warning"); 
                                        $('#db_service_form').dialog('close');
                                        $("#db_service_grid").datagrid("reload");
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
                            $('#db_service_form').dialog('close');
                            $("#db_service_form").addClass("hide");
                        }
                    }]
                });

        },function(){
               $.messager.alert("提示信息","获取信息失败！","warning"); 
        });   

        
    }
}