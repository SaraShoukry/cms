<?php

namespace App\Http\Resources;

use App\Libraries\Hashids\Hashids;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'birthdate' => $this->birthdate,
            'grade' => $this->grade,
        ];
    }
}
