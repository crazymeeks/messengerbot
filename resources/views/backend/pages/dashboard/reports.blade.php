@extends('layouts.cms')
@section('title', 'Dashboard')
@section('css')
<link rel="stylesheet" href="/fe/css/main.css">
@append
@section('content')
<div class="dashboard-container">
    <div class="side-board">
        <div class="number-box">
            <div class="content-number">
                <h1 class="number">&#8369;{{number_format($lifetime_sales, 2)}}</h1>
                <h3 class="desc">Lifetime Sales</h3>
            </div>
        </div>
        <div class="number-box">
            <div class="content-number">
                <h1 class="number">&#8369;{{number_format($monthly_sales, 2)}}</h1>
                <h3 class="desc">{{date('F', strtotime(date('Y-m')))}} sales</h3>
            </div>
        </div>
        <!-- <div class="number-box">
            <div class="content-number">
                <h1 class="number">3000</h1>
                <h3 class="desc">Monthly Sales</h3>
            </div>
        </div> -->
        <!-- <div class="number-box">
            <div class="content-number">
                <h1 class="number">3000</h1>
                <h3 class="desc">Monthly Sales</h3>
            </div>
        </div> -->
    </div>
    <div class="main-board">
        <div class="board-row">
            <?php if(count($most_sales) > 0):?>
            <div class="sales-chart">
                <div class="title"><h1>Product Sales Performance (Top 5)</h1></div>
                <div class="ct-chart chart" id="saleschart"></div>
            </div>
            <?php endif;?>
            <div class="bar-chart">
                <div class="title"><h1>Revenues</h1></div>
                <div class="ct-chart bar" id="barchart"></div>
            </div>
            <!-- <div class="sales-chart ">
                <div class="title"><h1>Product Sales</h1></div>
                <div class="ct-chart chart" id="saleschart2"></div>
            </div> -->
            
        </div>
        <div class="board-row">
            <div class="line-chart">
                <div class="title"><h1>Sales Performance</h1></div>
                <div class="ct-chart line" id="linechart"></div>
            </div>
            <!-- <div class="bar-chart">
                    <div class="title"><h1>Revenues</h1></div>
                    <div class="ct-chart bar" id="barchart"></div>
                </div> -->
            
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="/fe/js/chartist.min.js"></script>
<script src="/fe/js/chartist-plugin-legend.js"></script>

<script type="text/javascript">
(function($){
    initDashboard();
    function initDashboard() {
        <?php if(count($most_sales) > 0):?>
        var salesData = {
        labels: <?php echo json_encode($most_sales['labels'])?>,
        series: <?php echo json_encode($most_sales['series'])?>,
        };
        <?php endif;?>

        var lineData = {
        labels: <?php echo json_encode($linegraph['labels']);?>,
        series: [
            { name: 'Converted', data: <?php echo json_encode($linegraph['series']['complete']);?> },
            { name: 'Unpaid', data: <?php echo json_encode($linegraph['series']['pending']);?> },
            { name: 'Cancelled', data: <?php echo json_encode($linegraph['series']['cancelled']);?> },
        ],
        };

        var barData = {
        labels: <?php echo json_encode($barGraph['labels']);?>,
        series: <?php echo json_encode($barGraph['series']);?>,
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
        <?php if(count($most_sales) > 0):?>
        new Chartist.Pie('#saleschart', salesData, salesOptions, responsiveOptions);
        <?php endif;?>
        // new Chartist.Pie('#saleschart2', salesData, salesOptions, responsiveOptions);

        new Chartist.Line('#linechart', lineData, lineOptions);

        new Chartist.Bar('#barchart', barData, barOptions);
    }
})(jQuery);
</script>
@append