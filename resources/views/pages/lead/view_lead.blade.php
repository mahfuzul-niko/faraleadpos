@extends('layouts.app')
@section('body_content')
@php

$lead_source = DB::table('lead_sources')->where('id', optional($data)->source)->first('name');

@endphp
<div class="p-2">
    <div class="block block-rounded">
        <div class="block-content">
            <div class="row" id="lender_body">
                <div class="col-md-9">
                    <!-- loan paid div Start -->
                    <div class="row p-1" id="loan_paid_div">
                    <div class="col-md-12 p-3 shadow rounded">
                            <h4 class="text-light bg-dark p-1"><b>Lead Info</b></h4>
                            <p class="shadow p-2">
                                <b>Name: </b>{{optional($data)->name}}<br />
                                <b>Phone: </b>{{optional($data)->phone}}<br />
                                <b>Email: </b>{{optional($data)->email}}<br />
                                <b>Address: </b>{{optional($data)->address}}<br />
                            </p>
                            <p class="shadow p-2">
                                <b class="text-success">Query: </b>{{optional($data)->lead_query}}<br />
                                <b class="text-success">Lead Created Date: </b>{{date("d M, Y h:i:s A", strtotime(optional($data)->lead_created_date))}}<br />
                                <b class="text-success">Lead Upload Date: </b>{{date("d M, Y h:i:s A", strtotime(optional($data)->created_at))}}<br />
                                <b class="text-success">Upload By: </b>{{optional($data->user_info)->name}}<br />
                                <b class="text-success">Inbox URL: </b> <a target="_blank" href="{{optional($data)->inbox_url}}">{{optional($data)->inbox_url}}</a><br />
                                <b class="text-success">Source: </b>{{optional($lead_source)->name}}<br />
                                <b class="text-success">Summery: </b>{{optional($data)->note}}<br />
                                <b class="text-success">Status: </b>{{optional($data)->status}}<br />
                                
                            </p>
                        </div>
                        <div class="col-md-12 p-3 shadow rounded">
                            <h4 class="text-light bg-dark p-1"><b>Lead Note</b></h4>
                            <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Date Time</th>
                                    <th scope="col">Note</th>
                                    <th scope="col">Added By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data->lead_notes as $item)
                                <tr>
                                    <td>{{date("d M, Y h:i:s A", strtotime(optional($item)->created_at))}}</td>
                                    <td>{{optional($item)->note}}</td>
                                    <td>{{optional($item->user_info)->name}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            </table>
                        </div>
                        

                        
                    </div>
                   
                    
                    
                </div>
                <div class="col-md-3 p-1">
                    <div class="lender_info rounded shadow">
                    <div class="block block-rounded ">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">Others</h3>
                            </div>
                            <div class="block-content text-muted text-justify">
                                <a type="button" href="{{route('admin.set.appiontment', ['id'=>$data->id])}}" class="btn btn-primary btn-rounded btn-lg btn-block mb-2">Set Appiontment</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 p-3 shadow rounded">
                    <h4 class="text-light bg-success p-1"><b>Appiontments</b></h4>
                    <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Appointment Date</th>
                            <th scope="col">Address</th>
                            <th width="50%">Others Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->lead_appointments as $appointment)
                        <tr>
                            <td>{{date("d M, Y h:i:s A", strtotime(optional($appointment)->appiontment_datetime))}}</td>
                            <td>{{optional($appointment)->address}}</td>
                            <td><small>
                                <b class="text-success">Message: </b>{!!optional($appointment)->message!!}<br />
                                <b class="text-success">Note: </b>{!!optional($appointment)->note!!}<br />
                                <b class="text-success">Status: </b>{{optional($appointment)->status}}<br />
                                <b class="text-success">Sent By: </b>{{optional($appointment->user_info)->name}}<br />
                                <b class="text-success">Sent Date: </b>{{date("d M, Y h:i:s A", strtotime(optional($appointment)->created_at))}}<br />
                                <b class="text-danger">Visiting Output: </b>{{optional($appointment)->visiting_output}}<br />
                                <b class="text-danger">Visitor: </b>{{optional($appointment->visitor_info)->name}}<br />
                                
                                
                                </small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
    <!-- END Full Table -->
</div>

@endsection
