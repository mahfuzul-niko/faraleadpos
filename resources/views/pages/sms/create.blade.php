@extends('layouts.app')
@section('title') Send SMS @endsection
@section('body_content')
<style>
    .my-custom-scrollbar {
        position: relative;
        height: 280px;
        overflow: auto;
    }
    tr td{
        font-size: 13px;
    }
</style>
<input type="hidden" name="" id="toggle_yes" value='1'>
<div class="content p-0">
	<div class="card">
		<div class="card-header bg-primary text-light h4">
				<ul class="p-0 mb-0">
					<li class="d-flex justify-content-between align-items-center">
						<strong>
							<i class="nav-main-link-icon si si-layers"></i>
							@yield('title')
						</strong>
						<span>
							<a href="{{ route('sms.index') }}" class="btn btn-primary">
								<i class="nav-main-link-icon si si-notebook"></i>
								SMS List
							</a>
						</span>
					</li>
				</ul>
		</div>
		<div class="card-body">

			<div class="shadow rounded p-3">
                    {{-- Start --}}
                    <div class="row">
                        <div class="col-md-6 p-2">
                            <div class="card rounded" id="sms_body">
                                <form method="POST" action="javascript:void(0)" id="send_sms_form" class="">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-wrapper-scroll-y my-custom-scrollbar shadow rounded p-2">
                                                <table id="mainTable" class="table table-sm table-hover ">
                                                    <thead class="thead-light">
                                                        <tr class="">
                                                            <th style="padding: 10px 7px;" width="50%">Name</th>
                                                            <th style="padding: 10px 7px;">Phone Number</th>
                                                            <th style="padding: 10px 7px;" width="5%" class="text-center">action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="demo" class="demo">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="col-md-12 px-3">
                                            <div class="form-group mt-3">
                                                <label class="ml-2">SMS Text @required </label>
                                                <textarea class="form-control" id="" name="sms_text" required="" rows="5" spellcheck="true"></textarea>
                                             </div>
                                        </div>
                                        <div class="form-check text-end my-2">

                                                <button type="submit" id="send_sms_form_submit_button" class="btn btn-primary btn-rounded"><i class="btn-icon-prepend" data-feather="navigation"></i> Send</button>

                                                <button type="button" id="send_sms_form_sending_button" style="display: none;" class="btn btn-primary btn-rounded">Sending....</button>
                                          </div>
                                    </div>

                                </form>
                            </div>

                            <div class="card rounded" id="sms_output" style="display: none;">
                                <div class="table-wrapper-scroll-y my-custom-scrollbar shadow rounded p-2">
                                    <span class="text-success fw-bold">SMS Output =></span>
                                    <table id="sms_output_tabel" class="table table-sm table-hover ">
                                        <thead class="bg-primary">
                                            <tr class="">
                                                <th style="padding: 10px 7px; color: #ffffff;">Phone Number</th>
                                                <th style="padding: 10px 7px; color: #ffffff;" width="60%">Info</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sms_output_tabel_body" class="">
                                            <tr>
                                                <td>phone</td>
                                                <td><span class="badge badge-success">Success</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center p-3 mt-4">
                                    <a type="button" class="btn btn-primary my-3 btn-rounded" href="{{route('sms.create')}}">Refresh</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 p-2">
                            <div class="card rounded shadow rounded">
                                <div class="tab-content border border-top-0 p-3" id="myTabContent">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                        <div class="form-group mb-2">
                                            <label for="">Name (Optional)</label>
                                            <input type="text" class="form-control" id="custom_contact_name" placeholder="Enter Client Name">
                                          </div>
                                          <div class="form-group mb-2">
                                            <label for="">Phone Number @required </label>
                                            <input type="number" class="form-control" id="custom_contact_phone" placeholder="Ex: 01766996853">
                                          </div>
                                          <div class="form-check text-end">
                                            <button type="button" onclick="add_custom_contact()" class="btn btn-primary btn-sm">Add</button>
                                          </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End --}}
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
    $(document).ready(function () {

        $("#mainTable").on('click', '.btnSelect', function() {
            var currentRow = $(this).closest("tr");
            $(this).parents("tr").remove();
        });

    });

function add_custom_contact() {
    var name = $('#custom_contact_name').val();
    var phone = $('#custom_contact_phone').val();
    if(phone.length == 11) {
       add_to_contact_store(name, phone);
    }
    else {
        Toastify({
            text: "Phone number must be 11 digits!",
            backgroundColor: "linear-gradient(to right, #6E32CF, #FFA300)",
            className: "error",
        }).showToast();
        var play = document.getElementById('error').play();
    }

}
function add_to_contact_store(name, phone) {
    if(phone.length == 10) {
        phone = 0+phone;
    }
    var check_phone = $('#'+phone).val();
    if(phone.length == 11 || phone.length == 10) {
        if(check_phone == null) {
            $('#demo').prepend('<tr><td>'+name+'</td><td><input type="hidden" name="phone[]" value="'+phone+'" id="'+phone+'">'+phone+'</td><td class="text-center"><button type="button" id="remove" name="remove" class="btn btn-outline-danger btn-sm remove btnSelect text-center">X</button></td></tr>');
            document.getElementById('success').play();

        }
        else {
            error('This Contact is already exist!');
        }
    }
    else {
        error('Phone number must be 10 or 11 digits!');
    }
}
$('#send_sms_form_submit_button').click(function(e){
    if (document.getElementById("send_sms_form").checkValidity()) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{route('sms.send')}}",
            method: 'post',
            data: $('#send_sms_form').serialize(),
            beforeSend: function() {
                $('#send_sms_form_sending_button').show();
                $('#send_sms_form_submit_button').hide();
            },
            success: function(response){
                if(response['status'] == 'yes') {
                    $('#sms_output').show();
                    $('#sms_body').hide();
                    $('#sms_output_tabel_body').html(response.output);
                    $('#send_sms_form_senging_button').hide();
                    $('#send_sms_form_submit_button').show();
                    success(response.success);
                }
                else {
                    error(response.reason);
                    $('#send_sms_form_senging_button').hide();
                    $('#send_sms_form_submit_button').show();
                }
            },
        });
    }
    else {
        error('Error Occoured!');
        $('#send_sms_form_sending_button').hide();
        $('#send_sms_form_submit_button').show();
    }
});
</script>
@endsection
