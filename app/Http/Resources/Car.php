<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Car extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'model'        => $this->model,
            'category'     => $this->category,
            'motor_power'  => $this->motor_power,
            'ports'        => $this->ports,
            'board'        => $this->board,
            'type_vehicle' => $this->type_vehicle,
            'year'         => $this->year,
            'fuel'         => $this->fuel,
            'mileage'      => $this->mileage,
            'exchange'     => $this->exchange,
            'direction'    => $this->direction,
            'color'        => $this->color,
            'options'      => $this->options,
            'created'      => (string)$this->created_at->format('m/d/Y')
        ];
    }
}
