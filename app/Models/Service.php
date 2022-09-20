<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use Illuminate\Database\Eloquent\Builder;

class Service extends Model
{
    use HasFactory;
    use PimpableTrait;

    protected $table = "services";

    protected $fillable = ['name', 'description'];

    protected $sortParameterName = 'sort';

    public $sortable = ['name', 'created_at'];

    protected $searchable = ['search_txt', 'name'];

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

    public static function addUpdate($service, $request)
    {
        if (isset($request->client_id)) {
            $service->client_id = null;
            if (!is_null($request->client_id)) {
                $service->client_id = $request->client_id;
            }
        }

        if (isset($request->name)) {
            $service->name = $request->name;
        }

        if (isset($request->description)) {
            $service->description = $request->description;
        }

        $service->save();

        return $service;
    }

    /**
     * Get all of the clients's name.
     */

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }
}
