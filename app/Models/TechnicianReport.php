<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicianReport extends Model
{
    use HasFactory;

    protected $fillable = ['project_id','title','notes','attachments','created_by'];

    protected $casts = ['attachments'=>'array'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function user(){ return $this->belongsTo(\App\Models\User::class,'created_by');
    }
}
