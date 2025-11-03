<?php

namespace App\Http\Controllers;

use App\Models\LeadSource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class LeadSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(User::checkPermission('lead.source.type.view') == true){
            $data = LeadSource::all();
            return view('pages.lead.lead_source', compact('data'));
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(User::checkPermission('lead.source.type.add') == true){
            $source_name = $request->name;
            $check = LeadSource::where('name', $source_name)->first();
            if(!empty($check->id)) {
                return Redirect()->back()->with('error', 'This Source Type is already exist!');
            }
            else {
                $data = array();
                $data['name'] = $source_name;
                $data['is_active'] = '1';
                $data['created_at'] = Carbon::now();
                $insert = LeadSource::insert($data);
                if($insert) {
                    DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Added New Lead Source Type(Type name: '.$request->name.')', 'created_at' => Carbon::now()]);
                    return Redirect()->back()->with('success', 'New Source Type has been created.');
                }
                else {
                    return Redirect()->back()->with('error', 'Error Occoured, Please Try again.');
                }
            }
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access  add this page');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LeadSource  $leadSource
     * @return \Illuminate\Http\Response
     */
    public function show(LeadSource $leadSource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeadSource  $leadSource
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(User::checkPermission('lead.source.type.update') == true){
            $info = LeadSource::where('id', $id)->first();
            if(!empty(optional($info)->id)) {
                return view('pages.lead.edit_source_type', compact('info'));
            }
            else {
                return Redirect()->back()->with('error', 'Empty');
            }
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access  edit this page');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeadSource  $leadSource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(User::checkPermission('lead.source.type.update') == true){
            $source_name = $request->name;
            $check = LeadSource::where('name', $source_name)->first();
            $info = LeadSource::where('id', $id)->first();
            
            if(!empty($check->id)) {
                return Redirect()->back()->with('error', 'Sorry, This Source Type is already exist!');
            }
            else {
                $data = array();
                $data['name'] = $source_name;
                $data['updated_at'] = Carbon::now();
                $update = LeadSource::where('id', $id)->update($data);
                if($update) {
                    DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Updated Lead Source Type '.$info->name.' To '.$source_name.'', 'created_at' => Carbon::now()]);
                    return Redirect()->route('crm.role.permission')->with('success', 'Source Type has benn Updated.');
                }
                else {
                    return Redirect()->back()->with('error', 'Error Occoured, Please Try again.');
                }
                
            }
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access   update this page');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeadSource  $leadSource
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeadSource $leadSource)
    {
        //
    }


    //Begin:: Deactive Lead Source type
    public function deactive($id) {
        if(User::checkPermission('lead.source.type.update') == true){
            $data = array(
                'is_active' => 0,
            );
            $Q = LeadSource::where('id', $id)->update($data);
            if($Q) {
                DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Deactivate Lead Source type', 'created_at' => Carbon::now()]);
                return redirect()->back()->with('success', 'Lead Source type Deactive Successfully.');
            }
            else {
                return redirect()->back()->with('error', 'Error occurred! Please try again.');
            }
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }

    }
    //End:: Deactive Lead Source type

    //Begin:: Active Lead Source type
    public function active($id) {
        if(User::checkPermission('lead.source.type.update') == true){
            $data = array(
                'is_active' => 1,
            );
            $Q = LeadSource::where('id', $id)->update($data);
            if($Q) {
                DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Activate Lead Source type', 'created_at' => Carbon::now()]);
                return redirect()->back()->with('success', 'Lead Source type Active Successfully.');
            }
            else {
                return redirect()->back()->with('error', 'Error occurred! Please try again.');
            }
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }

    }
    //End:: Active Lead Source type


}
