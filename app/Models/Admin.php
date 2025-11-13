<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    /** @use HasFactory<\Database\Factories\AdminFactory> */

    use HasFactory ;

    protected $table = 'admins';

    protected $primaryKey = 'id' ;

    protected $KeyType = 'integer' ;

    public $incrementing = true ;

    public $timestamps =true ;

    protected $guarded = ['id'];

    public function casts()
    {
        return [];
    }
}
