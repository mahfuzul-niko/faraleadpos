@extends('layouts.app')
@section('body_content')
@php( $category_serial = 1)
<div class="content">
    <div class="row row-deck">
        <div class="col-md-12"><h4>Fetures Category & Fetures List</h4></div>
        <div class="col-sm-12 col-xl-8 col-md-8">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full justify-content-between align-items-center">
                <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="5%">Serial Num</th>
                        <th scope="col">Category Name</th>
                        <th scope="col">Fetures Items</th>
                        <th scope="col">action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    @php( $category_serial = $category_serial+1)
                    @php($serial_num = 0)
                    <tr>
                        <td>{{$category->serial_num}}</td>
                        <td><p>{!!$category->icon!!} {{$category->title}}</p></td>
                        <td>
                            @foreach($category->feture_lists as $item)
                                <span class="text-primary"><b class="text-success">{{$item->serial_num}}) </b>{{$item->name}}</span><br />
                            @php($serial_num = $item->serial_num)
                            @endforeach
                            @php($serial_num += 1)
                        </td>
                        <td><button data-toggle="modal" onclick="set_feture_category_info('{{$category->id}}', '{{$serial_num}}', '{{$category->title}}')" data-target="#exampleModal" class="btn btn-success btn-rounded btn-sm" ><i class="fas fa-plus"></i> Feture</button></td>
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
                <form method="POST" action="{{route('admin.create.fetures.category')}}">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1">Fetures Category title</label>
                        <input type="text" class="form-control" name="title" required>
                        @error('title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Serial Num</label>
                        <input type="number" class="form-control" value="{{$category_serial}}" name="serial_num" required>
                        @error('serial_num')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Icon</label>
                        <input type="text" class="form-control" name="icon" required>
                        @error('icon')
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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title text-light" id="exampleModalLabel">Add New Feture Under <span id="modal_feture_category" class="text-success"></span></h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{route('admin.create.fetures.category.feture')}}">
            @csrf
            <div class="form-group">
                <label for="exampleInputEmail1">Feture Name</label>
                <input type="text" class="form-control" name="name" required>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Serial Num</label>
                <input type="number" class="form-control" name="feture_serial_num" id="feture_serial_num" value="" required>
                @error('feture_serial_num')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <input type="hidden" class="form-control" name="features_category_id" id="features_category_id" required>
            
            <div class="form-group text-right">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Add</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    function set_feture_category_info(catID, serial_num, title) {
        $('#features_category_id').val(catID);
        $('#feture_serial_num').val(serial_num);
        $('#modal_feture_category').text(title);
    }
</script>


@endsection
