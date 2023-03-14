<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserRes extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'id_staff'=> $this->id_staff,
            'name'=> $this-> name,
            'email'=>$this->email,
            'telp'=>$this->telp,
            'image'=>$this->image,
            'level'=>$this->level
        ];
    }
}
