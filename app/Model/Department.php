<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id_department';
    protected $table = 'departments';
    protected $fillable = ['name_department', 'view_department'];
}
