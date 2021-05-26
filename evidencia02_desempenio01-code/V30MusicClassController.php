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
use App\V30MusicClass;
// -------------------------------------

class V30MusicClassController extends CalendarVisitBaseController
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

        $data['musicVisit'] = V30MusicClass::where('calendar_id', base64_decode($calendar_id))
            ->first();
        $data['visitStatusIdx'] = $this->visitStatusIdx($data['calendar']->visit_status_idx);
        return view('home::agency.patient.calendar_visits.music_visits.music_class', $data);
    }

    public function edit(Request $request) {
            $calendar = $this->edit_calendar($request);
            if ($calendar->save()) {
                $musicClass = V30MusicClass::where('calendar_id', $calendar->id)
                    ->first();
                $musicClass->activity = $request->activity;
                $musicClass->affect = $request->affect;
                $musicClass->goals = $request->goals;
                $musicClass->treat_provided = $request->treat_provided;
                $musicClass->client_response = $request->client_response;
                if ($musicClass->save())
                    return redirect()->back()->with('success','successfully');
            }
    }
   
}
