<?php

namespace App\Http\Controllers;

use App\Http\Resources\SmsTemplateResource;
use App\Models\SmsTemplate;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\SmsTemplate\CreateRequest;
use App\Http\Requests\SmsTemplate\EditRequest;

class SmsTemplateController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SmsTemplate $smsTemplate)
    {
        $smsTemplates = $smsTemplate->pimp()->paginate();
        SmsTemplateResource::collection($smsTemplates);
        return $this->sendResponse(compact('smsTemplates'), "All Record");
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $smsTemplate = SmsTemplate::addUpdate(new SmsTemplate, $request);
        $message = "SMS Template added successfully";
        $smsTemplate = new SmsTemplateResource($smsTemplate);
        return $this->sendResponse(compact('smsTemplate'), $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SmsTemplate  $smsTemplate
     * @return \Illuminate\Http\Response
     */
    public function show(SmsTemplate $smsTemplate)
    {
        $message = 'SMS Template listed successfully';
        $smsTemplate = new SmsTemplateResource($smsTemplate);
        return $this->sendResponse(compact('smsTemplate'), $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SmsTemplate  $smsTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(SmsTemplate $smsTemplate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SmsTemplate  $smsTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(EditRequest $request, SmsTemplate $smsTemplate)
    {
        $smsTemplate = SmsTemplate::addUpdate($smsTemplate, $request);
        $message = "SMS Template updated successfully";
        $smsTemplate = new SmsTemplateResource($smsTemplate);
        return $this->sendResponse(compact('smsTemplate'), $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SmsTemplate  $smsTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(SmsTemplate $smsTemplate)
    {
        $smsTemplate->delete();
        $message = "SMS Template deleted successfully";
        $smsTemplate = new SmsTemplateResource($smsTemplate);
        return $this->sendResponse(compact('smsTemplate'), $message);
    }
}
