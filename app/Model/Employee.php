<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    public $timestamps = false;

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
}
