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


use charlieuki\ReceiptPrinter\ReceiptPrinter as ReceiptPrinter;


class AdminController extends Controller
{

    public function print() {
        // Set params
        $mid = '123123456';
        $store_name = '';
        $store_address = 'Park More Rangpur, Bangladesh';
        $store_phone = '01627382866';
        $store_email = 'yourmart@email.com';
        $store_website = 'yourmart.com';
        $tax_percentage = 10;
        $transaction_id = 'hhhhhfghfdhg';
        $logo = '';

        $previous_due = 20;
        $discount = 'percent';
        $discount_amount = 60;
        $vat = 10;
        $others_charge = 100;
        $delivery_charge = 150;
        $paid = 446;

        // Set items
        $items = [
            [
                'name' => 'French Fries (tera) h fh fgh fgh fg hfg h',
                'qty' => 2,
                'price' => 65000,
                'discount' => 'percent',
                'discount_amount' => 3,
                'vat' => 400,
                'item_subtotal' => 400,
            ],
        ];


        // Init printer
        $printer = new ReceiptPrinter;
        $printer->init(
            config('receiptprinter.connector_type'),
            config('receiptprinter.connector_descriptor')
        );

        // Set store info
        $printer->setStore($mid, $store_name, $store_address, $store_phone, $store_email, $store_website);

        // Add items
        foreach ($items as $item) {
            $printer->addItem(
                $item['name'],
                $item['qty'],
                $item['price'],
                $item['discount'],
                $item['discount_amount'],
                $item['vat'],
                $item['item_subtotal'],
            );
        }
        // Set tax
        $printer->setTax($tax_percentage);

        // Calculate total
        $printer->calculateSubTotal();
        $printer->calculateGrandTotal();

        // Set transaction ID
        $printer->setTransactionID($transaction_id);
        $printer->setVat($vat);
        $printer->setDiscount($discount);
        $printer->setDiscountAmount($discount_amount);
        $printer->setDeliveryCharge($delivery_charge);
        $printer->setOthersCharge($others_charge);
        $printer->setPreviousDue($previous_due);
        $printer->setPaid($paid);

        $printer->setLogo($logo);






        //        $printer->setTransactionID($transaction_id);
        //        $printer->setTransactionID($transaction_id);
        //        $printer->setTransactionID($transaction_id);


        // Set qr code
        // $printer->setQRcode([
        //     'tid' => $transaction_id,
        // ]);

        // Print receipt
        $action = $printer->printReceipt();
        return $action;
    }
    
    public function test()
    {
        for($i = 1; $i <= 4; $i++) {
            $this->print();
        }
    }
    
    
    
    public function dashboard() {
        $todays_leads = LeadInfo::where('created_at', 'like', '%'.date('Y-m-d').'%')->get(['id']);
        $todays_appointments = Appiontment::where('appiontment_datetime', 'like', '%'.date('Y-m-d').'%')->get(['status', 'id', 'visitor']);
        $all_leads = LeadInfo::all(['id', 'status']);
        $all_appointments = Appiontment::all(['status', 'id', 'visitor']);
        
        return view('pages.dashboard', compact('todays_appointments', 'todays_leads', 'all_leads', 'all_appointments'));
    }





    //Begin:: Admin role and permission
    public function crm_roles() {
        if(User::checkPermission('role') == true){
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
        if(User::checkPermission('role') == true){
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
        if(User::checkPermission('role') == true){
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
        if(User::checkPermission('role') == true){
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
        if(User::checkPermission('permissions') == true){
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
