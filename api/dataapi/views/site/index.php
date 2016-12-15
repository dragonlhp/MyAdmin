<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf8"/>
    <meta name="applicable-device" content="pc">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta http-equiv="Cache-Control" content="no-transform"/>
    <title>管理后台</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EDGE"/>

    <script src=" ../../../web/scripts/jquery-1.11.2.js"></script>
    <script src=" ../../../web/scripts/jquery.cookie.js"></script>
    <script src=" ../../../web/scripts/jquery.form.min.js"></script>
</head>
<body>
<input type="text" name="login_name" id="login_name" value="">
<input type="password" name="pwd" id="pwd" value="">
<input type="button" value="登录" onclick="login()">


<p>请求地址:<br/><input type="text" name="url" id="url" style="width:500px;"></p>
<p>请求方法:<br/><input type="text" name="type" id="type" style="width:500px;"></p>
<p>请求数据：<br/><textarea id="postdata" style="width:100%;overflow-y: visible;"></textarea></p>

<input type="button" name="" value="提交" onclick="getData()">

<p>
    成功信息:<br/>
    <textarea id="result" style="width:100%; "></textarea>
</p>
<p>
    失败信息:<br/>
    <textarea id="error" style="width:100%; "></textarea>
</p>
</body>
<script type=text/javascript>

    $(function () {
        $("#postdata").css('height', 80);
        $("#error").css('height', 80);
        $("#result").css('height', 80);
    });

    $("#postdata").change(function () {

        autoHeight()
    });

    $('#postdata').keydown(function (e) {
        if (e.keyCode == 13) {

            autoHeight()
        }
    });


    function autoHeight() {

        $("#postdata").css('height', ($("#postdata").val().split("\n").length) * 19);
    }


    function login() {
        var login_name = $("#login_name").val();
        var pwd = $("#pwd").val();
        var url = "admin/login/token";
        $.post(url, {login_name: login_name, pwd: pwd}, function (result) {
            if (result.token == "" || result.token == null || result.token == undefined) {
                alert("登录失败");
            } else {
                alert("登录成功");
                $.cookie('login_name', login_name);
                $.cookie('token', result.token);
            }
        }, "json").error(function (XMLHttpRequest, textStatus, errorThrown) {
            alert("登录错误" + XMLHttpRequest);
        });
    }

    function getData() {
        var login_name = $.cookie('login_name');
        var token = $.cookie('token');


        var url = $("#url").val();
        var data = $("#postdata").val();
        if (data != '') {
            data = JSON.parse(data);
        }
        var type = $("#type").val();
        console.log(data);
        $.ajax(
            {
                headers: {
                    "X-Auth-Client": login_name,
                    "X-Auth-Token": token,
                },
                url: url,
                type: type,
                async: false,
                data: data,
                dataType: 'json',
                success: function (result) {
                    var length = ($("#result").val().split("\n").length) * 19;
                    var res = format(JSON.stringify(result));

                    $("#result").val(res);
                    $("#result").css('height', length);

                    $("#error").val("");


                },
                /*error:function(XMLHttpRequest, textStatus, errorThrown) {
                 alert(XMLHttpRequest.status);
                 alert(XMLHttpRequest.readyState);
                 alert(textStatus);
                 }*/
                error: function (error) {
                    var length = ($("#error").val().split("\n").length) * 19;
                    var res = format(JSON.stringify(error));


                    $("#error").val(res);
                    $("#result").val("");
                    $("#error").css('height', length);


                }
            }
        );
    }


    function format(txt, compress/*是否为压缩模式*/) {/* 格式化JSON源码(对象转换为JSON文本) */
        var indentChar = '    ';
        if (/^\s*$/.test(txt)) {
            alert('数据为空,无法格式化! ');
            return;
        }
        try {
            var data = eval('(' + txt + ')');
        }
        catch (e) {
            alert('数据源语法错误,格式化失败! 错误信息: ' + e.description, 'err');
            return;
        }
        ;
        var draw = [], last = false, This = this, line = compress ? '' : '\n', nodeCount = 0, maxDepth = 0;

        var notify = function (name, value, isLast, indent/*缩进*/, formObj) {
            nodeCount++;
            /*节点计数*/
            for (var i = 0, tab = ''; i < indent; i++)tab += indentChar;
            /* 缩进HTML */
            tab = compress ? '' : tab;
            /*压缩模式忽略缩进*/
            maxDepth = ++indent;
            /*缩进递增并记录*/
            if (value && value.constructor == Array) {/*处理数组*/
                draw.push(tab + (formObj ? ('"' + name + '":') : '') + '[' + line);
                /*缩进'[' 然后换行*/
                for (var i = 0; i < value.length; i++)
                    notify(i, value[i], i == value.length - 1, indent, false);
                draw.push(tab + ']' + (isLast ? line : (',' + line)));
                /*缩进']'换行,若非尾元素则添加逗号*/
            } else if (value && typeof value == 'object') {/*处理对象*/
                draw.push(tab + (formObj ? ('"' + name + '":') : '') + '{' + line);
                /*缩进'{' 然后换行*/
                var len = 0, i = 0;
                for (var key in value)len++;
                for (var key in value)notify(key, value[key], ++i == len, indent, true);
                draw.push(tab + '}' + (isLast ? line : (',' + line)));
                /*缩进'}'换行,若非尾元素则添加逗号*/
            } else {
                if (typeof value == 'string')value = '"' + value + '"';
                draw.push(tab + (formObj ? ('"' + name + '":') : '') + value + (isLast ? '' : ',') + line);
            }
            ;
        };
        var isLast = true, indent = 0;
        notify('', data, isLast, indent, false);
        return draw.join('');
    }

</script>
