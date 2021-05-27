<?php

namespace Modules\Home\Http\Controllers\agency\patient;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, Hash, Session, Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MaddHatter\LaravelFullcalendar\Event;
// -------------------------------------------------------
// Models Section
// -------------------------------------------------------
use App\Calendar, App\UserCompany,
App\VEvent, App\Patient, App\State, App\Program, App\User,
App\RolePermission, App\PatientUserCompany,
App\TabCodeDescription, App\UserCompanyPermission,
App\ContractorPatient, App\AgencyContractor,
App\Agency, App\GeneralLookup, App\Admission,
App\Episode, App\CompanyProgram, App\CompanyDiscipline,
App\CompanyDisciplineVisitType, App\VisitType,
App\UserCompanyDiscipline, App\Discipline,
App\PatientInsurance, App\PatientContact, App\AgencyDoctorOffice,
App\PatientContractor, App\ContractorReferral, App\ContractorReferralDiscipline,
App\DoctorOffice, App\PatientNote;

class PatientController extends Controller
{
    public function Index(Request $request){

        $user_id            = Auth::user()->id;
        $role_id            = Session::get('role_id');
        $user_company_id    = Session::get('user_company_id');
        $company_id         = Session::get('company_id');
        $type               = Session::get('type');
        $product_id         = Session::get('product_id');
        $company_name       = Session::get('company_name');

        // dd($user_company_id);

        if ($request->input('client')) {

            // checking role permission

            $permissions = UserCompanyPermission::where('user_companies_id',$user_company_id)->where('allow_permission','1')->with('permission_data')->whereHas("permission_data",function($q){
                $q->where('description','like','%'.'Allow user to see all the patients of the company'.'%'); // if user have permission to access all patients
            })->first();


            $permission_flag = false;
            $teamAddUrl = "";
            if(empty($permissions)){ //if user does not have permission to access all patient. We will show agency patients
                $patientsIDS = PatientUserCompany::where('user_companies_id',$user_company_id)->pluck('patient_id')->toArray();
                $details = Patient::with('status','agency')->whereIn('id',$patientsIDS)->orderBy('last_name','asc')->get();
                $teamAddUrl = url('user/add/patient/team/'.base64_encode($user_company_id));

            }else{
                if ($type=='A') {

                    $details = Patient::with(['status' => function($query) {
                        $query->where('field_name', 'Patient Status');
                    },'agency'])
                        ->where('agency_id',$company_id)
                        ->orderBy('last_name','asc')
                        ->get(); // if persmission then
                }else{
                    //for contractor side
                    $ids = ContractorPatient::where('contractor_id',$company_id)
                        ->pluck('patient_id');
                    $details = Patient::with(['status' => function($query) {
                        $query->where('field_name', 'Patient Status');
                    },'agency'])
                        ->whereIn('id',$ids)
                        ->orderBy('last_name','asc')
                        ->get();
                }
                $permission_flag = true;
            }

            $records = array();
            if($permission_flag)
            {
                foreach ($details as $value) {

                    $edit_url           = url('user/agency/patient/edit/'.base64_encode($value['id']));
                    $js_url           = 'javascript:;';
                    $cal_url           = url('user/agency/patient/calander/'.base64_encode($value['id']));
                    $delete_url         = url('user/agency/patient/delete/'.base64_encode($value['id']));


                    $action = '<div class="btn-group">
                               <a href="' . $edit_url . '" title="Edit" class="all-edit-tabs edit btn-white btn btn-xs">Edit</a>
                               <a href="' . $js_url . '" title="Pt-Chart" class="all-edit-tabs edit btn-white btn btn-xs">Pt-Chart</a>
                               <a href="' . $cal_url . '" title="Calendar" class="all-edit-tabs edit btn-white btn btn-xs">Calendar</a>
                               <a href="' . $js_url . '" title="Visit Log" class="all-edit-tabs edit btn-white btn btn-xs">Visit Log</a>
                               </div>';
                                // <a href="'.$delete_url.'" title="Delete" class="del_btn btn-white btn btn-xs">Delete</a>



                    $records[] = array(
                        'id'            => $value['id'],
                        'name'          => ucfirst($value['last_name'].' '.$value['first_name']),
                        'phone'         => $value['primary_phone'],
                        'status'         => $value['status']['option'] ?? '',
                        'agency'         => ucfirst($value['agency']['agency_name']),
                        'action'        => $action,
                    );


                }
            }else{
                foreach ($details as $value) {

                    //get ids of user_comany_user
                    $pat = PatientUserCompany::where('patient_id',$value['id'])->where('user_companies_id',$user_company_id)->first();


                    $delete_url         = url('user/company/patient/delete/'.base64_encode($pat->id));

                    $action = '<div class="btn-group">
                               <a href="'.$delete_url.'" title="Delete" class="del_btn btn-white btn btn-xs">Delete</a>
                               </div>
                                ';


                    $records[] = array(
                        'id'            => $value['id'],
                        'name'          => ucfirst($value['last_name'].' '.$value['first_name']),
                        'phone'         => $value['primary_phone'],
                        'status'         => $value['status']['option'] ?? '',
                        'agency'         => ucfirst($value['agency']['agency_name']),
                        'action'        => $action,
                    );


                }
            }

            return response()->json(['array' => $records, 'permission' => $permission_flag ,'teamAddUrl' => $teamAddUrl]);
            // return $records;
        }

        // dd($type);

        $page = 'Patient_List';
        return view('home::agency.patient.index',compact('page','type'));
    }

    public function calendar(Request $request,$id)
    {
        $patient_id = base64_decode($id);
        $lastAdmission_episodes = Admission::where('patients_id', $patient_id)
            ->with(['episodes' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->orderBy('created_at', 'desc')->first();
        $patient = Patient::where('id', $patient_id)->get();
        $program = Program::where('id', $patient[0]->programs_id)->get();
        if ($program[0]->calendar_view == '60 Days')
            $data['calendar_type'] = 'tenWeek';
        else if ($program[0]->calendar_view == 'Open') {
            $data['calendar_type'] = 'oneYear';
        }
        $data['start_date'] = $lastAdmission_episodes->soc_date;
        $data['end_date'] = ($lastAdmission_episodes['episodes'][0]->start_date ? $request->end_date : NULL);

        if($data['calendar_type'] == 'tenWeek'){
            $from = Carbon::parse($lastAdmission_episodes->soc_date);
            $to = \Carbon\Carbon::parse($lastAdmission_episodes['episodes'][0]->start_date)->addDays(59);
        }else if($data['calendar_type'] == 'oneYear'){
            $from = Carbon::parse($lastAdmission_episodes->soc_date);
            $to = \Carbon\Carbon::parse($lastAdmission_episodes['episodes'][0]->start_date)->addYear();
        }

        if($request->ajax()){

            $calendar_table = (new Calendar())->getTable();

            $calendar_visit_task = Calendar::select('visit_tasks_id')
                ->where('episodes_id', $lastAdmission_episodes['episodes'][0]->id)
                ->groupBy('visit_tasks_id')
                ->get();

            $visits_model_list = [];
            $model_temp = '';

            foreach($calendar_visit_task as $visit) {
                $visitType = VisitType::select('model_path')
                    ->where('id', $visit->visit_tasks_id)
                    ->first();
                $visits_model_list[] = $visitType->model_path;
            }

            $calendar_data = [];

            foreach($visits_model_list as $_Model) {
                $calendar = \App\Calendar::query();
                $_ModelTemp = (new $_Model())->getTable();
                $calendar->select($calendar_table . '.id',
                    DB::raw('visit_date_time AS start'),
                    DB::raw('visit_date_time AS end'),
                        $_ModelTemp . '.id AS title', 'visit_tasks_id',
                        'user_companies_id', 'visit_notes', 'visit_status_idx');

                if($data['calendar_type'] == 'tenWeek'){
                    //$from = Carbon::parse($lastAdmission_episodes->soc_date);
                    //$to = \Carbon\Carbon::parse($lastAdmission_episodes['episodes'][0]->start_date)->addDays(59);
                    $calendar->whereBetween('visit_date_time', [$from, $to]);
                }else if($data['calendar_type'] == 'oneYear'){
                    //$from = Carbon::parse($lastAdmission_episodes->soc_date);
                    //$to = \Carbon\Carbon::parse($lastAdmission_episodes['episodes'][0]->start_date)->addYear();
                    $calendar->whereBetween('visit_date_time', [$from, $to]);
                }else if($data['calendar_type'] == 'open'){
                    //TODO show events per month view
    //                $from = Carbon::parse($data['start_date']);
    //                $to = \Carbon\Carbon::parse($data['end_date'])->addYear();
    //                $calendar->whereBetween('visit_task_date', [$from, $to]);
                }
                $calendar->join($_ModelTemp, $calendar_table . '.id', '=', $_ModelTemp . '.calendar_id');
                $calendar->where('patients_id', $patient_id);
                $calendar_list = $calendar->get();
                foreach($calendar_list as $data) {
                    $calendar_data[] = $data;
                }
            }
            return $calendar_data;
        }

        $type = Session::get('type');
        $type_x = '';
        $data['patient_id'] = $id;
        $type == 'C' ? $type_x = 'C' : $type_x = 'A';
        $data['users'] = UserCompany::with('user')
            ->where('type', $type_x)
            ->where('company_id',Session::get('company_id'))
            ->get();



        $data['lastAdmission_episodes'] = $lastAdmission_episodes;
        $data['from'] = $from->format('m-d-Y');
        $data['to'] = $to->format('m-d-Y');
        return view('home::agency.patient.calendar', $data);
    }

    public function visitTaskSelect(Request $request, $type_view = null) {
        if ($request->ajax()) {
            $type = Session::get('type');
            $type_x = '';
            if ($type == 'C') {
                $type_x = 'App\Contractor';
            } else {
                $type_x = 'App\Agency';
            }
            if ($type_view == 'visitTypeOnlyName') {
                $visitTypeResponse = VisitType::select('name')
                    ->find($request->id);
                return response()->json($visitTypeResponse);
            }
            if ($request->data_user['type'] == 'single'){
                $visitTypeResponse = array();
                $company_discipline = CompanyDiscipline::where('company_id', $request->data_user['company_id'])
                    ->where('company_type', $type_x)
                    ->where('discipline_id', $request->data_user['discipline_id'])
                    ->first();
                if(count( (array) $company_discipline) >= 1) {
                    $companyDisciplineVisitType = CompanyDisciplineVisitType::where('company_discipline_id', $company_discipline->id)
                        ->get();
                    if (count( (array) $companyDisciplineVisitType) >= 1) {
                        foreach($companyDisciplineVisitType as $comDisVT) {
                            $visitType = VisitType::where('id', $comDisVT->visit_type_id)->first();
                            $visitTypeResponse[] = $visitType;
                        }
                    }
                }
            }
            /*else {
                foreach($request->data_user['discipline_id'] as $discipline) {
                    $company_discipline = CompanyDiscipline::where('company_id', $request->data_user['company_id'])
                        ->where('company_type', $type_x)
                        ->where('discipline_id', $discipline['id'])
                        ->first();
                    $company_discipline_list[] = $company_discipline;
                }

                foreach($company_discipline_list as $dis) {
                    $companyDisciplineVisitType = CompanyDisciplineVisitType::where('company_discipline_id', $dis->id)
                    ->get();
                    $companyDisciplineVisitType_list[] = $companyDisciplineVisitType;
                }

                foreach($companyDisciplineVisitType_list as $value) {
                    foreach($value as $v) {
                        $visitType = VisitType::where('id', $v['visit_type_id'])->first();
                        $visitTypeResponse[] = $visitType;
                    }
                }
            }*/
            return response()->json($visitTypeResponse);
        }
    }

    public function userCompanyDisciplineSelect(Request $request) {
        $user_disciplines = UserCompanyDiscipline::where('user_company_id', $request->data_user['user_id'])
            ->get();
        $discipline_list = [];
        foreach($user_disciplines as $u_d) {
            $discipline = Discipline::find($u_d->discipline_id);
            $discipline_list[] = $discipline;
        }
        return response()->json($discipline_list);
    }
    /**
     *
     */
    public function createTask(Request $request){
        DB::beginTransaction();
        try {
            $calendar = new Calendar();
            $calendar->patients_id = base64_decode($request->patient_id);
            $calendar->episodes_id = $request->episodes_id;
            $calendar->user_companies_id = $request->user;

            $date = str_replace(',', '-', preg_replace("/\s*([\/: ])\s*/", '', strstr($request->date, ':')));
            $time = str_replace(' ', '', $request->time);
            /*convert to 24 hr format*/
            $time = date("H:i", strtotime($time));
            $date = Carbon::createFromFormat('M-d-Y H:i', "{$date} {$time}");
            $calendar->visit_date_time = $date;

            $calendar->visit_notes = $request->comment;
            $calendar->visit_status_idx = 1;
            $calendar->visit_tasks_id = $request->visit_tasks_id;
            $calendar->visit_notes = $request->comment;

            if ($calendar->save()) {
                $visitType = VisitType::find($calendar->visit_tasks_id);

                $model_path = $visitType->model_path;
                $_Model = new $model_path();
                $_Model->calendar_id = $calendar->id;
                $_Model->created_by = Auth::user()->id;
                $_Model->save();
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            DB::commit();
            echo $e; exit;
            return response()->json(['message' => 'error', 'status' => '0']);
        }
        DB::commit();
        return response()->json(['message' => 'success', 'status' => '1']);
    }

    public function editTask(Request $request){
        $messages = '';
        $type = '';
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                $calendar = Calendar::find($request->calendar_id);
                $lastAdmission_episodes = Admission::where('patients_id', $calendar->patients_id)
                    ->with(['episodes' => function($query) {
                        $query->orderBy('created_at', 'desc');
                    }])
                    ->orderBy('created_at', 'desc')
                    ->first();
                $patient = Patient::find($calendar->patients_id);
                $program = Program::find($patient->programs_id);
                if ($program->calendar_view == '60 Days')
                    $data['calendar_type'] = 'tenWeek';
                else if ($program->calendar_view == 'Open') {
                        $data['calendar_type'] = 'oneYear';
                }
                $data['start_date'] = $lastAdmission_episodes->soc_date;
                $data['end_date'] = ($lastAdmission_episodes['episodes'][0]->start_date ? $request->end_date : NULL);
                if($data['calendar_type'] == 'tenWeek'){
                    $from = Carbon::parse($lastAdmission_episodes->soc_date);
                    $to = \Carbon\Carbon::parse($lastAdmission_episodes['episodes'][0]->start_date)->addDays(59);
                }else if($data['calendar_type'] == 'oneYear'){
                    $from = Carbon::parse($lastAdmission_episodes->soc_date);
                    $to = \Carbon\Carbon::parse($lastAdmission_episodes['episodes'][0]->start_date)->addYear();
                }

                $visit_date = str_replace(',', '-', preg_replace("/\s*([\/: ])\s*/", '', strstr($request->date, ':')));
                $time = str_replace(' ', '', $request->time);

                $time = date("H:i", strtotime($time));
                $date = Carbon::createFromFormat('M-d-Y H:i', "{$visit_date} {$time}");
                if (strtotime($visit_date) >= strtotime($from) && strtotime($visit_date) <= strtotime($to)) {
                    $test = "It is success";
                    if ($calendar->visit_status_idx == 1) {
                        if ($calendar->visit_tasks_id !== $request->visit_tasks_id) {
                            $visitType_delete = VisitType::find($calendar->visit_tasks_id);
                            $model_path = $visitType_delete->model_path;

                            $model_path::where('calendar_id',  $request->calendar_id)->delete();

                            // New Type
                            $visitType_create = VisitType::find($request->visit_tasks_id);
                            $model_path = $visitType_create->model_path;
                            $_Model = new $model_path();
                            $_Model->calendar_id = $calendar->id;
                            $_Model->created_by = Auth::user()->id;

                            if ($_Model->save()) {
                                $calendar->visit_tasks_id = $request->visit_tasks_id;

                                $messages .= "Visit Type Changed\n";
                            }
                        }
                        $calendar->user_companies_id = $request->user;
                        $calendar->visit_tasks_id = $request->visit_tasks_id;

                        //echo dd($date);
                        $calendar->visit_date_time = $date;

                        $calendar->visit_notes = $request->visit_notes;
                        $calendar->updated_by = Auth::user()->id;


                        if ($calendar->save()) {
                            $messages .= 'Update is success';
                        }
                        $type = 'success';
                    } else {
                        $messages = '';
                        $messages .= "It is not Schedule Status";
                        $type = 'error';
                    }
                } else {
                    $messages = "Visit date has to be in the episode date!";
                    $type = 'error';
                }
            }
            catch (\Exception $e) {
                DB::rollback();
                DB::commit();
                echo $e; exit;
                return response()->json(['message' => $messages, 'type' => $type]);
            }
            DB::commit();
            return response()->json(['message' => $messages, 'type' => $type]);
         }
    }

    public function openTask($calendar_id, $visit_tasks_id) {
        $visit_tasks_id = base64_decode($visit_tasks_id);
        $visitType = VisitType::where('id', $visit_tasks_id)
            ->first();
        return redirect(route("user.agency.patient.calendar_visits.{$visitType->controller_path}", $calendar_id));
    }

    public function visitForm(Request $request,$patient_id,$visit_id)
    {
        $visit = VVisit1::where('v_visit1_id', base64_decode($visit_id))
            ->whereHas('calendar', function (Builder $query) use ($patient_id) {
                $query->where('patient_id', base64_decode($patient_id));
            })
            ->with('calendar')->firstOrFail();
        if($request->isMethod('post')){
            $visit->name = $request->name;
            $date = str_replace(',', '-', preg_replace("/\s*([\/: ])\s*/", '', strstr($request->visit_date, ':')));
            $date = Carbon::createFromFormat('M-d-Y', $date);
            $visit->date = $date;
            $visit->date = $date;
            $visit->comment = $request->comment;

            $visit->calendar->visit_task_date = $date;
            if($request->has('time_in') && $request->time_in != ''){
                $time_in = str_replace(' ', '', $request->time_in);
                $time_in = date("H:i", strtotime($time_in));
                $visit->calendar->time_in = $time_in;
            }

            if($request->has('time_out') != null && $request->time_out != ''){
                $time_out = str_replace(' ', '', $request->time_out);
                $time_out = date("H:i", strtotime($time_out));
                $visit->calendar->time_out = $time_out;
            }

            $visit->calendar->comment = $request->comment;
            $visit->push();

            return redirect(session()->get('visit_form_redirect_url'));
        }
        $visit->duration = null;

        if ($visit->time_in != null && $visit->time_out != null) {
            $time_in = Carbon::parse($visit->time_in);
            $time_out= Carbon::parse($visit->time_out);
            $duration = $time_in->diffInSeconds($time_out);
            $duration = gmdate('H:i:s', $duration);
            $visit->duration = $duration;
        }

        return view('home::agency.patient.visit_form', compact('visit'));
    }

    public function AddTeamPatient(Request $request,$user_company_id){

        if($request->isMethod('post')){
            $data = $request->all();
            $obj = new PatientUserCompany();
            $obj->user_companies_id = base64_decode($user_company_id);
            $obj->patient_id = $data['patient_id'];

            $obj->save();

            return redirect()->back()->with('success','Patient added successfully');

        }

        $programs = Program::get();
        $page = 'Patient_List';
        $patientsIDS = PatientUserCompany::where('user_companies_id',base64_decode($user_company_id))->pluck('patient_id')->toArray();
        $listPatient = Patient::whereNotIn('id',$patientsIDS)->get();
        return view('home::agency.company_user_patient.add-patient',compact('page','listPatient','programs'));
    }

    public function deleteCompanyUser(Request $request,$company_user_id){
        PatientUserCompany::where('id',base64_decode($company_user_id))->delete();
        return redirect()->back()->with('success','Patient deleted successfully');
    }

    public function Add(Request $request, $agency_id = null){

        $agency_selected = null;
    	$user_id            = Auth::user()->id;
    	$role_id            = Session::get('role_id');
        $user_company_id    = Session::get('user_company_id');

        if ($agency_id == null) {
            $company_id         = Session::get('company_id');
            Session::put('multiple_agency_id_selected', $company_id);
        }
        else {
            $company_id =  base64_decode($agency_id);
            $agency_selected = base64_decode($agency_id);
            Session::put('multiple_agency_id_selected', $agency_selected);
        }
        //session()->save();
        //echo dd(Session::get('multiple_agency_id_selected'));
        //exit;
        $type               = Session::get('type');
        $product_id         = Session::get('product_id');
        $company_name       = Session::get('company_name');

    	if($request->isMethod('post')){
    		$data = $request->all();


            $patient_contact_length = count((array) $request->contact_name);
            $patient_insure_length = count((array) $request->insurance_type);
            //echo $patient_insure_length ."件";

            DB::beginTransaction();
            try {
                $patient = new Patient();

                if ($type == 'C') {
                    $patient->agency_id = $data['agency_id'];
                } else{
                    $patient->agency_id = $company_id;
                }

                $patient->programs_id = $data['programs_id'];
                $patient->first_name = $data['first_name'];
                $patient->last_name = $data['last_name'];
                $patient->middle_initial     = $data['middle_initial'];
                $patient->date_of_birth = $data['date_of_birth'];
                $patient->sex = $data['sex'];
                $patient->marital_status_idx = $data['marital_status_idx'];
                $patient->soc_sec_nbr = $data['soc_sec_nbr'];
                $patient->address = $data['address'];
                $patient->city = $data['city'];
                $patient->states_id = $data['state'];
                $patient->zip_code = $data['zip_code'];
                $patient->primary_phone = $data['primary_phone'];
                $patient->secondary_phone = $data['secondary_phone'];
                //$patient->fax = $data['fax'];
                $patient->email = $data['email'];
                $patient->dnr_orders = $data['dnr_orders'];
                $patient->geo_latitud = $data['latitude'];
                $patient->geo_longitud = $data['longitude'];
                $patient->agency_patient_status_idx = $data['agency_patient_status_idx'];
                $patient->mrn_case_nbr = $data['mrn_case_nbr'];
                //$patient->notes = $data['notes'];
                $patient->treating_doctor_office_id = $data['treating_doctor_office_id'] == null ? 0 : base64_decode($data['treating_doctor_office_id']);
                $patient->created_by = $user_id;

                if($patient->save()){
                    if ($data['notes'] != '') {
                        $patient_note = new PatientNote;
                        $patient_note->patient_id = $patient->id;
                        $patient_note->note_type = 0;
                        $patient_note->notes_descrip = $data['notes'];
                        $patient_note->created_by = $user_id;
                        $patient_note->save();
                    }
                    if ($type == 'C') {
                        /* maybe it can be deleted
                        $patient_contractor = new PatientContractor;
                        $patient_contractor->patient_id = $patient->id;
                        $patient_contractor->contractor_id = Session::get('company_id');
                        $patient_contractor->status = 'A';
                        $patient_contractor->created_by = Auth::user()->id;
                        $patient_contractor->save();*/
                        $contractor_patient = new ContractorPatient;
                        $contractor_patient->contractor_id = Session::get('company_id');
                        $contractor_patient->patient_id = $patient->id;
                        $contractor_patient->co_patient_status = '1';
                        $contractor_patient->thera_referral_user_companies_id = 0;
                        $contractor_patient->save();
                    }
                    $admission = new Admission();
                    $admission->patients_id = $patient->id;
                    $admission->admission_date = $data['referral_date'];
                    $admission->soc_date = $data['soc_date'];
                    $admission->referral_sources_id = $request->has('referral_sources_id') ? $data['referral_sources_id'] : null;
                    $admission->diagnosis_1 = $data['diagnosis_1'];
                    $admission->diagnosis_2 = $data['diagnosis_2'];
                    $admission->physician_instructions = $data['physician_instructions'];
                    if ($type == 'A')
                        $admission->referral_notes = $data['referral_notes'];
                    $admission->referral_doctor_office_id = $data['referral_doctor_office_id'] == null ? 0 : base64_decode($data['referral_doctor_office_id']);
                    $admission->created_by = $user_id;
                    if($admission->save()){
                        $episode = new Episode();
                        $episode->admissions_id = $admission->id;
                        $episode->start_date = $data['start_date'];
                        $episode->end_date = $data['end_date'];
                        $episode->created_by = $user_id;
                        if ( $episode->save() ) {
                            if ($type == 'C') {
                                $contractor_referral = new ContractorReferral;
                                $contractor_referral->episode_id = $episode->id;
                                $contractor_referral->contractor_id = Session::get('company_id');
                                $contractor_referral->status = 'A';
                                $contractor_referral->referral_datetime = $data['referral_date'];
                                $contractor_referral->special_orders_notes = $data['referral_notes'];
                                $contractor_referral->created_by = Auth::user()->id;
                                if ( $contractor_referral->save() ) {
                                    if ($request->has('contractor_referral_discipline_id')) {
                                        $discipline_length = count((array) $data['contractor_referral_discipline_id']);
                                        if ($discipline_length >= 1) {
                                            for($i = 0; $i <= $discipline_length - 1; $i++) {
                                                $referral_discipline = new ContractorReferralDiscipline;
                                                $referral_discipline->contractor_referral_id = $contractor_referral->id;
                                                $referral_discipline->discipline_id = $data['contractor_referral_discipline_id'][$i];
                                                $referral_discipline->created_by = Auth::user()->id;
                                                $referral_discipline->save();
                                            }
                                        }
                                    }
                                }
                            }
                            if ($patient_insure_length >= 1) {
                                for ($i = 0; $i <= $patient_insure_length - 1; $i++) {
                                    $patient_insure = new PatientInsurance();
                                    $patient_insure->patient_id = $patient->id;
                                    $patient_insure->insurance_type = $data['insurance_type'][$i];
                                    $patient_insure->insurance_id = $data['insurance_id'][$i];
                                    $patient_insure->primary_secondary = $data['primary_secondary'][$i];
                                    $patient_insure->name = $data['insurance_name'][$i];
                                    $patient_insure->created_by = $user_id;
                                    $patient_insure->save();
                                }
                            }

                            if ($patient_contact_length >= 1) {
                                for ($i = 0; $i <= $patient_contact_length - 1; $i++) {
                                    //echo $data['contact_name'][$i].'-->';
                                    $patient_contact = new PatientContact;
                                    $patient_contact->patient_id = $patient->id;
                                    $patient_contact->name = $data['contact_name'][$i];
                                    $patient_contact->relationship = $data['relationship'][$i];
                                    $patient_contact->phone1 = $data['contact_phone'][$i];
                                    $patient_contact->email = $data['contact_email'][$i];
                                    $patient_contact->created_by = $user_id;
                                    $patient_contact->save();
                                }
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                DB::rollback();
                DB::commit();
                echo $e; exit;
                return redirect('user/agency/patients')->with('error',COMMON_ERROR);
            }
            DB::commit();
            return redirect(url('user/agency/patient/edit/'.base64_encode($patient->id)))->with('success','Patient added successfully');
    	}

        $states = State::all();
        $programs = Program::get();
        $status_patient_list = GeneralLookup::where('field_name', 'Patient Status')->get();
        $marital_status_list = GeneralLookup::where('field_name', 'Marital Status')->get();

        $agencies = null;
        $discipline_list = [];
        if ($type == 'C') {
            $ids = AgencyContractor::where('contractor_id',Session::get('company_id'))
                ->pluck('agency_id');
            $agencies = Agency::whereIn('id',$ids)->get();
            $company_discipline = CompanyDiscipline::select('discipline_id')
            ->where('company_id', Session::get('company_id'))
            ->get();
            if (is_iterable($company_discipline)) {
                foreach($company_discipline as $cd) {
                    $discipline_list[] = Discipline::find($cd->discipline_id);
                }
            }
           // echo dd($discipline_list); exit;
        }
        $agency_state = Agency::select('state_id')
            ->where('id', $company_id)
            ->get();
        $program_id_company = CompanyProgram::select('programs_id')
            ->where('company_id', Session::get('company_id'))
            ->get();

        $company_programs = [];
        $program_list = [];
        if (is_iterable($program_id_company)) {
            foreach($program_id_company as $key => $value) {
                $program_list = Program::select('id', 'name', 'calendar_view', 'display_dnr', 'doctor_required')
                ->where('id', $value->programs_id)->first();
                $company_programs[$program_list->id] = $program_list;
            }
        }

        $page = 'Patient_List';
    	return view('home::agency.patient.add_patient',compact('page','states','programs',
            'type','agencies', 'status_patient_list', 'marital_status_list', 'agency_state',
        'program_id_company', 'program_list', 'company_programs', 'agency_selected', 'discipline_list'));
    }

    public function Edit(Request $request,$patient_id = null){

        $patient_id         = base64_decode($patient_id);
        $user_id            = Auth::user()->id;
        $role_id            = Session::get('role_id');
        $user_company_id    = Session::get('user_company_id');
        $type               = Session::get('type');
        $company_id         = Session::get('company_id');
        $product_id         = Session::get('product_id');
        $company_name       = Session::get('company_name');

        $patient = Patient::find($patient_id);
        // prx($patient);
        Session::put('multiple_agency_id_selected', $patient->agency_id);
        if($request->isMethod('post')){
            $data = $request->all();
            //echo dd($_FILES);
            //echo dd($data); exit;
            $insurance_length = 0;
            $patient_contact_length = 0;
            if ($request->has('insurance_key'))
                $insurance_length = count((array) $data['insurance_key']);
            if ($request->has('emergency_contact_key'))
                $patient_contact_length = count((array) $data['emergency_contact_key']);
            $patient->programs_id = $data['programs_id'];
            $patient->first_name = $data['first_name'];
            $patient->last_name = $data['last_name'];
            $patient->middle_initial     = $data['middle_initial'];
            $patient->date_of_birth = $data['date_of_birth'];
            $patient->sex = $data['sex'];
            $patient->marital_status_idx = $data['marital_status_idx'];
            $patient->soc_sec_nbr = $data['soc_sec_nbr'];
            $patient->address = $data['address'];
            $patient->city = $data['city'];
            $patient->states_id = $data['state'];
            $patient->zip_code = $data['zip_code'];
            $patient->primary_phone = $data['primary_phone'];
            $patient->secondary_phone = $data['secondary_phone'];
            //$patient->fax = $data['fax'];
            $patient->email = $data['email'];
            // $patient->userID = $data['first_name']; //will update
            $patient->dnr_orders = $data['dnr_orders'];
            $patient->geo_latitud = $data['latitude'];
            $patient->geo_longitud = $data['longitude'];
            $patient->agency_patient_status_idx = $data['agency_patient_status_idx'];
            $patient->mrn_case_nbr = $data['mrn_case_nbr'];
            $patient->notes = $data['notes'];
            $patient->treating_doctor_office_id = base64_decode($data['treating_doctor_office_id']);
            $patient->updated_by = $user_id;

            /*if (!empty($_FILES)) {

                $filename                   = $_FILES['dnr_attachment']['name'];
                $tmp_name                   = $_FILES['dnr_attachment']['tmp_name'];
                $file_ext                   = pathinfo($_FILES['dnr_attachment']['name']);
                $extension                  = $file_ext['extension'];

                $time = time();
                $num = rand(1111,9999);
                $str = str_random(2);
                $random_number = $str.$time.$num;
                $new_name                   = $random_number.'.'.$extension;
                $destination                = PatientDNROrderPath.'/'.$new_name;
                $uploaded='';
                if ($extension == 'jpg'|| $extension == 'jpeg'|| $extension == 'png'||$extension == 'pdf'|| $extension == 'doc'|| $extension == 'docx'||$extension == 'xls'|| $extension == 'xlsx') {
                    move_uploaded_file($tmp_name, $destination);
                    $uploaded                = PatientDNROrderPath.'/'.$new_name;
                    $patient->dnr_attachment = $uploaded;
                }
            }*/
            if($patient->save()){
                if ($insurance_length >= 1) {
                    for ($i = 0; $i <= $insurance_length - 1; $i++) {
                        //echo $data['insurance_key'][$i]."-->";
                        if ($data['insurance_key'][$i] === "new_insurance") {
                            $patient_insure = new PatientInsurance;
                            $patient_insure->patient_id = $patient->id;
                            $patient_insure->insurance_type = $data['insurance_type'][$i];
                            $patient_insure->insurance_id = $data['insurance_id'][$i];
                            $patient_insure->primary_secondary = $data['primary_secondary'][$i];
                            $patient_insure->name = $data['insurance_name'][$i];
                            $patient_insure->created_by = $user_id;
                            $patient_insure->save();
                        } else {
                            $patient_insure = PatientInsurance::find(base64_decode($data['insurance_key'][$i]));
                            $patient_insure->patient_id = $patient->id;
                            $patient_insure->insurance_type = $data['insurance_type'][$i];
                            $patient_insure->insurance_id = $data['insurance_id'][$i];
                            $patient_insure->primary_secondary = $data['primary_secondary'][$i];
                            $patient_insure->name = $data['insurance_name'][$i];
                            $patient_insure->updated_by = $user_id;
                            $patient_insure->save();
                        }
                    }
                }
                if ($patient_contact_length >= 1) {
                    for ($i = 0; $i <= $patient_contact_length - 1; $i++) {
                        //echo $data['contact_name'][$i].'-->';
                        if ($data['emergency_contact_key'][$i] == "new_emergecy_contact") {
                            $patient_contact = new PatientContact;
                            $patient_contact->patient_id = $patient->id;
                            $patient_contact->name = $data['contact_name'][$i];
                            $patient_contact->relationship = $data['relationship'][$i];
                            $patient_contact->phone1 = $data['contact_phone'][$i];
                            $patient_contact->email = $data['contact_email'][$i];
                            $patient_contact->created_by = $user_id;
                            $patient_contact->save();
                        } else {
                            $patient_contact = PatientContact::find(base64_decode($data['emergency_contact_key'][$i]));
                            $patient_contact->patient_id = $patient->id;
                            $patient_contact->name = $data['contact_name'][$i];
                            $patient_contact->relationship = $data['relationship'][$i];
                            $patient_contact->phone1 = $data['contact_phone'][$i];
                            $patient_contact->email = $data['contact_email'][$i];
                            $patient_contact->updated_by = $user_id;
                            $patient_contact->save();
                        }
                    }
                }
                return redirect()->back()->with('success','Patient updated successfully');
            } else{
                return redirect()->back()->with('error',COMMON_ERROR);
            }
        }
        $lastAdmission = Admission::where('patients_id', $patient_id)
            ->with(['episodes' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->orderBy('created_at', 'desc')
            ->first();

        $states = State::all();
        $programs = Program::get();
        $program_id_company = CompanyProgram::select('programs_id')
            ->where('company_id', $patient->agency_id)
            ->get();
        $program_list = Program::select('calendar_view', 'display_dnr')
            ->where('id', $program_id_company[0]->programs_id)
            ->first();
        $patient_insure_list = PatientInsurance::where('patient_id', $patient_id)
            ->get();
        $patient_contact_list = PatientContact::where('patient_id', $patient_id)
            ->get();
        $patient_id = base64_encode($patient_id);
        $status_patient_list = GeneralLookup::where('field_name', 'Patient Status')
            ->get();
        $marital_status_list = GeneralLookup::where('field_name', 'Marital Status')
            ->get();
        $agency_state = Agency::select('state_id')
            ->where('id', $patient->agency_id)
            ->get();
        $ids = AgencyContractor::where('contractor_id',$company_id)
            ->pluck('agency_id');
        $agencies = Agency::whereIn('id',$ids)
            ->get();
        $physician = DoctorOffice::select('*', DB::raw('CONCAT(first_Name, " ", last_Name) AS full_name'))
            ->find($patient->treating_doctor_office_id);
        $page = 'Patient_List';
        if ($request->input('client')) {
            $records = array();
            if ($request->input('table') == 'emergency_contact') {
                $records = $this->getEmergencyContact($patient_contact_list);
            }
            return $records;
        }
        return view('home::agency.patient.patient_info',compact('patient','page','states',
            'programs','type','agencies', 'status_patient_list', 'patient_insure_list', 'patient_contact_list',
            'lastAdmission', 'marital_status_list', 'agency_state', 'program_list',
            'physician'));

    }

    public function Delete(Request $request,$patient_id){

        $patient_id = base64_decode($patient_id);
        $delete = Patient::where('id',$patient_id)->delete();

        if($delete){
            return redirect()->back()->with('success','Patient deleted successfully');
        }else{
            return redirect()->back()->with('error','Failed');
        }

    }

    public function ValidateEmail(Request $request){

        if ($request['id'] == null) {
            $count = Patient::where('email',$request['email'])
                            ->count();
        }else{
            $count = Patient::where('email',$request['semail'])
                            ->where('id','<>',base64_decode($request['id']))
                            ->count();
        }
        if ($count > 0) {
            return 'false';
        }else{
            return 'true';
        }
    }

    public function getAgencyDoctorOffice() {
        $agency_id = Session::get('multiple_agency_id_selected');
        $doctor_office = AgencyDoctorOffice::select('doctor_office_id')
            ->where('agency_id',$agency_id)
            ->with('doctor')
            ->get();
        return $doctor_office;
    }

    public function deletePatientInsurance(Request $request) {
        if ($request->ajax()) {
            $insure_id = base64_decode($request->id);
            $patient_insure = PatientInsurance::find($insure_id);
            $type = $patient_insure->delete() ? 'success' : 'error';
            return response()->json(['type' => $type]);
        }
    }

    public function deleteEmergencyContact(Request $request) {
        if ($request->ajax() || $request->input('id')) {
            $emergency_contact_id = base64_decode($request->id);
            $patient_contact = PatientContact::find($emergency_contact_id);
            if ($patient_contact->delete()) {
                if ($request->from == 'grid') {
                    return redirect()->back()->with('success','Patient deleted successfully');
                }
                $type = 'success';
            }
            else
                $type = 'error';
            return response()->json(['type' => $type]);
        }
        echo dd($request);
    }

    public function getEmergencyContact($emergency_contact_list) {
        $time_zone          = Session::get('time_zone');
        foreach( $emergency_contact_list as $value ) {
            $delete_url         = route('patient.delete.emergency_contact', [
                'id' => base64_encode($value['id']),
                'from' => 'grid'
                ]);
            $view_url         = url('user/internal-email/view/'.base64_encode($value['id']).'/'.base64_encode($value['ierid']));
            $action = '<div class="btn-group">
                        <a href="'.$delete_url.'" title="Delete" class="del_btn btn-white btn btn-xs">Delete</a>
                        <a href="'.$view_url.'" title="View" class="btn-white btn btn-xs">View</a>
                </div>
            ';
            $records[] = array(
                'id'   => $value['id'],
                'name' => "{$value['name']}",
                'relationship' => $value['relationship'],
                'phone1' => $value['phone1'],
                'phone2' => $value['phone'],
                'email' => $value['email'],
                'created_at'    => date('m-d-Y h:i A',strtotime('-'.$time_zone.' hour',strtotime($value['created_at']))),
                'updated_at'    => date('m-d-Y h:i A',strtotime('-'.$time_zone.' hour',strtotime($value['updated_at']))),
                'action' => $action
            );
        }
        return $records;
    }

}
