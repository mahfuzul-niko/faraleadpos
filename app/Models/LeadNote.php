<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadNote extends Model
{
    use HasFactory;
    
    //user info
    public function user_info() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
