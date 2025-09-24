<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdAdminController extends Controller
{
    /** قائمة */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        // اجلب 'active' كقيمة نصية: null | '' | '0' | '1'
        $active = $request->query('active', null);

        $rows = Ad::query()
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($q2) use ($q) {
                    $q2->where('title_ar', 'like', "%{$q}%")
                       ->orWhere('title_en', 'like', "%{$q}%")
                       ->orWhere('desc_ar', 'like', "%{$q}%")
                       ->orWhere('desc_en', 'like', "%{$q}%");
                });
            })
            // فعّل الفلترة فقط عندما يرسل المستخدم القيمة فعلاً
            ->when($active !== null && $active !== '', function ($qq) use ($active) {
                $qq->where('is_active', (int) $active);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.ads.index', [
            'rows'   => $rows,
            'q'      => $q,
            'active' => $active, // أرسلها للواجهة
        ]);
    }

    /** نموذج إنشاء */
    public function create()
    {
        return view('admin.ads.create');
    }

    /** حفظ جديد */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title_ar'        => ['nullable','string','max:255'],
            'title_en'        => ['nullable','string','max:255'],
            'desc_ar'         => ['nullable','string'],
            'desc_en'         => ['nullable','string'],
            'location_title'  => ['nullable','string','max:255'],
            'is_active'       => ['nullable','boolean'],
            'images'          => ['nullable','array'],
            'images.*'        => ['file','mimes:jpg,jpeg,png,webp','max:8192'],
        ]);

        $paths = $this->storeMany($request);

        Ad::create([
            'title_ar'       => $data['title_ar'] ?? null,
            'title_en'       => $data['title_en'] ?? null,
            'desc_ar'        => $data['desc_ar'] ?? null,
            'desc_en'        => $data['desc_en'] ?? null,
            'location_title' => $data['location_title'] ?? null,
            'images'         => $paths ?: null,
            'is_active'      => array_key_exists('is_active', $data) ? (bool) $data['is_active'] : true,
            'created_by'     => Auth::id(),
        ]);

        return redirect()->route('admin.ads.index')->with('ok','✅ تمت إضافة الإعلان.');
    }

    /** عرض (اختياري) */
    public function show(Ad $ad)
    {
        return view('admin.ads.show', compact('ad'));
    }

    /** نموذج تعديل */
    public function edit(Ad $ad)
    {
        $images = $this->decode($ad->images);
        return view('admin.ads.edit', compact('ad','images'));
    }

    /** تحديث */
    public function update(Request $request, Ad $ad)
    {
        $data = $request->validate([
            'title_ar'        => ['nullable','string','max:255'],
            'title_en'        => ['nullable','string','max:255'],
            'desc_ar'         => ['nullable','string'],
            'desc_en'         => ['nullable','string'],
            'location_title'  => ['nullable','string','max:255'],
            'is_active'       => ['nullable','boolean'],
            'images'          => ['nullable','array'],
            'images.*'        => ['file','mimes:jpg,jpeg,png,webp','max:8192'],
            'keep'            => ['nullable','array'],
        ]);

        $current = $this->decode($ad->images);

        if (is_array($data['keep'] ?? null)) {
            $toKeep = array_values(array_intersect($current, $data['keep']));
            foreach ($current as $old) {
                if (!in_array($old, $toKeep, true)) {
                    $this->deletePublicPath($old);
                }
            }
            $current = $toKeep;
        }

        $added = $this->storeMany($request);
        $all   = array_values(array_unique(array_merge($current, $added)));

        $ad->update([
            'title_ar'       => $data['title_ar'] ?? null,
            'title_en'       => $data['title_en'] ?? null,
            'desc_ar'        => $data['desc_ar'] ?? null,
            'desc_en'        => $data['desc_en'] ?? null,
            'location_title' => $data['location_title'] ?? null,
            'images'         => $all ?: null,
            'is_active'      => (bool) ($data['is_active'] ?? false),
        ]);

        return redirect()->route('admin.ads.index')->with('ok','🛠 تم تحديث الإعلان.');
    }

    /** حذف إعلان + صوره */
    public function destroy(Ad $ad)
    {
        foreach ($this->decode($ad->images) as $p) {
            $this->deletePublicPath($p);
        }
        $ad->delete();

        return back()->with('ok','🗑 تم حذف الإعلان.');
    }

    /* ================= Helpers ================= */

    protected function decode($images): array
    {
        if (!$images) return [];
        $arr = is_array($images) ? $images : json_decode($images, true);
        return is_array($arr) ? $arr : [];
    }

    protected function storeMany(Request $request, string $dir='ads'): array
    {
        $out = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $f) {
                $stored = $f->store($dir, 'public'); // storage/app/public/ads/...
                $out[]  = 'storage/' . $stored;      // ليعمل مع asset()
            }
        }
        return $out;
    }

    protected function deletePublicPath(?string $publicPath): void
    {
        if (!$publicPath) return;
        if (Str::startsWith($publicPath, 'storage/')) {
            $relative = Str::after($publicPath,'storage/');
            Storage::disk('public')->delete($relative);
        }
    }
}