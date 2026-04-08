<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id_employee';

    public function position()
    {
        return $this->belongsTo(Position::class, 'id_position');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'id_department');
    }

    public function composition()
    {
        return $this->belongsTo(Composition::class, 'id_composition');
    }
    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'gender',
        'birth_date',
        'address',
        'id_position',
        'id_department',
        'id_composition'
    ];
}
