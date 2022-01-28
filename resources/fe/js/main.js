(function () {
  function togglePaymentEdit() {
    $('.payment-status .status').toggleClass('active');
    $('.payment-status .form-control').toggleClass('active');
    $('.payment-status .button').toggleClass('active');
  }

  function toggleOrderEdit() {
    $('.order-status .status').toggleClass('active');
    $('.order-status .form-control').toggleClass('active');
    $('.order-status .button').toggleClass('active');
  }

  $('.payment-status .edit-btn').on('click', function () {
    togglePaymentEdit();
  });

  $('.payment-status .save-btn').on('click', function () {
    togglePaymentEdit();
  });

  $('.order-status .edit-btn').on('click', function () {
    toggleOrderEdit();
  });

  $('.order-status .save-btn').on('click', function () {
    toggleOrderEdit();
  });

  initDashboard();

  function initDashboard() {
    var salesData = {
      labels: ['Bananas', 'Apples', 'Grapes'],
      series: [20, 15, 40],
    };

    var lineData = {
      labels: ['January', 'February', 'March', 'April', 'May'],
      series: [
        { name: '2019', data: [12, 9, 7, 8, 5] },
        { name: '2020', data: [2, 1, 3.5, 7, 3] },
      ],
    };

    var barData = {
      labels: ['January', 'February', 'March', 'April', 'May'],
      series: [20, 60, 50, 30, 60],
    };

    var barOptions = {
      distributeSeries: true,
    };
    var lineOptions = {
      fullWidth: true,
      chartPadding: {
        right: 40,
      },
      plugins: [
        Chartist.plugins.legend({
          clickable: false,
        }),
      ],
    };

    var salesOptions = {
      labelInterpolationFnc: function (value) {
        return value;
      },
      donut: true,
      donutWidth: 60,
      donutSolid: true,
      startAngle: 270,
      showLabel: false,
      plugins: [Chartist.plugins.legend()],
    };

    var responsiveOptions = [
      [
        'screen and (max-width: 1441px)',
        {
          donutWidth: 30,
        },
      ],
    ];

    new Chartist.Pie('#saleschart', salesData, salesOptions, responsiveOptions);
    new Chartist.Pie('#saleschart2', salesData, salesOptions, responsiveOptions);

    new Chartist.Line('#linechart', lineData, lineOptions);

    new Chartist.Bar('#barchart', barData, barOptions);
  }
})();
