<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appiontment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\LeadInfo;
use App\Models\Sale;
//use Laradevsbd\Zkteco\Http\Library\ZktecoLib;
use Rats\Zkteco\Lib\ZKTeco;

class AdminController extends Controller
{
    
    public function finger_print_test() {
        $zk = new ZKTeco('192.168.0.201');
        $zk->connect();
        return 'Hello';
    }
    
    
    public function dashboard(Request $request) {
        if(isset($request->user_id)){ 
            $user_id = $request->user_id; 
        }else{
            $user_id = Auth::user()->id;
        }
        
        $todays_leads = LeadInfo::where('created_at', 'like', '%'.date('Y-m-d').'%');
        if(Auth::user()->type=='crm' || isset($request->user_id)){ $todays_leads = $todays_leads->where('assigned_to', '=', $user_id); }
        $todays_leads = $todays_leads->get('id');
        
        $all_leads = LeadInfo::where('assigned_to','!=','');
        if(Auth::user()->type=='crm' || isset($request->user_id)){ $all_leads = $all_leads->where('assigned_to', '=', $user_id); }
        $all_leads = $all_leads->get('id');
        
        $todays_sale = Sale::where('created_at', 'like', '%'.date('Y-m-d').'%');
        if(Auth::user()->type=='crm' || isset($request->user_id)){ $todays_sale = $todays_sale->where('saller_id', '=', $user_id); }
        $todays_sale = $todays_sale->get(['status', 'id']);
        
        $all_sales = Sale::where('saller_id', '!=', '');
        if(Auth::user()->type=='crm' || isset($request->user_id)){ $all_sales = $all_sales->where('saller_id', '=', $user_id); }
        $all_sales = $all_sales->get();
		
        $todays_install=Sale::where('created_at', 'like', '%'.date('Y-m-d').'%');
        if(Auth::user()->type=='crm' || isset($request->user_id)){ $todays_install = $todays_install->where('installer_id', '=', $user_id); }
        $todays_install = $todays_install->get();
        
		$all_install=Sale::where('installer_id', '!=', '');
        if(Auth::user()->type=='crm' || isset($request->user_id)){ $all_install = $all_install->where('installer_id', '=', $user_id); }
        $all_install = $all_install->get();
        
        return view('pages.dashboard', compact('todays_leads', 'todays_sale', 'todays_install', 'all_leads', 'all_sales', 'all_install'));
    }





    //Begin:: Admin role and permission
    public function crm_roles() {
        if(User::checkPermission('role.view') == true){
            $roles = DB::table('roles')->get();
            return view('pages.role.roles', compact('roles'));
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }
    //End:: Admin role and permission

    //Begin:: Admin Create role
    public function Admin_Create_helper_role(Request $request) {
        if(User::checkPermission('role.view') == true){
            $role_name = $request->name;
            $check = DB::table('roles')->where('name', $role_name)->first();
            if(!empty($check->id)) {
                return Redirect()->back()->with('error', 'This role is already exist!');
            }
            else {
                $data = array();
                $data['name'] = $role_name;
                $data['guard_name'] = 'web';
                $data['created_at'] = Carbon::now();
                $insert = DB::table('roles')->insert($data);
                if($insert) {
                    DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Added New Role(role name: '.$request->name.')', 'created_at' => Carbon::now()]);
                    return Redirect()->back()->with('success', 'New role has been created.');
                }
                else {
                    return Redirect()->back()->with('error', 'Error Occoured, Please Try again.');
                }
            }
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
        
    }
    //Begin:: Admin Create role

     //Begin:: Edit Admin  role
     public function edit_role($id) {
        if(User::checkPermission('role.view') == true){
            $role_info = DB::table('roles')->where('id', $id)->first();
            if(!empty($role_info->id)) {
                return view('pages.role.edit_roles', compact('role_info'));
            }
            else {
                return Redirect()->back()->with('error', 'Sorry you can not access this page');

            }
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
        
    }
    //Begin:: Edit Admin helper role

    //Begin:: Update Admin helper role
    public function update_role(Request $request, $id) {
        if(User::checkPermission('role.view') == true){
            $role_name = $request->name;
            $check = DB::table('roles')->where('name', $role_name)->first();
            if(!empty($check->id)) {
                return Redirect()->back()->with('error', 'Sorry, This role is already exist!');
            }
            else {
                $data = array();
                $data['name'] = $role_name;
                $data['updated_at'] = Carbon::now();
                $update = DB::table('roles')->where('id', $id)->update($data);
                if($update) {
                    DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Update Role, '.$check->name.' To '.$role_name.'', 'created_at' => Carbon::now()]);
                    return Redirect()->route('crm.role.permission')->with('success', 'Role has benn Updated.');
                }
                else {
                    return Redirect()->back()->with('error', 'Error Occoured, Please Try again.');
                }
                
            }
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
        
    }
    //End:: Update Admin helper role

    //Begin:: Update Admin helper role Permission
    public function admin_helper_permission($id) {
        if(User::checkPermission('permissions.view') == true){
            $role = Role::findById($id);
            $permissions = Permission::all();
            $permissionGroups = User::getPermissionGroupsForAdminHealperRole();
            return view('pages.role.permissions', compact('permissions', 'permissionGroups', 'role'));
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }
    //End:: Update Admin helper role Permission

    //Begin:: Set Permission to admin helper role
    public function set_permission_to_admin_helper_role() {
        $role_id = $_GET['roleID'];
        $permission_id = $_GET['permission_id'];
        
        $check = DB::table('role_has_permissions')->where('role_id', $role_id)->where('permission_id', $permission_id)->first();
        if(empty($check->role_id)) {
            $data = array();
            $data['role_id'] = $role_id;
            $data['permission_id'] = $permission_id;

            $insert = DB::table('role_has_permissions')->insert($data);

            if($insert) {
                \Artisan::call('permission:cache-reset');
                $sts = [
                    'status' => 'yes',
                    'reason' => 'Permission set successfully'
                ];
                return response()->json($sts);
            }
            else {
                $sts = [
                    'status' => 'no',
                    'reason' => 'Something is wrong, please try again.'
                ];
                return response()->json($sts);
            }
            
        }
        else {
            $sts = [
                'status' => 'no',
                'reason' => 'Permission is already exist, Please try another.'
            ];
            return response()->json($sts);
        }
        
    }
    //End:: Set Permission to admin helper role

    //Begin:: Delete Permission from role
    public function delete_permission_from_role() {
        $role_id = $_GET['roleID'];
        $permission_id = $_GET['permission_id'];
        
        $check = DB::table('role_has_permissions')->where('role_id', $role_id)->where('permission_id', $permission_id)->first();
        if(!empty($check->role_id)) {
            
            $delete = DB::table('role_has_permissions')->where('role_id', $role_id)->where('permission_id', $permission_id)->delete();
            if($delete) {
                \Artisan::call('permission:cache-reset');
                $sts = [
                    'status' => 'yes',
                    'reason' => 'Permission Delete successfully'
                ];
                return response()->json($sts);
            }
            else {
                $sts = [
                    'status' => 'no',
                    'reason' => 'Something is wrong, please try again.'
                ];
                return response()->json($sts);
            }
            
        }
        else {
            $sts = [
                'status' => 'no',
                'reason' => 'Permission is not exist, Please try another.'
            ];
            return response()->json($sts);
        }
        
    }
    //End:: Delete Permission from role
























}
