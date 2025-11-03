@extends('layouts.app')
@section('body_content')
<!-- Page Content -->
<div class="content">
    <!-- Overview -->
    <div class="row">
    <div class="col-sm-12 col-xl-12 col-md-12">
            <!-- Pending Orders -->
            <div class="block block-rounded d-flex flex-column">
                <div
                    class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <div class="col-lg-12 col-xl-12">
                    <form action="{{url('/admin/update-lead-source-type-name/'.$info->id)}}" method="post">
                    @csrf
                    <div class="form-group">
                            <label for="example-text-input-alt"><span class="text-danger">*</span> Source Type Name</label>
                            <input type="text" class="form-control" value='{{$info->name}}' name="name" required>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Overview -->

</div>
<!-- END Page Content -->
@endsection
