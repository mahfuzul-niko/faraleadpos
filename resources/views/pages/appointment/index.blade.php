
@extends('layouts.app')
@section('body_content')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<style>
    tr td{
        font-size: 13px;
    }
</style>
<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
    <form action="javascript:void(0)" method="post" >
            @csrf
            <div class="row p-2">
                <div class="col-md-6"><h4 id="transaction_type_title">All Appointments</h4></div>
                <!-- <div class="col-md-6 text-right row">
                    <div class="form-group col-md-9">
                        <input type="date"  value="{{date('Y-m-d')}}" name="" class="form-control" id="single_day_date">
                    </div>
                    <div class="col-md-3 text-left">
                        <button type="submit"  class="btn btn-primary btn-sm">Print</button>
                    </div>
                </div> -->
            </div>
        </form>
        

        <input type="hidden" name="" id="toggle_yes" value='1'>
        <div class="block-content">
            <div class="table-responsive">
            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>client Info</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>



<!-- appointment Note Modal -->
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
      <form action="{{route('set.visitor.output')}}" method="post">
        @csrf
        <div class="form-group">
            <h4><b class="text-success">Client Name: </b> <span id="lead_note_client_name"></span></h4>
        </div>
        <div class="form-group">
            <label for="example-text-input"><span class="text-danger">*</span>Status</label>
            <select class="form-control" name="status" id="" required>
                <option value="">Select one</option>
                <option value="success">Success</option>
                <option value="waiting">Waiting</option>
                <option value="reschedule">Reschedule</option>
                <option value="cancel after visit">Cancel After Visit</option>
                @if(Auth::user()->type == 'admin')<option class="bg-danger text-light" value="cancel before visit">Cancel Before Visit</option>@endif
            </select>
        </div>
        
        <div class="form-group">
            <label for="example-text-input"><span class="text-danger">*</span>visiting output</label>
            <textarea class="form-control" name="visiting_output" id="" cols="30" rows="4" required></textarea>
            <input type="hidden" name="appointment_id" value="" id="appointment_id">
        </div>

        <div class="block-content block-content-full text-right border-top">
            <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
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

   

});



$(function () {
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('aadmin.all.appointments.data.info') }}",
        columns: [
            {data: 'date_and_time', name: 'date_and_time'},
            {data: 'user_info', name: 'user_info'},
            {data: 'address', name: 'address'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'},
        ],
        "scrollY": "300px",
        "pageLength": 50,
        "ordering": false,
    });
    
  });

  function set_visiting_output(id, name) {
    $('#appointment_id').val(id);
    $('#lead_note_client_name').text(name);
    
}


</script>
@endsection
