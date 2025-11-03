@extends('layouts.app')
@section('title') SMS List @endsection
@section('body_content')
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
						<span>
							<a href="{{ route('sms.create') }}" class="btn btn-primary">
								<i class="nav-main-link-icon si si-notebook"></i>
								Send SMS
							</a>
						</span>
					</li>
				</ul>
		</div>
		<div class="card-body">

			<div class="shadow rounded p-3">
				<div class="table-responsive">
					<table class="table table-striped table-hover table-vcenter">
						<thead>
							<tr>
								<th width="5%">SL</th>
								<th width="10%">CRM</th>
								<th width="20%">Contact Number</th>
								<th width="15%">SMS</th>
								<th width="20%">Date</th>
							</tr>
						</thead>
						<tbody>
                            @php
                                $n=1;
                            @endphp
                            @foreach ($sms as $res)
                            <tr>
                                <td>{{ $n++ }}</td>
                                <td>{{ $res->user->name }}</td>
                                <td>{{ $res->phone }}</td>
                                <td>{{ $res->sms }}</td>
                                <td>{{ $res->created_at }}</td>
                            </tr>

                            @endforeach

						</tbody>
						<tfoot>

						</tfoot>
					</table>
				</div>



            </div>
        </div>
    </div>
</div>

@endsection
