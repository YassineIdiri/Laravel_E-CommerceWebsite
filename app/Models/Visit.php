<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'visits';
    protected $fillable = ['visit_date', 'visit_count'];
}
