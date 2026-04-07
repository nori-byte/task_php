<?php
namespace Model;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id_role';
    protected $fillable = ['role_name'];
}