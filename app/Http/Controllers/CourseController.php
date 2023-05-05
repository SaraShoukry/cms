<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseCollection;
use App\Http\Resources\CourseResource;
use App\Interfaces\CourseRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

class CourseController extends Controller
{
    private CourseRepositoryInterface $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }



    public function index(Request $request): JsonResponse
    {
        $page = $request->get('page', 1);
        $offset = $request->get('offset', 10);

        return response()->json([
            'data' =>  new CourseCollection($this->courseRepository->getAllCourses($offset, $page))
        ]);
    }

    public function show(Request $request): JsonResponse
    {
        $courseId = $request->route('id');

        return response()->json([
            'data' => new CourseResource($this->courseRepository->getCourseById($courseId))
        ]);
    }


    public function store(Request $request): JsonResponse
    {
        $courseDetails = $request->only([
            'name' ,
            'subject' ,
            'instructor_id',
            'start_date',
            'end_date'
        ]);
        $validator = Validator::make($courseDetails, [
            'name' => 'required|max:255|unique:courses,name',
            'subject' => 'required|max:255',
            'instructor_id'  => 'required|exists:instructors,id',
            'start_date'      => 'required|date|before:end_date',
            'end_date'        => 'date|after:start_date',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $courseDetails = $validator->validated();


        return response()->json(
            [
                'data' => $this->courseRepository->createCourse($courseDetails)
            ],
            Response::HTTP_CREATED
        );
    }


    public function update(Request $request): JsonResponse
    {
        $courseId = $request->id;
        $courseDetails = $request->only([
            'id',
            'name' ,
            'subject' ,
            'instructor_id' ,
            'start_date',
            'end_date',
        ]);
        $validator = Validator::make($courseDetails,[
            'id' => "required|exists:courses,id",
            'name' => "required|max:255|unique:courses,name,{$request->id},id",
            'subject' => 'required|max:255',
            'instructor_id'  => 'required|exists:instructors,id',
            'start_date'      => 'required|date|before:end_date',
            'end_date'        => 'date|after:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        return response()->json([
            'data' => $this->courseRepository->updateCourse($courseId, $courseDetails)
        ]);
    }



    public function delete(Request $request){

        $courseId = $request->id;

        $this->courseRepository->deleteCourse($courseId);

        return response()->json(['msg' => __('messages.course_deleted')]);


    }
}
