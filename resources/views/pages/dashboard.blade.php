@extends('layouts.app')
@section('title') Dashboard @endsection
@section('body_content')

<div class="content">
    @if(Auth::user()->type=='admin')
    <div class="row">
        <ul class="nav nav-tabs">
            @php $crm = App\Models\User::where(['type'=>'crm', 'is_active'=>1])->get(['id','name']); @endphp
            @foreach($crm as $key => $res)
          <li class="nav-item">
            <a class="nav-link <?php if(isset(request()->user_id)){ if(request()->user_id==$res->id){ ?> active <?php }}else{ if($key==0){ ?>  <?php } } ?>" href="{{ route('index',['user_id'=>$res->id]) }}">{{ $res->name }}</a>
          </li>
          @endforeach

        </ul>
    </div>
    @endif
    <div class="row">
        <div class="col-md-8">

            <div class="row shadow rounded p-2 border">
                <div class="col-md-12"><h4><b>Today's {{date("d M Y")}}</b></h4></div>
                <div class="col-12 col-md-4">
                    <div class="block block-rounded d-flex flex-column border border-primary">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                            <dl class="mb-0">
                                <dt class="font-size-h4 font-w700">{{$todays_leads->count('id')}}</dt>
                                <dd class="text-muted mb-0"><a href="{{ route('admin.all.lead.info', ['search' => '', 'status' => 'All', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'), 'Submit' => 'Submit']) }}">Today's Lead</a></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="block block-rounded d-flex flex-column border border-primary">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                            <dl class="mb-0">
                                <dt class="font-size-h4 font-w700">{{$todays_sale->count('id')}}</dt>
                                <dd class="text-muted mb-0"><a href="{{route('admin.all.lead.info')}}">Today's Sale</a></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="block block-rounded d-flex flex-column border border-primary">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                            <dl class="mb-0">
                                <dt class="font-size-h4 font-w700">{{$todays_install->count('id')}}</dt>
                                <dd class="text-muted mb-0"><a href="{{route('admin.all.lead.info')}}">Today's Install</a></dd>
                            </dl>
                        </div>
                    </div>
                </div>

            </div>

            <br>
             <!-- Total Data -->
            <div class="row shadow rounded p-2 border">
                <div class="col-md-12"><h4><b>Total</b></h4></div>
                <div class="col-12 col-md-4">
                    <div class="block block-rounded d-flex flex-column border border-primary">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                            <dl class="mb-0">
                                <dt class="font-size-h4 font-w700">{{$all_leads->count('id')}}</dt>
                                <dd class="text-muted mb-0"><a href="{{route('admin.all.lead.info')}}">Total Leads</a></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="block block-rounded d-flex flex-column border border-primary">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                            <dl class="mb-0">
                                <dt class="font-size-h4 font-w700">{{$all_sales->count('id')}}</dt>
                                <dd class="text-muted mb-0"><a href="{{route('admin.all.lead.info')}}">Total Sale</a></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="block block-rounded d-flex flex-column border border-primary">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                            <dl class="mb-0">
                                <dt class="font-size-h4 font-w700">{{$all_install->count('id')}}</dt>
                                <dd class="text-muted mb-0"><a href="{{route('admin.all.lead.info')}}">Total Install</a></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                @if(Auth::user()->type=='admin')
                <div class="col-12 col-md-4">
                    <div class="block block-rounded d-flex flex-column border border-primary">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                            <dl class="mb-0">
                                <dt class="font-size-h4 font-w700">{{$all_sales->where('status', 'Bounce')->count('id')}}</dt>
                                <dd class="text-muted mb-0"><a href="{{route('admin.all.lead.info')}}">Total Bounce</a></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                @endif

            </div>
            <br>
             <!-- Monthly Data -->
            <div class="row shadow rounded p-2 border">
                <div class="col-md-12"><h4><b>Monthly Data</b></h4></div>
                <div class="col-12 col-md-12 shadow rounded bg-light">
                    <table class="table table-striped table-bordered m-2 table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Year</th>
                          <th scope="col" colspan="2">Data</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php
                            $startDate = new \DateTime('2024-01-01');
                            $currentDate = new \DateTime();
                        @endphp

                        @foreach (new DatePeriod($startDate, new DateInterval('P1M'), $currentDate) as $date)
                        <?php
                            if(isset(request()->user_id)){
                                $user_id = request()->user_id;
                            }else{
                                $user_id = Auth::user()->id;
                            }

                            $total_lead_count = DB::table('lead_infos')->whereMonth('created_at', $date->format('m'))->whereYear('created_at', $date->format('Y'));
                            if(Auth::user()->type=='crm' || isset(request()->user_id)){
                             $total_lead_count = $total_lead_count->where('assigned_to', '=', $user_id);
                            }
                            $total_lead_count = $total_lead_count->count('id');

                            $total_sale_count = DB::table('sales')->whereMonth('created_at', $date->format('m'))->whereYear('created_at', $date->format('Y'));
                            if(Auth::user()->type=='crm' || isset(request()->user_id)){
                             $total_sale_count = $total_sale_count->where('saller_id', '=', $user_id);
                            }
                            $total_sale_count = $total_sale_count->count('id');

                            $total_install_count = DB::table('sales');
                            if(Auth::user()->type=='crm' || isset(request()->user_id)){
                                $total_install_count = $total_install_count->where('installer_id','=',$user_id);
                            }else{
                                $total_install_count = $total_install_count->where('installer_id','!=','');
                            }
                            $total_install_count = $total_install_count->whereMonth('created_at', $date->format('m'))
                            ->whereYear('created_at', $date->format('Y'))->count('id');
                            $percentage = ($total_lead_count != 0) ? round(($total_sale_count / $total_lead_count) * 100) : 0;

                        ?>
                        <tr>
                          <td style="vertical-align:middle;">
                              <h4 class="mb-0"><b>{{ $date->format('F Y') }}</b></h4>
                          </td>
                          <td style="vertical-align:middle;">
                              <div class="d-flex justify-content-between">
                                  <span>
                                      <b>Total Leads: </b> {{ $total_lead_count }}<br>
                                      <b>Total Sale: </b> {{ $total_sale_count }}<br>
                                      <b>Total Install: </b> {{ $total_install_count }}
                                  </span>
                              </div>
                          </td>
                          <td style="vertical-align:middle;"><h1 class="mb-0 text-success"><b>{{$percentage}}%</b></h1></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>

                </div>
            </div>
            <br>


        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header text-light bg-dark text-center" style="padding: 5px; font-weight: bold;">
                    <h2 style="font-weight: bold; color: #fff;">Installation</h2>
                    <p id="valueP">Upcoming Installation</p>
                </div>
                <div class="card-body">
                    @php($i = 1)
                    @while($i < 8)
                        @php( $date = date('Y-m-d', strtotime('+'.$i.' days')))
                        @php( $installation_count = DB::table('sales')->where('installer_id',"!=","")->where('installation_date', 'like', '%'.$date.'%')->count('id'))
                        <div class="row border-bottom mb-2">
                            <div class="col-md-6 col-6 text-center"><a href="{{route('admin.all.lead.info')}}">{{date('d M Y', strtotime($date))}}</a></div>
                            <div class="col-md-6 col-6 text-center"><h6>{{$installation_count+0}}</h6></div>
                        </div>
                        @php($i++)
                    @endwhile
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
                <!--<div class="col-xl-12 d-flex flex-column">-->
                <!--    <div class="block block-rounded flex-grow-1 d-flex flex-column">-->
                <!--        <div class="block-header block-header-default">-->
                <!--            <h3 class="block-title">Monthley Income & Expense Of 2022</h3>-->
                <!--        </div>-->
                <!--        <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">-->
                <!--            <canvas class="js-chartjs-earnings"></canvas>-->

                <!--             <canvas id="canvas" height="280" width="600"></canvas>-->
                <!--        </div>-->

                <!--    </div>-->
                <!--</div>-->
            </div>
</div>
@endsection
