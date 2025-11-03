<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LeadInfoController;
use App\Http\Controllers\AppiontmentController;
use App\Http\Controllers\CRMController;
use App\Http\Controllers\LeadSourceController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SentSMSController;







Route::get('/fr', [Admincontroller::class, 'finger_print_test']);



Route::group(['middleware' => ['auth', 'UserIsActive']], function () {


    Route::get('/', [Admincontroller::class, 'dashboard'])->name('index');

    Route::get('/dashboard', [Admincontroller::class, 'dashboard'])->name('dashboard');

	//Sale
	Route::resource('sale', SaleController::class);
	Route::get('calendar', [SaleController::class,'calendar'])->name('calendar');
	Route::get('bounce', [SaleController::class,'bounce'])->name('bounce');

    //Begin::Lead Source Type
    Route::get('/lead-sources', [LeadSourceController::class, 'index'])->name('admin.lead.source.type');
    Route::post('/admin/create-lead-source-type', [LeadSourceController::class, 'store'])->name('admin.create.lead.source.type');
    Route::get('/admin/edit-lead-source-type/{id}', [LeadSourceController::class, 'edit']);
    Route::post('/admin/update-lead-source-type-name/{id}', [LeadSourceController::class, 'update']);
    Route::get('/admin/deactive-source-type/{id}', [LeadSourceController::class, 'deactive']);
    Route::get('/admin/active-source-type/{id}', [LeadSourceController::class, 'active']);
    //End::Admin  CRM


    //Begin:: demo file download
        Route::get('/download/demo/{file_name}', function($file_name = null){
            $path = public_path().'/images/'.$file_name;
            if (file_exists($path)) {
                return Response::download($path);
            }
            else {
                return Redirect()->back()->with('error', 'No such file exist, Please try again.');
            }
        })->name('download.demo.file');
        //End:: demo file download


    //Begin:: Leads
    Route::get('/admin/leads', [LeadInfoController::class, 'index'])->name('admin.lead.info');
    Route::get('/admin/all-leads-data', [LeadInfoController::class, 'index_data'])->name('admin.all.leads.data');
    Route::get('/admin/create-lead', [LeadInfoController::class, 'create'])->name('admin.create.lead');
    Route::post('/admin/store-lead', [LeadInfoController::class, 'store'])->name('admin.store.lead');
    Route::get('/admin/edit-lead/{id}', [LeadInfoController::class, 'edit'])->name('admin.edit.lead');
    Route::post('/admin/upload-lead-info', [LeadInfoController::class, 'bulk_upload_lead_info'])->name('admin.upload.lead.info');
    Route::post('/admin/upload-lead-info-confirm', [LeadInfoController::class, 'admin_lead_upload_confrim'])->name('admin.lead.upload.confirm');
    Route::post('/admin/set-lead-note', [LeadInfoController::class, 'set_lead_note'])->name('set.lead.note');
    Route::post('/admin/update-lead-info/{id}', [LeadInfoController::class, 'update'])->name('admin.update.lead.info');
    Route::get('/admin/view-lead/{id}', [LeadInfoController::class, 'show'])->name('admin.view.lead');

    Route::get('/admin/all-leads-info', [LeadInfoController::class, 'all_lead_info'])->name('admin.all.lead.info');
    Route::get('/admin/pending-lead-re-assign', [LeadInfoController::class, 'pending_lead_re_assign'])->name('admin.pending.lead.re.assign');
    Route::post('/admin/store-lead-note-by-ajax', [LeadInfoController::class, 'store_lead_note_by_ajax'])->name('store.lead.note.by.ajax');
    Route::post('/admin/store-lead-status-by-ajax', [LeadInfoController::class, 'store_lead_status_by_ajax'])->name('store.lead.status.by.ajax');
    /* Lead Assign */
    Route::get('/admin/lead_assign', [LeadInfoController::class, 'lead_assign'])->name('admin.lead_assign');
    Route::post('/admin/lead_assigned', [LeadInfoController::class, 'lead_assigned'])->name('admin.lead_assigned');



    //Begin:: Appiontments
    Route::get('/admin/appiontments', [AppiontmentController::class, 'index'])->name('admin.appiontments');
    Route::get('/admin/appiontments-data-info', [AppiontmentController::class, 'index_data'])->name('aadmin.all.appointments.data.info');
    Route::get('/admin/set-appiontment/{id}', [AppiontmentController::class, 'create'])->name('admin.set.appiontment');
    Route::post('/admin/store-appointment', [AppiontmentController::class, 'store'])->name('admin.store.appointment');
    Route::post('/admin/store-visitor-output', [AppiontmentController::class, 'store_visitor_output'])->name('set.visitor.output');
    Route::get('/appointment/{date}/{status}', [AppiontmentController::class, 'appointment_report'])->name('appointment.report');
    Route::get('/admin/appointment-report-data/{date}/{status}', [AppiontmentController::class, 'appointment_report_data']);
    Route::get('/admin/delete-appiontment/{id}', [AppiontmentController::class, 'destroy'])->name('admin.delete.appiontment');

    //Begin::SMS Routes
    Route::group(['prefix'=>'sms','as'=>'sms.'], function(){

        Route::get('/list', [SentSMSController::class, 'index'])->name('index');
        Route::get('/send', [SentSMSController::class, 'create'])->name('create');
        Route::post('/send_sms', [SentSMSController::class, 'send_sms'])->name('send');
        Route::post('/send_sale_sms', [SentSMSController::class, 'send_sale_sms'])->name('send_sale_sms');

    });
    //End::SMS Routes


    // Begin:: CRM role and permission
    Route::get('/admin/crm-roles', [AdminController::class, 'crm_roles'])->name('crm.role.permission');
    Route::post('/admin/create-helper-role', [AdminController::class, 'Admin_Create_helper_role'])->name('admin.create.roll');
    Route::get('/admin/edit-role/{id}', [AdminController::class, 'edit_role']);
    Route::post('/admin/update-role/{id}', [AdminController::class, 'update_role']);
    Route::get('/admin/admin-helper-role-permissions/{id}', [AdminController::class, 'admin_helper_permission']);
    Route::get('/admin/set-permission-to-admin-helper-role', [AdminController::class, 'set_permission_to_admin_helper_role']);
    Route::get('/admin/delete-permission-from-role', [AdminController::class, 'delete_permission_from_role']);




    //Begin::Admin  CRM
    Route::get('/admin/all-crm', [CRMController::class, 'index'])->name('admin.crm');
    Route::post('/admin/create-crm', [CRMController::class, 'store'])->name('admin.create.crm');
    Route::get('/admin/edit-crm/{id}', [CRMController::class, 'edit']);
    Route::post('/admin/update-crm/{id}', [CRMController::class, 'update']);
    Route::get('/admin/deactive-crm/{id}', [CRMController::class, 'DeactiveCRM']);
    Route::get('/admin/active-crm/{id}', [CRMController::class, 'ActiveCRM']);
    //End::Admin  CRM










});
