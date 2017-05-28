/**
 * Created by wayne on 2017/5/23.
 */



$(function () {

    var url = $('.app-download-btn').data('dl-rul');
    $(".dl_qrcode").hide().qrcode({

        // render: "table", //table方式
        width: 200, //宽度
        height:200, //高度
        text: toUtf8(url.trim()) //任意内容
    });

    if (!IsPC()) {
        $('.app-download-btn').click(function () {
            window.open(url);
        });
    }
    else {

        $('.app-download-btn').hover(function () {
            $('.dl_qrcode').width($(this).width());
            $('.dl_qrcode').show( 200);
            // $(document).one('click',function () {
            //     $('.dl_qrcode').stop().hide( 200);
            // });
        },function () {
            $('.dl_qrcode').stop().hide( 200);
        })
    }
});