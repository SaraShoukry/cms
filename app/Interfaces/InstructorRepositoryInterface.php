<?php

namespace App\Interfaces;

interface InstructorRepositoryInterface
{
    public function getAllInstructors($offset, $page);
    public function getInstructorById($instructorId);
    public function deleteInstructor($instructorId);
    public function createInstructor(array $instructorDetails);
    public function updateInstructor($instructorId, array $newDetails);
    }
