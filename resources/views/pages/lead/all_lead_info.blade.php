
@extends('layouts.app')
@section('title') All Leads Info @endsection
@section('body_content')
@php
    $status = [
        ['id' => 0, 'name' => 'All'],
        ['id' => 1, 'name' => 'New'],
        ['id' => 2, 'name' => 'Positive'],
        ['id' => 3, 'name' => 'Sale'],
        ['id' => 4, 'name' => 'Cancel'],
        ['id' => 5, 'name' => 'Flowup'],
    ];

    $crm = App\Models\User::where(['type'=>'crm', 'is_active'=>1])->get(['id','name']);

@endphp
<style>
    tr td{
        font-size: 13px;
    }
</style>
<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <input type="hidden" name="" id="toggle_yes" value='1'>
        <div class="block-content">
            <div class=" rounded p-2">
                <form method="get" action="{{route('admin.all.lead.info')}}">
                <div class="row">
                    <div class="form-group  @if(Auth::user()->type=='admin') col-md-2 @else col-md-4 @endif">
                        <label for="">Search</label>
                        <input type="text" class="form-control rounded-pill" value="{{ request()->search }}" name="search" placeholder="name, phone, address">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="">Status</label>
						<select class="form-control rounded-pill" name="status" id="lead_status">
                            @foreach($status as $key => $res)
                            <option {{ (isset(request()->status) && request()->status == $res['name']) || (!isset(request()->status) && $key == 0) ? 'selected' : '' }} value="{{ $res['name'] }}">{{ $res['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(Auth::user()->type=='admin')
                    <div class="form-group col-md-2">
                        <label for="">Assigned To</label>
						<select class="form-control rounded-pill" name="assigned_to" id="lead_status">
                            <option {{ (!isset(request()->assigned_to)) ? 'selected' : '' }} value="">Select</option>
                            @foreach($crm as $key => $res)
                            <option {{ (isset(request()->assigned_to) && request()->assigned_to == $res->id) ? 'selected' : '' }} value="{{ $res->id }}">{{ $res->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group col-md-2">
                        <label for="">Start</label>
                        <input type="date" class="form-control rounded-pill" value="{{ request()->start_date }}" name="start_date">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="">End</label>
                        <input type="date" class="form-control rounded-pill" value="{{ request()->end_date }}" name="end_date">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="">&nbsp;</label>
                        <input type="submit" class="form-control rounded-pill" value="Submit" name="Submit">
                    </div>
                </div>
                </form>
            </div>
            <div class="row p-2">
                <div class="col-md-6">
                    <h4 id="transaction_type_title" class=""> @yield('title') </h4>
                </div>
            </div>
            <div class="table-responsive">
            <table class="table table-bordered data-table" id="lead_table">
                <thead>
                    <tr>
                        <th>SN.</th>
                        <th width="10%">Date</th>
                        <th width="35%">Clients & Others Info</th>
                        <th>Assigned To</th>
                        <th width="30%">Last Note</th>
                        <th>Status</th>
                        <th width="20%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leads as $key=>$lead)
                    <tr class="@if(\Carbon\Carbon::parse(optional($lead)->created_at)->format('Y-m-d')==date('Y-m-d'))  @endif">
                        <td>{{$key + $leads->firstItem()}}</td>
                        <td>{{date("d-m-Y", strtotime($lead->created_at))}}</td>
                        <td>
                            <p>
                                <b>Name: </b>{{optional($lead)->name}}<br />
                                <b>Mobile: </b>{{optional($lead)->phone}}<br />
                                <b>Address: </b>{{$lead->address}}<br />
                                <b>Source: </b>{{$lead->lead_source->name}}<br>
                                {{--<b>Lead Received Date: </b>{{date("d-m-Y h:i:s a", strtotime($lead->lead_created_date))}}<br />--}}
                                <b>Latest Note Date: </b>
                                @if(optional($lead->last_note)->created_at <> '')
                                    {{date("d-m-Y h:i:s a", strtotime(optional($lead->last_note)->created_at))}}
                                @else Not Set
                                @endif<br /></p></td>
                        <td>{{optional($lead->assigned)->name}}</td>
                        <td class="LN{{optional($lead)->id}}">
                            <span class="lead-note" id="last_note_{{optional($lead)->id}}">{{optional($lead->last_note)->note}}</span><br>
                            <span class="lead-note" id="last_note_date_{{optional($lead)->id}}">
                                @if(optional($lead->last_note)->created_at <> '')
                                    {{date("d-m-Y h:i:s a", strtotime(optional($lead->last_note)->created_at))}}
                                @endif
                            </span>
                        </td>
                        <td class="LS{{optional($lead)->id}}">
                            <span class="lead-status" id="last_status_{{optional($lead)->id}}">

                                    <span
                                    @if(optional($lead)->status == 'New')
                                        class="badge badge-danger"
                                    @else
                                        class="badge badge-primary"
                                    @endif
                                    >{{optional($lead)->status}}</span>

                            </span>
                        </td>
                        <td>
                            <button data-toggle="modal" onclick="set_lead_note('{{$lead->id}}', '{{$lead->name}}', '{{$lead->phone}}', '{{$lead->address}}')"
                        data-target="#lead_note_modal" title="Set Note" class="btn btn-danger btn-sm">
                            <i class="fab fa-neos"></i></button>
                            @if($user->checkPermission('lead.update'))
                            <a href="{{ route('admin.edit.lead', ['id'=>$lead->id]) }}" class="btn btn-primary btn-sm btn-rounded">Edit</a>
                            @endif
                            @if($user->checkPermission('lead.view'))
                            <a title="View" target="_blank" href="{{route('admin.view.lead', ['id'=>$lead->id])}}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                            @endif
                            @if($user->checkPermission('lead.status.update'))
                            <button data-toggle="modal" onclick="set_lead_status('{{$lead->id}}', '{{$lead->name}}', '{{$lead->phone}}', '{{$lead->address}}', '{{$lead->status}}')" data-target="#change_status_modal" class="btn btn-success btn-sm">Status</button>
                            @endif
                            {{--<a target="_blank" type="button" href="{{route('admin.set.appiontment', ['id'=>$lead->id])}}" class="btn btn-dark btn-sm">App</a>--}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <div class="row">
                <div class="d-flex justify-content-center col-md-7">
                    {!! $leads->appends([
                    'status' => request()->status??'New',
                    'assigned_to' => request()->assigned_to
                    ])->links() !!}
                </div>
                <div class="d-flex justify-content-center col-md-5">
                    <p class="text-bold">Showing {{$leads->firstItem()}} to {{$leads->lastItem()}} of {{$leads->total()}} results</p>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Lead Status Modal -->
<div class="modal fade" id="change_status_modal" tabindex="-1" role="dialog" aria-labelledby="status_modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title text-light" id="lead_status_modalLabel">Set Status</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="javascript:void(0)" id="set_status_form" method="post">
        @csrf
        <div class="form-group">
            <h4><b class="text-success">Client Name: </b> <span id="lead_status_client_name"></span></h4>
            <h4><b class="text-success">Client Phone: </b> <span id="lead_status_client_phone"></span></h4>
            <p><b class="text-success">Client Address: </b> <span id="lead_status_client_address"></span></p>
        </div>

        <div class="form-group">
            <label for="example-text-input">Status</label>
            <select class="form-control" name="status" id="lead_status">
                @foreach($status as $key => $res)
                <option value="{{ $res['name'] }}">{{ $res['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="block-content block-content-full text-right border-top">
            <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">Close</button>
            <input type="hidden" name="lead_id_for_lead_status" value="" id="lead_id_for_lead_status">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        <div class="form-group" id="set_status_note_loading"></div>
        </form>
      </div>

    </div>
  </div>
</div>

<!-- Lead Note Modal -->
<div class="modal fade" id="lead_note_modal" tabindex="-1" role="dialog" aria-labelledby="lead_note_modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title text-light" id="lead_note_modalLabel">Set Note</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="javascript:void(0)" id="set_note_form" method="post">
        @csrf
        <div class="form-group">
            <h4><b class="text-success">Client Name: </b> <span id="lead_note_client_name"></span></h4>
            <h4><b class="text-success">Client Phone: </b> <span id="lead_note_client_phone"></span></h4>
            <p><b class="text-success">Client Address: </b> <span id="lead_note_client_address"></span></p>
        </div>

        <div class="form-group">
            <label for="example-text-input">Status</label>
            <select class="form-control" name="status" id="lead_note_status">
              <option value="">-- Select --</option>

              <option value="Number Off">Number Off</option>
              <option value="Call Not Received">Call Not Received</option>
              <option value="Call cut / busy">Call cut / busy</option>

              <option value="Positive">Positive</option>
              <option value="Sale">Sale</option>
              <option value="Cancel">Cancel</option>

              <option value="Transfer To Web Team">Transfer To Web Team</option>
              <option value="Transfer To School Team">Transfer To School Team</option>
              <option value="Others">Others</option>

              {{--<option value="Decision Pending - good">Decision Pending - good</option>--}}
              {{--<option value="Decision Pending - bad">Decision Pending - bad</option>--}}
              {{--<option value="Not interested">Not interested</option>--}}
              {{--<option value="Digital marketing / others service">Digital marketing / others service</option>--}}
              {{--<option value="Software service">Software service</option>--}}
              {{--<option value="Bad">Bad</option>--}}
              {{--<option value="Meeting">Meeting</option>--}}

            </select>
        </div>

        <div class="form-group">
            <label for="example-text-input">Note</label>
            <textarea class="form-control" name="note" id="note" cols="30" rows="4"></textarea>
            <input type="hidden" name="lead_id_for_lead_note" value="" id="lead_id_for_lead_note">
        </div>
        <div class="block-content block-content-full text-right border-top">
            <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        <div class="form-group" id="set_lead_note_loading"></div>
        </form>
      </div>

    </div>
  </div>
</div>


<!-- END Page Content -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">

$(document).ready(function () {


    var single_day_date = $('#single_day_date').val();
    //get_data('cash_and_banks');

    var toggle_yes = $('#toggle_yes').val();
    if (typeof (toggle_yes) != 'undefined' && toggle_yes != null) {
        SidebarColpase();
    }

    lead_body_color();


});

$('#single_day_date').change(function() {
    var date = $(this).val();
    console.log(date)
});

function lead_body_color() {
    var spans = document.getElementsByClassName("lead-note");

    for (var i = 0; i < spans.length; i++) {
        let note = spans[i].innerText;
        let className = spans[i].parentNode.className;
        check_lead_note(note, className);
    }
}

function check_lead_note(note, className) {
    if((note.indexOf('Number Off') !== -1) || (note.indexOf('Call Not Received') !== -1) || (note.indexOf('Call cut / busy') !== -1)) {
        //$('.'+className).addClass('bg-warning text-light');
        $('.'+className).css({
          'background-color': '#E5AE67',
          'color': '#ffffff'
        });
    }
    else if((note.indexOf('Positive') !== -1) || (note.indexOf('Sale') !== -1) || (note.indexOf('Decision Pending - good') !== -1)) {
        //$('.'+className).addClass('bg-success text-light');
        $('.'+className).css({
          'background-color': '#30C78D',
          'color': '#ffffff'
        });
    }
    else if((note.indexOf('Cancel') !== -1) || (note.indexOf('Not interested') !== -1) || (note.indexOf('Decision Pending - bad') !== -1) || (note.indexOf('Transfer To School') !== -1) || (note.indexOf('Transfer To Web') !== -1)) {
        // $('.'+className).addClass('bg-danger text-light'); Not interested
        $('.'+className).css({
          'background-color': '#E56767',
          'color': '#ffffff'
        });
    }

}
function set_lead_status(id, name, phone, address, status) {
    $('#lead_id_for_lead_status').val(id);
    $('#lead_status_client_name').text(name);
    $('#lead_status_client_phone').text(phone);
    $('#lead_status_client_address').text(address);

    var selectElement = document.getElementById("lead_status");
    for (var i = 0; i < selectElement.options.length; i++) {
        if (selectElement.options[i].value === status) {
            selectElement.options[i].selected = true;
            break;
        }
    }
}
function set_lead_note(id, name, phone, address) {
    $('#lead_id_for_lead_note').val(id);
    $('#lead_note_client_name').text(name);
    $('#lead_note_client_phone').text(phone);
    $('#lead_note_client_address').text(address);
    $('#note').val('');
    var selectElement = document.getElementById("lead_note_status");
    for (var i = 0; i < selectElement.options.length; i++) {
        if (selectElement.options[i].value != '') {
            selectElement.options[i].selected = false;
            break;
        }
    }
}

$(document).ready(function (e) {
    $('#set_note_form').on('submit',(function(e) {
        if (document.getElementById("set_note_form").checkValidity(e)) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type:'POST',
                url: "{{ route('store.lead.note.by.ajax') }}",
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#set_lead_note_loading').html('<div style="padding: 10px; text-align: center;">Submitting...<div>');
                },
                success:function(response){
                    if(response['status'] == 'yes') {
                        $('#last_note_'+response['id']).html('<span>'+response['note']+'</span>');
                        $('#last_note_date_'+response['id']).html('<span>'+response['date']+'</span>');
                        let parentClassName = 'LN'+response.id;
                        check_lead_note(response.note, parentClassName);
                        $('#note').val('');
                        Toastify({
                            text: 'Note Set Successfully.',
                            backgroundColor: "linear-gradient(to right, #3F8C14, #3F8C14)",
                            className: "error",
                        }).showToast();
                        var play = document.getElementById('success').play();
                    }
                    else {
                         Toastify({
                            text: response['reason'],
                            backgroundColor: "linear-gradient(to right, #F50057, #2F2E41)",
                            className: "error",
                        }).showToast();
                        var play = document.getElementById('error').play();
                    }
                    $('#set_lead_note_loading').html('');
                },
                error: function(data){
                    //console.log(data);
                    $('#set_lead_note_loading').html('');
                }
            });
        }
        else {
            Toastify({
                text: "Error Occoured!",
                backgroundColor: "linear-gradient(to right, #F50057, #2F2E41)",
                className: "error",
            }).showToast();
            var play = document.getElementById('error').play();
        }

    }));
});


$(document).ready(function (e) {
    $('#set_status_form').on('submit',(function(e) {
        if (document.getElementById("set_status_form").checkValidity(e)) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type:'POST',
                url: "{{ route('store.lead.status.by.ajax') }}",
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#set_lead_status_loading').html('<div style="padding: 10px; text-align: center;">Updatting...<div>');
                },
                success:function(response){
                    if(response['status'] == 'yes') {
                        location.reload();
                        $('#last_status_'+response['id']).html('<span>'+response['note']+'</span>');
                        let parentClassName = 'LS'+response.id;
                        //check_lead_status(response.note, parentClassName);
                        Toastify({
                            text: 'Status Successfully.',
                            backgroundColor: "linear-gradient(to right, #3F8C14, #3F8C14)",
                            className: "error",
                        }).showToast();
                        var play = document.getElementById('success').play();
                    }
                    else {
                         Toastify({
                            text: response['reason'],
                            backgroundColor: "linear-gradient(to right, #F50057, #2F2E41)",
                            className: "error",
                        }).showToast();
                        var play = document.getElementById('error').play();
                    }
                    $('#set_lead_status_loading').html('');
                },
                error: function(data){
                    //console.log(data);
                    $('#set_lead_status_loading').html('');
                }
            });
        }
        else {
            Toastify({
                text: "Error Occoured!",
                backgroundColor: "linear-gradient(to right, #F50057, #2F2E41)",
                className: "error",
            }).showToast();
            var play = document.getElementById('error').play();
        }

    }));
});



</script>
@endsection
