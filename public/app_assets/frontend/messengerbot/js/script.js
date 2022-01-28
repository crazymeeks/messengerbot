$('.next-btn').on('click', function () {
  $('.order-page').addClass('-hidden');
  $('.order-form').removeClass('-hidden');
});

$('.back-btn').on('click', function () {
  $('.order-page').removeClass('-hidden');
  $('.order-form').addClass('-hidden');
});
