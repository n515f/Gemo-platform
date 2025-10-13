<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateTechnicianReportRequest extends FormRequest
{
    /**
     * الإدمن يعدّل أي تقرير، والفنّي يعدّل تقاريره فقط.
     * تأكد أن بارامتر الراوت اسمه {report} لالتقاط الموديل.
     */
    public function authorize(): bool
    {
        $u = Auth::user();                // تفادي تحذير Intelephense
        $report = $this->route('report'); // Model from route

        if (! $u || ! $report) return false;

        if (method_exists($u, 'hasRole') && call_user_func([$u, 'hasRole'], 'admin')) {
            return true;
        }

        return method_exists($u, 'hasRole')
            && call_user_func([$u, 'hasRole'], 'technician')
            && (int) $report->created_by === (int) $u->id;
    }

    public function rules(): array
    {
        return [
            'project_id'     => ['sometimes','nullable','integer','exists:projects,id'],
            'title'          => ['sometimes','required','string','max:255'],
            'notes'          => ['sometimes','nullable','string'],
            'attachments'    => ['sometimes','nullable','array'],
            'attachments.*'  => ['file','mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx,zip','max:8192'],
            'keep'           => ['sometimes','array'],
        ];
    }
}
