<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuctionRes extends JsonResource
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
            'id_auction' => $this->id_auction,
            'id_staff' => $this->id_staff,
            'date_open_auction' => $this->date_open_auction,
            'date_close_auction' => $this->date_close_auction,
            'status_item' => $this->status_item,
            'item' => [
                'id_item'=> $this->id_item,
                'item_name'=> $this->name_item,
                'open_price'=> $this->opening_price,
                'final_price'=> $this->final_price,
                'description_item' => $this->description_item
            ],
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'image' => $this->image,
                'final_price'=>$this->final_price,
            ]
        ];
    }
}
