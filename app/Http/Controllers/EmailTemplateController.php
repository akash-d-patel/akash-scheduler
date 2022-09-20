<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use App\Http\Requests\EmailTemplate\CreateRequest;
use App\Http\Requests\EmailTemplate\EditRequest;
use App\Http\Resources\EmailTemplateResource;
use App\Http\Controllers\Api\BaseController;

class EmailTemplateController extends BaseController
{
    /**
     * List
     * @group EmailTemplate
     */
    public function index(EmailTemplate $emailTemplate)
    {
        $emailTemplates = $emailTemplate->pimp()->paginate();
        EmailTemplateResource::collection($emailTemplates);
        return $this->sendResponse(compact('emailTemplates'), "All Record");
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
     * @group EmailTemplate
     */
    public function store(CreateRequest $request)
    {
        $emailTemplate = EmailTemplate::addUpdate(new EmailTemplate, $request);
        $message = "Email Template added successfully";
        $emailTemplate = new EmailTemplateResource($emailTemplate);
        return $this->sendResponse(compact('emailTemplate'), $message);
    }

    /**
     * Show
     * @group EmailTemplate
     */
    public function show(EmailTemplate $emailTemplate)
    {
        $message = 'Email Template listed successfully';
        $emailTemplate = new EmailTemplateResource($emailTemplate);
        return $this->sendResponse(compact('emailTemplate'), $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        //
    }

    /**
     * Update
     * @group EmailTemplate
     */
    public function update(EditRequest $request, EmailTemplate $emailTemplate)
    {
        $emailTemplate = EmailTemplate::addUpdate($emailTemplate, $request);
        $message = "Email Template updated successfully";
        $emailTemplate = new EmailTemplateResource($emailTemplate);
        return $this->sendResponse(compact('emailTemplate'), $message);
    }

    /**
     * Delete
     * @group EmailTemplate
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();
        $message = "Email Template deleted successfully";
        $emailTemplate = new EmailTemplateResource($emailTemplate);
        return $this->sendResponse(compact('emailTemplate'), $message);
    }
}
