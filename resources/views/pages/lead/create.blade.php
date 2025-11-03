@extends('layouts.app')
@section('title') Create Lead @endsection
@section('body_content')
<div class="content">
    <div class="block block-rounded">

        <div class="row p-2">
            <div class="col-md-4"><h4 class=""> @yield('title') </h4></div>
            <div class="col-md-8 text-right">
                <div class="dropdown push mr-3">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" id="dropdown-content-rich-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Upload CSV</button>
                    <div class="dropdown-menu font-size-sm mr-3 p-0" aria-labelledby="dropdown-content-rich-primary" style="">
                        <div class="card">
                            <div class="card-header h5"> Upload Original CSV (Supershop pos lead_Leads_...) File </div>
                                <div class="card-body">
                                    <form class="p-2" action="{{route('admin.upload.lead.info')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-8">
                                                <label for=""><span class="text-danger">*</span>Select File</label>
                                                <input type="file" class="form-control" id="" name="csvFile" required="">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <button type="submit" class="btn btn-success btn-md mt-4">Open</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center p-2 shadow rounded">
                                    <a href="{{url('/download/demo/demo-lead - pos.csv')}}" class="btn btn-rounded btn-success btn-sm">Download Demo CSV</a>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-2">
            <div class="shadow rounded p-3">
            <form method="POST" action="{{route('admin.store.lead')}}">
                    @csrf
                    <input type="hidden" name="lead_created_date" value="{{date('d-m-Y')}}">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for=""><span class="text-danger">*</span>Name</label>
                            <input type="text" class="form-control" name="name" required>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for=""><span class="text-danger">*</span>Phone</label>
                            <input type="number" class="form-control" name="phone" required>
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Email</label>
                            <input type="email" class="form-control" name="email" >
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12">
                            <label for=""><span class="text-danger">*</span>Address</label>
                            <textarea name="address" class="form-control" cols="30" rows="2"></textarea>
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for=""><span class="text-danger">*</span>source</label>
                            <select name="source" class="form-control" id="" required>
                                <option value="">-- Select One --</option>
                                @foreach($lead_source as $data)
                                <option value="{{$data->id}}">{{$data->name}}</option>
                                @endforeach
                            </select>
                            @error('source')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Assign to</label>
                            <select name="assigned_to" class="form-control" id="" required>
                                <option value="">-- Choose --</option>
                                @foreach($users as $res)
                                <option value="{{$res->id}}">{{$res->name}}</option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for=""><span class="text-danger">*</span>Query</label>
                            <textarea class="form-control" name="lead_query" id="" cols="30" rows="2"></textarea>
                            @error('lead_query')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Summery (optional)</label>
                            <textarea class="form-control" name="note" id="" cols="30" rows="2"></textarea>
                            @error('note')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group text-right">
                    <button type="submit" class="btn btn-success">Add</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <!-- END Full Table -->
</div>
@endsection
