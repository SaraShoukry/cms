<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CourseCollection extends ResourceCollection
{
  public function toArray($request)
  {
      return [
          'data' => CourseResource::collection($this->collection),
          'pagination' => [
              'total' => $this->total(),
              'count' => $this->count(),
              'number_per_page' => $this->perPage(),
              'page' => $this->currentPage(),
              'last_page' => $this->lastPage(),
          ],

      ];
  }
}
