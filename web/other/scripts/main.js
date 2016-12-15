
    $(function(){
        /*创建main页面左侧树状导航*/
        $('#menu').accordion({
            border: false,
            animate: true,
            multiple: false,
            collapsed:true
        });

    });


    /*跳转页面动画*/
    function show(){
        $("#loading").fadeOut("normal", function(){
            $(this).remove();
        });
    }
    var delayTime;
    $.parser.onComplete = function(){
        if(delayTime)
            clearTimeout(delayTime);
        delayTime = setTimeout(show,100);
    };
