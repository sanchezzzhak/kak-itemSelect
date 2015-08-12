(function(root, factory) {
    // CommonJS support
    if (typeof exports === 'object') {
        module.exports = factory();
    }
    // AMD
    else if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    }
    // Browser globals
    else {
        factory(root.jQuery);
    }
}(this, function($) {
    'use strict';

    var domSelectors = {
        fromList: '.itemselect-list-from',
        toList: '.itemselect-list-to',
        item: '.itemselect-item'
    }


    // **********************************
    // Constructor
    // **********************************
    var kakItemSelect = function(element, options) {

    };

    kakItemSelect.prototype = {
        constructor: kakItemSelect,
        // ----------------------------------
        // Methods to override
        // ----------------------------------
    };

    $.fn.kakItemSelect = function(option) {
        var options = typeof option == 'object' && option;
        new kakItemSelect(this, options);
        return this;
    };
    $.fn.kakItemSelect.Constructor = kakItemSelect;

}));