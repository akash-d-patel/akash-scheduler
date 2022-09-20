<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Pimpable\PimpableTrait;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Searchable\Constraint;
use Illuminate\Support\Facades\Auth;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PimpableTrait;

    protected $table = 'clients';

    protected $sortParameterName = 'sort';

    public $sortable = ['name', 'status', 'created_at'];

    protected $searchable = ['search_txt', 'name', 'status'];

    protected function processSearchTxtFilter(Builder $builder, Constraint $constraint)
    {

        if ($constraint->getValue() == '') {
            return true;
        }

        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($constraint) {
                $query->where('name', $constraint->getOperator(), $constraint->getValue());
            });

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

    //Boot Method

    public static function boot()
    {
        parent::boot();

        self::creating(function ($client) {
            if (Auth::check()) {
                $client->created_by = Auth::user()->id;
            }
            //$user->order = self::count() + 1;
        });

        self::created(function ($client) {
            // ... code here
        });

        self::updating(function ($client) {
            $client->updated_by = Auth::user()->id;
        });

        self::updated(function ($client) {
            // ... code here
        });

        self::deleting(function ($client) {
            $client->deleted_by = Auth::user()->id;
            $client->save();
        });

        self::deleted(function ($client) {
        });
    }

    //Create update method

    public static function createUpdate($client, $request)
    {

        if (isset($request->name)) {
            $client->name = $request->name;
        }

        if (isset($request->status)) {
            $client->status = $request->status;
        }

        $client->save();

        return $client;
    }

    //created name

    public function getCreatedAttribute()
    {
        return ucfirst($this->creater->name);
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
        return $this->hasone(User::class, 'id', 'deleted_by')->withTrashed();
    }
}
