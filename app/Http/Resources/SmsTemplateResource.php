<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SmsTemplateResource extends JsonResource
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
                'client_id' => $this->client_id,
                'project_id' => $this->project_id,
                'mobile_no' => $this->mobile_no,
                'text' => $this->text,
                'DLT_no' => $this->DLT_no,
                'DLT_text' => $this->DLT_text,
                'created_at' => $this->created_at,
            ];
    }
}
