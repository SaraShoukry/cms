<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOperatorRequest;
use App\Http\Resources\OperatorResource;
use App\Http\Resources\StudentResource;
use App\Interfaces\StudentRepositoryInterface;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class StudentController extends Controller
{
    private StudentRepositoryInterface $studentRepository;

    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }



    public function index(Request $request): JsonResponse
    {
        $page = $request->get('page', 1);
        $offset = $request->get('offset', 10);

        return response()->json([
            'data' => $this->studentRepository->getAllStudents($offset, $page)
        ]);
    }

    public function show(Request $request): JsonResponse
    {
        $studentId = $request->route('id');

        return response()->json([
            'data' => $this->studentRepository->getStudentById($studentId)
        ]);
    }


    public function store(Request $request): JsonResponse
    {
        $studentDetails = $request->only([
            'name',
            'birthdate',
            'grade'
        ]);
        $validator = Validator::make($studentDetails, [
            'name' => 'required|max:255|unique:students,name',
            'birthdate' => 'required|before:5 years ago',
            'grade'  => 'nullable|min:0',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $studentDetails = $validator->validated();


        return response()->json(
            [
                'data' => $this->studentRepository->createStudent($studentDetails)
            ],
            Response::HTTP_CREATED
        );
    }


    public function update(Request $request): JsonResponse
    {
        $studentId = $request->id;
        $studentDetails = $request->only([
            'id',
            'name',
            'birthdate',
            'grade'
        ]);
        $validator = Validator::make($studentDetails,[
            'id' => "required|exists:students,id",
            'name' => "required|max:255|unique:students,name,{$request->id},id",
            'birthdate' => "required|before:5 years ago",
            'grade'  => "nullable|min:0"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        return response()->json([
            'data' => $this->studentRepository->updateStudent($studentId, $studentDetails)
        ]);
    }



    public function delete(Request $request){

        $studentId = $request->route('id');

        $this->studentRepository->deleteStudent($studentId);

        return response()->json(['msg' => __('messages.student_deleted')]);


    }
}
