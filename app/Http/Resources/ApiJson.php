<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiJson extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'lastName' => $this->lastName,
            'messageFirst' => $this->messageFirst,
            'messageSecond' => $this->messageSecond,
        ];
    }
}
