<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
	
	protected $guard_name = "web";
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
	
	 
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];


    // Custom code
	
	public function newLead() { return $this->belongsTo(LeadInfo::class, 'id', 'assigned_to')->where('status','New')->count(); }
	public function reAssignedLead() { return $this->belongsTo(LeadInfo::class, 'id', 'assigned_to')->where('status','Re-Assigned')->count(); }
	public function totalLead() { return $this->belongsTo(LeadInfo::class, 'id', 'assigned_to')->count(); }
    public function sale() { return $this->belongsTo(Sale::class, 'id', 'saller_id')->count(); }
    public function install() { return $this->belongsTo(Sale::class, 'id', 'installer_id')->count(); }
    public function bounce() { return $this->belongsTo(Sale::class, 'id', 'saller_id')->where('status','Cancel')->count(); }
	
	public static function checkPermission($permissionName) {
      if(Auth::user()->can($permissionName) || Auth::user()->type == 'admin') {
          return true;
      }
    }
	
	public static function getPermissionGroupsForAdminHealperRole()
    {
      $permissionGroups = DB::table('permissions')->select('group_name')->groupBy('group_name')->orderBy('group_name', 'asc')->get();
      return $permissionGroups;
    }
	
	public static function permissionsByGroupNameForAdminHealperRole($groupname)
    {
      $permissions = DB::table('permissions')->where('group_name', $groupname)->orderBy('name', 'asc')->get();
      return $permissions;
    }
	
  
    
    public static function getPermissionGroupsForBranchUser()
    {
      $permissionGroups = DB::table('permissions')->select('group_name')->groupBy('group_name')->where('group_name', 'Branch')->get();
      return $permissionGroups;
    }

    public static function permissionsByGroupNameForBranchUserRole($groupname)
    {
      $permissions = DB::table('permissions')->where('group_name', $groupname)->where('group_name', 'Branch')->orderBy('name', 'asc')->get();
      return $permissions;
    }

    public static function checkMultiplePermission($permissionName) {
      if(Auth::user()->hasAnyPermission($permissionName) || Auth::user()->type == 'admin') {
          return true;
      }
    }



}
