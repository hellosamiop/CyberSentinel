@extends('theme::layouts.app')
@section('content')
    <script src="https://cdn.tailwindcss.com"></script>
    <div class="py-20 mx-auto max-w-7xl">
        <div class="container mx-auto p-5">
            <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
                <div class="bg-white col-span-1 shadow text-center border"
                     style="border-radius: 15px; padding: 15px 30px">
                    <h1>{{$domain->name}}</h1>
                </div>
                <div class="bg-white col-span-1 shadow text-center border"
                     style="border-radius: 15px; padding: 15px 30px">
                    <h1>{{$domain->domain_url}}</h1>
                </div>
                <div class="bg-white col-span-1 shadow text-center border"
                     style="border-radius: 15px; padding: 15px 30px">
                    <h1>{{$domain->industry->name}}</h1>
                </div>
                <div class="bg-white col-span-1 shadow text-center border"
                     style="border-radius: 15px; padding: 15px 30px">
                    <h1>{{$domain->country->name}}</h1>
                </div>
                <div class="bg-white col-span-1 shadow text-center border"
                     style="border-radius: 15px; padding: 15px 30px">
                    <h1>{{$domain->score}}</h1>
                </div>
                <div class="bg-white col-span-1 shadow text-center border"
                     style="border-radius: 15px; padding: 15px 30px">
                    <img src="{{$domain->logo}}" alt="" style="min-height: 150px">
                </div>
                <div class="bg-white col-span-4 shadow text-center border"
                     style="border-radius: 15px; padding: 15px 30px">
                    <div id="score_chart"></div>
                </div>
                <div class="bg-white col-span-5 shadow border" style="border-radius: 15px; padding: 15px 30px">
                    <h4 class="mb-3"><strong>WEBSITE HEALTH SCORE</strong></h4>
                    <div class="grid grid-cols-1 sm:grid-cols-6 gap-4">
                        <div class="bg-white col-span-1 flex flex-col gap-2 justify-between items-center shadow border"
                             style="border-radius: 15px; padding: 15px 30px">
                            <div class="flex gap-2 justify-center items-center">
                                <i class="icon voyager-settings"></i>
                                <span>Code Health</span>
                            </div>
                            <h1 style="font-size: 30px">4</h1>
                        </div>
                        <div class="bg-white col-span-1 flex flex-col gap-2 justify-between items-center shadow border"
                             style="border-radius: 15px; padding: 15px 30px">
                            <div class="flex gap-2 justify-center items-center">
                                <div class="icon voyager-settings"></div>
                                <span>Configuration Health</span>
                            </div>
                            <h1 style="font-size: 30px">4</h1>
                        </div>
                        <div class="bg-white col-span-1 flex flex-col gap-2 justify-between items-center shadow border"
                             style="border-radius: 15px; padding: 15px 30px">
                            <div class="flex gap-2 justify-center items-center">
                                <div class="icon voyager-settings"></div>
                                <span>Maintenance Health</span>
                            </div>
                            <h1 style="font-size: 30px">4</h1>
                        </div>
                        <div class="bg-white col-span-1 flex flex-col gap-2 justify-between items-center shadow border"
                             style="border-radius: 15px; padding: 15px 30px">
                            <div class="flex gap-2 justify-center items-center">
                                <div class="icon voyager-settings"></div>
                                <span>Client Health</span>
                            </div>
                            <h1 style="font-size: 30px">4</h1>
                        </div>
                        <div class="bg-white col-span-1 flex flex-col gap-2 justify-between items-center shadow border"
                             style="border-radius: 15px; padding: 15px 30px">
                            <div class="flex gap-2 justify-center items-center">
                                <div class="icon voyager-settings"></div>
                                <span>3rd Party Health</span>
                            </div>
                            <h1 style="font-size: 30px">4</h1>
                        </div>
                        <div class="bg-white col-span-1 flex flex-col gap-2 justify-between items-center shadow border"
                             style="border-radius: 15px; padding: 15px 30px">
                            <div class="flex gap-2 justify-center items-center">
                                <div class="icon voyager-settings"></div>
                                <span>Social Health</span>
                            </div>
                            <h1 style="font-size: 30px">4</h1>
                        </div>
                    </div>

                </div>
                <div class="bg-white col-span-5 shadow border" style="border-radius: 15px; padding: 15px 30px">
                    <h4 class="mb-3"><strong>ATTACK CLASS LIKELIHOOD</strong></h4>
                    <div class="grid grid-cols-1 sm:grid-cols-6 gap-4">
                        <div class="bg-white col-span-1 flex flex-col gap-2 justify-between items-center shadow border"
                             style="border-radius: 15px; padding: 15px 30px">
                            <div class="flex gap-2 justify-center items-center">
                                <div class="icon voyager-settings"></div>
                                <span>Business Disruption</span>
                            </div>
                            <h1 style="font-size: 30px">4</h1>
                        </div>
                        <div class="bg-white col-span-1 flex flex-col gap-2 justify-between items-center shadow border"
                             style="border-radius: 15px; padding: 15px 30px">
                            <div class="flex gap-2 justify-center items-center">
                                <div class="icon voyager-settings"></div>
                                <span>Credentials Attack</span>
                            </div>
                            <h1 style="font-size: 30px">4</h1>
                        </div>
                        <div class="bg-white col-span-1 flex flex-col gap-2 justify-between items-center shadow border"
                             style="border-radius: 15px; padding: 15px 30px">
                            <div class="flex gap-2 justify-center items-center">
                                <div class="icon voyager-settings"></div>
                                <span>Website Hijack</span>
                            </div>
                            <h1 style="font-size: 30px">4</h1>
                        </div>
                        <div class="bg-white col-span-1 flex flex-col gap-2 justify-between items-center shadow border"
                             style="border-radius: 15px; padding: 15px 30px">
                            <div class="flex gap-2 justify-center items-center">
                                <div class="icon voyager-settings"></div>
                                <span>Client Session Attack</span>
                            </div>
                            <h1 style="font-size: 30px">4</h1>
                        </div>
                        <div class="bg-white col-span-1 flex flex-col gap-2 justify-between items-center shadow border"
                             style="border-radius: 15px; padding: 15px 30px">
                            <div class="flex gap-2 justify-center items-center">
                                <div class="icon voyager-settings"></div>
                                <span>Data Hijack</span>
                            </div>
                            <h1 style="font-size: 30px">4</h1>
                        </div>
                        <div class="bg-white col-span-1 flex flex-col gap-2 justify-between items-center shadow border"
                             style="border-radius: 15px; padding: 15px 30px">
                            <div class="flex gap-2 justify-center items-center">
                                <div class="icon voyager-settings"></div>
                                <span>Web Initiated Social Attack</span>
                            </div>
                            <h1 style="font-size: 30px">4</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const scores = @json($domain->scoreHistory());

        const options = {
            chart: {
                type: 'line',
                height: 150
            },
            series: [{
                name: 'Score',
                data: scores
            }],
            xaxis: {
                labels: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    show: false
                }
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: false
                    }
                }
            }
        };
        const chart = new ApexCharts(document.querySelector("#score_chart"), options);
        chart.render();
    </script>
@endsection
