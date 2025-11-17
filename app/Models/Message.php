<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'username',
        'message',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * العلاقة مع المستخدم (Admin)
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'user_id');
    }
}
