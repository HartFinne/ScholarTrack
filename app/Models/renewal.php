<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class renewal extends Model
{
    use HasFactory;

    protected $table = 'renewal';

    protected $primaryKey = 'rid';

    protected $fillable = [
        'caseCode',
        'datesubmitted',
        'idpic',
        'reportcard',
        'regicard',
        'autobio',
        'familypic',
        'houseinside',
        'houseoutside',
        'utilitybill',
        'sketchmap',
        'payslip',
        'indigencycert',
        'status',
        'prioritylevel'
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }

    public function casedetails()
    {
        return $this->hasOne(RnwCaseDetails::class, 'rid', 'rid');
    }

    public function grade()
    {
        return $this->hasOne(RnwEducation::class, 'rid', 'rid');
    }

    public function otherinfo()
    {
        return $this->hasOne(RnwOtherInfo::class, 'rid', 'rid');
    }
}
