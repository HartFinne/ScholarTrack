<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApehEducation extends Model
{
    use HasFactory;

    protected $table = 'apeheducation';

    protected $primaryKey = 'apehid';

    protected $fillable = [
        'casecode',
        'schoolname',
        'ingrade',
        'strand',
        'section',
        'gwa',
        'gwaconduct',
        'chinesegwa',
        'chinesegwaconduct',
    ];

    public function applicant()
    {
        return $this->belongsTo(applicants::class, 'casecode', 'casecode');
    }
}
