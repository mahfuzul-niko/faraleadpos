<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadInfo extends Model
{
    use HasFactory;
	
	protected $fillable = ['assigned_to','status'];

    //user info
    public function user_info() {
        return $this->belongsTo(User::class, 'user_id');
    }
	
	public function assigned() {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    //lead Note
    public function lead_notes(){
        return $this->hasMany(LeadNote::class, 'lead_id', 'id');
    }
    public function lead_source() {
        return $this->belongsTo(LeadSource::class, 'source');
    }

    //last Note
    public function last_note(){
        return $this->hasOne(LeadNote::class, 'lead_id', 'id')->orderBy('id', 'desc');
    }

    //lead Note
    public function lead_appointments(){
        return $this->hasMany(Appiontment::class, 'lead_id', 'id')->orderBy('appiontment_datetime', 'desc');
    }

    



}
