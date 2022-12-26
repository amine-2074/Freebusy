<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freebusy extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'freebusies';
    protected $primaryKey = 'id';
    protected $dates = [
        'start_busy', 'end_busy', 'employee_id'
    ];

    public function employees(){
        return $this->belongsTo('Employee');
    }
}
