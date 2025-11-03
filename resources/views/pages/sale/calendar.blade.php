@extends('layouts.app')
@section('title') Installation Calendar @endsection
@section('body_content')
<style>
	.calendar {
		display: grid;
		grid-template-columns: repeat(7, 1fr);
		gap: 10px;
	}

	.day {
		border: 1px solid #ccc;
		padding: 10px;
		text-align: center;
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
							<a href="{{ route('sale.index') }}" class="btn btn-primary">
								<i class="nav-main-link-icon si si-notebook"></i>
								Sales Report
							</a>
						</span>
					</li>
				</ul>
		</div>
		<div class="card-body">
			<div class="shadow rounded p-3">

				<div class="calendar">
					 @foreach($futureInstallations as $installation)
						@php
							$date = \Carbon\Carbon::parse($installation->installation_date)->format('Y-m-d');
						@endphp
						<div class="day @if($date==date('Y-m-d')) bg-primary text-light @endif">
							<strong>{{ $date }}</strong><br>
							{{ $installation->name }}<br>
							Installer: {{ $installation->installer->name ?? 'N/A' }}
						</div>
					@endforeach
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
