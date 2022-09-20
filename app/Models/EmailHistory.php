<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class EmailHistory extends Model
{
    use HasFactory;
    use PimpableTrait;
    use SoftDeletes;

    protected $table = "email_histories";

    protected $sortParameterName = 'sort';

    public $sortable = ['from_email', 'to_email', 'status', 'created_at'];

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

    public static function boot()
    {
        parent::boot();

        self::creating(function ($emailHistory) {
            if (Auth::check()) {

                $emailHistory->created_by = Auth::user()->id;
            }
        });

        self::created(function ($emailHistory) {
            // ... code here
        });

        self::updating(function ($emailHistory) {
            $emailHistory->updated_by = Auth::user()->id;
        });

        self::updated(function ($emailHistory) {
            // ... code here
        });

        self::deleting(function ($emailHistory) {
            $emailHistory->deleted_by = Auth::user()->id;
            $emailHistory->save();
        });

        self::deleted(function ($emailHistory) {
        });
    }

    /* emailHistory create and update method */

    public static function addUpdate($emailHistory, $request)
    {

        if (isset($request->email_template_id)) {
            $emailHistory->email_template_id = null;
            if (!is_null($request->email_template_id)) {
                $emailHistory->email_template_id = $request->email_template_id;
            }
        }

        if (isset($request->client_id)) {
            $emailHistory->client_id = null;
            if (!is_null($request->client_id)) {
                $emailHistory->client_id = $request->client_id;
            }
        }

        if (isset($request->project_id)) {
            $emailHistory->project_id = null;
            if (!is_null($request->project_id)) {
                $emailHistory->project_id = $request->project_id;
            }
        }

        if (isset($request->from_email)) {
            $emailHistory->from_email = $request->from_email;
        }

        if (isset($request->to_email)) {
            $toEmailArrayString = implode(',', $request->to_email);
            $emailHistory->to_email = $toEmailArrayString;
        }

        if (isset($request->cc)) {
            $ccArrayString = implode(',', $request->cc);
            $emailHistory->cc = $ccArrayString;
        }

        if (isset($request->bcc)) {
            $bccArrayString = implode(',', $request->bcc);
            $emailHistory->bcc = $bccArrayString;
        }

        if (isset($request->subject)) {
            $emailHistory->subject = $request->subject;
        }

        if (isset($request->attechment)) {
            $emailHistory->attechment = $request->attechment;
        }

        if (isset($request->template)) {
            $emailHistory->template = $request->template;
        }

        if (isset($request->status)) {
            $emailHistory->status = $request->status;
        }

        $emailHistory->save();

        return $emailHistory;
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
    public function emailTemplate()
    {
        return $this->belongsTo(EmailTemplate::class, 'email_template_id');
    }

    /**
     * Get all of the projects's name.
     */

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }
}
