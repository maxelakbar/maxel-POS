$(function () {
  $(document).scroll(function () {
    var $nav = $(".navbar-fixed-top");
    $nav.toggleClass("scroller", $(this).scrollTop() > $nav.height);
  });
});
