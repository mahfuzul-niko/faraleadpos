<?php

namespace App\Http\Controllers;

use App\Models\LeadInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use PDF;
use DataTables;
use App\Models\LeadNote;
use App\Models\User;
use App\Models\LeadSource;

class LeadInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(User::checkPermission('lead.view') == true){
            return view('pages.lead.index');
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }

    public function index_data(Request $request) {
        if ($request->ajax()) {
            $data = LeadInfo::orderBy('id', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '<a href="'.route('admin.edit.lead', ['id'=>$row->id]).'" class="btn btn-primary btn-sm btn-rounded">Edit</a> <a href="'.route('admin.view.lead', ['id'=>$row->id]).'" class="btn btn-success btn-sm btn-rounded">view</a> <button data-toggle="modal" onclick="set_lead_note('.$row->id.', \''.$row->name.'\')" data-target="#lead_note_modal" class="btn btn-info btn-sm btn-rounded">Set Note</button> <a type="button" href="'.route('admin.set.appiontment', ['id'=>$row->id]).'" class="btn btn-dark btn-rounded btn-sm">App</a>';
                })
                ->addColumn('query_and_others', function($row){
                    return '<p><small><b>Query: </b>'.$row->lead_query.'<br /><b>Lead Received Date: </b>'.date("d-m-Y h:i:s a", strtotime($row->lead_created_date)).'<br /><b>Lead Upload Date: </b>'.date("d-m-Y h:i:s a", strtotime($row->created_at)).'<br /></small></p>';
                })
                ->addColumn('note', function($row){
                    return '<p>'.optional($row->last_note)->note.'</p>';
                    
                })
                
                ->rawColumns(['action', 'query_and_others', 'note', ])
                ->make(true);
        }
    }

    public function set_lead_note(Request $request) {
        if(User::checkPermission('lead.make.note') == true){
            $validated = $request->validate([
                'note' => 'required',
            ]);

            $insert = LeadNote::insert([
                'user_id'=>Auth::user()->id,
                'lead_id'=>$request->lead_id_for_lead_note,
                'note'=>$request->note,
                'created_at'=>Carbon::now()
            ]);
            if($insert) {
                DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Set Lead Note(Note: '.$request->note.')', 'created_at' => Carbon::now()]);
                return Redirect()->back()->with('success', 'Lead Note Set Successfully.');
            }
            else {
                return Redirect()->back()->with('error', 'Error occoured! Please Try Again.');
            }
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

        if(User::checkPermission('lead.add') == true){
            $lead_source = LeadSource::where('is_active', '1')->get();
            return view('pages.lead.create', compact('lead_source'));
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(User::checkPermission('lead.add') == true){
            $validated = $request->validate([
                'lead_created_date' => 'required',
                'name' => 'required',
                'phone' => 'required | unique:lead_infos,phone',
                'lead_query' => 'required',
            ]);

            $insert = DB::table('lead_infos')->insert([
                'user_id'=>Auth::user()->id,
                'lead_created_date'=>$request->lead_created_date,
                'name'=>$request->name,
                'phone'=>$request->phone,
                'email'=>$request->email,
                'address'=>$request->address,
                'lead_query'=>$request->lead_query,
                'inbox_url'=>$request->inbox_url,
                'source'=>$request->source,
                'note'=>$request->note,
                'status'=>'new',
                'created_at'=>Carbon::now()
            ]);

            if($insert) {
                DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Create New Lead. Name: '.$request->name.', Phone: '.$request->phone.'', 'created_at' => Carbon::now()]);
                return Redirect()->route('admin.lead.info')->with('success', 'New Lead Info Added Successfully.');
            }
            else {
                return Redirect()->back()->with('error', 'Error occoured! Please Try Again.');
            }
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }

    public function bulk_upload_lead_info(Request $request) {
        if(User::checkPermission('lead.add') == true){
            $validated = $request->validate([
                'csvFile' => 'required',
            ]);

            $filename= $request->csvFile; 
            return view('pages.lead.bulk_upload_view_lead', compact('filename'));
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }

    }

    public function admin_lead_upload_confrim(Request $request) {
        
        $phone = $request->phone;
        if(!is_null($phone)) {
            foreach($phone as $key => $item) {
                $name = $request->name[$key];
                $updated_phone = $request->phone[$key];
                
                $insert = DB::table('lead_infos')->insert([
                    'user_id'=>Auth::user()->id,
                    'lead_created_date'=>$request->lead_created_date[$key],
                    'name'=>$name,
                    'phone'=>$updated_phone,
                    'email'=>$request->email[$key],
                    'lead_query'=>$request->lead_query[$key],
                    'inbox_url'=>$request->inbox_url[$key],
                    'source'=>$request->source[$key],
                    'status'=>'new',
                    'created_at'=>Carbon::now()
                ]);
                DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Create New Lead By Uploading CSV.(Name: '.$name.', Phone: '.$updated_phone.')', 'created_at' => Carbon::now()]);
            }
            return Redirect()->route('admin.lead.info')->with('success', 'New Lead Info Added Successfully.');
        }
        else {
            return Redirect()->back()->with('error', 'Empty Data!');
        }
        

    }

    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LeadInfo  $leadInfo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(User::checkPermission('lead.view') == true){
            $data = LeadInfo::where('id', $id)->first();
            return view('pages.lead.view_lead', compact('data'));
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeadInfo  $leadInfo
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(User::checkPermission('lead.update') == true){
            $data = LeadInfo::where('id', $id)->first();
            $lead_source = LeadSource::where('is_active', '1')->get();
            return view('pages.lead.edit_lead', compact('data', 'lead_source'));
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeadInfo  $leadInfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(User::checkPermission('lead.update') == true){
            $validated = $request->validate([
                'lead_created_date' => 'required',
                'name' => 'required',
                'phone' => 'required',
                'lead_query' => 'required',
            ]);

            $update = DB::table('lead_infos')->where('id', $id)->update([
                'user_id'=>Auth::user()->id,
                'lead_created_date'=>$request->lead_created_date,
                'name'=>$request->name,
                'phone'=>$request->phone,
                'email'=>$request->email,
                'address'=>$request->address,
                'lead_query'=>$request->lead_query,
                'inbox_url'=>$request->inbox_url,
                'source'=>$request->source,
                'note'=>$request->note,
                'status'=>$request->status,
            ]);

            if($update) {
                DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Updated Lead info Name: '.$request->name.', Phone: '.$request->phone.'', 'created_at' => Carbon::now()]);
                return Redirect()->route('admin.lead.info')->with('success', 'Lead Info Update Successfully.');
            }
            else {
                return Redirect()->back()->with('error', 'Error occoured! Please Try Again.');
            }
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeadInfo  $leadInfo
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeadInfo $leadInfo)
    {
        //
    }
}
