@extends('layouts.app')
@section('body_content')
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<div class="content">
    <div class="block block-rounded">
        <div class="row p-2">
            <div class="col-md-4"><h4 class="">Lead Info</h4></div>
        </div>
        <div class="p-2">
        <form action="{{route('admin.lead.upload.confirm')}}" method="post">
            @csrf
        <table class="table">
        <tbody>
                @php 
                    
                    $file = fopen($filename, "r");
                    $i = 1;
                    while(($getData = fgetcsv($file, 20000, ",")) !== FALSE) {
                   
                    $created_time = $getData[0] ?? 'N/A'; // 0 Assuming 'created_time' is at index 1
					$name = $getData[1] ?? 'N/A'; // 1 Assuming 'full_name' is at index 13
					$phone = $getData[2] ?? 'N/A'; // 2 Assuming 'phone_number' is at index 14
					$address = $getData[3] ?? 'N/A'; // 3 Assuming 'city' is at index 15

                    $lead_source_check = DB::table('lead_sources')->where('name', 'Facebook Lead')->first();
                    $lead_source = DB::table('lead_sources')->get();
                    
                    
                    $updated_phone = str_replace(['p:+8800', 'p:+880', '+8800', '+880', 'p:00', 'p:0'], '0', $phone);
                    $check_previous = DB::table('lead_infos')->where(['phone'=>$updated_phone])->first(['id']);
                    @endphp
                    <tr id="tr_{{$i}}">
                        <td>
                        <div class="shadow rounded p-3 mt-3 @if(!empty(optional($check_previous)->id)) bg-danger  @endif">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <button type="button" class="btn btn-info btn-rounded">{{$i}}</button>
                                </div>
                                <div class="col-md-6 form-group text-right">
                                    @if(!empty(optional($check_previous)->id))
                                        <h2 class="text-light"><b>This content is Exist!</b></h2>
                                    @else
                                        <p class="text-success"><span><i class="fas fa-clipboard-check h3"></i></span> Ok</p>
                                    @endif
                                </div>
                                <div class="col-md-2 form-group text-right">
                                    <button type="button" onclick="deleteRow('{{$i}}')" class="btn btn-success btn-sm btn-rounded">Remove</button>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control form-control-sm" value="{{$name}}" name="name[]" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Lead Created Date</label>
                                    <input type="text" class="form-control form-control-sm"  value="{{$created_time}}" name="lead_created_date[]" required>
                                    
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Phone</label>
                                    <input type="text" class="form-control form-control-sm"  value="{{$updated_phone}}"  name="phone[]" required>
                                    
                                </div>

								<input type="hidden" class="form-control form-control-sm" value="0" name="email[]">
								<input type="hidden" class="form-control form-control-sm" value="0" name="lead_query[]">
								<input type="hidden" class="form-control form-control-sm" value="0" name="inbox_url[]">
                                <div class="form-group col-md-4">
                                    <label for=""><span class="text-danger">*</span>source</label>
                                    @if(!empty(optional($lead_source_check)->id))
                                        <p class="text-success">{{$lead_source_check->name}}</p>
                                        <input type="hidden" class="form-control form-control-sm"  value="{{$lead_source_check->id}}" readonly name="source[]" >
                                    @else
                                    <select name="source[]" class="form-control" id="" required>
                                        <option value="">-- Select One --</option>
                                        @foreach($lead_source as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                    
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="">Address</label>
                                    <textarea class="form-control form-control-sm" name="address[]" id="" cols="30" rows="2">{!!$address!!}</textarea>
                                </div>
                                
                            </div>
                    </div>
                    </td>
            </tr>  
                    @php
                        $i++;
                        $ss= substr(str_shuffle($getData[0]),0, 4).rand(0,3);
                    }
                    fclose($file);
                @endphp
                
        </tbody>
        </table>

        <div class="form-group text-right">
            <button type="submit" class="btn btn-success btn-rounded">Submit</button>
        </div>
            
        </form>
        </div>
    </div>
    <!-- END Full Table -->
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    function deleteRow(id) {
        swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
                $('#tr_'+id).remove();
            }
        });
    }
</script>

@endsection
