<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // للتعامل مع المستخدمين   
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectAdminController extends Controller
{
    /** قائمة الحالات المسموح بها (مطابقة للـ enum في DB) */
    public const STATUSES = ['supply','install','operate','maintenance'];

    /* ======================== Projects ======================== */

    /** index */
    public function index(Request $request)
    {
        $q     = trim((string)$request->get('q', ''));
        $order = $request->get('order', 'id');
        $dir   = strtolower($request->get('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedOrder = ['id','title','client_name','status','start_date','due_date','updated_at'];
        if (!in_array($order, $allowedOrder, true)) $order = 'id';

        $rows = Project::query()
            ->with('product:id,name_ar,name_en')
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where('title', 'like', "%{$q}%")
                   ->orWhere('client_name', 'like', "%{$q}%")
                   ->orWhere('id', $q);
            })
            ->orderBy($order, $dir)
            ->paginate(12);

        return view('admin.projects.index', compact('rows','q','order','dir'));
    }

    /** create */
    public function create()
    {
        $products = Product::orderBy('name_ar')->get(['id','name_ar','name_en']);
        $statuses = self::STATUSES;
        return view('admin.projects.create', compact('products','statuses'));
    }

    /** store */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required','string','max:255'],
            'client_name' => ['required','string','max:255'],
            'product_id'  => ['nullable','integer','exists:products,id'],
            'status'      => ['required','in:'.implode(',', self::STATUSES)],
            'notes'       => ['nullable','string'],
            'start_date'  => ['nullable','date'],
            'due_date'    => ['nullable','date','after_or_equal:start_date'],
        ]);

        $project = Project::create($data);

        return redirect()
            ->route('admin.projects.show', $project)
            ->with('ok', '✅ تم إنشاء المشروع بنجاح.');
    }

    /** show + نموذج تحديثات */
    public function show(Project $project)
    {
        $project->load(['product:id,name_ar,name_en', 'updates' => function($q){ $q->latest(); }]);
        $statuses = self::STATUSES;
        return view('admin.projects.show', compact('project','statuses'));
    }

    /** edit */
    public function edit(Project $project)
    {
        $products = Product::orderBy('name_ar')->get(['id','name_ar','name_en']);
        $statuses = self::STATUSES;
        return view('admin.projects.edit', compact('project','products','statuses'));
    }

    /** update */
    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title'       => ['required','string','max:255'],
            'client_name' => ['required','string','max:255'],
            'product_id'  => ['nullable','integer','exists:products,id'],
            'status'      => ['required','in:'.implode(',', self::STATUSES)],
            'notes'       => ['nullable','string'],
            'start_date'  => ['nullable','date'],
            'due_date'    => ['nullable','date','after_or_equal:start_date'],
        ]);

        $project->update($data);

        // زر "إلغاء" يرجع للاندكس بدون خطأ
        if ($request->has('cancel')) {
            return redirect()->route('admin.projects.index')
                ->with('info', '↩ تم إلغاء التعديلات.');
        }

        return redirect()->route('admin.projects.index')
            ->with('ok', '🛠 تم تحديث المشروع بنجاح.');
    }

    /** destroy */
    public function destroy(Project $project)
    {
        DB::transaction(function () use ($project) {
            foreach ($project->updates as $upd) {
                $this->deleteAttachments($upd->attachments);
                $upd->delete();
            }
            $project->delete();
        });

        return redirect()->route('admin.projects.index')
            ->with('ok', '🗑 تم حذف المشروع بنجاح.');
    }

    /* ======================== Project Updates ======================== */

    /** إضافة تحديث للمشروع (ملاحظة + حالة + مرفقات) */
    public function storeUpdate(Request $request, Project $project)
    {
        $payload = $request->validate([
            'note'        => ['nullable','string'],
            'status'      => ['nullable','in:'.implode(',', self::STATUSES)],
            'attachments' => ['nullable','array'],
            'attachments.*' => ['file','mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx,zip','max:8192'],
        ]);

        $paths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $p = $file->store('project_updates','public'); // storage/app/public/...
                $paths[] = 'storage/'.$p;                     // للعرض عبر asset()
            }
        }

        ProjectUpdate::create([
            'project_id'  => $project->id,
            'note'        => $payload['note'] ?? null,
            'status'      => $payload['status'] ?? null,
            'attachments' => $paths ? json_encode($paths, JSON_UNESCAPED_UNICODE) : null,
            'created_by'  => Auth::id(),
        ]);

        return back()->with('ok', '✅ تم إضافة تحديث للمشروع.');
    }

    /** حذف تحديث واحد */
    public function destroyUpdate(Project $project, ProjectUpdate $update)
    {
        abort_if($update->project_id !== $project->id, 404);
        $this->deleteAttachments($update->attachments);
        $update->delete();

        return back()->with('ok', '🗑 تم حذف التحديث.');
    }

    /* ======================== Helpers ======================== */

    /** حذف ملفات المرفقات من القرص */
    protected function deleteAttachments($attachments): void
    {
        if (!$attachments) return;

        $arr = is_array($attachments) ? $attachments : json_decode($attachments, true);
        if (!is_array($arr)) return;

        foreach ($arr as $publicPath) {
            if (!is_string($publicPath)) continue;
            if (Str::startsWith($publicPath, 'storage/')) {
                $relative = Str::after($publicPath, 'storage/'); // project_updates/...
                Storage::disk('public')->delete($relative);
            }
        }
    }
}