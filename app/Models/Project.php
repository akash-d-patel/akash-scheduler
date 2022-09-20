<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PimpableTrait;

    protected $table = "projects";

    protected $sortParameterName = 'sort';

    public $sortable = ['name', 'status', 'created_at'];

    protected $searchable = ['search_txt', 'name', 'status'];


    // use one filter to search in multiple columns

    protected function processSearchTxtFilter(Builder $builder, Constraint $constraint)
    {

        if ($constraint->getValue() == '') {
            return true;
        }

        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($constraint) {
                $query->where('name', $constraint->getOperator(), $constraint->getValue());
                //->orWhere('status', $constraint->getOperator(), $constraint->getValue());
            });

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($project) {
            if (Auth::check()) {

                $project->created_by = Auth::user()->id;
            }
        });

        self::created(function ($project) {
            // ... code here
        });

        self::updating(function ($project) {
            $project->updated_by = Auth::user()->id;
        });

        self::updated(function ($project) {
            // ... code here
        });

        self::deleting(function ($project) {
            $project->deleted_by = Auth::user()->id;
            $project->save();
        });

        self::deleted(function ($product) {
        });
    }

    /* product create and update method */

    public static function addUpdate($project, $request)
    {

        if (isset($request->client_id)) {
            $project->client_id = $request->client_id;
        } else {
            $project->client_id = Auth::user()->client->id;
        }

        if (isset($request->name)) {
            $project->name = $request->name;
        }

        if (isset($request->status)) {
            $project->status = $request->status;
        }

        $project->save();

        return $project;
    }

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
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
        return $this->hasone(User::class, 'id', 'deleted_by')->withTrasheds();
    }

    /**
     * Get all of the client name.
     */

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }
}
