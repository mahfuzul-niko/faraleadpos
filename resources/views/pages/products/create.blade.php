@extends('layouts.app')
@section('body_content')
@php( $category_serial = 1)
<div class="content">
    <div class="row row-deck">
        <div class="col-md-12"><h4>Create Products</h4></div>
        <div class="col-sm-12 col-xl-12 col-md-12">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full justify-content-between align-items-center">
                <form method="POST" action="{{route('admin.create.fetures.category')}}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1"><span class="text-danger">*</span>Category</label>
                                <select class="form-control" id="exampleFormControlSelect1">
                                    @foreach($product_categories as $p_category)
                                    <option value="{{$p_category->id}}">{{$p_category->category_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1"><span class="text-danger">*</span>Brand</label>
                                <select class="form-control" id="exampleFormControlSelect1">
                                    <option value="">-- Select Brand --</option>
                                    @foreach($brands as $brand)
                                    <option value="{{$brand->id}}">{{$brand->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1"><span class="text-danger">*</span>Serial Num</label>
                                <input type="number" class="form-control" name="title" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><span class="text-danger">*</span>Phone Title</label>
                        <input type="text" class="form-control" name="title" required>
                        @error('title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1"><span class="text-danger">*</span>Comming Soon Status</label><br>
                                <select class="form-control" name="coming_soon_status" id="">
                                    <option value="1">Yes</option>
                                    <option value="0" selected>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1"><span class="text-danger">*</span>Relese Date</label>
                                <input type="date" class="form-control" name="release_date" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1"><span class="text-danger">*</span>Front Image</label>
                                <input type="file" class="form-control" name="release_date" required>
                            </div>
                        </div>
                        
                    </div>

                    <!-- fetures Start -->
                    <div class="row shadow rounded">
                        <div class="col-md-12 p-3">
                            <div><h2 class="text-success"><b>Fetures</b></h2></div>
                            @foreach($categories as $category)
                            @php( $category_serial = $category_serial+1)
                            <div class="block block-rounded shadow">
                                <div class="block-header">
                                    <h3 class="block-title">{{$category->title}}</h3>
                                    <div class="block-options">
                                        <div class="block-options-item">
                                            <code>{!!$category->icon!!}</code>
                                        </div>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <table class="table table-vcenter">
                                        <tbody>
                                        @foreach($category->feture_lists as $item)
                                        <tr>
                                            <td class="font-w600 text-danger font-size-sm" width="30%">{{$item->name}}</td>
                                            <td class="d-none d-sm-table-cell">
                                            <input type="hidden" name="product_feture_category_id[]" value="">
                                            <input type="hidden" name="serial_num[]" value="">
                                            <input type="hidden" name="features_list_id[]" value="">
                                            <input type="text" step="any"  class="form-control border-dark"  name="info[]">
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- fetures End -->

                    <!-- Price Start -->
                    <div class="row shadow rounded mt-3">
                        <div class="col-md-12 p-3">
                            <div><h2 class="text-success"><b>Price</b></h2></div>
                            
                            <div class="block block-rounded shadow">
                                <div class="block-header">
                                    <h3 class="block-title"></h3>
                                    <div class="block-options">
                                        <div class="block-options-item">
                                            <code><button type="button" class="btn btn-success btn-sm btn-rounded"><i class="fas fa-plus"></i> Add Price</button></code>
                                        </div>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <table class="table table-vcenter">
                                        <tbody>
                                        
                                        <tr>
                                            <td class="font-w600 text-danger font-size-sm" width="50%"><input type="text" step="any"  class="form-control border-dark"  name="info[]"></td>
                                            <td class="font-w600 text-danger font-size-sm" width="25%"><input type="text" step="any"  class="form-control border-dark"  name="info[]"></td>
                                            <td class="font-w600 text-danger font-size-sm" width="25%"><input type="text" step="any"  class="form-control border-dark"  name="info[]"></td>
                                                
                                        </tr>
                                       
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <!-- Price Start -->

                    
                    
                    
                    
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
