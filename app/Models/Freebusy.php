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
    public $timestamps = false;
    protected $fillable = [
        'employee_id'
    ];
    protected $dates = [
        'start_busy', 'end_busy'
    ];

    public function employees(){
        return $this->belongsTo('Employee');
    }
}
