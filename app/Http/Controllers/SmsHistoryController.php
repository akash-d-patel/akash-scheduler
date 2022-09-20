<?php

namespace App\Http\Controllers;

use App\Http\Resources\SmsHistoryResource;
use App\Models\SmsHistory;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\SmsHistory\CreateRequest;
use App\Http\Requests\SmsHistory\EditRequest;
use App\Jobs\SendSmsJob;

class SmsHistoryController extends BaseController
{
    /**
     * List
     * @group SmsHistory
     */
    public function index()
    {
        $smsHistories = SmsHistory::with(['creater'])->pimp()->paginate();
        $message = "All Records";
        SmsHistoryResource::collection($smsHistories);
        return $this->sendResponse(compact('smsHistories'), $message);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Add
     * @group SmsHistory
     */
    public function store(CreateRequest $request)
    {
        $smsHistory = SmsHistory::addUpdate(new SmsHistory, $request);
        $message = "SMS History added successfully";
        $smsHistory = new SmsHistoryResource($smsHistory);
        return $this->sendResponse(compact('smsHistory'), $message);
    }

    /**
     * Show
     * @group SmsHistory
     */
    public function show(SmsHistory $smsHistory)
    {
        $message = 'SMS History listed sucessfully';
        $smsHistory = new SmsHistoryResource($smsHistory);
        return $this->sendResponse(compact('smsHistory'), $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SmsHistory  $smsHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(SmsHistory $smsHistory)
    {
        //
    }

    /**
     * Update
     * @group SmsHistory
     */
    public function update(EditRequest $request, SmsHistory $smsHistory)
    {
        $smsHistory = SmsHistory::addUpdate($smsHistory, $request);
        $message = "SMS History updated successfully";
        $smsHistory = new SmsHistoryResource($smsHistory);
        return $this->sendResponse(compact('smsHistory'), $message);
    }

    /**
     * Delete
     * @group SmsHistory
     */
    public function destroy(SmsHistory $smsHistory)
    {
        $smsHistory->delete();
        $message = "SMS History deleted successfully";
        $smsHistory = new SmsHistoryResource($smsHistory);
        return $this->sendResponse(compact('smsHistory'), $message);
    }

    /**
     * Send
     * @group SmsHistory
     */
    public function send(SmsHistory $smsHistory, CreateRequest $request)
    {
        $smsHistory = SmsHistory::addUpdate(new SmsHistory, $request);
        $message = "SMS send successfully";

        $smsHistory = new SmsHistoryResource($smsHistory);

        SendSmsJob::dispatch($smsHistory);

        return $this->sendResponse(compact('smsHistory'), $message);
    }
}
