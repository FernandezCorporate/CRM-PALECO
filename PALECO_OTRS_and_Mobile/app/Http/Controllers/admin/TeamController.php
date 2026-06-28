<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\team\StoreTeamRequest;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Department;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\MemberRole;
use Illuminate\Support\Facades\DB;
use App\Enums\LogName;
use App\Enums\LogDescription;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function teamManagement(Request $request)
    {
        $departments = Department::orderBy('dept_name')->get();

        // 💡 Eager load fixed: 'shifts' removed to prevent RelationNotFoundException
        $teamQuery = Team::with(['users', 'department'])->orderBy('team_name');

        $teamQuery->when($request->filled('search'), function ($query) use ($request) {
            return $query->where('team_name', 'like', "%{$request->search}%");
        });

        $teamQuery->when($request->filled('department') && $request->department !== 'all', function ($query) use ($request) {
            return $query->where('department_id', $request->department);
        });

        $teams = $teamQuery->paginate(9)->withQueryString();

        return view('admin.pages.teamManagement', compact('teams', 'departments'));
    }

    public function addTeamForm(Request $request)
    {
        $departments = Department::orderBy('dept_name')->get();
        $personnels = User::where('role', UserRole::FIELD_PERSONNEL)->orderBy('first_name')->get();
        $memberRoles = MemberRole::cases();

        return view('admin.forms.teamForm', compact('departments', 'personnels', 'memberRoles'));
    }

    public function addTeam(StoreTeamRequest $request)
    {
        DB::transaction(function () use ($request){
            $team = Team::create($request->safe()->except('members'));

            if ($request->has('members')){
                $teamMembers = [];

                foreach ($request->validated('members') as $member) {
                    $teamMembers[$member['user_id']] = ['role' => $member['role']];
                }
                $team->users()->sync($teamMembers);

                $memberCount = count($teamMembers);
                $teamName = $team->team_name;

                activity(LogName::TEAM_MANAGEMENT->value)
                    ->performedOn($team)
                    ->causedBy(Auth::user())
                    ->tap(function ($activity) use ($teamMembers) {
                        $activity->attribute_changes = [
                            'attributes' => [
                                'members' => $teamMembers
                            ]
                        ];
                    })
                    ->log(LogDescription::memberAssigned($memberCount, $teamName));
            }
        });

        return redirect()->route('admin.teamManagement')->with('success', 'Team created successfully.');
    }
}