<?php

namespace App\Repositories;

use App\Interfaces\CourseRepositoryInterface;
use App\Models\Course;
use Carbon\Carbon;

class CourseRepository implements CourseRepositoryInterface
{
    public function getAllCourses($offset, $page)
    {
        $courses = Course::paginate($offset, '*', 'page', $page);

        return $courses;
    }

    public function getCourseById($courseId)
    {
        return Course::findOrFail($courseId);
    }

    public function deleteCourse($courseId)
    {

        $course = Course::find($courseId);

        $course->deleted_at = Carbon::now();
        $course->save();
    }

    public function createCourse(array $courseDetails)
    {
        $course = Course::make($courseDetails);
        $course->instructor()->associate($courseDetails['instructor_id']);
        $course->save();
        return $course;
    }

    public function updateCourse($courseId, array $newDetails)
    {
        return Course::whereId($courseId)->update($newDetails);
    }


}
