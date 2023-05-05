<?php

namespace App\Repositories;

use App\Interfaces\StudentRepositoryInterface;
use App\Models\Student;
use Carbon\Carbon;

class StudentRepository implements StudentRepositoryInterface
{
    public function getAllStudents($offset, $page)
    {
        $students = Student::paginate($offset, '*', 'page', $page);

        return $students;
    }

    public function getStudentById($studentId)
    {
        return Student::findOrFail($studentId);
    }

    public function deleteStudent($studentId)
    {

        $student = Student::find($studentId);

        $student->deleted_at = Carbon::now();
        $student->save();
    }

    public function createStudent(array $studentDetails)
    {
        return Student::create($studentDetails);
    }

    public function updateStudent($studentId, array $newDetails)
    {
        return Student::whereId($studentId)->update($newDetails);
    }


}
