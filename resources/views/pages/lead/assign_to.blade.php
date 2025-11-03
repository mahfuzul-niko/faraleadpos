@extends('layouts.app')
@section('title')  Lead Assign @endsection
@section('body_content')
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
							<a href="{{ route('admin.all.lead.info') }}" class="btn btn-primary">
								<i class="nav-main-link-icon si si-notebook"></i>
								Total Lead: {{$lead}}
							</a>
						</span>
					</li>
				</ul>
		</div>
		<div class="card-body">
			<div class="shadow rounded p-3">
				<div class="table-responsive">
					<form method="POST" action="{{route('admin.lead_assigned')}}">
						@csrf
						<table class="table table-striped table-hover table-vcenter">
							<thead>
								<tr>
									<th width="5%">Photo</th>
									<th>Name</th>
									<th>Amount</th>
									<th>Total</th>
									<th>New</th>
									<th>Re-Assigned</th>
									<th>Sale</th>
									<th>Install</th>
									<th>Bounce</th>
									<th>All Lead</th>
								</tr>
							</thead>
							<tbody>
								@foreach($users as $key=>$res)
								@php
									if(!is_null(optional($res)->profile_photo_path) && file_exists(optional($res)->profile_photo_path)){
										$photo = optional($res)->profile_photo_path;
									}else{
										$photo = 'images/no-profile.png';
									}
								@endphp
								<tr>
									<td> <img src="{{ asset($photo) }}" height="35" class="rounded-pill p-1 shadow"></td>
									<td>{{$res->name}}</td>
									<td>
										<input type="hidden" name="assigned_to[]" value="{{ $res->id }}">
										<input type="number" name="take[]" class="form-control rounded-pill">
									</td>
									<td>{{ $res->totalLead() }}</td>
									<td>{{ $res->newLead() }}</td>
									<td>{{ $res->reAssignedLead() }}</td>
									<td>{{ $res->sale() }}</td>
									<td>{{ $res->install() }}</td>
									<td>{{ $res->bounce() }}</td>
									<td>
										<a href="{{ route('admin.all.lead.info',['assigned_to'=>$res->id]) }}" class="btn btn-sm btn-outline-primary btn-rounded" target="_blank">
											<i class="nav-main-link-icon si si-eye"></i> View
										</a>

										<a href="{{ route('admin.pending.lead.re.assign',['assigned_to'=>$res->id]) }}" class="btn btn-sm btn-outline-primary btn-rounded">
											<i class="nav-main-link-icon si si-paper-plane"></i> 
											Re-Assign
										</a>
									</td>
								</tr>
								@endforeach
							</tbody>
							<tfoot>
								<tr>
									<td colspan="3">

										<div class="form-group text-right mt-2">
											<button type="submit" class="btn btn-outline-success btn-block">Assign To All Members</button>
										</div>

									</td>
								</tr>

							</tfoot>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
