<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use App\Jobs\SendSmsJob;
use PDF;
use DataTables;
use App\Models\User;
use App\Http\Requests\SaleRequest;
use Illuminate\Http\RedirectResponse;


class SaleController extends Controller
{

    public function bounce(Request $request)
    {
        if(User::checkPermission('bounce.view') == true){
            $sales = DB::table('sales')
					->join('users as sallers', 'sallers.id', 'sales.saller_id')
					->join('users as installers', 'installers.id', 'sales.installer_id')
					->select('sales.*', 'sallers.name as saller', 'installers.name as installer')
					->orderBy('file_no', 'desc');
					if (!empty($request->search)) {
                $searchTerm = $request->search;

                $sales = $sales->where(function ($query) use ($searchTerm) {
                    $query->where('sales.name', 'like', '%' . $searchTerm . '%')
                          ->orWhere('sales.mobile', 'like', '%' . $searchTerm . '%')
                          ->orWhere('sales.address', 'like', '%' . $searchTerm . '%');
                });
            }
					if(Auth::user()->type=='crm'){
                        $sales = $sales->where('sales.saller_id',Auth::user()->id);
                    }
                    if(!empty($request->saller_id)){
                        $sales = $sales->where('sales.saller_id',$request->saller_id);
                    }
                    if(!empty($request->status)){
                        $sales = $sales->where('sales.status',$request->status);
                    }else{
                        $sales = $sales->where('sales.status','!=','Cancel');
                    }
                    if (!empty($request->start_date) && !empty($request->end_date)) {
                      $startDate = $request->start_date . ' 00:00:00';
                        $endDate = $request->end_date . ' 23:59:59';

                    $sales = $sales->whereBetween('sales.created_at', [$startDate, $endDate]);
                    }
					$sales = $sales->paginate(250);

            return view('pages.sale.bounce', compact('sales'));
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }

    }

    public function calendar()
    {
        // Fetch future installation events from the database
        $futureInstallations = Sale::where('installation_date', '>=', date('Y-m-d'))->orderBy('installation_date')->get();

        return view('pages.sale.calendar', compact('futureInstallations'));
    }

	public function index(Request $request)
    {
        if(User::checkPermission('sale.view') == true){
            $sales = DB::table('sales')
					->join('users as sallers', 'sallers.id', 'sales.saller_id')
					->join('users as installers', 'installers.id', 'sales.installer_id')
					->select('sales.*', 'sallers.name as saller', 'installers.name as installer')
					->orderBy('file_no', 'desc');
					if (!empty($request->search)) {
                $searchTerm = $request->search;

                $sales = $sales->where(function ($query) use ($searchTerm) {
                    $query->where('sales.name', 'like', '%' . $searchTerm . '%')
                          ->orWhere('sales.mobile', 'like', '%' . $searchTerm . '%')
                          ->orWhere('sales.address', 'like', '%' . $searchTerm . '%');
                });
            }
					if(Auth::user()->type=='crm'){
                        $sales = $sales->where('sales.saller_id',Auth::user()->id);
                    }
                    if(!empty($request->saller_id)){
                        $sales = $sales->where('sales.saller_id',$request->saller_id);
                    }
                    if(!empty($request->status)){
                        $sales = $sales->where('sales.status',$request->status);
                    }else{
                        $sales = $sales->where('sales.status','!=','Cancel');
                    }
                    if (!empty($request->start_date) && !empty($request->end_date)) {
                      $startDate = $request->start_date . ' 00:00:00';
                        $endDate = $request->end_date . ' 23:59:59';

                    $sales = $sales->whereBetween('sales.created_at', [$startDate, $endDate]);
                    }
					$sales = $sales->paginate(250);

            return view('pages.sale.index', compact('sales'));
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }

    public function create()
    {
        if(User::checkPermission('sale.add') == true){
            $sale = 0;
			$users = User::where(['type'=>'crm', 'is_active'=>1])->get(['id','name']);
            return view('pages.sale.create', compact('sale','users'));
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }

    public function store(SaleRequest $request)
    {
		$sale = Sale::create($request->validated());
        if($sale){
            //$this->send_sale_sms($sale);
            return redirect()->route('sale.index')->with('success', 'Sale added successfully.');
        }else{
            return redirect()->route('sale.index')->with('error', 'Sale added Failed.');
        }
    }

    public function send_sale_sms($sale){
        if($sale){
            //$sale = Sale::where('id',$request->id)->first();
            if($sale->bill_type=='Monthly'){
                $bill = 'Monthly charge:'.$sale->bill_amount;
            }else{
                $bill = 'Yearly charge:'.$sale->bill_amount;
            }
            $sms = 'Welcome To E-Hishab,Software price:'.$sale->installation_charge.',Advance:'.$sale->advance.',Due:'.$sale->due.','.$bill.',Date of confirmation:'.$sale->sale_date.',Date of installation:'.$sale->installation_date;
            if (!is_null($sale->mobile)) {
                $jobs = collect($sale->mobile)->map(function ($mobile) use ($sms) {
                    return new SendSmsJob($sms, $mobile);
                })->all();
                Bus::batch($jobs)->onQueue('sms_queue')->dispatch();
/*                 $output = ['status' => 'yes','success' => 'Send SMS',];
                return response()->json($output); */
            }
        }

    }

    public function show(Sale $sale)
    {
        if(User::checkPermission('sale.view') == true){
            return view('pages.sale.show', compact('sale'));
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }

    public function edit(Sale $sale)
    {
       if(User::checkPermission('sale.update') == true){
		   $users = User::where('type','crm')->get(['id','name']);
            return view('pages.sale.create', compact('sale','users'));
        }
        else {
            return Redirect()->back()->with('error', 'Sorry you can not access this page');
        }
    }

    public function update(SaleRequest $request, Sale $sale)
    {
		$sale->update($request->validated());
		return redirect()->route('sale.index')->with('success', 'Sale update successfully.');
    }

    public function destroy(Sale $sale): RedirectResponse
    {
        if (User::checkPermission('sale.delete')) {
			$sale->delete();

			return redirect()->route('sale.index')->with('success', 'Sale deleted successfully.');
		} else {
			return redirect()->back()->with('error', 'Sorry you do not have permission to delete a sale.');
		}
    }
}
