<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTechnicianReportRequest extends FormRequest
{
    /** يسمح فقط لصاحب دور technician بالإنشاء */
    public function authorize(): bool
    {
        $u = Auth::user(); // استخدم Facade لتفادي تحذير "Undefined method user"
        return $u && method_exists($u, 'hasRole')
            ? (bool) call_user_func([$u, 'hasRole'], 'technician')
            : false;
    }

    public function rules(): array
    {
        return [
            'project_id'     => ['nullable','integer','exists:projects,id'],
            'title'          => ['required','string','max:255'],
            'notes'          => ['nullable','string'],
            'attachments'    => ['nullable','array'],
            'attachments.*'  => ['file','mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx,zip','max:8192'],
        ];
    }
}
