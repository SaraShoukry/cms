<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOperatorRequest;
use App\Http\Resources\OperatorResource;
use App\Http\Resources\InstructorResource;
use App\Interfaces\InstructorRepositoryInterface;
use App\Models\Instructor;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class InstructorController extends Controller
{
    private InstructorRepositoryInterface $instructorRepository;

    public function __construct(InstructorRepositoryInterface $instructorRepository)
    {
        $this->instructorRepository = $instructorRepository;
    }



    public function index(Request $request): JsonResponse
    {
        $page = $request->get('page', 1);
        $offset = $request->get('offset', 10);

        return response()->json([
            'data' => $this->instructorRepository->getAllInstructors($offset, $page)
        ]);
    }

    public function show(Request $request): JsonResponse
    {
        $instructorId = $request->route('id');

        return response()->json([
            'data' => $this->instructorRepository->getInstructorById($instructorId)
        ]);
    }


    public function store(Request $request): JsonResponse
    {
        $instructorDetails = $request->only([
            'name',
            'birthdate',
            'years_of_experience'
        ]);
        $validator = Validator::make($instructorDetails, [
            'name' => 'required|max:255|unique:instructors,name',
            'birthdate' => 'required|before:5 years ago',
            'years_of_experience'  => 'nullable|min:0',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $instructorDetails = $validator->validated();


        return response()->json(
            [
                'data' => $this->instructorRepository->createInstructor($instructorDetails)
            ],
            Response::HTTP_CREATED
        );
    }


    public function update(Request $request): JsonResponse
    {
        $instructorId = $request->id;
        $instructorDetails = $request->only([
            'id',
            'name',
            'birthdate',
            'years_of_experience'
        ]);
        $validator = Validator::make($instructorDetails,[
            'id' => "required|exists:instructors,id",
            'name' => "required|max:255|unique:instructors,name,{$request->id},id",
            'birthdate' => "required|before:20 years ago",
            'years_of_experience'  => "nullable|min:0"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        return response()->json([
            'data' => $this->instructorRepository->updateInstructor($instructorId, $instructorDetails)
        ]);
    }



    public function delete(Request $request){

        $instructorId = $request->id;

        $this->instructorRepository->deleteInstructor($instructorId);

        return response()->json(['msg' => __('messages.instructor_deleted')]);


    }
}
