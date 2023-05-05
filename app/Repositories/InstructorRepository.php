<?php

namespace App\Repositories;

use App\Interfaces\InstructorRepositoryInterface;
use App\Models\Instructor;
use Carbon\Carbon;

class InstructorRepository implements InstructorRepositoryInterface
{
    public function getAllInstructors($offset, $page)
    {
        $instructors = Instructor::paginate($offset, '*', 'page', $page);

        return $instructors;
    }

    public function getInstructorById($instructorId)
    {
        return Instructor::findOrFail($instructorId);
    }

    public function deleteInstructor($instructorId)
    {

        $instructor = Instructor::find($instructorId);

        $instructor->deleted_at = Carbon::now();
        $instructor->save();
    }

    public function createInstructor(array $instructorDetails)
    {
        return Instructor::create($instructorDetails);
    }

    public function updateInstructor($instructorId, array $newDetails)
    {
        return Instructor::whereId($instructorId)->update($newDetails);
    }


}
