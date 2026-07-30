<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPdfIngestion;
use App\Models\BusinessKnowledge;
use App\Models\BusinessUnit;
use App\Models\Company;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $isAdministrator = $user?->isAdministrator() ?? false;

        if ($isAdministrator) {
            $companies = Company::query()
                ->with([
                    'businessUnits' => function (HasMany $query): void {
                        $query->withCount('businessKnowledge')->orderBy('name');
                    },
                ])
                ->withCount(['businessUnits', 'businessKnowledge'])
                ->orderBy('name')
                ->get();

            $selectedCompany = $this->resolveSelectedCompany($request, $companies);
            $businessUnits = $selectedCompany?->businessUnits ?? collect();
            $selectedBusinessUnit = $this->resolveSelectedBusinessUnit($request, $selectedCompany, $businessUnits);
        } else {
            abort_unless($user && $user->company_id && $user->business_unit_id, 403);

            $selectedBusinessUnit = BusinessUnit::query()->with('company')->find($user->business_unit_id);
            $selectedCompany = $selectedBusinessUnit?->company ?? Company::query()->find($user->company_id);
            $businessUnits = $selectedBusinessUnit ? collect([$selectedBusinessUnit]) : collect();
            $companies = $selectedCompany ? collect([$selectedCompany]) : collect();
        }

        $knowledgeSnippets = $selectedBusinessUnit
            ? $selectedBusinessUnit->businessKnowledge()->latest()->get()
            : collect();

        return view('admin', [
            'companies' => $companies,
            'selectedCompany' => $selectedCompany,
            'businessUnits' => $businessUnits,
            'selectedBusinessUnit' => $selectedBusinessUnit,
            'knowledgeSnippets' => $knowledgeSnippets,
            'isAdministrator' => $isAdministrator,
        ]);
    }

    public function storeCompany(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $company = Company::query()->create($validated);

        return $this->redirectToAdmin($company->id, null, 'Company created successfully.');
    }

    public function storeBusinessUnit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
        ]);

        $businessUnit = BusinessUnit::query()->create($validated);

        return $this->redirectToAdmin($businessUnit->company_id, $businessUnit->id, 'Business unit created successfully.');
    }

    public function storeBusinessKnowledge(Request $request, BusinessUnit $businessUnit): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $businessUnit->businessKnowledge()->create($validated);

        return $this->redirectToAdmin($businessUnit->company_id, $businessUnit->id, 'Knowledge snippet added successfully.');
    }

    public function uploadPdf(Request $request, BusinessUnit $businessUnit): RedirectResponse
    {
        $request->validate([
            'pdf' => ['required', 'file', 'mimetypes:application/pdf,application/x-pdf', 'mimes:pdf', 'max:10000'],
        ]);

        $uploadedFile = $request->file('pdf');
        $storedPath = $uploadedFile->storeAs(
            'pdf-ingestions',
            Str::uuid()->toString().'.pdf',
            'local'
        );

        // Persist the upload immediately, then continue indexing in the queue so the response stays fast.
        ProcessPdfIngestion::dispatch(
            businessUnitId: $businessUnit->id,
            storageDisk: 'local',
            storedPath: $storedPath,
        )->afterResponse();

        return $this->redirectToAdmin(
            $businessUnit->company_id,
            $businessUnit->id,
            'PDF upload received. Background indexing has started.'
        );
    }

    public function updateBusinessKnowledge(Request $request, BusinessUnit $businessUnit, BusinessKnowledge $businessKnowledge): RedirectResponse
    {
        abort_unless($businessKnowledge->business_unit_id === $businessUnit->id, 404);

        $validated = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $businessKnowledge->update($validated);

        return $this->redirectToAdmin($businessUnit->company_id, $businessUnit->id, 'Knowledge snippet updated successfully.');
    }

    public function destroyBusinessKnowledge(BusinessUnit $businessUnit, BusinessKnowledge $businessKnowledge): RedirectResponse
    {
        abort_unless($businessKnowledge->business_unit_id === $businessUnit->id, 404);

        $businessKnowledge->delete();

        return $this->redirectToAdmin($businessUnit->company_id, $businessUnit->id, 'Knowledge snippet deleted successfully.');
    }

    private function resolveSelectedCompany(Request $request, Collection $companies): ?Company
    {
        $selectedCompanyId = $request->integer('company_id');

        if ($selectedCompanyId) {
            return $companies->firstWhere('id', $selectedCompanyId)
                ?? Company::query()->find($selectedCompanyId);
        }

        $selectedBusinessUnitId = $request->integer('business_unit_id');

        if ($selectedBusinessUnitId) {
            return BusinessUnit::query()->with('company')->find($selectedBusinessUnitId)?->company;
        }

        return $companies->first();
    }

    private function resolveSelectedBusinessUnit(Request $request, ?Company $selectedCompany, Collection $businessUnits): ?BusinessUnit
    {
        $selectedBusinessUnitId = $request->integer('business_unit_id');

        if ($selectedBusinessUnitId) {
            $selectedBusinessUnit = $businessUnits->firstWhere('id', $selectedBusinessUnitId)
                ?? BusinessUnit::query()->with('company')->find($selectedBusinessUnitId);

            if ($selectedCompany && $selectedBusinessUnit && $selectedBusinessUnit->company_id !== $selectedCompany->id) {
                return $businessUnits->first();
            }

            return $selectedBusinessUnit;
        }

        return $businessUnits->first();
    }

    private function redirectToAdmin(?int $companyId, ?int $businessUnitId, string $status): RedirectResponse
    {
        return redirect()
            ->route('admin.index', array_filter([
                'company_id' => $companyId,
                'business_unit_id' => $businessUnitId,
            ]))
            ->with('status', $status);
    }
}
