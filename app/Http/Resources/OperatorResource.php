<?php

namespace App\Http\Resources;

use App\Libraries\Hashids\Hashids;
use Illuminate\Http\Resources\Json\JsonResource;

class OperatorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (new Hashids('', 6, ''))->encode($this->id),
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
        ];
    }
}
