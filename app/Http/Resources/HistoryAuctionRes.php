<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HistoryAuctionRes extends JsonResource
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
            'id'=>$this->id,
            'id_auction'=>$this->id_auction,
            'bid_price'=>$this->bid_price,
            'user'=>[
                'name' => $this->name,
                'image' => $this->image,
                'telp' => $this->telp,
                'email' => $this->email
            ]
            ];
    }
}
