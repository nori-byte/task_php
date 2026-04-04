<?php
namespace Model;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $table = 'positions';
    protected $primaryKey = 'id_position';
    public $timestamps = false;
    protected $fillable = ['title_position'];
}
