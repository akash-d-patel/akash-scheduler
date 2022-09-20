<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SmsHistory extends Model
{
    use HasFactory;
    use PimpableTrait;
    use SoftDeletes;

    protected $table = "sms_histories";

    protected $sortParameterName = 'sort';

    public $sortable = ['mobile_no', 'DLT_no', 'status', 'created_at'];

    protected $searchable = ['search_txt', 'mobile_no', 'text'];


    // use one filter to search in multiple columns

    protected function processSearchTxtFilter(Builder $builder, Constraint $constraint)
    {

        if ($constraint->getValue() == '') {
            return true;
        }

        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($constraint) {
                $query->where('mobile_no', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('text', $constraint->getOperator(), $constraint->getValue());
            });

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($smsHistory) {
            if (Auth::check()) {

                $smsHistory->created_by = Auth::user()->id;
            }
        });

        self::created(function ($smsHistory) {
            // ... code here
        });

        self::updating(function ($smsHistory) {
            $smsHistory->updated_by = Auth::user()->id;
        });

        self::updated(function ($smsHistory) {
            // ... code here
        });

        self::deleting(function ($smsHistory) {
            $smsHistory->deleted_by = Auth::user()->id;
            $smsHistory->save();
        });

        self::deleted(function ($smsHistory) {
        });
    }

    /* smsHistory create and update method */

    public static function addUpdate($smsHistory, $request)
    {

        if (isset($request->sms_template_id)) {
            $smsHistory->sms_template_id = null;
            if (!is_null($request->sms_template_id)) {
                $smsHistory->sms_template_id = $request->sms_template_id;
            }
        }

        if (isset($request->client_id)) {
            $smsHistory->client_id = null;
            if (!is_null($request->client_id)) {
                $smsHistory->client_id = $request->client_id;
            }
        }

        if (isset($request->project_id)) {
            $smsHistory->project_id = null;
            if (!is_null($request->project_id)) {
                $smsHistory->project_id = $request->project_id;
            }
        }

        if (isset($request->mobile_no)) {
            $smsHistory->mobile_no = $request->mobile_no;
        }

        if (isset($request->text)) {
            $smsHistory->text = $request->text;
        }

        if (isset($request->DLT_no)) {
            $smsHistory->DLT_no = $request->DLT_no;
        }

        if (isset($request->DLT_text)) {
            $smsHistory->DLT_text = $request->DLT_text;
        }

        if (isset($request->status)) {
            $smsHistory->status = $request->status;
        }

        $smsHistory->save();

        return $smsHistory;
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
     * Get all of the clients's name.
     */

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    /**
     * Get all the email template data
     */
    public function smsTemplate()
    {
        return $this->belongsTo(SmsTemplate::class, 'sms_template_id');
    }

    /**
     * Get all of the projects's name.
     */

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }
}
