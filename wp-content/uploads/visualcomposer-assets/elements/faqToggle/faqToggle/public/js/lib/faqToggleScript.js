(function ($) {
  $.fn.collapsible = function () {
    var settings = {
      collapseSpeed: 400,
      activeClass: 'vce-faq-toggle-state--opened'
    };

    return this.each( function () {
      var element = $(this);
      var title = element.find('.vce-faq-toggle-title');
      var content = element.find('.vce-faq-toggle-text-block');

      element.data('vcvCollapsible', true);

      title.on('click', function () {
        title.toggleClass(settings.activeClass);
        content.slideToggle(settings.collapseSpeed);
      });
    });
  };
})(jQuery);