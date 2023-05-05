<?php

namespace App\Http\Resources;

use App\Libraries\Hashids\Hashids;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'birthdate' => $this->birthdate,
            'years_of_experience' => $this->years_of_experience,
        ];
    }
}
