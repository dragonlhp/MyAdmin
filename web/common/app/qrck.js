var Qrck = {
    baseApiUrl: "http://admin.sc.com/api/dataapi/web/",
    baseHtmlUrl: "http://admin.sc.com/",
    menusId: {//主要的模块菜单的id
        attendance: 4,//考勤


        //welcome: 0,//欢迎页面
        //check: 1,//常用功能（审核模块）
        //base: 2,//基本管理
        article: 3,//文章管理
        //flink: 4,//友情链接管理
        //member: 5,//会员管理
        //sale: 6,//交易大市场
        //project: 7,//项目申报
        //coupon: 8,//优惠券
        //grade: 9,//高级会员
        //order: 10,//在线交易管理
        //database: 11,//数据库信息管理
        //sale_sup: 51,//交易大市场-供应
        //sale_req: 52,//交易大市场-需求
    },
    cookiePath: "/",
    asyncFlag: false,
    dataType: 'json',
    get: function (url, parm, beforeSend, success, error) {
        this.GetAjaxData(url, "get", parm, beforeSend, success, error);
    },
    post: function (url, parm, beforeSend, success, error) {
        this.GetAjaxData(url, "post", parm, beforeSend, success, error);
    },
    put: function (url, parm, beforeSend, success, error) {
        this.GetAjaxData(url, "put", parm, beforeSend, success, error);
    },
    delete: function (url, parm, beforeSend, success, error) {
        this.GetAjaxData(url, "delete", parm, beforeSend, success, error);
    },
    GetAjaxData: function (url, method, parm, beforeSend, success, error) {

        var login_name = $.cookie('login_name');
        var token = $.cookie('token');

        var dataurl = this.baseApiUrl + url;
        $.ajax({
            type: method,
            url: dataurl,
            cache: false,
            async: this.asyncFlag,
            dataType: this.dataType,
            data: parm,
            beforeSend: function () {
                if (typeof beforeSend === "function") {
                    beforeSend();
                }
            },
            success: function (result) {
                if (typeof success === "function") {
                    success(result);
                }
            },
            error: function (result) {
                if (typeof error === "function") {
                    error(result);
                }
            },
        });
    },

    form: function (form, url, method, parm, beforeSend, success, error) {
        var login_name = $.cookie('login_name');
        var token = $.cookie('token');

        var dataurl = this.baseApiUrl + url;
        $(form).ajaxSubmit({
            headers: {
                "X-Auth-Client": login_name,
                "X-Auth-Token": token,
            },
            type: method,
            url: dataurl,
            cache: false,
            async: this.asyncFlag,
            dataType: this.dataType,
            data: parm,
            beforeSend: function () {
                if (typeof beforeSend === "function") {
                    beforeSend();
                }
            },
            success: function (result) {
                if (typeof success === "function") {
                    success(result);
                }
            },
            error: function (result) {
                if (typeof error === "function") {
                    error(result);
                }
            }
        });
    },
    isLogin: function () {
        var login_name = $.cookie('login_name');
        var token = $.cookie('token');
        if (!login_name || typeof login_name == "undefined" || login_name == "" || !token || typeof token == "undefined" || token == "") {
            return false;
        } else {
            return true;
        }
    }
};
function dump(data) {
    console.log(data);
}

function isFun(fn) {
    return Object.prototype.toString.call(fn) === '[object Function]';
}

