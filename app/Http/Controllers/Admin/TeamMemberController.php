<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTeamMemberRequest;
use App\Http\Requests\Admin\UpdateTeamMemberRequest;
use App\Models\TeamMember;
use App\Services\ImageOptimizerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    public function __construct(
        protected ImageOptimizerService $optimizer
    ) {}

    /**
     * Display a listing of team members.
     */
    public function index(Request $request): View
    {
        $group = $request->query('group');

        $query = TeamMember::ordered();
        if ($group && in_array($group, ['trustee', 'management', 'advisor'], true)) {
            $query->byGroup($group);
        }
        $members = $query->get();

        return view('admin.team.index', [
            'members' => $members,
            'currentGroup' => $group,
        ]);
    }

    /**
     * Show the form for creating a new team member.
     */
    public function create(): View
    {
        return view('admin.team.create');
    }

    /**
     * Store a newly created team member in storage.
     */
    public function store(StoreTeamMemberRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->optimizer->optimize(
                $request->file('photo'),
                'uploads/team',
                800,
                800
            );
        }

        TeamMember::create($validated);

        return redirect()->route('admin.team.index')->with('success', 'Team member added successfully.');
    }

    /**
     * Show the form for editing the specified team member.
     */
    public function edit(TeamMember $team): View
    {
        return view('admin.team.edit', [
            'member' => $team,
        ]);
    }

    /**
     * Update the specified team member in storage.
     */
    public function update(UpdateTeamMemberRequest $request, TeamMember $team): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        if ($request->boolean('remove_photo')) {
            $validated['photo'] = null;
        } elseif ($request->hasFile('photo')) {
            $validated['photo'] = $this->optimizer->optimize(
                $request->file('photo'),
                'uploads/team',
                800,
                800
            );
        }

        $team->update($validated);

        return redirect()->route('admin.team.index')->with('success', 'Team member updated successfully.');
    }

    /**
     * Remove the specified team member from storage.
     */
    public function destroy(TeamMember $team): RedirectResponse
    {
        $team->delete();

        return redirect()->route('admin.team.index')->with('success', 'Team member deleted successfully.');
    }

    /**
     * Toggle the active status of the specified team member.
     */
    public function toggleActive(TeamMember $team): RedirectResponse
    {
        $team->is_active = ! $team->is_active;
        $team->save();

        $status = $team->is_active ? 'visible' : 'hidden';

        return redirect()->back()->with('success', "Team member is now {$status}.");
    }
}
