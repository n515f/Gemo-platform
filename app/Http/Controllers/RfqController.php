<?php
// app/Http/Controllers/RfqController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rfq;
use Illuminate\Support\Str;

class RfqController extends Controller
{
  public function create(Request $r)
{
    $product = null;
    if ($r->filled('product_id')) {
        $product = \App\Models\Product::with('images')->find($r->integer('product_id'));
    }
    return view('rfq.create', compact('product'));
}


    public function store(Request $r)
    {
        // 1) التحقق
        $data = $r->validate([
            'full_name' => ['required','string','max:190'],
            'email'     => ['required','email','max:190'],
            'phone'     => ['required','string','max:50'],
            'location'  => ['nullable','string','max:190'],
            'service'   => ['required','string','max:50'],
            'budget'    => ['required','string','max:50'],
            'brief'     => ['nullable','string','max:4000'],
        ]);

        // 2) إنشاء السجل (مراعاة أسماء الأعمدة في جدول rfqs)
        $payload = [
            'client_name' => $data['full_name'],
            'email'       => $data['email'],
            'phone'       => $data['phone'],
            'location'    => $data['location'] ?? null,
            'service'     => $data['service'],
            'budget'      => $data['budget'],
            'brief'       => $data['brief'] ?? null,
        ];
        $rfq = Rfq::create($payload);

        // 3) جلب رقم الواتساب (config ثم env)، وتنظيفه ليصبح أرقام فقط
        $wh = config('whatsapp.number')
           ?: config('services.whatsapp.number')
           ?: env('WHATSAPP_NUMBER');

        $wh = preg_replace('/\D+/', '', (string)$wh); // أرقام فقط

        // إن لم يوجد رقم، نعيد رسالة نجاح بدون تحويل
        if (!$wh) {
            return back()->with('ok', __('app.rfq_sent'));
        }

        // 4) تحويل القيم (service/budget) إلى تسميات مترجمة حسب اللغة الحالية
        $serviceLabel = match ($data['service']) {
            'import'       => __('app.service_import'),
            'procurement'  => __('app.service_procurement'),
            'customs'      => __('app.service_customs'),
            'installation' => __('app.service_installation'),
            'training'     => __('app.service_training'),
            'full_line'    => __('app.service_full_line'),
            default        => $data['service'],
        };

        $budgetLabel = match ($data['budget']) {
            'under_20k' => __('app.budget_under_20k'),
            '20_100k'   => __('app.budget_20_100k'),
            '100_500k'  => __('app.budget_100_500k'),
            'over_500k' => __('app.budget_over_500k'),
            default     => $data['budget'],
        };

        // عنوان الرسالة حسب اللغة (نستخدم مفاتيحك)
        // إن كان لديك 'rfq_title' في اللغتين، سيظهر حسب اللغة النشطة
        $title = __('app.rfq_title');

        // 5) نص رسالة واتساب — بالكامل حسب الترجمة النشطة
        $txt =
            $title . "\n" .
            __('app.full_name') . ': ' . $data['full_name'] . "\n" .
            __('app.email')     . ': ' . $data['email']     . "\n" .
            __('app.phone')     . ': ' . $data['phone']     . "\n" .
            __('app.location')  . ': ' . ($data['location'] ?? '-') . "\n" .
            __('app.service')   . ': ' . $serviceLabel . "\n" .
            __('app.budget')    . ': ' . $budgetLabel  . "\n" .
            __('app.brief')     . ': ' . ($data['brief'] ?? '-') . "\n" .
            __('app.code')      . ': ' . $rfq->id; // استخدم مفتاح مناسب مثل "رقم الطلب" لو أحببت

        // 6) تجهيز الروابط (تطبيق الجوال/الويب) واختيار الأنسب حسب الجهاز
        $encoded = rawurlencode($txt);
        $urlWeb  = "https://wa.me/{$wh}?text={$encoded}";
        $urlApp  = "whatsapp://send?phone={$wh}&text={$encoded}";

        $agent    = strtolower($r->header('User-Agent', ''));
        $isMobile = Str::contains($agent, ['iphone','ipad','android']);

        return redirect()->away($isMobile ? $urlApp : $urlWeb);
    }
}
