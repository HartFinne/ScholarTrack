<?php

namespace App\Http\Controllers;

use App\Http\Middleware\applicant;
use Illuminate\Http\Request;
use App\Models\staccount;
use App\Models\User;
use App\Models\communityservice;
use App\Models\csregistration;
use App\Models\humanitiesclass;
use App\Models\hcattendance;
use App\Models\lte;
use App\Models\penalty;
use App\Models\renewal;
use App\Models\ScEducation;
use App\Models\scholarshipinfo;
use App\Models\criteria;
use App\Models\institutions;
use App\Models\courses;
use App\Models\applicants;
use App\Models\apceducation;
use App\Models\apeheducation;
use App\Models\apfamilyinfo;
use App\Models\specialallowanceforms;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\UsesTrait;

class StaffController extends Controller
{
    public function showAccountSW()
    {
        $worker = Auth::guard('staff')->user();
        return view('staff.profile-socialworker', compact('worker'));
    }

    public function showAccountSA()
    {
        $worker = Auth::guard('staff')->user();
        return view('staff.profile-admin', compact('worker'));
    }

    public function showApplicants()
    {
        $totalapplicants = applicants::get()->count();
        $applicants = applicants::get();
        $pending = applicants::whereNotIn('applicationstatus', ['Accepted', 'Rejected', 'Withdrawn'])->count();
        $accepted = applicants::where('applicationstatus', 'Accepted')->count();
        $rejected = applicants::where('applicationstatus', 'Rejected')->count();
        $withdrawn = applicants::where('applicationstatus', 'Withdrawn')->count();
        $college = apceducation::get()->count();
        $shs = apeheducation::whereIN('ingrade', ['Grade 11', 'Grade 12'])->get()->count();
        $jhs = apeheducation::whereIN('ingrade', ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'])->get()->count();
        $elem = apeheducation::whereIN('ingrade', ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'])->get()->count();
        return view('staff.applicants', compact('totalapplicants', 'applicants', 'pending', 'accepted', 'rejected', 'withdrawn', 'college', 'shs', 'jhs', 'elem'));
    }

    public function showapplicantinfo($casecode)
    {
        $applicant = applicants::with('educcollege', 'educelemhs', 'otherinfo', 'requirements', 'casedetails')
            ->where('casecode', $casecode)
            ->first();
        $father = apfamilyinfo::where('casecode', $casecode)
            ->where('relationship', 'Father')->first();
        $mother = apfamilyinfo::where('casecode', $casecode)
            ->where('relationship', 'Mother')->first();
        $siblings = apfamilyinfo::where('casecode', $casecode)
            ->where('relationship', 'Sibling')->get();
        $iscollege = apceducation::where('casecode', $casecode)->first()->exists();
        return view('staff.applicant-info', compact('applicant', 'father', 'mother', 'siblings', 'iscollege'));
    }

    public function showApplicationForms()
    {
        return view('staff.applicationforms');
    }

    public function showScholarsCollege()
    {
        $scholar = User::with(['basicInfo', 'education', 'addressInfo'])->get();

        return view('staff.listcollege', compact('scholar'));
    }

    public function showScholarsElem()
    {
        return view('staff.listelementary');
    }

    public function showScholarsHS()
    {
        return view('staff.listhighschool');
    }

    public function showScholarProfile($id)
    {
        $data = User::with(['basicInfo', 'education', 'addressInfo'])->findOrFail($id);

        return view('staff.scholarsinfo', compact('data'));
    }

    public function showLTE()
    {
        $lte = lte::with('hcattendance', 'csattendance')->get();
        $scholars = User::with(['basicInfo'])->get();
        return view('staff.lte', compact('lte', 'scholars'));
    }

    public function showPenalty()
    {
        $penalties = penalty::all();
        $scholars = User::with(['basicInfo'])->get();
        return view('staff.penalty', compact('penalties', 'scholars'));
    }

    // SCHOLARSHIP CRITERIA
    public function showQualification()
    {
        $criteria = criteria::first();
        $courses = courses::where('level', 'College')->get();
        $strands = courses::where('level', 'Senior High')->get();
        $institutions = institutions::all();
        return view('staff.qualification', compact('criteria', 'institutions', 'courses', 'strands'));
    }

    public function updatecriteria(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'cgwa' => 'required|numeric|min:1|max:5',
                'shsgwa' => 'required|numeric|min:1|max:100',
                'jhsgwa' => 'required|numeric|min:1|max:100',
                'elemgwa' => 'required|numeric|min:1|max:100',
                'fincome' => 'required|numeric|min:0',
                'mincome' => 'required|numeric|min:0',
                'sincome' => 'required|numeric|min:0',
                'aincome' => 'required|numeric|min:0',
            ]);

            $criteria = criteria::first();

            if (is_null($criteria)) {
                criteria::create([
                    'cgwa' => $request->cgwa,
                    'shsgwa' => $request->shsgwa,
                    'jhsgwa' => $request->jhsgwa,
                    'elemgwa' => $request->elemgwa,
                    'fincome' => $request->fincome,
                    'mincome' => $request->mincome,
                    'sincome' => $request->sincome,
                    'aincome' => $request->aincome,
                ]);
            } else {
                $criteria->update([
                    'cgwa' => $request->cgwa,
                    'shsgwa' => $request->shsgwa,
                    'jhsgwa' => $request->jhsgwa,
                    'elemgwa' => $request->elemgwa,
                    'fincome' => $request->fincome,
                    'mincome' => $request->mincome,
                    'sincome' => $request->sincome,
                    'aincome' => $request->aincome,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('critsuccess', 'Successfully updated scholarship requirements');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('criterror', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('criterror', 'Unable to update scholarship requirements.');
        }
    }

    public function addinstitution(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'institute' => 'required|string|max:255',
            ]);

            institutions::create([
                'schoolname' => $request->institute
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Successfully added an institution.')->withFragment('confirmmsg2');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Failed to add institution.')->withFragment('confirmmsg2');
        }
    }

    public function updateinstitution($inid, Request $request)
    {
        $request->validate([
            'newschoolname' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $institution = institutions::findOrFail($inid);

            $institution->update([
                'schoolname' => $request->newschoolname
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Successfully updated the institution name.')->withFragment('confirmmsg2');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to update institution name.')->withFragment('confirmmsg2');
        }
    }

    public function deleteinstitution($inid)
    {
        DB::beginTransaction();
        try {
            $institution = institutions::findOrFail($inid);

            $institution->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Successfully deleted the institution.')->withFragment('confirmmsg2');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to delete institution.')->withFragment('confirmmsg2');
        }
    }

    public function addcourse($level, Request $request)
    {
        DB::beginTransaction();
        if ($level == 'College') {
            try {
                $request->validate([
                    'course' => 'required|string|max:255',
                ]);

                courses::create([
                    'level' => $level,
                    'coursename' => $request->course
                ]);

                DB::commit();

                return redirect()->back()->with('success', 'Successfully added a course.')->withFragment('confirmmsg2');
            } catch (ValidationException $e) {
                DB::rollback();
                return redirect()->back()->with('error', $e->getMessage());
            } catch (\Exception $e) {
                DB::rollback();

                return redirect()->back()->with('error', 'Failed to add course.')->withFragment('confirmmsg2');
            }
        } elseif ($level == 'Senior High') {
            try {
                $request->validate([
                    'strand' => 'required|string|max:255',
                ]);

                courses::create([
                    'level' => $level,
                    'coursename' => $request->strand
                ]);

                DB::commit();

                return redirect()->back()->with('success', 'Successfully added a strand.')->withFragment('confirmmsg2');
            } catch (ValidationException $e) {
                DB::rollback();
                return redirect()->back()->with('error', $e->getMessage());
            } catch (\Exception $e) {
                DB::rollback();

                return redirect()->back()->with('error', 'Failed to add strand.')->withFragment('confirmmsg2');
            }
        }
    }

    public function updatecourse($coid, Request $request)
    {
        $request->validate([
            'newcoursename' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $course = courses::findOrFail($coid);

            $course->update([
                'coursename' => $request->newcoursename
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Successfully updated the course name.')->withFragment('confirmmsg2');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to update institution name.')->withFragment('confirmmsg2');
        }
    }

    public function deletecourse($coid)
    {
        DB::beginTransaction();
        try {
            $course = courses::findOrFail($coid);

            $course->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Successfully deleted the institution.')->withFragment('confirmmsg2');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to delete institution.')->withFragment('confirmmsg2');
        }
    }

    public function showRenewal()
    {
        $totalrenew = renewal::all()->count();
        $pending = renewal::where('status', 'Pending')->count();
        $approved = renewal::where('status', 'Approved')->count();
        $rejected = renewal::where('status', 'Rejected')->count();
        return view('staff.renewal', compact('totalrenew', 'pending', 'approved', 'rejected'));
    }

    public function showRenewalCollege()
    {
        $scholars = User::with('education', 'basicInfo');
        $renewals = renewal::all();
        return view('staff.renewcollege', compact('renewals', 'scholars'));
    }

    public function showRenewalElem()
    {
        $scholars = User::with('education', 'basicInfo');
        $renewals = renewal::all();
        return view('staff.renewelementary', compact('renewals', 'scholars'));
    }

    public function showRenewalHS()
    {
        $scholars = User::with('education', 'basicInfo');
        $renewals = renewal::all();
        return view('staff.renewhighschool', compact('renewals', 'scholars'));
    }

    public function showAllowanceRegular()
    {
        return view('staff.regularallowance');
    }

    public function showAllowanceSpecial()
    {
        return view('staff.specialallowance');
    }

    public function updatetransporeimbursenment(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'transporeimbursement' => ['mimes:doc,docx,pdf', 'max:2048'],
                ],
                [
                    'transporeimbursement.mimes' => 'The transportation reimbursement form must be a valid file (doc, docx, or pdf).',
                    'transporeimbursement.max' => 'The transportation reimbursement form must not exceed 2 MB.',
                ]
            );

            $uploadedfile = $request->file('transporeimbursement');

            $filename = 'Transportation Reimbursement Form.' . $uploadedfile->getClientOriginalExtension();

            $path = $uploadedfile->storeAs('uploads/allowance_forms/special', $filename, 'public');

            $filetype = 'TRF';

            $fileexists = specialallowanceforms::where('filetype', $filetype)->first();

            if ($fileexists) {
                $fileexists->pathname = $path;
                $fileexists->save();
            } else {
                specialallowanceforms::create([
                    'filetype' => $filetype,
                    'pathname' => $path,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'File has been successfully uploaded.');
        } catch (ValidationException $e) {
            DB::rollBack();
            $errors = $e->errors();
            $errorMessages = '<ul>';
            foreach ($errors as $fieldErrors) {
                foreach ($fieldErrors as $errorMessage) {
                    $errorMessages .= '<li>' . $errorMessage . '</li>';
                }
            }
            $errorMessages .= '</ul>';
            return redirect()->back()->with('error', 'Unable to update file. ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Unable to update file. ' . $e->getMessage());
        };
    }

    public function updateacknowledgementreceipt(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'acknowledgementreceipt' => ['mimes:doc,docx,pdf', 'max:2048'],
                ],
                [
                    'acknowledgementreceipt.mimes' => 'The acknowledgement receipt must be a valid file (doc, docx, or pdf).',
                    'acknowledgementreceipt.max' => 'The acknowledgement receipt must not exceed 2 MB.',
                ]
            );

            $uploadedfile = $request->file('acknowledgementreceipt');

            $filename = 'Acknowledgement Receipt.' . $uploadedfile->getClientOriginalExtension();

            $path = $uploadedfile->storeAs('uploads/allowance_forms/special', $filename, 'public');

            $filetype = 'AR';

            $fileexists = specialallowanceforms::where('filetype', $filetype)->first();

            if ($fileexists) {
                $fileexists->pathname = $path;
                $fileexists->save();
            } else {
                specialallowanceforms::create([
                    'filetype' => $filetype,
                    'pathname' => $path,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'File has been successfully uploaded.');
        } catch (ValidationException $e) {
            DB::rollBack();
            $errors = $e->errors();
            $errorMessages = '<ul>';
            foreach ($errors as $fieldErrors) {
                foreach ($fieldErrors as $errorMessage) {
                    $errorMessages .= '<li>' . $errorMessage . '</li>';
                }
            }
            $errorMessages .= '</ul>';
            return redirect()->back()->with('error', 'Unable to update file. ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Unable to update file. ' . $e->getMessage());
        };
    }

    public function updateliquidationform(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'liquidationform' => ['mimes:doc,docx,pdf', 'max:2048'],
                    'certificationform' => ['mimes:doc,docx,pdf', 'max:2048'],
                ],
                [
                    'liquidationform.mimes' => 'The liquidation form must be a valid file (doc, docx, or pdf).',
                    'liquidationform.max' => 'The liquidation form must not exceed 2 MB.',
                ]
            );

            $uploadedfile = $request->file('liquidationform');

            $filename = 'Liquidation Form.' . $uploadedfile->getClientOriginalExtension();

            $path = $uploadedfile->storeAs('uploads/allowance_forms/special', $filename, 'public');

            $filetype = 'LF';

            $fileexists = specialallowanceforms::where('filetype', $filetype)->first();

            if ($fileexists) {
                $fileexists->pathname = $path;
                $fileexists->save();
            } else {
                specialallowanceforms::create([
                    'filetype' => $filetype,
                    'pathname' => $path,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'File has been successfully uploaded.');
        } catch (ValidationException $e) {
            DB::rollBack();
            $errors = $e->errors();
            $errorMessages = '<ul>';
            foreach ($errors as $fieldErrors) {
                foreach ($fieldErrors as $errorMessage) {
                    $errorMessages .= '<li>' . $errorMessage . '</li>';
                }
            }
            $errorMessages .= '</ul>';
            return redirect()->back()->with('error', 'Unable to update file. ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Unable to update file. ' . $e->getMessage());
        };
    }

    public function updatecertificationform(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'certificationform' => ['mimes:doc,docx,pdf', 'max:2048'],
                ],
                [
                    'certificationform.mimes' => 'The certification form must be a valid file (doc, docx, or pdf).',
                    'certificationform.max' => 'The certification form must not exceed 2 MB.',
                ]
            );

            $uploadedfile = $request->file('certificationform');

            $filename = 'Project and Book Certification Form.' . $uploadedfile->getClientOriginalExtension();

            $path = $uploadedfile->storeAs('uploads/allowance_forms/special', $filename, 'public');

            $filetype = 'PBCF';

            $fileexists = specialallowanceforms::where('filetype', $filetype)->first();

            if ($fileexists) {
                $fileexists->pathname = $path;
                $fileexists->save();
            } else {
                specialallowanceforms::create([
                    'filetype' => $filetype,
                    'pathname' => $path,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'File has been successfully uploaded.');
        } catch (ValidationException $e) {
            DB::rollBack();
            $errors = $e->errors();
            $errorMessages = '<ul>';
            foreach ($errors as $fieldErrors) {
                foreach ($fieldErrors as $errorMessage) {
                    $errorMessages .= '<li>' . $errorMessage . '</li>';
                }
            }
            $errorMessages .= '</ul>';
            return redirect()->back()->with('error', 'Unable to update file. ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Unable to update file. ' . $e->getMessage());
        };
    }

    public function showScholarsoverview()
    {
        $totalscholars = User::all()->count();
        $totalnewscholars = scholarshipinfo::where('scholartype', 'New Scholar')->count();
        $scholarsmd = scholarshipinfo::where('area', 'Mindong')->count();
        $scholarsmx = scholarshipinfo::where('area', 'Minxi')->count();
        $scholarsmz = scholarshipinfo::where('area', 'Minzhong')->count();
        $college = ScEducation::where('scSchoolLevel', 'College')->count();
        $shs = ScEducation::where('scSchoolLevel', 'Senior High')->count();
        $jhs = ScEducation::where('scSchoolLevel', 'Junior High')->count();
        $elem = ScEducation::where('scSchoolLevel', 'Elementary')->count();
        return view('staff.scholars', compact('totalscholars', 'totalnewscholars', 'scholarsmd', 'scholarsmx', 'scholarsmz', 'college', 'shs', 'jhs', 'elem'));
    }

    public function showUsersScholar()
    {
        $scholarAccounts = User::all();

        return view('staff.users-scholar', compact('scholarAccounts'));
    }

    public function showUserApplicants()
    {
        $applicants = applicants::all();

        return view('staff.users-applicant', compact('applicants'));
    }

    public function showUserStaff()
    {
        $staffAccounts = Staccount::all();

        return view('staff.users-staff', compact('staffAccounts'));
    }

    public function showDashboard()
    {
        $totalscholar = user::all()->count();
        $totalstaff = staccount::all()->count();
        $totalapplicant = applicants::all()->count();
        $totalusers = $totalapplicant + $totalstaff + $totalscholar;

        return view('staff.dashboard-admin', compact('totalscholar', 'totalstaff', 'totalapplicant', 'totalusers'));
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

    public function activateapplicant($apid)
    {
        $user = applicants::findOrFail($apid);
        $user->accountstatus = 'Active';
        $user->save();

        return redirect()->back()->with('success', 'User activated successfully.');
    }

    public function deactivateapplicant($apid)
    {
        $user = applicants::findOrFail($apid);
        $user->accountstatus = 'Inactive';
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
        $user = Staccount::findOrFail($id);

        return view('staff.admstaffinfo', compact('user'));
    }

    public function showScholarInfo($id)
    {
        $user = User::findOrFail($id);

        return view('staff.admscholarinfo', compact('user'));
    }

    public function showapplicantaccount($apid)
    {
        $user = applicants::findOrFail($apid);

        return view('staff.admapplicantinfo', compact('user'));
    }

    public function showCommunityService()
    {
        communityservice::where('slotnum', 0)
            ->where('eventstatus', '!=', 'Closed')
            ->update(['eventstatus' => 'Closed']);
        $events = communityservice::all();
        $totalevents = communityservice::count();
        $openevents = communityservice::where('eventstatus', 'Open')->count();
        $closedevents = communityservice::where('eventstatus', 'Closed')->count();

        $requiredHours = 8;

        $scholarsWithCompletedHours = DB::table('csattendance')
            ->select('caseCode', DB::raw('SUM(hoursspent) as total_hours'))
            ->groupBy('caseCode')
            ->having('total_hours', '>=', $requiredHours)
            ->count();

        $scholarsWithRemainingHours = DB::table('csattendance')
            ->select('caseCode', DB::raw('SUM(hoursspent) as total_hours'))
            ->groupBy('caseCode')
            ->having('total_hours', '<', $requiredHours)
            ->count();

        return view('staff.managecs', compact('events', 'totalevents', 'openevents', 'closedevents', 'scholarsWithCompletedHours', 'scholarsWithRemainingHours'));
    }

    public function showCSOpenEvents()
    {
        $events = communityservice::where('eventstatus', 'Open')->get();
        $totalevents = communityservice::count();
        $openevents = communityservice::where('eventstatus', 'Open')->count();
        $closedevents = communityservice::where('eventstatus', 'Closed')->count();

        return view(
            'staff.openevents',
            compact('events', 'totalevents', 'openevents', 'closedevents')
        );
    }

    public function showCSClosedEvents()
    {
        $events = communityservice::where('eventstatus', 'Closed')->get();
        $totalevents = communityservice::count();
        $openevents = communityservice::where('eventstatus', 'Open')->count();
        $closedevents = communityservice::where('eventstatus', 'Closed')->count();

        return view(
            'staff.closedevents',
            compact(
                'events',
                'totalevents',
                'openevents',
                'closedevents'
            )
        );
    }


    public function showcseventinfo($csid)
    {
        $event = communityservice::findOrFail($csid);
        $volunteers = csregistration::where('csid', $csid)->get();
        return view('staff.cseventinfo', compact('event', 'volunteers'));
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
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('communityservice')->with('error', 'Activity creation was unsuccessful.');
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
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Updating activity details was unsuccessful.');
        }
    }

    public function showHumanitiesClass()
    {
        $classes = humanitiesclass::all();
        return view('staff.managehc', compact('classes'));
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
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('humanitiesclass')->with('error', 'Activity creation was unsuccessful.');
        }
    }

    public function showAttendanceSystem($hcid)
    {
        $event = humanitiesclass::findOrFail($hcid);
        $scholars = User::with(['basicInfo'])->get();

        return view('staff.hcattendancesystem', compact('scholars', 'event'));
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
                    DB::rollBack();

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
                return redirect()->route('attendancesystem', ['hcid' => $hcid])->with('error', 'Failed to submit attendance.', $e->getMessage());
            }

            return redirect()->route('attendancesystem', ['hcid' => $hcid])->with('success', 'Attendance successfully submitted');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('attendancesystem', ['hcid' => $hcid])->with('error', 'Attendance failed: Humanities class not found.');
        } catch (\Exception $e) {
            return redirect()->route('attendancesystem', ['hcid' => $hcid])->with('error', 'Attendance was unsuccessful.');
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
                ->with('error', 'Access failed.');
        }
    }

    public function viewattendeeslist($hcid)
    {
        $event = HumanitiesClass::findOrFail($hcid);

        $attendees = HcAttendance::with(['basicInfo'])
            ->where('hcId', $hcid)
            ->get();

        return view('staff.viewhcattendeeslist', compact('event', 'attendees'));
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
                ->with('error', 'Access failed');
        }
    }

    public function savehc($hcid)
    {
        try {
            DB::beginTransaction();
            hcattendance::where('hcid', $hcid)
                ->whereNull('timeout')
                ->update(['timeout' => Carbon::now(new \DateTimeZone('Asia/Manila'))]);

            DB::commit();

            return $this->viewattendeeslist($hcid)->with('success', 'Checkout was successful.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->viewattendeeslist($hcid)->with('error', 'Checkout was successful.');
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

            return $this->viewattendeeslist($attendee->hcid)->with('error', 'Checkout was successful.');
        }
    }
}
