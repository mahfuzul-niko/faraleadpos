@extends('layouts.app')
@section('title')
    Installation Calendar
@endsection
@section('body_content')
    <input type="hidden" name="" id="toggle_yes" value='1'>
    <div class="content p-0 mb-3">
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

        </div>
    </div>


    <div class="container-fluid">
        <div class="row">
            @foreach ($futureInstallations as $installation)
                @php
                    $date = \Carbon\Carbon::parse($installation->installation_date)->format('Y-m-d');
                    $isToday = $date == date('Y-m-d');
                @endphp

                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card {{ $isToday ? 'border-primary' : '' }} h-100">

                        <div class="card-header {{ $isToday ? 'bg-primary text-white' : 'bg-light' }}">
                            <strong>
                                <i class="si si-calendar"></i> {{ $date }}
                            </strong>
                        </div>

                        <div class="card-body p-3">

                            <p class="mb-1"><strong>Name:</strong> {{ $installation->name }}</p>
                            <p class="mb-1"><strong>Contact:</strong> {{ $installation->mobile }}</p>
                            <p class="mb-1"><strong>Address:</strong> {{ $installation->address }}</p>

                            <hr>

                            <p class="mb-1"><strong>Bill:</strong> {{ $installation->bill_type }} –
                                ৳{{ $installation->bill_amount }}</p>
                            <p class="mb-1"><strong>Installation Charge:</strong>
                                ৳{{ $installation->installation_charge }}</p>
                            <p class="mb-1"><strong>Advance Paid:</strong> ৳{{ $installation->advance }}</p>
                            <p class="mb-1 text-danger"><strong>Due:</strong> ৳{{ $installation->due }}</p>

                            <hr>

                            <p class="mb-1">
                                <strong>Note / Device list:</strong><br>
                                <span class="text-muted">{{ $installation->note ?? 'N/A' }}</span>
                            </p>

                            <p class="mb-0">
                                <strong>Installer:</strong>
                                <span class="badge badge-info">
                                    {{ $installation->installer->name ?? 'N/A' }}
                                </span>
                            </p>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var toggle_yes = $('#toggle_yes').val();
            if (typeof(toggle_yes) != 'undefined' && toggle_yes != null) {
                SidebarColpase();
            }
        });
    </script>
@endsection
