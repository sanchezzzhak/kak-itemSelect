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
        toList:   '.itemselect-list-to',
        item:     '.itemselect-item',
        select:   '.itemselect-select',
        search:   '.itemselect-input-search'
    };

    // **********************************
    // Constructor
    // **********************************
    var kakItemSelect = function(element, options) {

        $.extend($.expr[":"], {
            "contains-ci": function (elem, i, match, array) {
                return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
            }
        });

        this.el = $(element);
        this.fromList = this.el.find(domSelectors.fromList);
        this.toList = this.el.find(domSelectors.toList);


        this.el.find('.btnFrom').off().on('click', $.proxy(function(e){
            this.moveItems(this.fromList.find(domSelectors.select),false)
        },this));

        this.el.find('.btnTo').off().on('click', $.proxy(function(e){
            this.moveItems(this.toList.find(domSelectors.select),true)
        },this));

        this.el.find(domSelectors.item).off().on('click', function (e) {
            $(this).toggleClass('itemselect-select');
        });

        $(function(){
            var search = 'foo';
            $("table tr td").filter(function() {
                return $(this).text() == search;
            }).parent('tr').css('color','red');
        });

        this.el.find(domSelectors.search).on('keydown', function(ev) {

            if ((ev.keyCode || ev.which) === 13) {
                ev.preventDefault();
                return false;
            }
        });

        this.el.find(domSelectors.search).on('keyup click search input paste blur',  $.proxy(function(e){
                var input = $(e.target);
                var q = input.val();

                this.searchItems(q, input.parent().data('search'));
        },this));


        /*
        this.el.find(domSelectors.fromList+','+domSelectors.toList).sortable({
            items: domSelectors.item,
            stop: function(event, ui) {
                console.log(event,ui);
            },
            placeholder: 'ui-sortable-placeholder',
            connectWith: domSelectors.fromList+','+domSelectors.toList,
            helper: 'original'
        }).disableSelection();
        */
    };

    kakItemSelect.prototype = {
        constructor: kakItemSelect,
        searchItems: function(q,selector){
            if (q.length < 3) {
                $(selector).find(domSelectors.item).removeClass('hide');
                return;
            }

            $.each($(selector).find(domSelectors.item), function (i, o) {
                var match = $("*:contains-ci('" + q + "')", this);
                (match.length > 0)  ? $(this).removeClass('hide') : $(this).addClass('hide');
            });


        },
        inputHidden: function(item,direction){
            item.removeClass('itemselect-select');
            item.find('input[name="' +  this.el.data('inputname') +'"]').remove();

            if(!direction) {
                var input = $('<input>',{type:'hidden', name: this.el.data('inputname') , value: item.data('id')});
                item.append(input);
            }
        },
        moveItems: function (items,direction) {
            var _this = this;
            $.each(items, function(k,i){
                if(!$(i).is('.hide')) {
                    var item  = $(i).detach();
                    _this.inputHidden(item,direction);
                    item.appendTo(direction ? _this.fromList: _this.toList )
                }
            });
        }
    };

    $.fn.kakItemSelect = function(option) {
        var options = typeof option == 'object' && option;
        new kakItemSelect(this, options);
        return this;
    };
    $.fn.kakItemSelect.Constructor = kakItemSelect;

}));