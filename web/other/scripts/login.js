/**
 *
 */
$(function () {
    if (Qrck.isLogin()) {
        //window.location.href = Qrck.baseHtmlUrl + "web/pages/module/main.html";
        window.location.href = Qrck.baseHtmlUrl + "desk/index.html";
    }
    document.onkeydown = function (e) {
        e = e || event;
        if (e.keyCode == 13) {
            loginFun();
        }
    };
    // 构建登录窗口
    $('#LoginBox').dialog({
        title: '菁蓉国际创新创业中心',
        width: 400,
        height: 200,
        iconCls: 'icon-man',
        closable: false,
        draggable: false,
        cache: false,
        modal: false,
        shadow: false,
        buttons: [{
            text: '登录',
            iconCls: 'icon-man',
            id: 'aa',
            handler: function () {
                loginFun()
            }
        }],
        onOpen: function () {
            $('#username').focus();
            // $('#loginform:input').keyup(function (event) {
            //     if (event.keyCode == 13) {
            //         loginFun();
            //     }
            // });
        }
    });

    //	登录账户验
    $('#username').validatebox({
        required: true,
        missingMessage: '请输入登录账号'
    });
    //	登录密码验证
    $('#password').validatebox({
        required: true,
        validType: 'length[3,8]',
        missingMessage: '请输入登录密码',
        invalidMessage: '密码长度不应低于3位或大于8位'
    });

});
//Login 登录

var loginFun = function () {
    if ($('#loginform').form('validate')) {

        Qrck.post('admin/login/token', $('#loginform').serialize(), function () {
            $.messager.progress({
                text: '登录验证中请稍后……',

            });
        }, function (data) {
            var login_name = $('#username').val();

            $.cookie('login_name', login_name, {path: Qrck.cookiePath});
            $.cookie('token', data.token, {path: Qrck.cookiePath});

            //window.location.href = Qrck.baseHtmlUrl + "web/pages/module/main.html";
            window.location.href = Qrck.baseHtmlUrl + "desk/index.html";
        }, function (data) {
            $.messager.progress('close');
            $.messager.show({
                title: '提示',
                msg: '用户不存在或密码错误，请重新登录',
                showType: 'slide',
            });
        });
    }
};
