<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadInfo extends Model
{
    use HasFactory;

    //user info
    public function user_info() {
        return $this->belongsTo(User::class, 'user_id');
    }

    //lead Note
    public function lead_notes(){
        return $this->hasMany(LeadNote::class, 'lead_id', 'id');
    }


    //last Note
    public function last_note(){
        return $this->hasOne(LeadNote::class, 'lead_id', 'id')->orderBy('id', 'desc');
    }

    //lead Note
    public function lead_appointments(){
        return $this->hasMany(Appiontment::class, 'lead_id', 'id');
    }

    



}
