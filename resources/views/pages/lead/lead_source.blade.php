@extends('layouts.app')
@section('body_content')
<!-- Page Content -->
<div class="content">
    
    <div class="block block-rounded">
        <div class="block-header">
            <h4 class="">Lead Sources</h4>
            <div class="block-options">
                <button type="button" class="btn btn-rounded btn-success push" data-toggle="modal" data-target="#modal-block-fadein">Add New</button>
            </div>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <table width="100%" class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th>SI</th>
                            <th>Source Name</th>
                            <th>Orders Info</th>
                            <th class="text-center">Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php( $i = 1 )
                        @foreach($data as $item)
                        <tr>
                            <td>{{$i}}</em></td>
                            <td>{{$item->name}}</td>
                            <td>
                                @php($leads_group = DB::table('lead_infos')->where('source', $item->id)->select('status')->groupBy('status')->get())
                                @foreach($leads_group as $group)
                                @php( $order_count = DB::table('lead_infos')->where(['status'=>$group->status, 'source'=>$item->id])->count('id') )
                                    <p>
                                        <b class="text-success text-uppercase">{{$group->status}}: </b> {{$order_count}}<br />
                                    </p>
                                @endforeach
                            </td>
                            <td class="text-center">
                                @if($item->is_active == 1)
                                    <span class="badge badge-success">Active</span>
                                @else
                                <span class="badge badge-danger">Deactive</span>
                                @endif
                            </td>
                            <td width="25%">
                                <a type="button" href="{{url('/admin/edit-lead-source-type/'.$item->id)}}" class="btn btn-sm btn-danger btn-rounded"><i class="fa fa-fw fa-pencil-alt"></i></a>
                                @if($item->is_active != 1)
                                    <a type="button" href="{{url('/admin/active-source-type/'.$item->id)}}" class="btn btn-sm btn-success btn-rounded">Active</a>
                                @else
                                    <a type="button" href="{{url('/admin/deactive-source-type/'.$item->id)}}" class="btn btn-sm btn-warning btn-rounded">Deactive</a>
                                @endif
                            </td>
                        </tr>
                        @php( $i += 1 )
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END Full Table -->

</div>
<!-- END Page Content -->

<!-- Fade In Block Modal -->
<div class="modal fade" id="modal-block-fadein" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-themed block-transparent mb-0">
                    <form action="{{route('admin.create.lead.source.type')}}" method="post">
                        @csrf
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title text-light">Add New Source Type</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content font-size-sm">
                            <div class="form-group">
                                <label for="example-text-input">Type Name</label>
                                <input type="text" class="form-control" id="" required name="name">
                            </div>
                        </div>
                        <div class="block-content block-content-full text-right border-top">
                            <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Fade In Block Modal -->
@endsection
