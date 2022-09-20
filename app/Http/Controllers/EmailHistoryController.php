<?php

namespace App\Http\Controllers;

use App\Models\EmailHistory;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\EmailHistory\CreateRequest;
use App\Http\Requests\EmailHistory\EditRequest;
use App\Http\Resources\EmailHistoryResource;
use App\Jobs\SendEmailJob;
use App\Models\Client;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Request;

class EmailHistoryController extends BaseController
{
    /**
     * List
     * @group EmailHistory
     */
    public function index()
    {
        $emailHistories = EmailHistory::with(['creater'])->pimp()->paginate();
        $message = "All Records";
        EmailHistoryResource::collection($emailHistories);
        return $this->sendResponse(compact('emailHistories'), $message);
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
     * @group EmailHistory
     */
    public function store(CreateRequest $request)
    {
        $emailHistory = EmailHistory::addUpdate(new EmailHistory, $request);
        $message = "Email History added successfully";
        $emailHistory = new EmailHistoryResource($emailHistory);
        return $this->sendResponse(compact('emailHistory'), $message);
    }

    /**
     * Show
     * @group EmailHistory
     */
    public function show(EmailHistory $emailHistory)
    {
        $message = 'Email History listed sucessfully';
        $emailHistory = new EmailHistoryResource($emailHistory);
        return $this->sendResponse(compact('emailHistory'), $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmailHistory  $emailHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailHistory $emailHistory)
    {
        //
    }

    /**
     * Update
     * @group EmailHistory
     */
    public function update(EditRequest $request, EmailHistory $emailHistory)
    {
        $emailHistory = EmailHistory::addUpdate($emailHistory, $request);
        $message = "Email History updated successfully";
        $emailHistory = new EmailHistoryResource($emailHistory);
        return $this->sendResponse(compact('emailHistory'), $message);
    }

    /**
     * Delete
     * @group EmailHistory
     */
    public function destroy(EmailHistory $emailHistory)
    {
        $emailHistory->delete();
        $message = "Email History deleted successfully";
        $emailHistory = new EmailHistoryResource($emailHistory);
        return $this->sendResponse(compact('emailHistory'), $message);
    }

    /**
     * Send
     * @group EmailHistory
     */
    public function send(EmailHistory $emailHistory, CreateRequest $request)
    {
        $data = $request->all();

        $arrTemplateVar =  json_decode($request->template_var,true);

        $email_template_id = $request->email_template_id;

        $emailBody = EmailTemplate::where('id',$email_template_id)->value('template');
        
        foreach($arrTemplateVar as $key => $value)
        {
            $emailBody = str_replace($key, $value, $emailBody);
        }

        $base64String = $request->file_base64;

        /* uniq name create */
        $file_name =  uniqid();

        @list($type, $file_data) = explode(';', $base64String);
        @list(, $file_data) = explode(',', $file_data);
        $type = explode(";", explode("/", $base64String)[0])[0];
        /* file create */
        $attechment = 'files/' . $file_name . '.' . $type;
        /* public path storage */
        // Storage::disk('public')->put($attechment, base64_decode($file_data));
        // $file_path = Storage::url('') . $attechment;
        // $data['file_url'] = config('app.url') . $file_path;
        /* file name store in title */
        $request->title = $file_name . '.' . $type;
        /* file url store in path */
        $request->attechment = config('app.url') . $attechment;

        $emailHistory = EmailHistory::addUpdate(new EmailHistory, $request);

        $message = "Email send successfully";

        $emailHistory = new EmailHistoryResource($emailHistory);

        $emailHistories = EmailHistory::all();

        SendEmailJob::dispatch($emailHistory,$emailBody);
        
        return $this->sendResponse(compact('emailHistory'), $message);
    }
}
