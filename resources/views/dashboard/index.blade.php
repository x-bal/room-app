@extends('layouts.master')

@section('content')
<div class="row">
    <!-- BEGIN col-3 -->
    <div class="col-xl-3 col-md-6">
        <div class="widget widget-stats bg-white text-inverse">
            <div class="stats-icon stats-icon-square bg-gradient-cyan-blue text-white"><i class="ion-ios-analytics"></i></div>
            <div class="stats-content">
                <div class="stats-title text-gray-700">TODAY'S VISITS</div>
                <div class="stats-number">7,842,900</div>
                <div class="stats-progress progress">
                    <div class="progress-bar" style="width: 70.1%;"></div>
                </div>
                <div class="stats-desc text-gray-700">Better than last week (70.1%)</div>
            </div>
        </div>
    </div>
    <!-- END col-3 -->
    <!-- BEGIN col-3 -->
    <div class="col-xl-3 col-md-6">
        <div class="widget widget-stats bg-white text-inverse">
            <div class="stats-icon stats-icon-square bg-gradient-cyan-blue text-white"><i class="ion-ios-pricetags"></i></div>
            <div class="stats-content">
                <div class="stats-title text-gray-700">TODAY'S PROFIT</div>
                <div class="stats-number">180,200</div>
                <div class="stats-progress progress">
                    <div class="progress-bar" style="width: 40.5%;"></div>
                </div>
                <div class="stats-desc text-gray-700">Better than last week (40.5%)</div>
            </div>
        </div>
    </div>
    <!-- END col-3 -->
    <!-- BEGIN col-3 -->
    <div class="col-xl-3 col-md-6">
        <div class="widget widget-stats bg-white text-inverse">
            <div class="stats-icon stats-icon-square bg-gradient-cyan-blue text-white"><i class="ion-ios-cart"></i></div>
            <div class="stats-content">
                <div class="stats-title text-gray-700">NEW ORDERS</div>
                <div class="stats-number">38,900</div>
                <div class="stats-progress progress">
                    <div class="progress-bar" style="width: 76.3%;"></div>
                </div>
                <div class="stats-desc text-gray-700">Better than last week (76.3%)</div>
            </div>
        </div>
    </div>
    <!-- END col-3 -->
    <!-- BEGIN col-3 -->
    <div class="col-xl-3 col-md-6">
        <div class="widget widget-stats bg-white text-inverse">
            <div class="stats-icon stats-icon-square bg-gradient-cyan-blue text-white"><i class="ion-ios-chatboxes"></i></div>
            <div class="stats-content">
                <div class="stats-title text-gray-700">NEW COMMENTS</div>
                <div class="stats-number">3,988</div>
                <div class="stats-progress progress">
                    <div class="progress-bar" style="width: 54.9%;"></div>
                </div>
                <div class="stats-desc text-gray-700">Better than last week (54.9%)</div>
            </div>
        </div>
    </div>
    <!-- END col-3 -->
</div>
@endsection