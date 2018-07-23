/*
 * Copyright (C) 2018 Baidu, Inc. All Rights Reserved.
 */
(function ($) {
    $.fn.myPlugin = function () {
        this.getColor = function () {
            console.log('getColor');
        }
    };
    $.fn.setHeight = function (width) {
        return this.height(width);
    }
    $.fn.maxHeight = function () {
        var max = 0;

        this.each(function() {
            max = Math.max( max, $(this).height() );
        });

        return max;
    }
})(jQuery);