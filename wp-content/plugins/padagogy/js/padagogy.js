/**
 * Created by wayne on 2017/5/8.
 */

function p_query() {
    var slugs = $.map($('#menu-padagogy .menu-item-type-taxonomy.current-menu-item>a'),function (el) {
        return $(el).data('slug');
    }).join('+');
    console.log(slugs);
    location.href = '';
}
$(function () {
   $('.menu-item>a[href="#"]').attr('href','javascript:void(0);');
   var padagogy_menu_timeid = null;
   $('#menu-padagogy .menu-item-type-taxonomy>a').click(function () {
       var _this = $(this);
       _this.parent().toggleClass('current-menu-item');
       clearTimeout(padagogy_menu_timeid);
       setTimeout(function () {
           p_query();
       },1000);
   });
});