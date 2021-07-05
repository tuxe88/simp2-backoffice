@extends('layouts.base', ["title"=> "Dashboard",
                            "sectionTitle"=>"Dashboard",
                            "subSectionTitle"=>"Dashboard",
                            "subSectionSubTitle"=>"Main"])

@push('css')
    <link href="{{asset('plugins/charts-c3/c3-chart.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/morris/morris.css')}}" rel="stylesheet">
@endpush

@section('content')

    <div class="row row-deck">
        <div class="col-lg-8 col-sm-12">
            <div class="card ">
                <div class="card-header">
                    <h3 class="card-title">Grafico de maquetación sin informacion real a modo de ejemplo</h3>
                </div>
                <div class="card-body">
                    <div id="echart1" class="chartsh" _echarts_instance_="ec_1622555655677" style="-webkit-tap-highlight-color: transparent; user-select: none; position: relative; background: transparent;"><div style="position: relative; overflow: hidden; width: 728px; height: 256px; padding: 0px; margin: 0px; border-width: 0px; cursor: default;"><canvas width="728" height="256" data-zr-dom-id="zr_0" style="position: absolute; left: 0px; top: 0px; width: 728px; height: 256px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas></div><div style="position: absolute; display: block; border-style: solid; white-space: nowrap; z-index: 9999999; transition: left 0.4s cubic-bezier(0.23, 1, 0.32, 1) 0s, top 0.4s cubic-bezier(0.23, 1, 0.32, 1) 0s; background-color: rgba(50, 50, 50, 0.7); border-width: 0px; border-color: rgb(51, 51, 51); border-radius: 4px; color: rgb(255, 255, 255); font: 14px / 21px sans-serif; padding: 5px; left: 539.5px; top: 152px;">2018<br><span style="display:inline-block;margin-right:5px;border-radius:10px;width:9px;height:9px;background-color:#ecb403;"></span>sales: 0<br><span style="display:inline-block;margin-right:5px;border-radius:10px;width:9px;height:9px;background-color:#1792d6;"></span>profit: 0<br><span style="display:inline-block;margin-right:5px;border-radius:10px;width:9px;height:9px;background-color:#172f71;"></span>growth: 5</div></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12">
            <div class="card ">
                <div class="card-header">
                    <div class="card-title">Indicadores de ejemplo para futura implementacion</div>
                </div>
                <div class="card-body p-4">
                    <div class="chats-wrap">
                        <div class="chat-details mb-1 p-3">
                            <h4 class="mb-0">
                                <span class="h5 font-weight-normal">Sales</span>
                                <span class="float-right p-1 bg-primary btn btn-sm text-white">
													<b>70</b>%</span>
                            </h4>
                            <div class="progress progress-md mt-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width: 70%"></div>
                            </div>
                        </div>
                        <div class="chat-details mb-1 p-3">
                            <h4 class="mb-0">
                                <span class="h5 font-weight-normal">Profit</span>
                                <span class="float-right p-1 bg-secondary  btn btn-sm text-white">
														<b>60</b>%</span>
                            </h4>
                            <div class="progress progress-md mt-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary" style="width: 60%"></div>
                            </div>
                        </div>
                        <div class="chat-details mb-1 p-3">
                            <h4 class="mb-0">
                                <span class="h5 font-weight-normal">Users</span>
                                <span class="float-right p-1 bg-cyan btn btn-sm text-white">
														<b>47%</b>
													</span>
                            </h4>
                            <div class="progress progress-md mt-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-cyan" style="width: 47%"></div>
                            </div>
                        </div>
                        <div class="chat-details mb-1 p-3">
                            <h4 class="mb-0">
                                <span class="h5 font-weight-normal">Growth</span>
                                <span class="float-right p-1 bg-info btn btn-sm text-white">
														<b>25%</b>
													</span>
                            </h4>
                            <div class="progress progress-md mt-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated  bg-info" style="width: 25%"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{asset('plugins/echarts/echarts.js')}}"></script>
    <script>


        $(function(e){
            'use strict'
            /*-----echart2-----*/

            var chartdata = [
                {
                    name: 'sales',
                    type: 'line',
                    smooth:true,
                    data: [12, 25, 22, 30, 14, 0],
                },
                {
                    name: 'profit',
                    type: 'line',
                    smooth:true,
                    data: [0, 10, 25, 10, 30, 0],
                    lineStyle: {
                        normal: { width: 1 }
                    },
                    itemStyle: {
                        normal: {
                            areaStyle: { type: 'default' }
                        }
                    }
                },
                {
                    name: 'growth',
                    type: 'line',
                    smooth:true,
                    data: [0, 20, 10, 15, 8, 5, 0],
                    lineStyle: {
                        normal: { width: 1 }
                    },
                    itemStyle: {
                        normal: {
                            areaStyle: { type: 'default' }
                        }
                    }
                }
            ];

            var chart = document.getElementById('echart1');
            var barChart = echarts.init(chart);

            var option = {
                grid: {
                    top: '6',
                    right: '0',
                    bottom: '17',
                    left: '25',
                },
                xAxis: {
                    data: [  '2013', '2014', '2015', '2016', '2017', '2018','2019','2020','2021'],
                    axisLine: {
                        lineStyle: {
                            color: '#eaeaea'
                        }
                    },
                    axisLabel: {
                        fontSize: 10,
                        color: '#000'
                    }
                },
                tooltip: {
                    show: true,
                    showContent: true,
                    alwaysShowContent: true,
                    triggerOn: 'mousemove',
                    trigger: 'axis',
                    axisPointer:
                        {
                            label: {
                                show: false,
                            }
                        }

                },
                yAxis: {
                    splitLine: {
                        lineStyle: {
                            color: '#eaeaea'
                        }
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#eaeaea'
                        }
                    },
                    axisLabel: {
                        fontSize: 10,
                        color: '#000'
                    }
                },
                series: chartdata,
                color:[ '#ecb403','#1792d6','#172f71',]
            };

            barChart.setOption(option);

        });

    </script>
@endpush
