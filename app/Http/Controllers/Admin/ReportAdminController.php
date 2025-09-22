<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TechnicianReport;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // للتعامل مع المستخدمين
use Illuminate\Support\Str;

class ReportAdminController extends Controller
{
    /** الفهرس + بحث/فرز */
    public function index(Request $request)
    {
        $q     = trim((string) $request->get('q',''));
        $order = $request->get('order','id');
        $dir   = strtolower($request->get('dir','desc')) === 'asc' ? 'asc' : 'desc';

        $allowed = ['id','title','updated_at','created_at'];
        if (!in_array($order,$allowed,true)) $order = 'id';

        $rows = TechnicianReport::query()
            ->with(['project:id,title','user:id,name'])
            ->when($q !== '', function($qq) use ($q){
                $qq->where('title','like',"%{$q}%")
                   ->orWhere('id',$q)
                   ->orWhereHas('project', fn($p)=>$p->where('title','like',"%{$q}%"))
                   ->orWhereHas('user', fn($u)=>$u->where('name','like',"%{$q}%"));
            })
            ->orderBy($order,$dir)
            ->paginate(12);

        return view('admin.reports.index', compact('rows','q','order','dir'));
    }

    /** إنشاء */
    public function create()
    {
        $projects = Project::orderBy('title')->get(['id','title']);
        return view('admin.reports.create', compact('projects'));
    }

    /** حفظ */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required','string','max:255'],
            'notes'       => ['nullable','string'],
            'project_id'  => ['nullable','integer','exists:projects,id'],
            'attachments' => ['nullable','array'],
            'attachments.*' => ['file','mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx,zip','max:8192'],
        ]);

        $paths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $p = $file->store('reports','public');  // storage/app/public/...
                $paths[] = 'storage/'.$p;               // للعرض عبر asset()
            }
        }

        $report = TechnicianReport::create([
            'title'       => $data['title'],
            'notes'       => $data['notes'] ?? null,
            'project_id'  => $data['project_id'] ?? null,
            'attachments' => $paths ? json_encode($paths, JSON_UNESCAPED_UNICODE) : null,
            'created_by'  => Auth:: id(),
        ]);

        return redirect()->route('admin.reports.show',$report)->with('ok','✅ تم إنشاء التقرير بنجاح.');
    }

    /** عرض */
    public function show(TechnicianReport $report)
    {
        $report->load(['project:id,title','user:id,name']);
        return view('admin.reports.show', compact('report'));
    }

    /** تعديل */
    public function edit(TechnicianReport $report)
    {
        $projects = Project::orderBy('title')->get(['id','title']);
        return view('admin.reports.edit', compact('report','projects'));
    }

    /** تحديث */
    public function update(Request $request, TechnicianReport $report)
    {
        $data = $request->validate([
            'title'       => ['required','string','max:255'],
            'notes'       => ['nullable','string'],
            'project_id'  => ['nullable','integer','exists:projects,id'],
            'attachments' => ['nullable','array'],
            'attachments.*' => ['file','mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx,zip','max:8192'],
        ]);

        // إلغاء = رجوع بدون حفظ
        if ($request->has('cancel')) {
            return redirect()->route('admin.reports.index')->with('info','↩ تم إلغاء التعديلات.');
        }

        $paths = is_array($report->attachments) ? $report->attachments : (json_decode($report->attachments,true) ?: []);
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $p = $file->store('reports','public');
                $paths[] = 'storage/'.$p;
            }
        }

        $report->update([
            'title'       => $data['title'],
            'notes'       => $data['notes'] ?? null,
            'project_id'  => $data['project_id'] ?? null,
            'attachments' => $paths ? json_encode($paths, JSON_UNESCAPED_UNICODE) : null,
        ]);

        return redirect()->route('admin.reports.index')->with('ok','🛠 تم تحديث التقرير.');
    }

    /** حذف مرفق مفرد عبر زر */
    public function destroyAttachment(TechnicianReport $report, Request $request)
    {
        $request->validate(['path'=>['required','string']]);
        $arr = json_decode((string)$report->attachments, true) ?: [];
        $arr = array_values(array_filter($arr, function($p) use ($request){
            return $p !== $request->path;
        }));

        // حذف من القرص
        $publicPath = $request->path;
        if (Str::startsWith($publicPath,'storage/')) {
            $rel = Str::after($publicPath,'storage/');
            Storage::disk('public')->delete($rel);
        }

        $report->attachments = $arr ? json_encode($arr, JSON_UNESCAPED_UNICODE) : null;
        $report->save();

        return back()->with('ok','🧹 تم حذف المرفق.');
    }

    /** حذف تقرير كامل */
    public function destroy(TechnicianReport $report)
    {
        // حذف كل المرفقات من القرص
        $arr = json_decode((string)$report->attachments, true) ?: [];
        foreach ($arr as $publicPath) {
            if (!is_string($publicPath)) continue;
            if (Str::startsWith($publicPath,'storage/')) {
                $rel = Str::after($publicPath,'storage/');
                Storage::disk('public')->delete($rel);
            }
        }
        $report->delete();

        return redirect()->route('admin.reports.index')->with('ok','🗑 تم حذف التقرير.');
    }
}