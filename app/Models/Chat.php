<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $guarded = ['id'];

    public function participant(): HasMany
    {
        return $this->hasMany(ChatParticipant::class,'chat_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class,'chat_id');
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(ChatMessage::class,'chat_id')->lastest ('updated_at');
    }
}
