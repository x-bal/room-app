@extends('layouts.master')

@section('content')
@if(auth()->user()->level == 'admin')
<form action="" method="get" class="row mb-3">
    <div class="form-group col-md-4">
        <label for="from">From</label>
        <input type="date" name="from" id="from" class="form-control" value="{{ request('from') }}">
    </div>

    <div class="form-group col-md-4">
        <label for="to">To</label>
        <input type="date" name="to" id="to" class="form-control" value="{{ request('to') }}">
    </div>

    <div class="form-group mt-3 col-md-4">
        <button type="submit" class="btn btn-primary mt-1">Submit</button>
    </div>
</form>

<div class="row">
    <!-- BEGIN col-3 -->
    <div class="col-xl-3 col-md-6">
        <div class="widget widget-stats bg-white text-inverse">
            <div class="stats-icon stats-icon-square bg-gradient-cyan-blue text-white"><i class="ion-ios-analytics"></i></div>
            <div class="stats-content">
                <div class="stats-title text-gray-700">Tipe Room</div>
                <div class="stats-number">{{ $type }}</div>
                <div class="stats-progress progress">
                    <!-- <div class="progress-bar" style="width: 70.1%;"></div> -->
                </div>
            </div>
        </div>
    </div>
    <!-- END col-3 -->
    <!-- BEGIN col-3 -->
    <div class="col-xl-3 col-md-6">
        <div class="widget widget-stats bg-white text-inverse">
            <div class="stats-icon stats-icon-square bg-gradient-cyan-blue text-white"><i class="ion-ios-pricetags"></i></div>
            <div class="stats-content">
                <div class="stats-title text-gray-700">Total Room Price</div>
                <div class="stats-number">{{ number_format($roomprice ,0,',','.') }}</div>
                <div class="stats-progress progress">
                    <!-- <div class="progress-bar" style="width: 40.5%;"></div> -->
                </div>
            </div>
        </div>
    </div>
    <!-- END col-3 -->
    <!-- BEGIN col-3 -->
    <div class="col-xl-3 col-md-6">
        <div class="widget widget-stats bg-white text-inverse">
            <div class="stats-icon stats-icon-square bg-gradient-cyan-blue text-white"><i class="ion-ios-cart"></i></div>
            <div class="stats-content">
                <div class="stats-title text-gray-700">Total Extra Change</div>
                <div class="stats-number">{{ number_format($extraprice ,0,',','.') }}</div>
                <div class="stats-progress progress">
                    <!-- <div class="progress-bar" style="width: 76.3%;"></div> -->
                </div>
                <!-- <div class="stats-desc text-gray-700">Better than last week (76.3%)</div> -->
            </div>
        </div>
    </div>
    <!-- END col-3 -->
    <!-- BEGIN col-3 -->
    <div class="col-xl-3 col-md-6">
        <div class="widget widget-stats bg-white text-inverse">
            <div class="stats-icon stats-icon-square bg-gradient-cyan-blue text-white"><i class="ion-ios-chatboxes"></i></div>
            <div class="stats-content">
                <div class="stats-title text-gray-700">Total Final</div>
                <div class="stats-number">{{ number_format($final ,0,',','.') }}</div>
                <div class="stats-progress progress">
                    <!-- <div class="progress-bar" style="width: 54.9%;"></div> -->
                </div>
            </div>
        </div>
    </div>
    <!-- END col-3 -->
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-inverse">
            <!-- BEGIN panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">{{ $title }}</h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
                </div>
            </div>
            <!-- END panel-heading -->
            <!-- BEGIN panel-body -->
            <div class="panel-body">
                <canvas id="bar-chart"></canvas>
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Welcome to room app, {{ auth()->user()->name }}.</h4>
                <h6 class="text-secondary">Let's order your room.</h6>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('script')
<script src="{{ asset('/') }}plugins/chart.js/dist/Chart.min.js"></script>
<script>
    Chart.defaults.font.family = FONT_FAMILY;
    Chart.defaults.font.weight = FONT_WEIGHT;

    let data = ["{{ $type }}", "{{ $roomprice }}", "{{ $extraprice }}", "{{ $final }}"];

    var ctx2 = document.getElementById('bar-chart').getContext('2d');
    var barChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Tipe Room', 'Total Room Price', 'Total Extra Change', 'Final Total', ],
            datasets: [{
                label: 'Grafik',
                borderWidth: 2,
                borderColor: COLOR_INDIGO,
                backgroundColor: COLOR_INDIGO_TRANSPARENT_3,
                data: data
            }, ]
        }
    });
</script>
@endpush