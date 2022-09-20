<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;

class EmailAttechmentHistory extends Model
{
    use HasFactory;
    use PimpableTrait;

    protected $table = "email_attechment_histories";

    public static function addUpdate($emailAttechmentHistory, $request)
    {
        if (isset($request->email_history_id)) {
            $emailAttechmentHistory->email_history_id = null;
            if (!is_null($request->email_history_id)) {
                $emailAttechmentHistory->email_history_id = $request->email_history_id;
            }
        }

        $emailAttechmentHistory->save();

        return $emailAttechmentHistory;
    }

    /**
     * Get all of the email history attechment.
     */

    public function attechments()
    {
        return $this->belongsTo(EmailHistory::class, 'email_history_id');
    }
}
