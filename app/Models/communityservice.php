<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class communityservice extends Model
{
    use HasFactory;

    protected $table = 'communityservice';

    protected $fillable = [
        'caseCode',
        'image',
        'title',
        'eventloc',
        'eventdate',
        'meetingplace',
        'calltime',
        'starttime',
        'facilitator',
        'slotnum',
        'volunteersnum',
        'eventstatus'
    ];
}