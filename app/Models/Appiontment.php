<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appiontment extends Model
{
    use HasFactory;

    //user info
    public function user_info() {
        return $this->belongsTo(User::class, 'user_id');
    }

    //user info
    public function visitor_info() {
        return $this->belongsTo(User::class, 'visitor');
    }

    //lead info
    public function lead_info() {
        return $this->belongsTo(LeadInfo::class, 'lead_id');
    }

    
    

}
