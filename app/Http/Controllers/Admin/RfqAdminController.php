<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rfq;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RfqAdminController extends Controller
{
    /** الحالات المتاحة */
    public const STATUSES = ['pending','quoted','won','lost'];

    /** قائمة الطلبات (بطائق) */
    public function index(Request $request)
    {
        $q       = trim((string)$request->get('q',''));
        $status  = $request->get('status','');
        $service = $request->get('service','');

        $rfqs = Rfq::query()
            ->with('product:id,name_ar,name_en')
            ->when($q !== '', function($qq) use($q){
                $qq->where(function($w) use($q){
                    $w->where('client_name','like',"%{$q}%")
                      ->orWhere('email','like',"%{$q}%")
                      ->orWhere('phone','like',"%{$q}%")
                      ->orWhere('location','like',"%{$q}%")
                      ->orWhere('brief','like',"%{$q}%")
                      ->orWhere('id',$q);
                });
            })
            ->when(in_array($status,self::STATUSES,true), fn($qq)=>$qq->where('status',$status))
            ->when($service !== '', fn($qq)=>$qq->where('service',$service))
            ->latest('created_at')
            ->paginate(12);

        // عدادات سريعة حسب الحالة
        $counts = Rfq::selectRaw("status, COUNT(*) c")->groupBy('status')->pluck('c','status');

        return view('admin.rfqs.index', compact('rfqs','q','status','service','counts'));
    }

    /** عرض مفصل + تعديل بسيط */
    public function show(Rfq $rfq)
    {
        $products = Product::orderBy('name_ar')->get(['id','name_ar','name_en']);
        $statuses = self::STATUSES;
        return view('admin.rfqs.show', compact('rfq','products','statuses'));
    }

    /** تحديث (ملاحظات/منتج/ملف PDF/حالة) */
    public function update(Request $request, Rfq $rfq)
    {
        $data = $request->validate([
            'status'     => ['nullable','in:'.implode(',', self::STATUSES)],
            'notes'      => ['nullable','string'],
            'product_id' => ['nullable','integer','exists:products,id'],
            'pdf'        => ['nullable','file','mimes:pdf','max:8192'],
        ]);

        if($request->hasFile('pdf')){
            $path = $request->file('pdf')->store('rfq_pdfs','public'); // storage/app/public/...
            $data['pdf_path'] = 'storage/'.$path;
        }

        $rfq->update($data);

        return redirect()->route('admin.rfqs.index')->with('ok','✅ تم تحديث الطلب بنجاح.');
    }

    /** تغيير الحالة بسرعة من البطاقة */
    public function updateStatus(Request $request, Rfq $rfq)
    {
        $request->validate([
            'status' => ['required','in:'.implode(',', self::STATUSES)],
        ]);

        $rfq->status = $request->string('status');
        $rfq->save();

        return back()->with('ok', '🟢 تم تغيير حالة الطلب.');
    }

    /** حذف */
    public function destroy(Rfq $rfq)
    {
        $rfq->delete();
        return back()->with('ok','🗑 تم حذف الطلب.');
    }

    /** رابط واتساب نظيف من أي محارف */
    public static function whatsappUrl(?string $phone, string $text = '')
    {
        $digits = preg_replace('/\D+/', '', (string)$phone);
        $url = 'https://wa.me/'.$digits;
        if($text !== ''){
            $url .= '?text='.urlencode($text);
        }
        return $url;
    }
}