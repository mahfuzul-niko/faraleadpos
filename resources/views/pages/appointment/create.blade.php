@extends('layouts.app')
@section('body_content')
<link href='https://cdn.jsdelivr.net/npm/froala-editor@4.0.8/css/froala_editor.pkgd.min.css' rel='stylesheet' type='text/css' />

<style>
    .fr-wrapper div a{
        display:none !important;
    }
    #fr-logo {
        display: none !important;
    }
</style>

<div class="p-2">
    <div class="block block-rounded">
        <div class="block-content">
            <div class="row" id="lender_body">
                <div class="col-md-4">
                    <!-- loan paid div Start -->
                    <div class="row p-0" id="loan_paid_div">
                    <div class="col-md-12 shadow rounded">
                            <h4 class="text-light bg-dark p-1"><b>Client Info</b></h4>
                            <p class="shadow p-2">
                                <b>Name: </b>{{optional($data)->name}}<br />
                                <b>Phone: </b>{{optional($data)->phone}}<br />
                                <b>Email: </b>{{optional($data)->email}}<br />
                                <b>Address: </b>{{optional($data)->address}}<br />
                                <b class="text-success">Query: </b>{{optional($data)->lead_query}}<br />
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 p-1">
                    <div class="lender_info rounded shadow">
                    <div class="block block-rounded ">
                            <form method="POST" class="p-3" action="{{route('admin.store.appointment')}}">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for=""><span class="text-danger">*</span>Apponment Date & Time</label>
                                        <input type="datetime-local" class="form-control" onchange="change_appointment_date_time()" name="appiontment_date" id="appiontment_datetime" required>
                                        <input type="hidden" name="lead_id" value="{{optional($data)->id}}">
                                        @error('appiontment_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for=""><span class="text-danger">*</span>Address</label>
                                        <input type="text" class="form-control" value="{{optional($data)->address}}" name="address" >
                                        @error('address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label for=""><span class="text-danger">*</span>Message</label>
                                        <textarea id="froala-editor" class="form-control" name="message" id="" cols="30" rows="4">Dear {{optional($data)->name}}, Our IT Executive From FARA IT LTD. will meet with you on <span id="message_date_time">___</span> for your website/software service. For any kind of question please call +8801780504501</textarea>
                                        @error('message')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="">Note (optional)</label>
                                        <textarea class="form-control" name="note" id="" cols="30" rows="1"></textarea>
                                        @error('note')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="block-content text-muted text-justify">
                                    <button type="submit"  class="btn btn-success btn-rounded btn-lg btn-block mb-2">Set Appiontment</button>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
    <!-- END Full Table -->
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!--<script type='text/javascript' src='https://cdn.jsdelivr.net/npm/froala-editor@4.0.8/js/froala_editor.pkgd.min.js'></script>-->
<script type='text/javascript' src='{{asset('js/froala_editor.pkgd.min.js')}}'></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
  new FroalaEditor('textarea#froala-editor')

  function change_appointment_date_time() {
      var date_time = $('#appiontment_datetime').val();
     // alert(date_time);
     
     var updated_date = moment(date_time).format('DD MMM YYYY, h:mm a');
      $('#message_date_time').text(updated_date);
  }

</script>
@endsection
