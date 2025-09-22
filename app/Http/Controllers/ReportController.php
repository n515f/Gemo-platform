<?php

namespace App\Http\Controllers;

use App\Models\TechnicianReport;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /* ===================== Helpers (AuthZ) ===================== */

   // app/Http/Controllers/ReportController.php

protected function isAdmin(): bool
{
    $user = \Illuminate\Support\Facades\Auth::user();
    if (! $user) {
        return false;
    }

    // نستدعي hasRole بطريقة ديناميكية لتفادي تحذير Intelephense
    if (method_exists($user, 'hasRole')) {
        return (bool) call_user_func([$user, 'hasRole'], 'admin');
    }

    return false;
}

    protected function canTouch(TechnicianReport $report): bool
    {
        return $this->isAdmin() || (Auth::id() === $report->created_by);
    }

    protected function decodeAttachments($attachments): array
    {
        if (!$attachments) return [];
        $arr = is_array($attachments) ? $attachments : json_decode($attachments, true);
        return is_array($arr) ? array_values($arr) : [];
    }

    protected function storeUploadedAttachments(Request $request, string $dir = 'tech_reports'): array
    {
        $paths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $stored = $file->store($dir, 'public');  // storage/app/public/tech_reports/...
                $paths[] = 'storage/' . $stored;         // للعرض عبر asset()
            }
        }
        return $paths;
    }

    protected function deletePublicPath(?string $publicPath): void
    {
        if (!$publicPath) return;
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
            ->when(!$this->isAdmin(), fn($q) => $q->where('created_by', Auth::id()))
            ->latest()
            ->paginate(12);

        $projects = Project::orderBy('title')->get(['id','title']);
        return view('reports.index', compact('reports','projects'));
    }

    public function create()
    {
        $projects = Project::orderBy('title')->get(['id','title']);
        return view('reports.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id'     => ['nullable','integer','exists:projects,id'],
            'title'          => ['required','string','max:255'],
            'notes'          => ['nullable','string'],
            'attachments'    => ['nullable','array'],
            'attachments.*'  => ['file','mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx,zip','max:8192'],
        ]);

        $paths  = $this->storeUploadedAttachments($request);
        $report = TechnicianReport::create([
            'project_id'  => $data['project_id'] ?? null,
            'title'       => $data['title'],
            'notes'       => $data['notes'] ?? null,
            'attachments' => $paths ? json_encode($paths, JSON_UNESCAPED_UNICODE) : null,
            'created_by'  => Auth::id(),
        ]);

        return redirect()->route('reports.show', $report)
            ->with('ok', '✅ تم إنشاء التقرير بنجاح');
    }

    public function show(TechnicianReport $report)
    {
        abort_unless($this->canTouch($report), 403);

        $report->load('project:id,title');
        $attachments = $this->decodeAttachments($report->attachments);

        return view('reports.show', compact('report','attachments'));
    }

    public function edit(TechnicianReport $report)
    {
        abort_unless($this->canTouch($report), 403);

        $projects    = Project::orderBy('title')->get(['id','title']);
        $attachments = $this->decodeAttachments($report->attachments);

        return view('reports.edit', compact('report','projects','attachments'));
    }

    public function update(Request $request, TechnicianReport $report)
    {
        abort_unless($this->canTouch($report), 403);

        $data = $request->validate([
            'project_id'     => ['nullable','integer','exists:projects,id'],
            'title'          => ['required','string','max:255'],
            'notes'          => ['nullable','string'],
            'attachments'    => ['nullable','array'],
            'attachments.*'  => ['file','mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx,zip','max:8192'],
            'keep'           => ['nullable','array'],
        ]);

        $current = $this->decodeAttachments($report->attachments);

        if (is_array($data['keep'] ?? null)) {
            $toKeep = array_values(array_intersect($current, $data['keep']));
            foreach ($current as $oldPath) {
                if (!in_array($oldPath, $toKeep, true)) {
                    $this->deletePublicPath($oldPath);
                }
            }
            $current = $toKeep;
        }

        $added = $this->storeUploadedAttachments($request);
        $all   = array_values(array_unique(array_merge($current, $added)));

        $report->update([
            'project_id'  => $data['project_id'] ?? null,
            'title'       => $data['title'],
            'notes'       => $data['notes'] ?? null,
            'attachments' => $all ? json_encode($all, JSON_UNESCAPED_UNICODE) : null,
        ]);

        return redirect()->route('reports.show', $report)
            ->with('ok', '🛠 تم تحديث التقرير بنجاح');
    }

    public function destroy(TechnicianReport $report)
    {
        abort_unless($this->canTouch($report), 403);

        foreach ($this->decodeAttachments($report->attachments) as $path) {
            $this->deletePublicPath($path);
        }
        $report->delete();

        return redirect()->route('reports.index')
            ->with('ok', '🗑 تم حذف التقرير بنجاح');
    }
}