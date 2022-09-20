<?php

namespace App\Http\Resources;

use App\Models\EmailTemplate;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return
            [
                'id' => $this->id,
                'email_template_id' => new EmailTemplateResource($this->emailTemplate),
                'client_id' => $this->client_id,
                'project_id' => $this->project_id,
                'from_email' => $this->from_email,
                'to_email' => $this->to_email,
                'cc' => $this->cc,
                'bcc' => $this->bcc,
                'subject' => $this->subject,
                'attechment' => $this->attechment,
                'template' => $this->template,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'created_by' => new UserResource($this->creater),
            ];
    }
}
