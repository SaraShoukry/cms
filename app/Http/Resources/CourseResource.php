<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'subject' => $this->subject,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'instructor' => new InstructorResource($this->instructor),

        ];
    }
}
