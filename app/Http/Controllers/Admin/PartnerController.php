<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePartnerRequest;
use App\Http\Requests\Admin\UpdatePartnerRequest;
use App\Models\Partner;
use App\Services\ImageOptimizerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PartnerController extends Controller
{
    public function __construct(
        protected ImageOptimizerService $optimizer
    ) {}

    /**
     * Display a listing of partner organizations.
     */
    public function index(): View
    {
        $partners = Partner::ordered()->get();

        return view('admin.partners.index', [
            'partners' => $partners,
        ]);
    }

    /**
     * Show the form for creating a new partner.
     */
    public function create(): View
    {
        return view('admin.partners.create');
    }

    /**
     * Store a newly created partner in storage.
     */
    public function store(StorePartnerRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->optimizer->optimize(
                $request->file('logo'),
                'uploads/partners',
                600,
                300,
                85
            );
        } elseif ($request->filled('logo')) {
            $validated['logo'] = $request->input('logo');
        }

        Partner::create($validated);

        return redirect()->route('admin.partners.index')->with('success', 'Partner organization added successfully.');
    }

    /**
     * Show the form for editing the specified partner.
     */
    public function edit(Partner $partner): View
    {
        return view('admin.partners.edit', [
            'partner' => $partner,
        ]);
    }

    /**
     * Update the specified partner in storage.
     */
    public function update(UpdatePartnerRequest $request, Partner $partner): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->optimizer->optimize(
                $request->file('logo'),
                'uploads/partners',
                600,
                300,
                85
            );
        } elseif ($request->filled('logo')) {
            $validated['logo'] = $request->input('logo');
        } else {
            unset($validated['logo']);
        }

        $partner->update($validated);

        return redirect()->route('admin.partners.index')->with('success', 'Partner organization updated successfully.');
    }

    /**
     * Remove the specified partner from storage.
     */
    public function destroy(Partner $partner): RedirectResponse
    {
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner organization deleted successfully.');
    }

    /**
     * Toggle the active status of the specified partner.
     */
    public function toggleActive(Partner $partner): RedirectResponse
    {
        $partner->is_active = ! $partner->is_active;
        $partner->save();

        $status = $partner->is_active ? 'visible' : 'hidden';

        return redirect()->back()->with('success', "Partner is now {$status}.");
    }
}
