<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPdfIngestion;
use App\Models\BusinessKnowledge;
use App\Models\BusinessUnit;
use App\Models\Company;
use App\Models\ActivityLog;
use App\Models\StagedKnowledgeDocument;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function knowledgeBase(Request $request): View
    {
        abort_unless(session('user_role') === 'Administrator', 403);

        $chunks = BusinessKnowledge::query()
            ->with('businessUnit')
            ->latest()
            ->paginate(15);

        $businessUnits = \App\Models\BusinessUnit::query()->orderBy('name')->get();

        return view('admin.knowledge-base', compact('chunks', 'businessUnits'));
    }

    public function storeChunk(Request $request): JsonResponse
    {
        abort_unless(session('user_role') === 'Administrator', 403);

        $validated = $request->validate([
            'content'          => ['required', 'string'],
            'business_unit_id' => ['required', 'integer', 'exists:business_units,id'],
        ]);

        $chunk = BusinessKnowledge::query()->create($validated);
        $chunk->load('businessUnit');
        ActivityLog::record(Auth::id(), 'Indexed', 'Done', null, $chunk->businessUnit->name);

        return response()->json([
            'success' => true,
            'data' => [
                'id'            => $chunk->id,
                'business_unit' => $chunk->businessUnit?->name ?? '—',
                'content'       => $chunk->content,
                'created_at'    => $chunk->created_at->format('M d, Y · H:i'),
            ],
        ], 201);
    }

    public function destroyChunk(BusinessKnowledge $knowledge): JsonResponse
    {
        abort_unless(session('user_role') === 'Administrator', 403);

        $knowledge->delete();
        ActivityLog::record(Auth::id(), 'Deleted', 'Done');

        return response()->json(['success' => true]);
    }

    public function updateChunk(Request $request, BusinessKnowledge $knowledge): JsonResponse
    {
        abort_unless(session('user_role') === 'Administrator', 403);

        $validated = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $knowledge->update($validated);
        $knowledge->load('businessUnit');

        return response()->json([
            'success' => true,
            'data' => [
                'id'            => $knowledge->id,
                'business_unit' => $knowledge->businessUnit?->name ?? '—',
                'content'       => $knowledge->content,
                'created_at'    => $knowledge->created_at->format('M d, Y · H:i'),
            ],
        ]);
    }

    public function stagedKnowledge(): JsonResponse
    {
        abort_unless(session('user_role') === 'Administrator', 403);

        $documents = StagedKnowledgeDocument::query()
            ->where('status', 'staged')
            ->latest()
            ->get()
            ->map(static fn (StagedKnowledgeDocument $document): array => [
                'id' => $document->id,
                'branch' => $document->branch,
                'name' => $document->original_name,
                'type' => str_contains($document->mime_type, 'csv') ? 'CSV' : 'PDF',
                'size' => $document->file_size,
                'status' => $document->status,
            ]);

        return response()->json(['success' => true, 'data' => $documents]);
    }

    public function uploadKnowledge(Request $request): JsonResponse
    {
        abort_unless(session('user_role') === 'Administrator', 403);

        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,csv', 'max:25600'],
            'business_unit_id' => ['nullable', 'integer', 'exists:business_units,id'],
            'branch' => ['nullable', 'string', 'max:100'],
            'edited_content' => ['nullable', 'string'],
        ]);

        $businessUnit = isset($validated['business_unit_id'])
            ? BusinessUnit::query()->findOrFail($validated['business_unit_id'])
            : BusinessUnit::query()->firstOrFail();
        $file = $request->file('file');
        $storedPath = $file->storeAs(
            'knowledge-ingestions',
            Str::uuid()->toString().'.'.$file->getClientOriginalExtension(),
            'local'
        );

        $document = StagedKnowledgeDocument::query()->create([
            'business_unit_id' => $businessUnit->id,
            'branch' => $validated['branch'] ?? null,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType() ?: $file->getMimeType(),
            'file_size' => $file->getSize(),
            'stored_path' => $storedPath,
            'edited_content' => $validated['edited_content'] ?? null,
            'status' => 'staged',
        ]);

        ActivityLog::record(Auth::id(), 'Uploaded', 'Done', $document->original_name, $businessUnit->name);
        ActivityLog::record(
            Auth::id(),
            'Staged',
            'Pending',
            $document->original_name,
            $businessUnit->name,
        );

        return response()->json([
            'success' => true,
            'message' => 'File saved to staging.',
            'data' => [
                'id' => $document->id,
                'branch' => $document->branch,
                'name' => $document->original_name,
                'type' => str_contains($document->mime_type, 'csv') ? 'CSV' : 'PDF',
                'size' => $document->file_size,
                'status' => $document->status,
            ],
        ], 201);
    }

    public function approveStagedKnowledge(StagedKnowledgeDocument $stagedDocument): JsonResponse
    {
        abort_unless(session('user_role') === 'Administrator', 403);
        abort_unless($stagedDocument->status === 'staged', 409, 'This file has already been processed.');

        $stagedDocument->update(['status' => 'processing']);

        ActivityLog::record(
            Auth::id(),
            'Approved',
            'Done',
            $stagedDocument->original_name,
            $stagedDocument->businessUnit->name,
        );

        ProcessPdfIngestion::dispatch(
            businessUnitId: $stagedDocument->business_unit_id,
            storageDisk: 'local',
            storedPath: $stagedDocument->stored_path,
            stagedDocumentId: $stagedDocument->id,
            editedContent: $stagedDocument->edited_content,
            mimeType: $stagedDocument->mime_type,
            fileName: $stagedDocument->original_name,
            division: $stagedDocument->businessUnit->name,
            userId: Auth::id(),
        );

        return response()->json([
            'success' => true,
            'message' => 'File approved and queued for processing.',
            'data' => ['id' => $stagedDocument->id, 'status' => 'processing'],
        ]);
    }

    public function discardStagedKnowledge(StagedKnowledgeDocument $stagedDocument): JsonResponse
    {
        abort_unless(session('user_role') === 'Administrator', 403);
        abort_unless($stagedDocument->status === 'staged', 409, 'Only staged files can be discarded.');

        \Illuminate\Support\Facades\Storage::disk('local')->delete($stagedDocument->stored_path);
        ActivityLog::record(
            Auth::id(),
            'Rejected',
            'Failed',
            $stagedDocument->original_name,
            $stagedDocument->businessUnit->name,
        );
        $stagedDocument->delete();

        return response()->json(['success' => true, 'message' => 'Staged file discarded.']);
    }

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

        ActivityLog::record(Auth::id(), 'Uploaded', 'Done', $uploadedFile->getClientOriginalName(), $businessUnit->name);

        // Persist the upload immediately, then continue indexing in the queue so the response stays fast.
        ProcessPdfIngestion::dispatch(
            businessUnitId: $businessUnit->id,
            storageDisk: 'local',
            storedPath: $storedPath,
            fileName: $uploadedFile->getClientOriginalName(),
            division: $businessUnit->name,
            userId: Auth::id(),
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
            ->route('admin.knowledge-base', array_filter([
                'company_id' => $companyId,
                'business_unit_id' => $businessUnitId,
            ]))
            ->with('status', $status);
    }
}
