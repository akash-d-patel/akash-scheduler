<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use Illuminate\Database\Eloquent\Builder;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    use SoftDeletes;
    use PimpableTrait;

    protected $table = 'users';

    protected $with = ['creater'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'name',
        'email',
        'mobile_no',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'mobile_no_verified_at' => 'datetime'
    ];

    protected $sortParameterName = 'sort';

    public $sortable = ['name', 'email', 'mobile_no', 'status', 'created_at'];

    protected $searchable = ['search_txt', 'name', 'email', 'status'];


    // use one filter to search in multiple columns

    protected function processSearchTxtFilter(Builder $builder, Constraint $constraint)
    {

        if ($constraint->getValue() == '') {
            return true;
        }

        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($constraint) {
                $query->where('name', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('email', $constraint->getOperator(), $constraint->getValue());
                // ->orWhere('mobile_no', $constraint->getOperator(), $constraint->getValue());
            });

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

    public static function createUpdate($user, $request)
    {
        if (isset($request->client_id)) {
            $user->client_id = $request->client_id;
        } else {
            try {
                $user->client_id = Auth::user()->client->id;
            } catch (Exception $e) {

                $user->client_id = null;
            }
        }

        if (isset($request->name)) {
            $user->name = $request->name;
        }

        if (isset($request->email)) {
            $user->email = $request->email;
        }

        if (isset($request->mobile_no)) {
            $user->mobile_no = $request->mobile_no;
        }

        if (isset($request->password)) {
            $user->password = bcrypt($request->password);
        }

        if (isset($request->confirmpassword)) {
            $user->confirmpassword = $request->confirmpassword;
        }
        if (isset($request->status)) {
            $user->status = $request->status;
        }
        $user->save();

        return $user;
    }
    public static function boot()
    {
        parent::boot();

        self::creating(function ($user) {
            if (Auth::check()) {
                $user->created_by = Auth::user()->id;
            }
        });

        self::created(function ($user) {
            // ... code here
        });

        self::updating(function ($user) {
            if (Auth::check()) {
                $user->updated_by = Auth::user()->id;
            }
        });

        self::updated(function ($user) {
            // ... code here
        });

        self::deleted(function ($user) {
            $user->deleted_by = Auth::user()->id;
            $user->save();
        });
    }
    public function getCreatedAttribute()
    {
        return ucfirst($this->creater->name);
    }
    /** Get the ID of Address table*/

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addresstable');
    }

    /**
     * Get the phone associated with the user.
     */
    public function creater()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }

    public function updater()
    {
        return $this->hasOne(User::class, 'id', 'updated_by')->withTrashed();
    }

    public function deleter()
    {
        return $this->hasone(User::class, 'id', 'deleted_by')->withTrasheds();
    }

    /* get the client_id in client table */

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }
}
