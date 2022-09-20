<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class EmailTemplate extends Model
{
    use HasFactory;
    use PimpableTrait;

    protected $table = "email_templates";

    protected $sortParameterName = 'sort';

    public $sortable = ['from_email', 'to_email', 'created_at'];

    protected $searchable = ['search_txt', 'from_email', 'to_email'];

    // use one filter to search in multiple columns

    protected function processSearchTxtFilter(Builder $builder, Constraint $constraint)
    {

        if ($constraint->getValue() == '') {
            return true;
        }

        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($constraint) {
                $query->where('from_email', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('to_email', $constraint->getOperator(), $constraint->getValue());
            });

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }


    public static function addUpdate($emailTemplate, $request)
    {

        if (isset($request->client_id)) {
            $emailTemplate->client_id = null;
            if (!is_null($request->client_id)) {
                $emailTemplate->client_id = $request->client_id;
            }
        }

        if (isset($request->project_id)) {
            $emailTemplate->project_id = $request->project_id;
        }

        if (isset($request->from_email)) {
            $emailTemplate->from_email = $request->from_email;
        }

        if (isset($request->to_email)) {
            $emailTemplate->to_email = $request->to_email;
        }

        if (isset($request->cc)) {
            $emailTemplate->cc = $request->cc;
        }

        if (isset($request->bcc)) {
            $emailTemplate->bcc = $request->bcc;
        }

        if (isset($request->subject)) {
            $emailTemplate->subject = $request->subject;
        }

        if (isset($request->attechment)) {
            $emailTemplate->attechment = $request->attechment;
        }

        if (isset($request->template)) {
            $emailTemplate->template = $request->template;
        }

        if (isset($request->template_value)) {
            $emailTemplate->template_value = $request->template_value;
        }

        $emailTemplate->save();

        return $emailTemplate;
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
