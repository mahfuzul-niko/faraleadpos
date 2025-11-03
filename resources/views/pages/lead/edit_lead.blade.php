@extends('layouts.app')
@section('body_content')
<?php
    $user = Auth::user();
    $edit = $user->type=='admin' ? '' : 'readonly';
?>
<div class="content">
    <div class="block block-rounded">
        <div class="row p-2">
            <div class="col-md-4"><h4 class="">Update Lead</h4></div>
        </div>
        <div class="p-2">
            <div class="shadow rounded p-3">
            <form method="POST" action="{{route('admin.update.lead.info', ['id'=>$data->id])}}">
                    @csrf
                    
                    <div class="row">
                        <div class="form-group col-md-4">
                        <label for="">Name @required </label>
                        
                        <input type="text" class="form-control" {{ $edit }} value="{{optional($data)->name}}" name="name" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                        <div class="form-group col-md-4">
                            <label for="">Phone @required </label>
                            <input type="text" class="form-control" {{ $edit }} value="{{optional($data)->phone}}" name="phone" required>
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Email</label>
                            <input type="text" class="form-control" value="{{optional($data)->email}}" name="email" >
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12">
                            <label for="">Address</label>
                            <textarea name="address" class="form-control" {{ $edit }} cols="30" rows="2">{{optional($data)->address}}</textarea>
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Source</label>
                            <select name="source" class="form-control" id="">
                                <option value="">-- Select One --</option>
                                @foreach($lead_source as $item)
                                <option @if(optional($item)->id == optional($data)->source) selected class="text-light bg-success" @endif value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('source')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @if($user->checkPermission('lead.assign'))
                        <div class="form-group col-md-4">
                            <label for="">Assign to  @required </label>
                            <select name="assigned_to" class="form-control" id="" required>
                                <option value="">-- Choose --</option>
                                @foreach($users as $res)
                                <option @if(optional($data)->assigned_to == $res->id) selected class="text-light bg-success" @endif value="{{$res->id}}">{{$res->name}}</option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif
                        
                        <div class="form-group col-md-4">
                            <label for="">Status  @required </label>
                            <select name="status" class="form-control" id="" required>
                                <option @if(optional($data)->status == 'New') selected class="text-light bg-success" @endif value="New">New</option>
                                <option @if(optional($data)->status == 'Positive') selected class="text-light bg-success" @endif value="Positive">Positive</option>
                                <option @if(optional($data)->status == 'Sale') selected class="text-light bg-success" @endif value="Sale">Sale</option>
                                <option @if(optional($data)->status == 'Cancel') selected class="text-light bg-success" @endif value="Cancel">Cancel</option>
                                
                            </select>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="">Query  @required </label>
                            <textarea class="form-control" name="lead_query" id="" cols="30" rows="4" required >{!!optional($data)->lead_query!!}</textarea>
                            @error('lead_query')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Summery (optional)</label>
                            <textarea class="form-control" name="note" id="" cols="30" rows="4">{!!optional($data)->note!!}</textarea>
                            @error('note')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group text-right">
                    <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <!-- END Full Table -->
</div>


@endsection
