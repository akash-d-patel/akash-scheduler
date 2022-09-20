<?php

namespace App\Http\Controllers;

use App\Models\EmailAttechmentHistory;
use Illuminate\Http\Request;
use App\Http\Resources\EmailAttechmentHistoryResource;
use App\Http\Controllers\Api\BaseController;

class EmailAttechmentHistoryController extends BaseController
{
    /**
     * List
     * @group EmailAttechmentHistory
     */
    public function index(EmailAttechmentHistory $emailAttechmentHistory)
    {
        $emailAttechmentHistories = $emailAttechmentHistory->pimp()->paginate();
        EmailAttechmentHistoryResource::collection($emailAttechmentHistories);
        return $this->sendResponse(compact('emailAttechmentHistories'), "All Record");
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
     * @group EmailAttechmentHistory
     */
    public function store(Request $request)
    {
        $emailAttechmentHistory = EmailAttechmentHistory::addUpdate(new EmailAttechmentHistory, $request);
        $message = "Email Attechment History added successfully";
        $emailAttechmentHistory = new EmailAttechmentHistoryResource($emailAttechmentHistory);
        return $this->sendResponse(compact('emailAttechmentHistory'), $message);
    }

    /**
     * Show
     * @group Client
     */
    public function show(EmailAttechmentHistory $emailAttechmentHistory)
    {
        $message = 'Email Attechment History listed successfully';
        $emailAttechmentHistory = new EmailAttechmentHistoryResource($emailAttechmentHistory);
        return $this->sendResponse(compact('emailAttechmentHistory'), $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmailAttechmentHistory  $emailAttechmentHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailAttechmentHistory $emailAttechmentHistory)
    {
        //
    }

    /**
     * Update
     * @group EmailAttechmentHistory
     */
    public function update(Request $request, EmailAttechmentHistory $emailAttechmentHistory)
    {
        $emailAttechmentHistory = EmailAttechmentHistory::addUpdate($emailAttechmentHistory, $request);
        $message = "Email Attechment History updated successfully";
        $emailAttechmentHistory = new EmailAttechmentHistoryResource($emailAttechmentHistory);
        return $this->sendResponse(compact('emailAttechmentHistory'), $message);
    }


    /**
     * Delete
     * @group EmailAttechmentHistory
     */
    public function destroy(EmailAttechmentHistory $emailAttechmentHistory)
    {
        $emailAttechmentHistory->delete();
        $message = "Email Template deleted successfully";
        $emailAttechmentHistory = new EmailAttechmentHistoryResource($emailAttechmentHistory);
        return $this->sendResponse(compact('emailAttechmentHistory'), $message);
    }
}
