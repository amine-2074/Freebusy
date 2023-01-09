<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'name'
    ];
    public function freebusies()
    {
        return $this->belongsToMany('Freebusy', 'employee_id');
    }
}
