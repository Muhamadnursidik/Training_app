(function (global, factory) {
	typeof exports === 'object' && typeof module !== 'undefined' ? factory() :
	typeof define === 'function' && define.amd ? define(factory) :
	(factory());
}(this, (function () { 'use strict';

    $(document).ready(function () {
        $('.treeview a.treeview-toggle').on('click', function(e) {

            if (!$(this).hasClass('active')) {
                // reset treeview
                $(this).closest('ul').children().find('.show').removeClass('show');
                $(this).closest('ul').children().find('.active').removeClass('active');
            }

            // set toggle class active
            $(this).toggleClass('active');

            var $subMenu = $(this).next(".treeview-menu");
            $subMenu.toggleClass('show');

            return false;
        });
    });

})));
