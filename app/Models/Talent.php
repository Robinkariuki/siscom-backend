<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talent extends Model
{
    use HasFactory;

    protected $table = 'talents'; // Use the singular form if that's your table name

    protected $fillable = [
        'name',
        'email',
        'resume',
        'years_of_experience',
        'linkedin_profile',
        'previous_work_portfolio',
        'specialization',
        'technical_skills',
    ];
}
