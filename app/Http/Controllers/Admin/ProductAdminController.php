<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category; // << إضافة
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductAdminController extends Controller
{
    /* =========================== Index =========================== */
    public function index(Request $request)
    {
        $q        = trim((string) $request->get('q', ''));
        $order    = $request->get('order', 'id');
        $dir      = strtolower($request->get('dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $catId    = $request->integer('category_id'); // فلترة بالفئة (اختياري)

        $allowed = ['id','name_ar','name_en','code','price','sort_order','updated_at','created_at'];
        if (!in_array($order, $allowed, true)) $order = 'id';

        $products = Product::query()
            ->withCount('images')
            ->with('categories:id,name_ar,name_en') // للاستعراض
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('name_ar', 'like', "%{$q}%")
                      ->orWhere('name_en', 'like', "%{$q}%")
                      ->orWhere('code', 'like', "%{$q}%")
                      ->orWhere('slug', 'like', "%{$q}%");
                });
            })
            ->when($catId, function ($qq) use ($catId) {
                $qq->whereHas('categories', fn($w) => $w->where('categories.id', $catId));
            })
            ->orderBy($order, $dir)
            ->paginate(12)
            ->withQueryString();

        // لقائمة الفئات في الفلتر العلوي (اختياري)
        $categories = Category::orderBy('name_ar')->get(['id','name_ar','name_en']);

        return view('admin.products.index', compact('products', 'q', 'order', 'dir', 'categories', 'catId'));
    }

    /* =========================== Create / Store =========================== */
    public function create()
    {
        $categories = Category::orderBy('name_ar')->get(['id','name_ar','name_en']);
        return view('admin.products.create', compact('categories'));
    }

    // Helper: turn textarea lines into a clean JSON array
    private function normalizeSpecsText(?string $text): ?array
    {
        $lines = preg_split('/\r\n|\r|\n/', (string) $text);
        $items = array_values(array_filter(array_map('trim', $lines), fn ($l) => $l !== ''));
        return count($items) ? $items : null;
    }

    public function store(Request $request)
    {
        // زر إلغاء من فورم الإنشاء
        if ($request->has('cancel')) {
            return redirect()->route('admin.products.index')
                ->with('info', '🔙 تم الإلغاء ولم يتم حفظ أي بيانات.');
        }

        $data = $request->validate([
            'code'           => ['nullable','string','max:100','unique:products,code'],
            'slug'           => ['nullable','string','max:160','unique:products,slug'],
            'name_ar'        => ['required','string','max:255'],
            'name_en'        => ['required','string','max:190'],
            'short_desc_ar'  => ['nullable','string'],
            'short_desc_en'  => ['nullable','string'],
            'specs_ar'       => ['nullable','string'],
            'specs_en'       => ['nullable','string'],
            'price'          => ['nullable','numeric','min:0'],
            'is_active'      => ['nullable','boolean'],
            'sort_order'     => ['nullable','integer','min:0'],
            'images'         => ['nullable','array'],
            'images.*'       => ['file','image','mimes:jpg,jpeg,png,webp','max:4096'],

            // الفئات المتعددة
            'category_ids'   => ['nullable','array'],
            'category_ids.*' => ['integer','exists:categories,id'],
        ]);

        // slug تلقائي عند عدم الإرسال
        if (empty($data['slug'])) {
            $baseSlug   = Str::slug($data['name_en'] ?? $data['name_ar'] ?? Str::random(8));
            $candidate  = $baseSlug;
            $suffix     = 1;
            while (Product::where('slug', $candidate)->exists()) {
                $candidate = $baseSlug.'-'.(++$suffix);
            }
            $data['slug'] = $candidate;
        }

        // code تلقائي عند عدم الإرسال
        if (empty($data['code'])) {
            $prefix    = strtoupper(Str::of($data['name_en'] ?? $data['name_ar'] ?? 'PRD')
                                ->replace(['-', '_', ' '], '')->substr(0, 3));
            $n         = (int) ((Product::max('id') ?? 0) + 1);
            $candidate = sprintf('%s-%03d', $prefix, $n);
            $i = 1;
            while (Product::where('code', $candidate)->exists()) {
                $candidate = sprintf('%s-%03d', $prefix, $n + $i);
                $i++;
            }
            $data['code'] = $candidate;
        }

        $data['is_active']  = (bool) ($data['is_active'] ?? true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $product = null; // لتفادي ملاحظة التمرير بالمرجع
        DB::transaction(function () use ($data, $request, &$product) {
            // إنشاء المنتج
            $product = Product::create($data);

            // ربط الفئات المختارة
            $product->categories()->sync($data['category_ids'] ?? []);

            // رفع صور متعددة (name="images[]")
            if ($request->hasFile('images')) {
                $order = 0;
                foreach ($request->file('images') as $index => $file) {
                    $path       = $file->store('products', 'public'); // products/xxx.jpg
                    $publicPath = 'storage/'.$path;                    // storage/products/xxx.jpg
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path'       => $publicPath,
                        'sort_order' => $order++,
                        'is_primary' => $index === 0, // الصورة الأولى تكون primary
                    ]);
                }
            }
        });

        return redirect()->route('admin.products.index')
            ->with('success', '✅ تم إضافة المنتج وربط الفئات بنجاح.');
    }

    /* =========================== Show =========================== */
    public function show(Product $product)
    {
        $product->load(['images','categories:id,name_ar,name_en']);
        return view('admin.products.show', compact('product'));
    }

    /* =========================== Edit / Update =========================== */
    public function edit(Product $product)
    {
        $product->load('images','categories:id');
        $categories  = Category::orderBy('name_ar')->get(['id','name_ar','name_en']);
        $selectedIds = $product->categories->pluck('id')->toArray();

        return view('admin.products.edit', compact('product','categories','selectedIds'));
    }

    public function update(Request $request, Product $product)
    {
        // زر إلغاء من فورم التعديل
        if ($request->has('cancel')) {
            return redirect()->route('admin.products.index')
                ->with('info', '🔙 تم إلغاء التعديلات.');
        }

        $data = $request->validate([
            'code'           => ['nullable','string','max:100','unique:products,code,'.$product->id],
            'slug'           => ['nullable','string','max:160','unique:products,slug,'.$product->id],
            'name_ar'        => ['required','string','max:190'],
            'name_en'        => ['required','string','max:190'],
            'short_desc_ar'  => ['nullable','string'],
            'short_desc_en'  => ['nullable','string'],
            'specs_ar'       => ['nullable','array'],
            'specs_en'       => ['nullable','array'],
            'price'          => ['nullable','numeric','min:0'],
            'is_active'      => ['nullable','boolean'],
            'sort_order'     => ['nullable','integer','min:0'],

            // صور جديدة اختيارية
            'images'         => ['nullable','array'],
            'images.*'       => ['file','image','mimes:jpg,jpeg,png,webp','max:4096'],

            // حذف صور موجودة (IDs)
            'remove_images'   => ['nullable','array'],
            'remove_images.*' => ['integer','exists:product_images,id'],

            // الفئات المتعددة
            'category_ids'    => ['nullable','array'],
            'category_ids.*'  => ['integer','exists:categories,id'],
        ]);

        // slug/code تلقائيين عند التفريغ
        if (empty($data['slug'])) {
            $baseSlug  = Str::slug($data['name_en'] ?? $data['name_ar'] ?? $product->slug ?? Str::random(8));
            $candidate = $baseSlug;
            $i = 1;
            while (Product::where('slug', $candidate)->where('id','!=',$product->id)->exists()) {
                $candidate = $baseSlug.'-'.(++$i);
            }
            $data['slug'] = $candidate;
        }
        if (empty($data['code']) && empty($product->code)) {
            $prefix    = strtoupper(Str::of($data['name_en'] ?? $data['name_ar'] ?? 'PRD')
                                ->replace(['-', '_', ' '], '')->substr(0, 3));
            $candidate = sprintf('%s-%03d', $prefix, $product->id);
            $i = 1;
            while (Product::where('code', $candidate)->where('id','!=',$product->id)->exists()) {
                $candidate = sprintf('%s-%03d', $prefix, $product->id + $i);
                $i++;
            }
            $data['code'] = $candidate;
        }

        $data['is_active']  = (bool) ($data['is_active'] ?? $product->is_active);
        $data['sort_order'] = $data['sort_order'] ?? ($product->sort_order ?? 0);

        DB::transaction(function () use ($data, $request, $product) {
            // حذف صور مطلوبة
            if (!empty($data['remove_images'])) {
                $images = ProductImage::where('product_id', $product->id)
                    ->whereIn('id', $data['remove_images'])
                    ->get();
                foreach ($images as $img) {
                    $this->deleteImageFileIfExists($img->path);
                    $img->delete();
                }
            }

            // تحديث بيانات المنتج
            $product->update($data);

            // تحديث ربط الفئات
            $product->categories()->sync($data['category_ids'] ?? []);

            // إضافة صور جديدة
            if ($request->hasFile('images')) {
                $order = ($product->images()->max('sort_order') ?? -1) + 1;
                foreach ($request->file('images') as $file) {
                    $path       = $file->store('products', 'public');
                    $publicPath = 'storage/'.$path;
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path'       => $publicPath,
                        'sort_order' => $order++,
                        'is_primary' => $product->images()->count() === 0,
                    ]);
                }
            }
        });

        return redirect()->route('admin.products.index')
            ->with('ok', '🛠 تم تحديث المنتج والفئات بنجاح.');
    }

    /* =========================== Destroy / Helpers =========================== */
    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            // فك ربط الفئات
            $product->categories()->detach();

            // حذف الصور
            foreach ($product->images as $img) {
                $this->deleteImageFileIfExists($img->path);
                $img->delete();
            }

            // حذف المنتج
            $product->delete();
        });

        return redirect()->route('admin.products.index')
            ->with('ok', '🗑 تم حذف المنتج وما يتبعه.');
    }

    public function toggle(Product $product)
    {
        $product->is_active = ! $product->is_active;
        $product->save();

        return back()->with('ok', $product->is_active ? '✅ تم التفعيل' : '⛔ تم التعطيل');
    }

    public function destroyImage(Product $product, ProductImage $image)
    {
        abort_if($image->product_id !== $product->id, 404);

        $this->deleteImageFileIfExists($image->path);
        $image->delete();

        return back()->with('ok', '🖼 تم حذف الصورة.');
    }

    public function sortImages(Request $request, Product $product)
    {
        $payload = $request->validate([
            'items'               => ['required','array'],
            'items.*.id'          => ['required','integer','exists:product_images,id'],
            'items.*.sort_order'  => ['required','integer','min:0'],
        ]);

        DB::transaction(function () use ($payload, $product) {
            foreach ($payload['items'] as $row) {
                ProductImage::where('product_id', $product->id)
                    ->where('id', $row['id'])
                    ->update(['sort_order' => $row['sort_order']]);
            }
        });

        return response()->json(['ok' => true]);
    }

    protected function deleteImageFileIfExists(?string $publicPath): void
    {
        if (!$publicPath) return;

        $prefix = 'storage/';
        if (Str::startsWith($publicPath, $prefix)) {
            $relative = Str::after($publicPath, $prefix); // products/abc.jpg
            Storage::disk('public')->delete($relative);
        }
    }
}