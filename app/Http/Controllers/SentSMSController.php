<?php

namespace App\Http\Controllers;

use App\Models\SentSMS;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Bus;
use App\Jobs\SendSmsJob;
class SentSMSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sms = SentSMS::with('user')->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(100);
        return view('pages.sms.index', compact('sms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.sms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SentSMS  $sentSMS
     * @return \Illuminate\Http\Response
     */
    public function show(SentSMS $sentSMS)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SentSMS  $sentSMS
     * @return \Illuminate\Http\Response
     */
    public function edit(SentSMS $sentSMS)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SentSMS  $sentSMS
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SentSMS $sentSMS)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SentSMS  $sentSMS
     * @return \Illuminate\Http\Response
     */
    public function destroy(SentSMS $sentSMS)
    {
        //
    }

    public function send_sms(Request $request) {
        $output = '';
        $contacts_output = '';
        $phones = $request->phone;

        if (!is_null($phones)) {
            $jobs = collect($phones)->map(function ($phone) use ($request) {
                return new SendSmsJob($request->sms_text, $phone);
            })->all();

            $batch = Bus::batch($jobs)
                ->onQueue('sms_queue')
                ->dispatch();

            $batchId = $batch->id;
            //Log::info('Sending SMS started. Batch ID:' . $batchId);

            $contacts_output .= '<tr>
                                    <td>' . implode(', ', $phones) . '</td>
                                    <td><span class="badge bg-success">Successfully Sent</span></td>
                                </tr>';

            $output = [
                'status' => 'yes',
                'success' => 'SMS Successfully Sent',
                'output' => $contacts_output,
            ];
            return response()->json($output);
        } else {
            $output = [
                'status' => 'no',
                'reason' => 'No Contacts Selected!',
            ];
            return response()->json($output);
        }


    }

}
