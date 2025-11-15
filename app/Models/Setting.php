<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /** @use HasFactory<\Database\Factories\SettingFactory> */
    use HasFactory;

    protected $table = 'settings';

    protected $primaryKey = 'id';

    protected $keyType = 'integer';

    public $incrementing = true;

    public $timestamps = true;

    protected $guarded = ['id'];

    public function casts(): array
    {
        return [];
    }
}
