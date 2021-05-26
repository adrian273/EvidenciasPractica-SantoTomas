<?php

namespace Modules\Home\Http\Controllers\agency\patient\calendar_visits;

use Modules\Home\Http\Controllers\agency\patient\calendar_visits\CalendarVisitBaseController;
// ----------------------------------------------
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// -------------------------------------
use Auth, Hash, Session, Mail;
use MaddHatter\LaravelFullcalendar\Event;
// -------------------------------------
use App\Calendar;
use App\UserCompany;
use App\VisitType;
use App\V31MusicYeswaiver;

class V31MusicYesWaiverController extends CalendarVisitBaseController
{

    public function index($calendar_id){

        $user_id            = Auth::user()->id;
        $role_id            = Session::get('role_id');
        $user_company_id    = Session::get('user_company_id');
        $company_id         = Session::get('company_id');
        $type               = Session::get('type');
        $product_id         = Session::get('product_id');
        $company_name       = Session::get('company_name');

        $data['users'] = UserCompany::with('user')
            ->where('type', 'A')
            ->where('company_id',Session::get('company_id'))
            ->get();
        $data['calendar'] = Calendar::find(base64_decode($calendar_id));

        $data['musicVisit'] = V31MusicYeswaiver::where('calendar_id', base64_decode($calendar_id))
            ->first();
        $data['visitStatusIdx'] = $this->visitStatusIdx($data['calendar']->visit_status_idx);
        return view('home::agency.patient.calendar_visits.music_visits.music_yeswaiver', $data);
    }

    public function edit(Request $request) {
        $calendar = $this->edit_calendar($request);
        if ($calendar->save()) {
            $musicYeswaiver = V31MusicYeswaiver::where('calendar_id', $calendar->id)
                ->first();
            //echo dd($musicYeswaiver);
            $musicYeswaiver->wlocation_therapy = $request->wlocation_therapy;
            $musicYeswaiver->session_type = $request->session_type;
            $musicYeswaiver->wpersons_present = $request->wpersons_present;
            $musicYeswaiver->wactivity_descrip = $request->wactivity_descrip;
            $musicYeswaiver->wspecific_skills = $request->wspecific_skills;
            $musicYeswaiver->wclient_response = $request->wclient_response;
            $musicYeswaiver->wsummary_activities = $request->wsummary_activities;
            $musicYeswaiver->wspecific_interven = $request->wspecific_interven;
            $musicYeswaiver->wgoals_objectives = $request->wgoals_objectives;
            $musicYeswaiver->wprogress_lack_progress = $request->wprogress_lack_progress;
            $musicYeswaiver->wgoal_focus = $request->wgoal_focus;
            $musicYeswaiver->wsuperv_signature = $request->wsuperv_signature;
            
            if ($musicYeswaiver->save())
                return redirect()->back()->with('success','successfully');
        }
       
    }
   
}
