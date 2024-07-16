<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';
    protected $guarded = ['id'];

    protected $touches = ['chat'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(user::class,'user_id');
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class,'chat_id');
    }
}