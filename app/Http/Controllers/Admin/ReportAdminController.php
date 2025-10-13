<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTechnicianReportRequest;
use App\Http\Requests\UpdateTechnicianReportRequest;
use App\Models\Project;
use App\Models\TechnicianReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportAdminController extends Controller
{
    /** الفهرس + بحث/فرز */
    public function index(Request $request)
    {
        $q     = trim((string) $request->get('q', ''));
        $order = $request->get('order', 'id');
        $dir   = strtolower($request->get('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowed = ['id','title','updated_at','created_at'];
        if (! in_array($order, $allowed, true)) {
            $order = 'id';
        }

        $rows = TechnicianReport::query()
            ->with(['project:id,title','creator:id,name'])
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where('title', 'like', "%{$q}%")
                   ->orWhere('id', $q)
                   ->orWhereHas('project', fn($p) => $p->where('title', 'like', "%{$q}%"))
                   ->orWhereHas('creator', fn($u) => $u->where('name', 'like', "%{$q}%"));
            })
            ->orderBy($order, $dir)
            ->paginate(12);

        return view('admin.reports.index', compact('rows', 'q', 'order', 'dir'));
    }

    /** إنشاء */
    public function create()
    {
        $projects = Project::orderBy('title')->get(['id','title']);
        return view('admin.reports.create', compact('projects'));
    }

    /** حفظ (مسارات الأدمن محمية بـ role:admin) */
    public function store(StoreTechnicianReportRequest $request)
    {
        $paths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $p = $file->store('tech_reports', 'public'); // storage/app/public/tech_reports
                $paths[] = 'storage/' . $p;                  // للعرض عبر asset()
            }
        }

        $report = TechnicianReport::create([
            'title'       => $request->string('title'),
            'notes'       => $request->input('notes'),
            'project_id'  => $request->input('project_id'),
            'attachments' => $paths ?: null,               // مصفوفة مباشرة (بفضل $casts)
            'created_by'  => Auth::id(),
        ]);

        return redirect()->route('admin.reports.show', $report)
            ->with('ok', '✅ تم إنشاء التقرير بنجاح.');
    }

    /** عرض */
    public function show(TechnicianReport $report)
    {
        $report->load(['project:id,title','creator:id,name']);
        return view('admin.reports.show', compact('report'));
    }

    /** تعديل */
    public function edit(TechnicianReport $report)
    {
        $projects = Project::orderBy('title')->get(['id','title']);
        return view('admin.reports.edit', compact('report','projects'));
    }

    /** تحديث */
    public function update(UpdateTechnicianReportRequest $request, TechnicianReport $report)
    {
        // إلغاء = رجوع بدون حفظ
        if ($request->has('cancel')) {
            return redirect()->route('admin.reports.index')->with('info','↩ تم إلغاء التعديلات.');
        }

        $paths = is_array($report->attachments) ? $report->attachments : [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $p = $file->store('tech_reports', 'public');
                $paths[] = 'storage/' . $p;
            }
        }

        $report->update([
            'title'       => $request->string('title'),
            'notes'       => $request->input('notes'),
            'project_id'  => $request->input('project_id'),
            'attachments' => $paths ?: null,               // مصفوفة مباشرة
        ]);

        return redirect()->route('admin.reports.index')->with('ok', '🛠 تم تحديث التقرير.');
    }

    /** حذف مرفق مفرد عبر زر */
    public function destroyAttachment(TechnicianReport $report, Request $request)
    {
        $request->validate(['path' => ['required','string']]);

        $arr = $report->attachments ?? [];
        $arr = array_values(array_filter($arr, fn($p) => $p !== $request->path));

        // حذف من القرص
        $publicPath = $request->path;
        if (Str::startsWith($publicPath, 'storage/')) {
            $rel = Str::after($publicPath, 'storage/');
            Storage::disk('public')->delete($rel);
        }

        $report->attachments = $arr ?: null;
        $report->save();

        return back()->with('ok', '🧹 تم حذف المرفق.');
    }

    /** حذف تقرير كامل */
    public function destroy(TechnicianReport $report)
    {
        // حذف كل المرفقات من القرص
        $arr = $report->attachments ?? [];
        foreach ($arr as $publicPath) {
            if (! is_string($publicPath)) continue;
            if (Str::startsWith($publicPath, 'storage/')) {
                $rel = Str::after($publicPath, 'storage/');
                Storage::disk('public')->delete($rel);
            }
        }

        $report->delete();

        return redirect()->route('admin.reports.index')->with('ok', '🗑 تم حذف التقرير.');
    }
}
