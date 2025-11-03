@extends('layouts.app')
@section('title') Sale List @endsection
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
							Sale List
						</strong>
						<span>
							<a href="{{ route('sale.create') }}" class="btn btn-primary">
								<i class="nav-main-link-icon si si-notebook"></i>
								Add New Sale
							</a>
						</span>
					</li>
				</ul>
		</div>
		<div class="card-body">

		    <div class="shadow rounded p-3">
                    <form method="get" action="{{route('sale.index')}}">
                    <div class="row">
                        <div class="form-group  @if(Auth::user()->type=='admin') col-md-2 @else col-md-4 @endif">
                            <label for="">Search</label>
                            <input type="text" class="form-control rounded-pill" value="{{ request()->search }}" name="search" placeholder="name, phone, address">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">Status</label>
							<select class="form-control rounded-pill" name="status" id="lead_status">
							    <option {{ (!isset(request()->status)) ? 'selected' : '' }} value="">Select</option>
                                @foreach($status as $key => $res)
                                <option {{ (isset(request()->status) && request()->status == $res['name']) ? 'selected' : '' }} value="{{ $res['name'] }}">{{ $res['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(Auth::user()->type=='admin')
                        <div class="form-group col-md-2">
                            <label for="">Saller</label>
							<select class="form-control rounded-pill" name="saller_id" id="lead_status">
                                <option {{ (!isset(request()->saller_id)) ? 'selected' : '' }} value="">Select</option>
                                @foreach($crm as $key => $res)
                                <option {{ (isset(request()->saller_id) && request()->saller_id == $res->id) ? 'selected' : '' }} value="{{ $res->id }}">{{ $res->name }}</option>
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
								<th width="5%">Status</th>
								<th class="text-center" style="vertical-align:middle;">Action</th>
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
									<td>{{ $sale->status ?? 'N/A' }} </td>
									<td class="text-center">
									@if($user->checkPermission('sale.update'))
										<a href="{{ route('sale.edit', $sale->id) }}" class="btn btn-sm btn-outline-primary btn-rounded" target="_blank">
											<i class="nav-main-link-icon si si-note"></i>
											Edit
										</a>
									@endif
									{{--
									@if($user->checkPermission('sale.view'))
										<a href="{{ route('sale.show', $sale->id) }}" class="btn btn-sm btn-outline-success btn-rounded" target="_blank">
											<i class="nav-main-link-icon si si-eye"></i>
											Show
										</a>
									@endif
									--}}
									{{--
									@if($user->checkPermission('sale.delete'))
											<form method="POST" action="{{ route('sale.destroy', $sale->id) }}" style="display: inline;">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn btn-sm btn-outline-danger btn-rounded" onclick="return confirm('Are you sure you want to delete?')">
												<i class="nav-main-link-icon si si-trash"></i>
												Delete
											</button>
											</form>
									@endif
									--}}
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
@endsection
