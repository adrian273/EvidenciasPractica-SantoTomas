<?php

namespace Modules\Home\Http\Controllers\agency\patient\calendar_visits;

use App\Http\Controllers\Controller;
use Auth, Hash, Session, Mail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MaddHatter\LaravelFullcalendar\Event;
// --------------------------------------
// Models to use here.
use App\Calendar;
use App\UserCompany;
use App\GeneralLookup;
use App\VisitType;


class CalendarVisitBaseController extends Controller
{

    function __construct() {
        
    }

    public function visitStatusIdx($visit_status_idx) {
        $generalLookup = GeneralLookup::select('option')
            ->where('field_name', 'Visit Status')
            ->where('sequence', $visit_status_idx)
            ->first();
        return $generalLookup;
    }

    public function edit_calendar(Request $request) {
        $calendar = Calendar::where('id', base64_decode($request->calendar_id))
            ->first();
        if ($calendar->visit_status_idx == 1 || $calendar->visit_status_idx == 2) {
            $date = str_replace(',', '-', preg_replace("/\s*([\/: ])\s*/", '', strstr($request->visit_date, ':')));
            $time = str_replace(' ', '', $request->visit_time);
            /*convert to 24 hr format*/
            $time = date("H:i", strtotime($time));
            //echo dd($request->visit_date);
            $date = Carbon::createFromFormat('Y-m-d H:i', "{$request->visit_date} {$time}");
            $calendar->visit_date_time = $date;
            
            if($request->has('visit_time_in') && $request->visit_time_in != ''){
                $time_in = str_replace(' ', '', $request->visit_time_in);
                $time_in = date("H:i", strtotime($time_in));
                $calendar->visit_time_in = $time_in;
            }

            if($request->has('visit_time_out') != null && $request->visit_time_out != ''){
                $time_out = str_replace(' ', '', $request->visit_time_out);
                $time_out = date("H:i", strtotime($time_out));
                $calendar->visit_time_out = $time_out;
            }
            $calendar->	visit_duration = $request->visit_duration;
            if ($calendar->visit_status_idx == 1)
                $calendar->	visit_status_idx = 2;
            $calendar->billable = $request->billable;
            $calendar->mileage = $request->mileage;
            $calendar->submit_for_approval_date = null;
            $calendar->company_approver_id = null;
            $calendar->company_approved_date = null;
            $calendar->agency_approver_id = null;
            $calendar->agency_approved_date = null;
            $calendar->visit_notes = $request->visit_notes;
            $calendar->updated_by =  Auth::user()->id;
            return $calendar;
        } 
    }

    public function test() {
        echo dd("こんにちは");
    }
   
}
