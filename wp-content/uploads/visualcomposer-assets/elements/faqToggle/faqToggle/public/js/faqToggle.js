/* global vcv */
(function ($) {
  vcv.on('ready', function () {
    $(function () {
      var collapsibleItems = $('.vce-faq-toggle-inner');
      collapsibleItems.each(function() {
        var item = $(this);
        !item.data('vcvCollapsible') && item.collapsible();
      });
    })
  })
})(window.jQuery);
