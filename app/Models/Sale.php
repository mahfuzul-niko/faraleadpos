<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory; 
	
	protected $fillable = [
        'user_id',
        'file_no',
        'saller_id',
        'sale_date',
        'name',
        'address',
        'mobile',
        'bill_type',
        'bill_amount',
        'installation_charge',
        'installation_date',
        'installer_id',
        'advance',
        'due',
        'note',
        'status',
    ];
	
	public function saller() {
        return $this->belongsTo(User::class, 'saller_id');
    }
	
	public function installer() {
        return $this->belongsTo(User::class, 'installer_id');
    }
	
}
