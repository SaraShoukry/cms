<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOperatorRequest;
use App\Http\Resources\OperatorResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class OperatorController extends Controller
{
    public function index(Request $request)
    {

        $page = $request->get('page', 1);
        $offset = $request->get('offset', 10);
        $operators = User::where('role', User::OPERATOR)
            ->paginate($offset, '*', 'page', $page);
        return response()->json([
            'operators' => $operators
        ]);

    }

    public function show($id)
    {

        $operator = new OperatorResource(User::findOrFail($id));

        return response()->json([
            'operator' => $operator
        ]);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users,username',
            'email'  => 'email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $operator = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password),
                'role' => User::OPERATOR]
        ));

        return response()->json([
            'operator' => $operator
        ]);

    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id' => 'required|exists:users,id',
            'name' => 'required|max:255',
            'username' => "required|max:255|unique:users,username,{$request->id},id",
            'email' => "required|max:255|unique:users,email,{$request->id},id",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $operator=User::findOrFail($request->id);
        $operator->name = $request->name;
        $operator->username = $request->username;
        $operator->email = $request->email;

        $operator->save();

        return response()->json([
            'operator' => $operator
        ]);
    }


    public function delete(Request $request){

        $user = User::find($request->operator_id);

        $user->deleted_at = Carbon::now();
        $user->save();

        return response()->json([
            'msg' => __('messages.operator_deleted')
        ]);

    }
}
