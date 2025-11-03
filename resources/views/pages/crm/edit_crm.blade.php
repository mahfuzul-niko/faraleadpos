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
                    <form action="{{url('/admin/update-crm/'.$user_info->id)}}" method="post">
                    @csrf
                    <div class="block-content font-size-sm row">
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input">CRM Name</label>
                                    <input type="text" value="{{$user_info->name}}" class="form-control" id="" required name="name">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input">CRM Email</label>
                                    <input type="text" class="form-control" id="" value="{{$user_info->email}}" required name="email">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="admin_helper_role">Select a Role</label>
                                    <select class="form-control" id="admin_helper_role" name="role" required>
                                        <option value="">-- Select One --</option>
                                        @foreach($roles as $admin_role)
                                            <option @if($admin_role->id == $user_info->role_id) selected class="bg-success text-light" @endif  value="{{$admin_role->id}}">{{$admin_role->name}}</option>
                                            
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <script>
            $('input[type=radio][name=type]').on('change', function() {
                var admin_helper_role_div = document.getElementById("admin_helper_role_div");
                var branch_user_parent_div = document.getElementById("branch_user_parent_div");
                

                if($(this).val() == 'owner_helper') {
                    admin_helper_role_div.classList.remove("d-none");
                    branch_user_parent_div.classList.add("d-none");
                    
                    $("#admin_helper_role").prop('required', true);
                    $("#branch_user_role").prop('required', false);
                    $("#branch_id").prop('required', false);
                    
                }
                else if($(this).val() == 'branch_user'){
                    admin_helper_role_div.classList.add("d-none");
                    branch_user_parent_div.classList.remove("d-none");
                    
                    $("#admin_helper_role").prop('required', false);
                    $("#branch_user_role").prop('required', true);
                    $("#branch_id").prop('required', true);
                }
            });

            $("#confirm_password").on("change paste keyup cut select", function() {
                var password_matched = document.getElementById("password_matched");
                var password_not_matched = document.getElementById("password_not_matched");

                var password = $('#password').val();
                var confirm_password = $('#confirm_password').val();
                if(password == confirm_password && password != '') {
                    password_matched.classList.remove("d-none");
                    password_not_matched.classList.add("d-none");
                }
                else if(password == '' || confirm_password == ''){
                    password_matched.classList.add("d-none");
                    password_not_matched.classList.add("d-none");
                }
                else {
                    password_matched.classList.add("d-none");
                    password_not_matched.classList.remove("d-none");
                }
            });


        </script>


@endsection
