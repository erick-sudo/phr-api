<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    use HasFactory;

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }
}
