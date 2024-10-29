<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grades extends Model
{
    use HasFactory;

    protected $table = 'grades';

    protected $primaryKey = 'gid';

    protected $fillable = [
        'caseCode',
        'schoolyear',
        'SemesterQuarter',
        'GWA',
        'ReportCard',
        'GradeStatus',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
}
