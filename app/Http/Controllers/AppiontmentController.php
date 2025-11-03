<?php

namespace App\Http\Controllers;

use App\Models\Appiontment;
use Illuminate\Http\Request;
use App\Models\LeadInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PDF;
use DataTables;
use App\Models\SMS;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AppiontmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(User::checkPermission('appointment.view') == true){
            return view('pages.appointment.index');
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }

    public function index_data(Request $request) {
        if ($request->ajax()) {
            $data = Appiontment::orderBy('appiontment_datetime', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if(!empty($row->visitor)) {
                        return '<a href="'.route('admin.view.lead', ['id'=>$row->lead_id]).'" class="btn btn-success btn-sm btn-rounded">view</a> <p><small><b class="text-success">Output: </b>'.optional($row)->visiting_output.'<br /><b class="text-success">Update By: </b>'.optional($row->visitor_info)->name.'</small></p>';
                    }
                    else {
                        return '<button data-toggle="modal" onclick="set_visiting_output('.$row->id.', \''.optional($row->lead_info)->name.'\')" data-target="#lead_note_modal" class="btn btn-primary btn-sm btn-rounded">Visiting Output</button> <a href="'.route('admin.view.lead', ['id'=>$row->lead_id]).'" class="btn btn-success btn-sm btn-rounded">view</a> <a href="javascript:void(0)" onclick="delete_appointment(\''.$row->id.'\')" class="btn btn-danger btn-sm btn-rounded"><i class="fas fa-trash-alt"></i></a>';
                    }
                    
                })
                ->addColumn('user_info', function($row){
                    return '<p><small><b>Name: </b>'.optional($row->lead_info)->name.'<br /><b>Phone: </b>'.optional($row->lead_info)->phone.'</small></p>';
                })
                ->addColumn('date_and_time', function($row){
                    return date("d-m-Y h:i:s a", strtotime($row->appiontment_datetime));
                })
                
                ->rawColumns(['action', 'user_info', 'date_and_time'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        if(User::checkPermission('appointment.add') == true){
            $data = LeadInfo::where('id', $id)->first();
            return view('pages.appointment.create', compact('data'));
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
        if(User::checkPermission('appointment.add') == true){
            
            $validated = $request->validate([
                'lead_id' => 'required',
                'address' => 'required',
                'appiontment_date' => 'required',
                'message' => 'required',
            ]);
            
            $message = str_replace('<p data-f-id="pbf" style="text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;">Powered by <a href="https://www.froala.com/wysiwyg-editor?pb=1" title="Froala Editor">Froala Editor</a></p>',"", $request->message);

            $lead_id = $request->lead_id;
            $info = LeadInfo::where('id', $lead_id)->first();
            if(!empty(optional($info)->id)) {

                $insert = Appiontment::insert([
                    'user_id'=>Auth::user()->id,
                    'lead_id'=>$request->lead_id,
                    'address'=>$request->address,
                    'appiontment_datetime'=> date("Y-m-d H:i:s", strtotime($request->appiontment_date)),
                    'message'=>$message,
                    'note'=>$request->note,
                    'status'=>'appointment sent',
                    'created_at'=>Carbon::now()
                ]);
        
                if($insert) {
                    SMS::send_sms($message, $info->phone);
                    DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Set New Appointment for '.optional($info)->name.', Phone: '.optional($info)->phone.'(Info: '.$message.')', 'created_at' => Carbon::now()]);
                    $lead_info = array();
                    $lead_info['status'] = "appointment sent";
                    if(empty($info->address)) {
                        $lead_info['address'] = $request->address;
                    }
                    LeadInfo::where('id', $lead_id)->update($lead_info);
                    return Redirect()->route('admin.view.lead', ['id'=>$info->id])->with('success', 'Appointment Set Successfully.');
                }
                else {
                    return Redirect()->route('admin.set.appiontment', ['id'=>$lead_id])->with('error', 'Error occoured! Please Try Again.');
                }
            }
            else {
                return Redirect()->route('admin.set.appiontment', ['id'=>$lead_id])->with('error', 'Error! Information is not match.');
            }
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        } 
    }

    public function store_visitor_output(Request $request)
    {
        if(User::checkPermission('appointment.add') == true){
            $validated = $request->validate([
                'appointment_id' => 'required',
                'visiting_output' => 'required',
                'status' => 'required',
            ]);

            $appointment_id = $request->appointment_id;
            $info = Appiontment::where('id', $appointment_id)->first();
            if(!empty(optional($info)->id)) {
                $insert = Appiontment::where('id', $info->id)->update([
                    'visitor'=>Auth::user()->id,
                    'visiting_output'=>$request->visiting_output,
                    'status'=>$request->status,
                    'updated_at'=>Carbon::now()
                ]);
        
                if($insert) {
                    DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Marketing Visitor set output for '.optional($info->lead_info)->name.' and Phone: '.optional($info->lead_info)->phone.'(Info: '.$request->visiting_output.')', 'created_at' => Carbon::now()]);
                    $lead_info = array();
                    
                    if($request->status != 'cancel before visit') {
                        if($request->status == 'success') {
                            $lead_info['status'] = "complete";
                        }
                        else if($request->status == 'cancel after visit') {
                            $lead_info['status'] = "follow up";
                        }
                        LeadInfo::where('id', $info->lead_id)->update($lead_info);
                    }

                    return Redirect()->route('admin.appiontments')->with('success', 'Your provided information Set Successfully.');
                }
                else {
                    return Redirect()->route('admin.appiontments')->with('error', 'Error occoured! Please Try Again.');
                }
            }
            else {
                return Redirect()->route('admin.appiontments')->with('error', 'Error! Information is not match.');
            } 
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        } 
        
    }

    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appiontment  $appiontment
     * @return \Illuminate\Http\Response
     */
    public function show(Appiontment $appiontment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Appiontment  $appiontment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appiontment $appiontment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appiontment  $appiontment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appiontment $appiontment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appiontment  $appiontment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(User::checkPermission('appointment.delete') == true){
            $action = Appiontment::where('id', $id)->delete();
            if($action) {
                DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Appontment Deleted', 'created_at' => Carbon::now()]);
                return Redirect()->back()->with('success', 'Appontment deleted.');
            }
            else {
                return Redirect()->back()->with('error', 'Appontment can not deleted! Please try again.');
            }
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }

    public function appointment_report($date, $status) {
        return view('pages.appointment.report', compact('date', 'status'));
    }

    public function appointment_report_data(Request $request, $date, $status) {
        if ($request->ajax()) {
            
            if($date == 'all') {
                if($status == 'appointment') {
                    $data = Appiontment::orderBy('appiontment_datetime', 'desc')->get();
                }
                else if($status == 'complete') {
                    $data = Appiontment::where('visitor', '!=', '')->orderBy('appiontment_datetime', 'desc')->get();
                }
                else {
                    $data = Appiontment::where('status', 'success')->orderBy('appiontment_datetime', 'desc')->get();
                }
            }
            else {
                if($status == 'appointment') {
                    $data = Appiontment::where('appiontment_datetime', 'like', '%'.$date.'%')->get();
                }
                else if($status == 'complete') {
                    $data = Appiontment::where('appiontment_datetime', 'like', '%'.$date.'%')->where('visitor', '!=', '')->get();
                }
                else {
                    $data = Appiontment::where('appiontment_datetime', 'like', '%'.$date.'%')->where('status', 'success')->get();
                }
            }

            //$data = Appiontment::orderBy('id', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if(!empty($row->visitor)) {
                        return '<a href="'.route('admin.view.lead', ['id'=>$row->lead_id]).'" class="btn btn-success btn-sm btn-rounded">view</a> <p><small><b class="text-success">Output: </b>'.optional($row)->visiting_output.'<br /><b class="text-success">Update By: </b>'.optional($row->visitor_info)->name.'</small></p>';
                    }
                    else {
                        return '<button data-toggle="modal" onclick="set_visiting_output('.$row->id.', \''.optional($row->lead_info)->name.'\')" data-target="#lead_note_modal" class="btn btn-primary btn-sm btn-rounded">Visiting Output</button> <a href="'.route('admin.view.lead', ['id'=>$row->lead_id]).'" class="btn btn-success btn-sm btn-rounded">view</a> <a href="javascript:void(0)" onclick="delete_appointment(\''.$row->id.'\')" class="btn btn-danger btn-sm btn-rounded"><i class="fas fa-trash-alt"></i></a>';
                    }
                    
                })
                ->addColumn('user_info', function($row){
                    return '<p><small><b>Name: </b>'.optional($row->lead_info)->name.'<br /><b>Phone: </b>'.optional($row->lead_info)->phone.'</small></p>';
                })
                ->addColumn('date_and_time', function($row){
                    return date("d-m-Y h:i:s a", strtotime($row->appiontment_datetime));
                })
                
                ->rawColumns(['action', 'user_info', 'date_and_time'])
                ->make(true);
        }
    }


}
