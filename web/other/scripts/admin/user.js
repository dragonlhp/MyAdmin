/**
 * Created by Administrator on 2016/11/6 0006.
 */
/**
 *  模块    ：编纂模块
 *  流程    ：创建任务
 */

$(function () {
    $('#admin_user_grid').datagrid({   //定位到Table标签，Table标签的ID是grid
        url: Qrck.baseApiUrl+'admin/user/index',   //指向后台的Action来获取当前菜单的信息的Json格式的数据
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
            
            {title: '登录名', field: 'login_name', width: "15%", align: 'left'},
            {title: '用户名', field: 'name', width: "15%", align: 'left'},
            {title: '角色', field: 'role', width: '15%', align: 'left'},
            {
                title: '状态', field: 'status', formatter: function (value) { //格式化数据
                if (value == 1) {
                    return '启用';
                } else if (value == 2) {
                    return '停用'
                } 
            }, width: "5%", align: 'center'
            },
            {title: '发布时间', field: 'created', sortable: true, width: "20%", align: 'center'},
            {title: '更新时间', field: 'updated', width: "20%", align: 'center'},
            {title: '操作', field: 'id', width: "9%", align: 'center'
                ,formatter: function(value,row,index){
                    return '<a href="#" onclick="editUser('+value+')">编辑</a>';
                   /* if (row.user){
                        return row.user.name;
                    } else {
                        return value;
                    }*/
                }

            }
        ]],

        toolbar: [{
            id: 'articleAdd',
            text: '添加',

            iconCls: 'icon-add',
            handler: function () {
                addUser();
                //$('#articleGrid').datagrid('load', {});
            }
        },'-', {
            id: 'adminUserDelete',
            text: '删除',
            iconCls: 'icon-remove',
            handler: function () {
               // 删除用户
                editStatus(9);
            }
        }, '-', {
            id: 'adminUserActive',
            text: '启用',
            iconCls: 'icon-checkmark',
            handler: function () {
                // 启用用户
                editStatus(1);
            }
        },'-', {
            id: 'adminUserUnper',
            text: '禁用',
            iconCls: 'icon-close',
            handler: function () {
                 // 禁用用户
              
                editStatus(2);
            }
        }, '-', {
            id: 'adminUserReload',
            text: '刷新',
            iconCls: 'icon-reload',
            handler: function () {
                //实现刷新栏目中的数据
                $("#admin_user_grid").datagrid("reload");
            }
        },
        ],
        onDblClickRow: function (rowIndex, rowData) {
            $('#admin_user_grid').datagrid('uncheckAll');
            $('#admin_user_grid').datagrid('checkRow', rowIndex);
            ShowEditOrViewDialog();
        }
    })
    $('#article_toolbar').appendTo('.datagrid-toolbar');


    //  登录账户验证
    $('#admin_user_add_login_name').validatebox({
        required: true,
        missingMessage: '请输入登录名'
    });
     $('#admin_user_add_name').validatebox({
        required: true,
        missingMessage: '请输入昵称'
    });
    //  登录密码验证
    $('#admin_user_add_pwd').validatebox({
        required: false,
        validType: 'length[3,8]',
        missingMessage: '请输入密码',
        invalidMessage: '密码长度不应低于3位或大于8位'
    });
});


/*
 *修改用户的状态
 */
function editStatus(sflag){
    var rows=$("#admin_user_grid").datagrid("getChecked");
        if(rows.length > 0){
            var ids="";
            for(var i=0;i<rows.length;i++){
                ids+=ids==""?rows[i].id:","+rows[i].id;
            }
            var url='admin/user/status';
            var parm={ids:ids,status:sflag};
            Qrck.put(url,parm,"",function(){
                    $.messager.alert("提示信息","操作成功！","warning"); 
                    $("#admin_user_grid").datagrid("reload");
            },function(){
                    $.messager.alert("提示","操作失败！","warning"); 
            });   
        }
}


function addUser(){
    initRoles();
    $("#admin_user_form_box")[0].reset();
    $("#admin_user_form").removeClass("hide");
    $('#admin_user_form').dialog({
        title: '添加管理员',
        cache: false,
        width: 600,
        modal : true,
        inline : true,
        buttons: [{
            text: '保存',
            iconCls: 'icon-save',
            handler: function(){

                if($('#admin_user_form_box').form('validate')){
                    var data=$("#admin_user_form_box").serializeArray();
                    var url='admin/user/create';
                    Qrck.post(url,data,"",function(){
                            $.messager.alert("提示信息","操作成功！","warning"); 
                            $('#admin_user_form').dialog('close');
                            $("#admin_user_grid").datagrid("reload");
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
                $('#admin_user_form').dialog('close');
                $("#admin_user_form").addClass("hide");
            }
        }]
    });
}


function editUser(uid){
    if(uid >0){
        initRolesByUserId(uid);
        $("#admin_user_form_box")[0].reset();
        //$("#admin_user_add_id").val(uid);

        var url="admin/user/view?id="+uid;
        Qrck.get(url,{},"",function(data){
                $("#admin_user_add_login_name").val(data.login_name);
                $("#admin_user_add_name").val(data.name);
                $("#admin_user_form_box input[name='status']").removeAttr("checked");
                $("#admin_user_form_box input[name='status'][value='"+data.status+"']").prop("checked", "checked");
        },function(){
               $.messager.alert("提示信息","获取用户信息失败！","warning"); 
        });   


        $("#admin_user_form").removeClass("hide");
        $('#admin_user_form').dialog({
            title: '编辑管理员',
            cache: false,
            width: 600,
            modal : true,
            inline : true,
            buttons: [{
                text: '保存',
                iconCls: 'icon-save',
                handler: function(){

                    if($('#admin_user_form_box').form('validate')){
                        var data=$("#admin_user_form_box").serializeArray();
                        var url='admin/user/update?id='+uid;
                        Qrck.put(url,data,"",function(){
                                $.messager.alert("提示信息","操作成功！","warning"); 
                                $('#admin_user_form').dialog('close');
                                $("#admin_user_grid").datagrid("reload");
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
                    $('#admin_user_form').dialog('close');
                    $("#admin_user_form").addClass("hide");
                }
            }]
        });
    }
}


function initRoles(){
    var url='admin/role/userroles';
        Qrck.asyncFlag=true;
        Qrck.get(url,{},"",function(result){
            var res=result;
            if(res.length >0){
                var content="";
                for(var i=0;i< res.length;i++){
                    content+='<label><input type="checkbox" name="role_ids" value="'+res[i].id+'" />'+res[i].name+'</label>&nbsp;&nbsp;';
                }
               $("#admin_user_form_roles").html(content);
            }
        },"");   
        Qrck.asyncFlag=false;
}

function initRolesByUserId(uid){
    var url='admin/role/userroles?uid='+uid;
        Qrck.asyncFlag=true;
        Qrck.get(url,{},"",function(result){
            var res=result;
            if(res.length >0){
                var content="";
                for(var i=0;i< res.length;i++){
                    if(res[i].checkflag ==1){
                         content+='<label><input type="checkbox" checked="checked" name="role_ids[]" value="'+res[i].id+'" />'+res[i].name+'</label>&nbsp;&nbsp;';
                    }else{
                         content+='<label><input type="checkbox" name="role_ids[]" value="'+res[i].id+'" />'+res[i].name+'</label>&nbsp;&nbsp;';
                    }
                   
                }
               $("#admin_user_form_roles").html(content);
            }
        },"");   
        Qrck.asyncFlag=false;
}

