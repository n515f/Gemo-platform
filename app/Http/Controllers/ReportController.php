<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTechnicianReportRequest;
use App\Http\Requests\UpdateTechnicianReportRequest;
use App\Models\Project;
use App\Models\TechnicianReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /* ===================== Helpers (AuthZ) ===================== */

    protected function isAdmin(): bool
    {
        $user = Auth::user();
        // تحاشيًا لتحذير Intelephense: افحص وجود الميثود قبل استدعائها
        return $user && method_exists($user, 'hasRole')
            ? (bool) call_user_func([$user, 'hasRole'], 'admin')
            : false;
    }

    protected function canTouch(TechnicianReport $report): bool
    {
        return $this->isAdmin() || (Auth::id() === $report->created_by);
    }

    protected function storeUploadedAttachments(Request $request, string $dir = 'tech_reports'): array
    {
        $paths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $stored  = $file->store($dir, 'public');  // storage/app/public/tech_reports
                $paths[] = 'storage/' . $stored;          // للعرض عبر asset()
            }
        }
        return $paths;
    }

    protected function deletePublicPath(?string $publicPath): void
    {
        if (! $publicPath) return;
        if (str_starts_with($publicPath, 'storage/')) {
            $relative = substr($publicPath, strlen('storage/'));
            Storage::disk('public')->delete($relative);
        }
    }

    /* ===================== CRUD ===================== */

    public function index(Request $request)
    {
        $reports = TechnicianReport::query()
            ->with('project:id,title')
            ->when(! $this->isAdmin(), fn($q) => $q->where('created_by', Auth::id()))
            ->latest()
            ->paginate(12);

        $projects = Project::orderBy('title')->get(['id','title']);
        return view('reports.index', compact('reports', 'projects'));
    }

    public function create()
    {
        $projects = Project::orderBy('title')->get(['id','title']);
        return view('reports.create', compact('projects'));
    }

    public function store(StoreTechnicianReportRequest $request)
    {
        $paths  = $this->storeUploadedAttachments($request, 'tech_reports');

        $report = TechnicianReport::create([
            'project_id'  => $request->input('project_id'),
            'title'       => $request->string('title'),
            'notes'       => $request->input('notes'),
            'attachments' => $paths ?: null,     // مصفوفة مباشرة
            'created_by'  => Auth::id(),
        ]);

        return redirect()->route('reports.show', $report)
            ->with('ok', '✅ تم إنشاء التقرير بنجاح');
    }

    public function show(TechnicianReport $report)
    {
        abort_unless($this->canTouch($report), 403);

        $report->load('project:id,title');
        $attachments = $report->attachments ?? [];

        return view('reports.show', compact('report', 'attachments'));
    }

    public function edit(TechnicianReport $report)
    {
        abort_unless($this->canTouch($report), 403);

        $projects    = Project::orderBy('title')->get(['id','title']);
        $attachments = $report->attachments ?? [];

        return view('reports.edit', compact('report', 'projects', 'attachments'));
    }

    public function update(UpdateTechnicianReportRequest $request, TechnicianReport $report)
    {
        abort_unless($this->canTouch($report), 403);

        $current = $report->attachments ?? [];

        if (is_array($request->input('keep'))) {
            $toKeep = array_values(array_intersect($current, $request->input('keep')));
            foreach ($current as $oldPath) {
                if (! in_array($oldPath, $toKeep, true)) {
                    $this->deletePublicPath($oldPath);
                }
            }
            $current = $toKeep;
        }

        $added = $this->storeUploadedAttachments($request, 'tech_reports');
        $all   = array_values(array_unique(array_merge($current, $added)));

        $report->update([
            'project_id'  => $request->input('project_id'),
            'title'       => $request->string('title'),
            'notes'       => $request->input('notes'),
            'attachments' => $all ?: null,      // مصفوفة مباشرة
        ]);

        return redirect()->route('reports.show', $report)
            ->with('ok', '🛠 تم تحديث التقرير بنجاح');
    }

    public function destroy(TechnicianReport $report)
    {
        abort_unless($this->canTouch($report), 403);

        foreach (($report->attachments ?? []) as $path) {
            $this->deletePublicPath($path);
        }
        $report->delete();

        return redirect()->route('reports.index')
            ->with('ok', '🗑 تم حذف التقرير بنجاح');
    }
}
