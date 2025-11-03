@extends('layouts.app')
@section('title') @if($sale) Edit Sale Info @else Add New Sale @endif @endsection
@section('body_content')
@php
    $last = App\Models\Sale::orderBy('id', 'desc')->select('file_no')->first();
@endphp
<div class="content p-0">
    <div class="card">
        <div class="card-header bg-primary text-light h4">
			<ul class="p-0 mb-0">
				<li class="d-flex justify-content-between align-items-center">
					<strong>
						<i class="nav-main-link-icon si si-notebook"></i>
						@yield('title')
					</strong>
					<span>
						<a href="{{ route('sale.index') }}" class="btn btn-primary">
							<i class="nav-main-link-icon si si-layers"></i>
							Sale List
						</a>
					</span>
				</li>
			</ul>
		</div>
        <div class="card-body">
            <div class="shadow rounded p-3">
            <form method="POST" action="@if($sale) {{route('sale.update', $sale->id)}} @else {{route('sale.store')}} @endif">
                    @csrf
					@if($sale) @method('PUT') @endif
					<input type="hidden" name="user_id" value="{{Auth::user()->id}}" >
					<div class="row mb-2">

						<div class="form-group col-md-3">
							<label for="">File No. @required </label>
							<input type="number" class="form-control" name="file_no" value="{{ optional($sale)->file_no?? $last->file_no + 1 }}" required>
							@error('file_no')
								<span class="text-danger">{{ $message }}</span>
							@enderror
						</div>
						<div class="form-group col-md-3">
							<label for=""> Date </label>
							<input type="date" class="form-control" name="sale_date" value="{{ optional($sale)->sale_date??date('Y-m-d') }}" >
							@error('sale_date')
								<span class="text-danger">{{ $message }}</span>
							@enderror
						</div>
						@if(Auth::user()->type=='crm')
						    <input type="hidden" name="saller_id" value="{{ Auth::user()->id }}">
						@else
    						<div class="form-group col-md-3">
    							<label for="">Sale By</label>
    							<select name="saller_id" class="form-control" id="">
                                    @foreach($users as $res)
                                    <option @if(optional($sale)->saller_id===$res->id) selected @endif value="{{$res->id}}">{{$res->name}}</option>
                                    @endforeach
                                </select>
    							@error('saller_id')
    								<span class="text-danger">{{ $message }}</span>
    							@enderror
    						</div>
						@endif


					</div>

                    <div class="row mb-2"><!--Client-->

						<div class="form-group col-md-3">
							<label for="">Client Name @required </label>
							<input type="text" class="form-control" name="name" value="{{ optional($sale)->name }}" required>
							@error('name')
								<span class="text-danger">{{ $message }}</span>
							@enderror
						</div>
                        <div class="form-group col-md-3">
                            <label for="">Client Contact No. @required </label>
                            <input type="text" class="form-control" name="mobile" value="{{ optional($sale)->mobile }}" required>
                            @error('mobile')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>



						<div class="form-group col-md-12">
                            <label for="">Address @required </label>
                            <textarea class="form-control" name="address" id="" cols="30" rows="2" required>{{ optional($sale)->address }}</textarea>
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
						<div class="form-group col-md-3">
                            <label for="">Bill Type</label>
							<select name="bill_type" class="form-control" id="">
								<option @if(optional($sale)->bill_type=='Monthly') selected @endif >Monthly</option>
								<option @if(optional($sale)->bill_type=='Yearly') selected @endif >Yearly</option>
							</select>

                            @error('bill_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group col-md-3">
                            <label for=""> Amount (Monthly/Yearly) @required </label>
                            <input type="number" class="form-control" name="bill_amount" value="{{ optional($sale)->bill_amount }}" required>
                            @error('bill_amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for=""> Installation Charge @required </label>
                            <input type="number" id="installation_charge" class="form-control" name="installation_charge" value="{{ optional($sale)->installation_charge }}" oninput="calculateDue()" required>
                            @error('installation_charge')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
						<div class="form-group col-md-3">
							<label for="">Installation Date </label>
							<input type="date" class="form-control" name="installation_date" value="{{ optional($sale)->installation_date }}" >
							@error('installation_date')
								<span class="text-danger">{{ $message }}</span>
							@enderror
						</div>
						<div class="form-group col-md-3">
							<label for="">Install By</label>
							<select name="installer_id" class="form-control" id="">
                                @foreach($users as $res)
                                <option @if(optional($sale)->installer_id===$res->id) selected @endif value="{{$res->id}}">{{$res->name}}</option>
                                @endforeach
                            </select>

							@error('installer_id')
								<span class="text-danger">{{ $message }}</span>
							@enderror
						</div>
						<div class="form-group col-md-3">
                            <label for=""> Advance @required </label>
                            <input type="number" id="advanced" class="form-control" name="advance" value="{{ optional($sale)->advance }}" oninput="calculateDue()" required>
                            @error('advance')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
						<div class="form-group col-md-3">
                            <label for=""> Due @required </label>
                            <input type="number" id="due" class="form-control" name="due" value="{{ optional($sale)->due }}" required>
                            @error('due')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for=""> Status</label>
                            <select name="status" class="form-control" id="" required>
								<option @if(optional($sale)->status=='Sold') selected @endif >Sold</option>
								<option @if(optional($sale)->status=='Installed') selected @endif >Installed</option>
								<option @if(optional($sale)->status=='Cancel') selected @endif >Cancel</option>
							</select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12">
                            <label for="">Note (optional)</label>
                            <textarea class="form-control" name="note" id="" cols="30" rows="4">{{ optional($sale)->note }}</textarea>
                            @error('note')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group text-right">
						<button @if($sale) id="updateSaleBtn" @else id="addSaleBtn" @endif type="submit" class="btn btn-primary">
							<i class="nav-main-link-icon si
                            @if($sale) si-pencil @else si-plus @endif"></i>
							@if($sale) Update @else Add New sale @endif
						</button>

                        <span id="processingBtn" style="display: none;" class="text-secondary">
                            Data processing...
                        </span>

                    </div>
                </form>
            </div>







        </div>
    </div>
    <!-- END Full Table -->
</div>

<script type="text/javascript">

    $(document).ready(function() {
        $('#addSaleBtn').click(function() {
            $(this).hide();
            $('#processingBtn').show();
        });
    });

    function calculateDue() {
        // Get references to the input fields
        const installationChargeInput = document.getElementById('installation_charge');
        const advancedInput = document.getElementById('advanced');
        const dueInput = document.getElementById('due');

        // Get the values entered by the user
        const installationCharge = parseFloat(installationChargeInput.value) || 0;
        const advanced = parseFloat(advancedInput.value) || 0;

        // Calculate due amount
        const due = installationCharge - advanced;

        // Update the due amount input field
        dueInput.value = due > 0 ? due : 0;
    }

</script>

@endsection
