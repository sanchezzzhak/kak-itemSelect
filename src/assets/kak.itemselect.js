(function (root, factory) {
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
}(this, function ($) {
  'use strict';

  var domSelectors = {
    fromList: '.itemselect-list-from',
    btnFrom: '.btnFrom',
    btnTo: '.btnTo',
    toList: '.itemselect-list-to',
    item: '.itemselect-item',
    select: '.itemselect-select',
    search: '.itemselect-input-search',
    selectAllTo: '.select-all-to',
    selectAllFrom: '.select-all-from',
    unselectAllTo: '.unselect-all-to',
    unselectAllFrom: '.unselect-all-from'
  };

  // **********************************
  // Constructor
  // **********************************
  var kakItemSelect = function (element, options) {

    $.extend($.expr[":"], {
      "contains-ci": function (elem, i, match, array) {
        return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
      }
    });

    this.el = $(element);
    this.fromList = this.el.find(domSelectors.fromList);
    this.toList = this.el.find(domSelectors.toList);

    // move select items fromlist to tolist
    this.el.find(domSelectors.btnFrom).off().on('click', $.proxy(function (e) {
      this.moveItems(this.fromList.find(domSelectors.select), false)
    }, this));
    // move select items tolist to fromlist
    this.el.find(domSelectors.btnTo).off().on('click', $.proxy(function (e) {
      this.moveItems(this.toList.find(domSelectors.select), true)
    }, this));
    // select all from list
    this.el.find(domSelectors.selectAllFrom).off().on('click', $.proxy(function (e) {
      var className = String(domSelectors.select).substring(1);
      this.fromList.find(domSelectors.item).each(function (i, el) {
        $(el).addClass(className);
      });
    }, this));
    // unselect all from list
    this.el.find(domSelectors.unselectAllFrom).off().on('click', $.proxy(function (e) {
      var className = String(domSelectors.select).substring(1);
      this.fromList.find(domSelectors.item).each(function (i, el) {
        $(el).removeClass(className);
      });
    }, this));
   // select all to list
    this.el.find(domSelectors.selectAllTo).off().on('click', $.proxy(function (e) {
      var className = String(domSelectors.select).substring(1);
      this.toList.find(domSelectors.item).each(function (i, el) {
        $(el).addClass(className);
      });
    }, this));
    // unselect all to list
    this.el.find(domSelectors.unselectAllTo).off().on('click', $.proxy(function (e) {
      var className = String(domSelectors.select).substring(1);
      this.toList.find(domSelectors.item).each(function (i, el) {
        $(el).removeClass(className);
      });
    }, this));
    // select item
    this.el.find(domSelectors.item).off().on('click', $.proxy(function (e) {
      var move = this.el.data('moveClick');
      var elm = $(e.currentTarget);
      if (move) {
        var select = elm.closest(domSelectors.toList).length > 0;
        this.moveItems([elm], select)
      } else {
        elm.toggleClass(String(domSelectors.select).substring(1));
      }
    }, this));
    // search item
    this.el.find(domSelectors.search).on('keydown', function (ev) {
      if ((ev.keyCode || ev.which) === 13) {
        ev.preventDefault();
        return false;
      }
    });

    this.el.find(domSelectors.search).on('keyup click search input paste blur', $.proxy(function (e) {
      var input = $(e.target);
      var q = input.val();

      this.searchItems(q, input.parent().data('search'));
    }, this));


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
    searchItems: function (q, selector) {
      if (q.length < 3) {
        $(selector).find(domSelectors.item).removeClass('hide');
        return;
      }

      $.each($(selector).find(domSelectors.item), function (i, o) {
        var match = $("*:contains-ci('" + q + "')", this);
        (match.length > 0) ? $(this).removeClass('hide') : $(this).addClass('hide');
      });

    },
    inputHidden: function (item, direction) {
      var className = String(domSelectors.select).substring(1);
      item.removeClass(className);
      item.find('input[name="' + this.el.data('inputname') + '"]').remove();

      if (!direction) {
        var input = $('<input>', {type: 'hidden', name: this.el.data('inputname'), value: item.data('id')});
        item.append(input);
      }
    },
    moveItems: function (items, direction) {
      var _this = this;
      $.each(items, function (k, i) {
        if (!$(i).is('.hide')) {
          var item = $(i).detach();
          _this.inputHidden(item, direction);
          item.appendTo(direction ? _this.fromList : _this.toList)
        }
      });
    }
  };

  $.fn.kakItemSelect = function (option) {
    var options = typeof option == 'object' && option;
    new kakItemSelect(this, options);
    return this;
  };
  $.fn.kakItemSelect.Constructor = kakItemSelect;

}));