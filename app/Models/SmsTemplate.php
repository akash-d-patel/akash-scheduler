<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use Illuminate\Database\Eloquent\Builder;

class SmsTemplate extends Model
{
    use HasFactory;
    use PimpableTrait;

    protected $table = "sms_templates";

    protected $sortParameterName = 'sort';

    public $sortable = ['mobile_no', 'created_at'];

    protected $searchable = ['search_txt', 'text', 'mobile_no'];

    // use one filter to search in multiple columns

    protected function processSearchTxtFilter(Builder $builder, Constraint $constraint)
    {

        if ($constraint->getValue() == '') {
            return true;
        }

        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($constraint) {
                $query->where('text', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('mobile_no', $constraint->getOperator(), $constraint->getValue());
            });

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }


    public static function addUpdate($smsTemplate, $request)
    {

        if (isset($request->client_id)) {
            $smsTemplate->client_id = null;
            if (!is_null($request->client_id)) {
                $smsTemplate->client_id = $request->client_id;
            }
        }

        if (isset($request->project_id)) {
            $smsTemplate->project_id = null;
            if (!is_null($request->project_id)) {
                $smsTemplate->project_id = $request->project_id;
            }
        }
        if (isset($request->mobile_no)) {
            $smsTemplate->mobile_no = $request->mobile_no;
        }

        if (isset($request->text)) {
            $smsTemplate->text = $request->text;
        }

        if (isset($request->DLT_no)) {
            $smsTemplate->DLT_no = $request->DLT_no;
        }

        if (isset($request->DLT_text)) {
            $smsTemplate->DLT_text = $request->DLT_text;
        }

        $smsTemplate->save();

        return $smsTemplate;
    }

    /**
     * Get all of the clients's name.
     */

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    /**
     * Get all of the projects's name.
     */

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }
}
