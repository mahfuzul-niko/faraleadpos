@extends('layouts.app')

@section('body_content')
    <div class="content p-0">
        <div class="card">
            <div class="card-header bg-primary text-light h4">Project Details</div>
            <div class="card-body">
                <div class="shadow rounded p-3">
					<div class="row">
						<!-- Project -->
						<ul class="list-group col-md-4">
                        <li class="list-group-item bg-default text-light">
							<strong>Project</strong>
						</li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
							<strong>Project File No:</strong> 
							<span>{{ $project->project_no }}</span></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
							<strong>Start Date:</strong> 
							<span>{{ $project->project_start_date }}</span></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
							<strong>Delivery Date:</strong> 
							<span>{{ $project->project_delivery_date }}</span></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
							<strong>Enroll By:</strong> 
							<span>{{ $project->project_enroll_by }}</span>
						</li>
                    </ul>
						<!-- Domain Section -->
						<ul class="list-group col-md-4">
						<li class="list-group-item bg-default text-light">
							<strong>Domain</strong>
						</li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
							<strong>Domain:</strong> 
							<span>{{ $project->domain ?? 'N/A' }}</span></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
							<strong>Provider:</strong> 
							<span>{{ $project->domain_provider ?? 'N/A' }}</span></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
							<strong>By:</strong> 
							<span>{{ $project->domain_by ?? 'N/A' }}</span></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
							<strong>Purchase By:</strong> 
							<span>{{ $project->domain_purchase_by ?? 'N/A' }}</span></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
							<strong>Purchase Date:</strong> 
							<span>{{ $project->domain_purchase_date ?? 'N/A' }}</span></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
							<strong>Expire Date:</strong> 
							<span>{{ $project->domain_expire_date ?? 'N/A' }}</span>
						</li>
                    </ul>

						<!-- Hosting Section -->
						<ul class="list-group col-md-4">
							<li class="list-group-item bg-default text-light">
								<strong>Hosting</strong>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<strong>Hosting:</strong> 
								<span>{{ $project->hosting ?? 'N/A' }}</span></li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<strong>Provider:</strong> 
								<span>{{ $project->hosting_provider ?? 'N/A' }}</span></li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<strong>By:</strong> 
								<span>{{ $project->hosting_by ?? 'N/A' }}</span></li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<strong>Purchase By:</strong> 
								<span>{{ $project->hosting_purchase_by ?? 'N/A' }}</span></li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<strong>Purchase Date:</strong> 
								<span>{{ $project->hosting_purchase_date ?? 'N/A' }}</span></li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<strong>Expire Date:</strong> 
								<span>{{ $project->hosting_expire_date ?? 'N/A' }}</span></li>
						</ul>
					</div>
					
					<div class="row">
						<!-- Client Section -->
						<ul class="list-group col-md-6 mt-3">
							<li class="list-group-item bg-default text-light">
								<strong>Client</strong>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<strong>Client Name:</strong>
								<span>{{ $project->client ?? 'N/A' }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<strong>Client Contact No.:</strong>
								<span>{{ $project->client_mobile_1 ?? 'N/A' }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<strong>Client Other Contact No.:</strong>
								<span>{{ $project->client_mobile_2 ?? 'N/A' }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<strong>Email:</strong>
								<span>{{ $project->client_email ?? 'N/A' }}</span>
							</li>
							<li class="list-group-item">
								<strong>Address:</strong> 
								<span>{{ $project->client_address ?? 'N/A' }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<strong>Client Type:</strong> 
								<span>{{ $project->client_type ?? 'N/A' }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<strong>Assistant Contact No.:</strong>
								<span>{{ $project->assistant_mobile_1 ?? 'N/A' }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<strong>Assistant Other Contact No.:</strong>
								<span>{{ $project->assistant_mobile_2 ?? 'N/A' }}</span>
							</li>
						</ul>
					
						<ul class="list-group col-md-6 mt-3">
							<li class="list-group-item bg-default text-light">
								<strong>Package</strong>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<strong>Package Price:</strong>
								<span>{{ $project->package_price ?? 'N/A' }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<strong>Renew Price:</strong>
								<span>{{ $project->renew_price ?? 'N/A' }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<strong>Status:</strong>
								<span>{{ $project->status ?? 'N/A' }}</span>
							</li>
							<li class="list-group-item bg-default text-light mt-3">
								<strong>Note</strong>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<span><pre>{!! $project->note ?? 'N/A' !!}</pre></span>
							</li>
						</ul>
						
					</div>

                    <!-- Add more sections as needed -->
					@if($user->checkPermission('project.edit'))
                    <div class="row mt-5">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('project.edit', $project->id) }}" class="btn btn-primary">
								<i class="nav-main-link-icon si si-note"></i> 
								Edit Project
							</a>
                        </div>
                    </div>
					@endif
					
                </div>
            </div>
        </div>
    </div>
@endsection
