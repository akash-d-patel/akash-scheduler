<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SmsHistoryResource extends JsonResource
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
                'sms_template_id' => new SmsTemplateResource($this->smsTemplate),
                'client_id' => $this->client_id,
                'project_id' => $this->project_id,
                'mobile_no' => $this->mobile_no,
                'text' => $this->text,
                'DLT_no' => $this->DLT_no,
                'DLT_text' => $this->DLT_text,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'created_by' => new UserResource($this->creater),
            ];
    }
}
