<?php

namespace App\Interfaces;

interface StudentRepositoryInterface
{
    public function getAllStudents($offset, $page);
    public function getStudentById($studentId);
    public function deleteStudent($studentId);
    public function createStudent(array $studentDetails);
    public function updateStudent($studentId, array $newDetails);
    }
