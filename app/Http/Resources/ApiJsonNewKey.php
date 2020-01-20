<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiJsonNewKey extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'userKeyId' => $this->userKeyId,
            'userId' => $this->userId,
            'messageFirst' => $this->messageFirst,
            'messageSecond' => $this->messageSecond,
        ];
    }
}
