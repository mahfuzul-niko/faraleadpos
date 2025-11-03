@extends('layouts.app')
@section('body_content')
<div class="content">
    <div class="row row-deck">
        <div class="col-md-12"><h4>Brands</h4></div>
        <div class="col-sm-12 col-xl-8 col-md-8">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full justify-content-between align-items-center">
                <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                    <th scope="col">Serial Num</th>
                    <th scope="col">Brand Name</th>
                    <th scope="col">Total Products</th>
                    <th scope="col">action</th>
                   
                    </tr>
                </thead>
                <tbody>
                    @foreach($brands as $brand)
                    @php($total_products = App\Models\Products::where('brand_id', $brand->id)->count('id'))
                    <tr>
                        <td>{{$brand->serial_num}}</td>
                        <td>{{$brand->name}}</td>
                        <td>{{$total_products}}</td>
                        <td><a class="btn btn-info btn-rounded btn-sm" href="javascript:void(0)">edit</a></td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xl-4 col-md-4">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full justify-content-between align-items-center">
                <form method="POST" action="{{route('admin.create.brand')}}">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1">Brand Name</label>
                        <input type="text" class="form-control" name="name" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Serial Num</label>
                        <input type="number" class="form-control" name="serial_num" required>
                        @error('serial_num')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group text-right">
                    <button type="submit" class="btn btn-success">Add</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
