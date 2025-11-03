@extends('layouts.app')
@section('title') Bounce Reports @endsection
@section('body_content')
@php
    $status = [
        ['id' => 1, 'name' => 'Sold'],
        ['id' => 2, 'name' => 'Installed'],
        ['id' => 3, 'name' => 'Cancel'],
    ];

    $crm = App\Models\User::where(['type'=>'crm', 'is_active'=>1])->get(['id','name']);

@endphp
<style>
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
					</li>
				</ul>
		</div>
		<div class="card-body">
			<div class="shadow rounded p-3">
				<div class="table-responsive">
					<table class="table table-striped table-hover table-vcenter">
						<thead>
							<tr>
								<th width="5%">File_No.</th>
								<th width="10%">Saler Info</th>
								<th width="20%">Client Info</th>
								<th width="15%">Installer info</th>
								<th width="20%">Payment info</th>
							</tr>
						</thead>
						<tbody>
							@foreach($sales as $sale)
								<tr>
									<td class="text-center">{{ $sale->file_no ?? 'N/A' }}</td>
									<td class="text-center">
									    {{ $sale->saller ?? 'N/A' }}<br>
									    <small>{{ date("d-m-Y", strtotime($sale->sale_date ?? 'N/A')) }}</small>
									    </td>
									<td class="text-center">
									    <b>{{ $sale->name ?? 'N/A' }}</b><br>
									   {{ optional($sale)->mobile }}<br>
									   <small>{{ optional($sale)->address }}</small>
									</td>
									<td>
									    {{ $sale->installer ?? 'N/A' }}<br>
									    <small>{{ date("d-m-Y", strtotime($sale->installation_date ?? 'N/A')) }}</small>
										</td>
										<td>
										    <b>Bill Type:</b>{{ $sale->bill_amount ?? 'N/A' }}/{{ $sale->bill_type ?? 'N/A' }}<br>
										    <b>Ins.Charge:</b> {{ $sale->installation_charge ?? 'N/A' }}<br>
										    <b>Adv.:</b> {{ $sale->advance ?? 'N/A' }}<br>
										    <b>Due:</b> {{ $sale->due ?? 'N/A' }}<br>
										</td>
								</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<td colspan="7">
									<div class="row">
										<div class="d-flex justify-content-center col-md-6">
											<p class="text-bold">Showing {{$sales->firstItem()}} to {{$sales->lastItem()}} of {{$sales->total()}} results</p>
										</div>
										<div class="d-flex justify-content-center col-md-6">
											{!! $sales->links() !!}
										</div>
									</div>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>



            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var toggle_yes = $('#toggle_yes').val();
        if (typeof (toggle_yes) != 'undefined' && toggle_yes != null) {
            SidebarColpase();
        }
    });
</script>
@endsection
