<?php

namespace App;

use App\Models\Store;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\QueryException;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use Notifiable, SoftDeletes;
    use HasRoles;


    public static $ROLE_ADMIN = 'admin';
    public static $ROLE_ADMIN_IDG = 'admin_idg';
    public static $ROLE_EDITOR = 'editor';
    public static $ROLE_QUOTING = 'quoting';

    public static function getRoleOrPermission()
    {
        // Las rutas se cargan también desde artisan, cuando la base puede no
        // existir o estar vacía. Sin roles, el middleware niega el acceso.
        try {
            $roles = Role::where('guard_name', 'admin')->pluck('name')->toArray();
        } catch (QueryException $e) {
            if (!app()->runningInConsole()) {
                throw $e;
            }
            $roles = [];
        }
        return join('|', $roles);
        return join('|',[
            self::$ROLE_ADMIN,
            self::ROLE_EDITOR,
            self::ROLE_QUOTING,
        ]);
    }

    public static function getRoles()
    {
        return [
            self::$ROLE_ADMIN => 'Admin',
            self::ROLE_EDITOR => 'Editor',
            self::ROLE_QUOTING => 'Cotizador',

        ];
    }


    protected $table = 'users';
    protected $guard = 'admin';
    protected $guard_name = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public static $rules = [

    ];

    public static function boot()
    {
        parent::boot();

        //while creating/inserting item into db
        static::deleting(function ($user) {
            Log::debug(' Se elimino el usuario', $user->toArray());
            $user->email .= '_' . date('YmdHis');
            $user->telephone .= '_' . date('YmdHis');
            $user->save();
        });
    }
}
