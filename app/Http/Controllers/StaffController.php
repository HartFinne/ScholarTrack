<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\staccount;
use App\Models\User;
use App\Models\communityservice;
use App\Models\csregistration;
use App\Models\humanitiesclass;
use App\Models\hcattendance;
use App\Models\lte;
use App\Models\ScEducation;
use App\Models\scholarshipinfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// use App\Http\Controllers\DateTimeZone;
// use Exception;
// use Illuminate\Support\Facades\Redis;
// use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function showAccountSW()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.accountsw');
        }

        return redirect()->route('login');
    }

    public function showAccountSA()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.accountsa');
        }

        return redirect()->route('login');
    }

    public function showApplicants()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.applicants');
        }

        return redirect()->route('login');
    }

    public function showApplicationForms()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.applicationforms');
        }

        return redirect()->route('login');
    }

    public function showScholarsCollege()
    {
        if (Auth::guard('staff')->check()) {
            $scholar = User::with(['basicInfo', 'education', 'addressInfo'])->get();

            return view('staff.listcollege', compact('scholar'));
        }

        return redirect()->route('login');
    }

    public function showScholarsElem()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.listelementary');
        }

        return redirect()->route('login');
    }

    public function showScholarsHS()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.listhighschool');
        }

        return redirect()->route('login');
    }

    public function showScholarProfile($id)
    {
        if (Auth::guard('staff')->check()) {
            $data = User::with(['basicInfo', 'education', 'addressInfo'])->findOrFail($id);

            return view('staff.scholarsinfo', compact('data'));
        }

        return redirect()->route('login');
    }

    public function showLogin()
    {
        return view('staff.login');
    }

    public function showLTE()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.lte');
        }

        return redirect()->route('login');
    }

    public function showPenalty()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.penalty');
        }

        return redirect()->route('login');
    }

    public function showQualiCollege()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.qualificationcollege');
        }

        return redirect()->route('login');
    }

    public function showQualiElem()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.qualificationelem');
        }

        return redirect()->route('login');
    }

    public function showQualiJHS()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.qualificationjhs');
        }

        return redirect()->route('login');
    }

    public function showQualiSHS()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.qualificationshs');
        }

        return redirect()->route('login');
    }

    public function showAllowanceRegular()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.regularallowance');
        }

        return redirect()->route('login');
    }

    public function showRenewal()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.renewal');
        }

        return redirect()->route('login');
    }

    public function showRenewalCollege()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.renewcollege');
        }

        return redirect()->route('login');
    }

    public function showRenewalElem()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.renewelementary');
        }

        return redirect()->route('login');
    }

    public function showRenewalHS()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.renewhighschool');
        }

        return redirect()->route('login');
    }

    public function showAllowanceSpecial()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.specialallowance');
        }

        return redirect()->route('login');
    }

    public function showScholarsoverview()
    {
        if (Auth::guard('staff')->check()) {
            $totalscholars = User::all()->count();
            $totalnewscholars = scholarshipinfo::where('scholartype', 'New Scholar')->count();
            $scholarsmd = scholarshipinfo::where('area', 'Mindong')->count();
            $scholarsmx = scholarshipinfo::where('area', 'Minxi')->count();
            $scholarsmz = scholarshipinfo::where('area', 'Minzhong')->count();
            $college = ScEducation::where('scSchoolLevel', 'College')->count();
            $shs = ScEducation::where('scSchoolLevel', 'Senior High')->count();
            $jhs = ScEducation::where('scSchoolLevel', 'Junior High')->count();
            $elem = ScEducation::where('scSchoolLevel', 'Elementary')->count();
            // $scholarsperarea = [
            //     ['label' => 'Mindong', 'value' => scholarshipinfo::where('area', 'Mindong')->count()],
            //     ['label' => 'Minxi', 'value' => scholarshipinfo::where('area', 'Minxi')->count()],
            //     ['label' => 'Minzhong', 'value' => scholarshipinfo::where('area', 'Minzhong')->count()],
            // ];
            return view('staff.scholars', compact('totalscholars', 'totalnewscholars', 'scholarsmd', 'scholarsmx', 'scholarsmz', 'college', 'shs', 'jhs', 'elem'));
        }

        return redirect()->route('login');
    }

    public function showUsersScholar()
    {
        if (Auth::guard('staff')->check()) {
            $scholarAccounts = User::all();

            return view('staff.admscholars', compact('scholarAccounts'));
        }

        return redirect()->route('login');
    }

    public function showUserApplicants()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.admapplicants');
        }

        return redirect()->route('login');
    }

    public function showUserStaff()
    {
        if (Auth::guard('staff')->check()) {
            $staffAccounts = Staccount::all();

            return view('staff.admstaff', compact('staffAccounts'));
        }

        return redirect()->route('login');
    }

    public function showDashboard()
    {
        if (Auth::guard('staff')->check()) {
            return view('staff.admdashboard');
        }

        return redirect()->route('login');
    }

    public function activateStaff($id)
    {
        $user = Staccount::findOrFail($id);
        $user->status = 'Active';
        $user->save();

        return redirect()->back()->with('success', 'User activated successfully.');
    }

    public function deactivateStaff($id)
    {
        $user = Staccount::findOrFail($id);
        $user->status = 'Inactive';
        $user->save();

        return redirect()->back()->with('success', 'User deactivated successfully.');
    }

    public function activateScholar($id)
    {
        $user = User::findOrFail($id);
        $user->scStatus = 'Active';
        $user->save();

        return redirect()->back()->with('success', 'User activated successfully.');
    }

    public function deactivateScholar($id)
    {
        $user = User::findOrFail($id);
        $user->scStatus = 'Inactive';
        $user->save();

        return redirect()->back()->with('success', 'User deactivated successfully.');
    }

    public function showStaffInfo($id)
    {
        if (Auth::guard('staff')->check()) {
            $user = Staccount::findOrFail($id);

            return view('staff.admstaffinfo', compact('user'));
        }

        return redirect()->route('login');
    }

    public function showScholarInfo($id)
    {
        if (Auth::guard('staff')->check()) {
            $user = User::findOrFail($id);

            return view('staff.admscholarinfo', compact('user'));
        }

        return redirect()->route('login');
    }

    public function showCommunityService()
    {
        if (Auth::guard('staff')->check()) {
            $events = communityservice::all();
            $totalevents = communityservice::count();
            $openevents = communityservice::where('eventstatus', 'Open')->count();
            $closedevents = communityservice::where('eventstatus', 'Closed')->count();

            return view('staff.managecs', compact('events', 'totalevents', 'openevents', 'closedevents'));
        }

        return redirect()->route('login');
    }

    public function showCSOpenEvents()
    {
        if (Auth::guard('staff')->check()) {
            $events = communityservice::where('eventstatus', 'Open')->get();
            $totalevents = communityservice::count();
            $openevents = communityservice::where('eventstatus', 'Open')->count();
            $closedevents = communityservice::where('eventstatus', 'Closed')->count();

            return view('staff.openevents', compact('events', 'totalevents', 'openevents', 'closedevents'));
        }

        return redirect()->route('login');
    }

    public function showCSClosedEvents()
    {
        if (Auth::guard('staff')->check()) {
            $events = communityservice::where('eventstatus', 'Closed')->get();
            $totalevents = communityservice::count();
            $openevents = communityservice::where('eventstatus', 'Open')->count();
            $closedevents = communityservice::where('eventstatus', 'Closed')->count();

            return view('staff.closedevents', compact('events', 'totalevents', 'openevents', 'closedevents'));
        }

        return redirect()->route('login');
    }

    public function showcseventinfo($csid)
    {
        if (Auth::guard('staff')->check()) {
            $event = communityservice::findOrFail($csid);
            $volunteers = csregistration::where('csid', $csid)->get();
            return view('staff.cseventinfo', compact('event', 'volunteers'));
        }

        return redirect()->route('login');
    }

    public function createcsevent(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'eventloc' => 'required|string|max:255',
                'eventdate' => 'required|date',
                'meetingplace' => 'required|string|max:255',
                'calltime' => 'required',
                'starttime' => 'required',
                'facilitator' => 'required|string|max:255',
                'slotnum' => 'required|integer|min:1',
            ]);

            $volunteersnum = 0;
            $eventstatus = 'Open';

            $event = communityservice::create([
                'title' => $request->title,
                'eventloc' => $request->eventloc,
                'eventdate' => $request->eventdate,
                'meetingplace' => $request->meetingplace,
                'calltime' => $request->calltime,
                'starttime' => $request->starttime,
                'facilitator' => $request->facilitator,
                'slotnum' => $request->slotnum,
                'volunteersnum' => $volunteersnum,
                'eventstatus' => $eventstatus
            ]);

            return redirect()->route('communityservice')->with('success', 'Activity created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('communityservice')->with('error', 'Activity creation was unsuccessful. ' . $e->getMessage());
        }
    }

    public function updatecsevent($csid, Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'eventloc' => 'required|string|max:255',
                'eventdate' => 'required|date',
                'meetingplace' => 'required|string|max:255',
                'calltime' => 'required',
                'starttime' => 'required',
                'facilitator' => 'required|string|max:255',
                'slotnum' => 'required|integer|min:1',
                'eventstatus' => 'required',
            ]);

            $event = communityservice::where('csid', $csid)->first();

            if (!$event) {
                return redirect()->back()->with('error', 'Event not found.');
            }

            $event->update([
                'title' => $request->title,
                'eventloc' => $request->eventloc,
                'eventdate' => $request->eventdate,
                'meetingplace' => $request->meetingplace,
                'calltime' => $request->calltime,
                'starttime' => $request->starttime,
                'facilitator' => $request->facilitator,
                'slotnum' => $request->slotnum,
                'volunteersnum' => $event->volunteersnum,
                'eventstatus' => $request->eventstatus
            ]);

            return redirect()->back()->with('success', 'Successfully updated activity details.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Updating activity details was unsuccessful. ' . $e->getMessage());
        }
    }

    public function showHumanitiesClass()
    {
        if (Auth::guard('staff')->check()) {
            $classes = humanitiesclass::all();
            return view('staff.managehc', compact('classes'));
        }

        return redirect()->route('login');
    }

    public function createhc(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:255',
            'hclocation' => 'required|string|max:255',
            'hcstarttime' => 'required',
            'hcendtime' => 'required',
        ]);

        try {
            $totalattendees = 0;
            $hcdate = now();

            $event = humanitiesclass::create([
                'topic' => $request->topic,
                'hcdate' => $hcdate,
                'hclocation' => $request->hclocation,
                'hcstarttime' => $request->hcstarttime,
                'hcendtime' => $request->hcendtime,
                'totalattendees' => $totalattendees,
            ]);

            return redirect()->route('attendancesystem', $event->hcid);
        } catch (\Exception $e) {
            return redirect()->route('humanitiesclass')->with('error', 'Activity creation was unsuccessful. ' . $e->getMessage());
        }
    }

    public function showAttendanceSystem($hcid)
    {
        if (Auth::guard('staff')->check()) {
            $event = humanitiesclass::findOrFail($hcid);
            $scholars = User::with(['basicInfo'])->get();

            return view('staff.hcattendancesystem', compact('scholars', 'event'));
        }

        return redirect()->route('login');
    }

    public function saveattendance($hcid, Request $request)
    {
        $request->validate([
            'scholar' => 'required',
        ]);

        try {
            $event = humanitiesclass::findOrFail($hcid);
            $timeIn = Carbon::now(new \DateTimeZone('Asia/Manila'));

            if ($timeIn->greaterThan($event->hcstarttime)) {
                $tardinessDuration = $timeIn->diffInMinutes($event->hcstarttime, true);
                $hcstatus = 'Late';
            } else {
                $tardinessDuration = 0;
                $hcstatus = 'Present';
            }

            try {
                DB::beginTransaction();

                $existingAttendance = HCAttendance::where('hcid', $hcid)
                    ->where('caseCode', $request->scholar)
                    ->first();

                if ($existingAttendance) {
                    // Rollback the transaction
                    DB::rollBack();

                    // Redirect back with an error message about duplicate entry
                    return redirect()->route('attendancesystem', ['hcid' => $hcid])
                        ->with('error', 'Attendance was unsuccessful: Duplicate Entry.');
                }

                HCAttendance::create([
                    'hcid' => $hcid,
                    'caseCode' => $request->scholar,
                    'timein' => $timeIn->toTimeString(),
                    'timeout' => null,
                    'tardinessduration' => $tardinessDuration,
                    'hcastatus' => $hcstatus,
                ]);

                humanitiesclass::where('hcid', $hcid)->increment('totalattendees', 1);

                DB::commit();

                if ($hcstatus == 'Late') {
                    $worker = Auth::guard('staff')->user();
                    $attendee = hcattendance::where('hcid', $hcid)
                        ->where('caseCode', $request->scholar)
                        ->first();

                    lte::create([
                        'caseCode' => $attendee->caseCode,
                        'conditionid' => $attendee->hcaid,
                        'eventtype' => "Humanities Class",
                        'dateissued' => $event->hcdate,
                        'deadline' => Carbon::parse($event->hcdate)->addDays(3),
                        'datesubmitted' => NULL,
                        'reason' => NULL,
                        'explanation' => NULL,
                        'proof' => NULL,
                        'ltestatus' => 'No Response',
                        'workername' => strtoupper($worker->name) . ", RSW",
                    ]);

                    DB::commit();
                }

                return redirect()->route('attendancesystem', ['hcid' => $hcid])->with('success', 'Attendance successfully submitted');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('attendancesystem', ['hcid' => $hcid])->with('error', 'Failed to submit attendance: ' . $e->getMessage());
            }

            return redirect()->route('attendancesystem', ['hcid' => $hcid])->with('success', 'Attendance successfully submitted');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('attendancesystem', ['hcid' => $hcid])->with('error', 'Attendance failed: Humanities class not found.');
        } catch (\Exception $e) {
            return redirect()->route('attendancesystem', ['hcid' => $hcid])->with('error', 'Attendance was unsuccessful: ' . $e->getMessage());
        }
    }

    public function viewhcattendees($hcid, Request $request)
    {
        try {
            $worker = Auth::guard('staff')->user();

            if (!Hash::check($request->password, $worker->password)) {
                return redirect()->back()->with('error', 'Incorrect password.');
            }

            return $this->viewattendeeslist($hcid);
        } catch (\Exception $e) {
            return redirect()->route('attendancesystem', ['hcId' => $hcid])
                ->with('error', 'Access failed: ' . $e->getMessage());
        }
    }

    public function viewattendeeslist($hcid)
    {
        if (Auth::guard('staff')->check()) {
            $event = HumanitiesClass::findOrFail($hcid);

            $attendees = HcAttendance::with(['basicInfo'])
                ->where('hcId', $hcid)
                ->get();

            return view('staff.viewhcattendeeslist', compact('event', 'attendees'));
        }

        return redirect()->route('login');
    }

    public function exitattendancesystem($hcId, Request $request)
    {
        try {
            $worker = Auth::guard('staff')->user();

            if (!Hash::check($request->password, $worker->password)) {
                return redirect()->back()->with('error', 'Incorrect password.');
            }
            return redirect()->route('humanitiesclass');
        } catch (\Exception $e) {
            return redirect()->route('attendancesystem', ['hcId' => $hcId])
                ->with('error', 'Access failed: ' . $e->getMessage());
        }
    }

    public function savehc($hcid)
    {
        try {
            DB::beginTransaction();
            // Perform a mass update directly
            hcattendance::where('hcid', $hcid)
                ->whereNull('timeout')
                ->update(['timeout' => Carbon::now(new \DateTimeZone('Asia/Manila'))]);

            DB::commit();

            return $this->viewattendeeslist($hcid)->with('success', 'Checkout was successful.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->viewattendeeslist($hcid)->with('error', 'Checkout Failed: ' . $e->getMessage());
        }
    }

    public function checkouthc($hcaid)
    {
        try {
            DB::beginTransaction();

            $attendee = hcattendance::findOrFail($hcaid);
            $event = humanitiesclass::findOrFail($attendee->hcid);
            $worker = Auth::guard('staff')->user();

            if ($attendee->timeout == NULL) {
                $timeout = Carbon::now(new \DateTimeZone('Asia/Manila'));
                $newhcstatus = 'Left Early';
                $tardinessduration = $timeout->diffInMinutes($event->hcendtime, true);

                $attendee->update([
                    'timeout' => $timeout,
                    'tardinessduration' => $tardinessduration,
                    'hcastatus' => $newhcstatus,
                ]);

                DB::commit();

                if ($attendee->hcastatus != 'Late') {
                    lte::create([
                        'caseCode' => $attendee->caseCode,
                        'conditionid' => $attendee->hcaid,
                        'eventtype' => "Humanities Class",
                        'dateissued' => $event->hcdate,
                        'deadline' => Carbon::parse($event->hcdate)->addDays(3),
                        'datesubmitted' => NULL,
                        'reason' => NULL,
                        'explanation' => NULL,
                        'proof' => NULL,
                        'ltestatus' => 'No Response',
                        'workername' => strtoupper($worker->name) . ", RSW",
                    ]);
                }

                DB::commit();
            }

            return $this->viewattendeeslist($attendee->hcid)->with('success', 'Checkout was successful.');
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->viewattendeeslist($attendee->hcid)->with('error', 'Checkout Failed: ' . $e->getMessage());
        }
    }
}
