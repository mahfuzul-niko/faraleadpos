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
use Validator;

class LeadInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (User::checkPermission('lead.view') == true) {
            return view('pages.lead.index');
        } else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }

    public function index_data(Request $request)
    {
        if ($request->ajax()) {
            $data = LeadInfo::orderBy('id', 'desc');
            if (Auth::user()->type == 'crm') {
                $data->where('assign_to', Auth::user()->id);

            }
            $data->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.edit.lead', ['id' => $row->id]) . '" class="btn btn-primary btn-sm btn-rounded">Edit</a> <a href="' . route('admin.view.lead', ['id' => $row->id]) . '" class="btn btn-success btn-sm btn-rounded">view</a> <button data-toggle="modal" onclick="set_lead_note(' . $row->id . ', \'' . $row->name . '\')" data-target="#lead_note_modal" class="btn btn-info btn-sm btn-rounded">Set Note</button> <a type="button" href="' . route('admin.set.appiontment', ['id' => $row->id]) . '" class="btn btn-dark btn-rounded btn-sm">App</a>';
                })
                ->addColumn('query_and_others', function ($row) {
                    return '<p><small><b>Query: </b>' . $row->lead_query . '<br /><b>Lead Received Date: </b>' . date("d-m-Y h:i:s a", strtotime($row->lead_created_date)) . '<br /><b>Lead Upload Date: </b>' . date("d-m-Y h:i:s a", strtotime($row->created_at)) . '<br /></small></p>';
                })
                ->addColumn('note', function ($row) {
                    return '<p>' . optional($row->last_note)->note . '</p>';

                })

                ->rawColumns(['action', 'query_and_others', 'note',])
                ->make(true);
        }
    }


    public function set_lead_note(Request $request)
    {
        if (User::checkPermission('lead.view') == true) {
            $validated = $request->validate([
                'note' => 'required',
            ]);

            $insert = LeadNote::insert([
                'user_id' => Auth::user()->id,
                'lead_id' => $request->lead_id_for_lead_note,
                'note' => $request->note,
                'created_at' => Carbon::now()
            ]);
            if ($insert) {
                DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Set Lead Note(Note: ' . $request->note . ')', 'created_at' => Carbon::now()]);
                return Redirect()->back()->with('success', 'Lead Note Set Successfully.');
            } else {
                return Redirect()->back()->with('error', 'Error occoured! Please Try Again.');
            }
        } else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }

    public function all_lead_info(Request $request)
    {
        if (User::checkPermission('lead.view') == true) {
            $leads = LeadInfo::with('assigned', 'lead_source', 'last_note')->orderBy('id', 'desc');

            if (!empty($request->search)) {

                $searchTerm = $request->search;

                $leads = $leads->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('phone', 'like', '%' . $searchTerm . '%')
                        ->orWhere('address', 'like', '%' . $searchTerm . '%');
                });

            }

            if (Auth::user()->type == 'crm') {
                $leads = $leads->where('assigned_to', Auth::user()->id);
            }
            if (!empty($request->assigned_to)) {
                $leads = $leads->where('assigned_to', $request->assigned_to);
            }
            if ($request->status != 'All') {
                $leads = $leads->where('status', $request->status);
            }
            if (!empty($request->start_date) && !empty($request->end_date)) {
                $startDate = $request->start_date . ' 00:00:00';
                $endDate = $request->end_date . ' 23:59:59';

                $leads = $leads->whereBetween('created_at', [$startDate, $endDate]);
            }
            $leads = $leads->paginate('100');

            return view('pages.lead.all_lead_info', compact('leads'));
        } else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }

    public function pending_lead_re_assign(Request $request)
    {
        $assigned_to = $request->assigned_to;
        $leads = LeadInfo::where('assigned_to', $assigned_to)->where('status', 'New')->get();
        foreach ($leads as $lead) {
            $lead->update([
                'status' => 'Re-Assigned',
                'assigned_to' => NULL,
            ]);
        }
        return back()->with('success', 'Ready For Re-Assign');
    }

    public function store_lead_status_by_ajax(Request $request)
    {
        if (User::checkPermission('lead.status.update') == true) {

            $note = '';
            if ($request->lead_id_for_lead_status <> '') {
                $id = $request->lead_id_for_lead_status;
            } else {
                $output = [
                    'status' => 'no',
                    'reason' => 'Please select lead info',
                ];

                return Response($output);
            }

            if ($request->status <> '') {
                $status = $request->status;
            } else {
                $output = [
                    'status' => 'no',
                    'reason' => 'Please select status',
                ];

                return Response($output);
            }


            $lead = LeadInfo::where('id', $id)->first();
            $lead->status = $status;
            $update = $lead->update();

            if ($update) {
                DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Update Lead Status(Status: ' . $request->status . ')', 'created_at' => Carbon::now()]);
                $output = [
                    'status' => 'yes',
                    'note' => $status,
                    'id' => $request->lead_id_for_lead_note,
                ];
            } else {
                $output = [
                    'status' => 'no',
                    'reason' => 'Error Occoured! Please Try again.',
                ];
            }
        } else {
            $output = [
                'status' => 'no',
                'reason' => 'Sorry you can not access this page',
            ];
        }
        return Response($output);
    }

    public function store_lead_note_by_ajax(Request $request)
    {
        if (User::checkPermission('lead.view') == true) {

            $note = '';

            if ($request->status <> '') {
                $note = $request->status;
            }

            $note = $note . " " . $request->note;

            if ($note == ' ') {
                $output = [
                    'status' => 'no',
                    'reason' => 'Please select status or write a note.',
                ];

                return Response($output);
            }


            $insert = LeadNote::insert([
                'user_id' => Auth::user()->id,
                'lead_id' => $request->lead_id_for_lead_note,
                'note' => $note,
                'created_at' => Carbon::now()
            ]);

            if ($insert) {
                DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Set Lead Note(Note: ' . $request->note . ')', 'created_at' => Carbon::now()]);
                $output = [
                    'status' => 'yes',
                    'note' => $note,
                    'date' => Carbon::now(),
                    'id' => $request->lead_id_for_lead_note,
                ];
            } else {
                $output = [
                    'status' => 'no',
                    'reason' => 'Error Occoured! Please Try again.',
                ];
            }
        } else {
            $output = [
                'status' => 'no',
                'reason' => 'Sorry you can not access this page',
            ];
        }
        return Response($output);
    }

    public function create()
    {
        if (User::checkPermission('lead.add') == true) {
            $lead_source = LeadSource::where('is_active', '1')->get();
            $users = User::where(['is_active' => 1, 'type' => 'crm'])->get(['id', 'name']);
            return view('pages.lead.create', compact('lead_source', 'users'));
        } else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }

    public function store(Request $request)
    {
        //return $request;

        if (User::checkPermission('lead.add') == true) {
            $validator = Validator::make($request->all(), [
                'lead_created_date' => 'required',
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required',
            ]);

            $validator->after(function ($validator) use ($request) {
                $existingLead = LeadInfo::where('phone', $request->phone)->first();

                if ($existingLead) {
                    $assignedUser = User::find($existingLead->assigned_to);
                    $userName = $assignedUser ? $assignedUser->name : 'someone';

                    $validator->errors()->add('phone', "This lead is already assigned to {$userName}.");
                }
            });

            $validated = $validator->validate();

            $insert = DB::table('lead_infos')->insert([
                'user_id' => Auth::user()->id,
                'lead_created_date' => $request->lead_created_date,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'lead_query' => $request->lead_query,
                'source' => $request->source,
                'note' => $request->note,
                'assigned_to' => $request->assigned_to,
                'created_at' => Carbon::now(),
            ]);

            if ($insert) {
                DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Create New Lead. Name: ' . $request->name . ', Phone: ' . $request->phone . '', 'created_at' => Carbon::now()]);
                return Redirect()->route('admin.all.lead.info')->with('success', 'New Lead Info Added Successfully.');
            } else {
                return Redirect()->back()->with('error', 'Error occoured! Please Try Again.');
            }
        } else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }

    public function bulk_upload_lead_info(Request $request)
    {
        if (User::checkPermission('lead.add') == true) {
            $validated = $request->validate([
                'csvFile' => 'required',
            ]);

            $filename = $request->csvFile;
            return view('pages.lead.bulk_upload_view_lead', compact('filename'));
        } else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }

    }

    public function admin_lead_upload_confrim(Request $request)
    {

        $phone = $request->phone;
        if (!is_null($phone)) {
            $batch = DB::table('lead_infos')->max('batch') + 1;
            foreach ($phone as $key => $item) {
                $name = $request->name[$key];
                $updated_phone = $request->phone[$key];

                $insert = DB::table('lead_infos')->insert([
                    'user_id' => Auth::user()->id,
                    'lead_created_date' => $request->lead_created_date[$key],
                    'name' => $name,
                    'phone' => $updated_phone,
                    'email' => $request->email[$key],
                    'address' => $request->address[$key],
                    'lead_query' => $request->lead_query[$key],
                    'source' => $request->source[$key],
                    'batch' => $batch,
                    'created_at' => Carbon::now(),

                ]);
                DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Create New Lead By Uploading CSV.(Name: ' . $name . ', Phone: ' . $updated_phone . ')', 'created_at' => Carbon::now()]);
            }
            if ($insert) {
                return Redirect()->route('admin.lead_assign')->with('success', 'New Lead Info Added Successfully.');
            }
            return Redirect()->route('admin.all.lead.info')->with('success', 'New Lead Info Added Successfully.');
        } else {
            return Redirect()->back()->with('error', 'Empty Data!');
        }


    }

    public function lead_assign()
    {
        $lead = LeadInfo::whereNull('assigned_to')->count();
        $users = User::where(['is_active' => 1, 'type' => 'crm'])->get(['id', 'name', 'profile_photo_path', 'current_team_id']);
        return view('pages.lead.assign_to', compact('lead', 'users'));
    }

    public function lead_assigned(Request $request)
    {

        $assigned_to_array = $request->assigned_to;
        if (!is_null($assigned_to_array)) {
            foreach ($assigned_to_array as $key => $assigned_to) {
                $take = $request->take[$key];
                if ($take > 0) {
                    $randomLeads = LeadInfo::whereNull('assigned_to')->inRandomOrder()->take($take)->get();
                    foreach ($randomLeads as $lead) {
                        $lead->update(['assigned_to' => $assigned_to]);
                    }
                }
            }
        }
        return Redirect()->route('admin.lead_assign')->with('success', 'Lead Assigned Successfully.');

    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LeadInfo  $leadInfo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (User::checkPermission('lead.view') == true) {
            $data = LeadInfo::where('id', $id)->first();
            return view('pages.lead.view_lead', compact('data'));
        } else {
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
        if (User::checkPermission('lead.update') == true) {
            $data = LeadInfo::where('id', $id)->first();
            $users = User::where(['is_active' => 1, 'type' => 'crm'])->get(['id', 'name']);
            $lead_source = LeadSource::where('is_active', '1')->get();
            return view('pages.lead.edit_lead', compact('data', 'users', 'lead_source'));
        } else {
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
        if (User::checkPermission('lead.update') == true) {
            $validated = $request->validate([
                'assigned_to' => 'required',
                'name' => 'required',
                'phone' => 'required',
                'lead_query' => 'required',
            ]);

            $update = DB::table('lead_infos')->where('id', $id)->update([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'lead_query' => $request->lead_query,
                'source' => $request->source,
                'note' => $request->note,
                'status' => $request->status,
                'assigned_to' => $request->assigned_to,
                'updated_at' => Carbon::now(),
            ]);

            if ($update) {
                DB::table('moments_traffic')->insert(['user_id' => Auth::user()->id, 'info' => 'Updated Lead info Name: ' . $request->name . ', Phone: ' . $request->phone . '', 'created_at' => Carbon::now()]);
                return Redirect()->route('admin.all.lead.info')->with('success', 'Updated Successfully.');
            } else {
                return Redirect()->back()->with('error', 'Error occoured! Please Try Again.');
            }
        } else {
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

