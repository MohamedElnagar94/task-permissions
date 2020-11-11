<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function destroy(Request $request)
    {
        $user = User::findOrFail($request->id);
        DB::table('model_has_roles')->where('model_id',$request->id)->delete();
        DB::table('model_has_permissions')->where('model_id',$request->id)->delete();
        $user->delete();
        return response()->json(['message' => 'user is deleted successfully'], 200);
    }

    public function show(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        return response()->json(['user' => $user], 200);
    }

    public function update(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        if ($user->email === $request->email && $user->username === $request->username) {


//        if ($validator->passes()) {
            $validator = Validator::make(request()->all(), [
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
            ]);
            if ($validator->passes()) {
                User::where('id', $request->user_id)->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                ]);
                return response()->json(['message' => 'user is updated successfully'], 200);
            } else {
                return response()->json(["error" => $validator->messages()], 404);
            }
        } else {
            if ($user->username === $request->username) {
                if ($user->email === $request->email) {
                    User::where('id', $request->user_id)->update([
                        'name' => $request->name,
                        'username' => $request->username,
                        'email' => $request->email,
                    ]);
                } else {
                    $validator = Validator::make(request()->all(), [
                        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    ]);
                    if ($validator->passes()) {
                        User::where('id', $request->user_id)->update([
                            'name' => $request->name,
                            'username' => $request->username,
                            'email' => $request->email,
                        ]);
                        return response()->json(['message' => 'user is updated successfully'], 200);
                    } else {
                        return response()->json(["error" => $validator->messages()], 404);
                    }
                }
            } else {
                $validator = Validator::make(request()->all(), [
                    'username' => ['required', 'string', 'max:255', 'unique:users'],
                ]);
                if ($validator->passes()) {
                    if ($user->email === $request->email) {
                        User::where('id', $request->user_id)->update([
                            'name' => $request->name,
                            'username' => $request->username,
                            'email' => $request->email,
                        ]);
                    } else {
                        $validator = Validator::make(request()->all(), [
                            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                        ]);
                        if ($validator->passes()) {
                            User::where('id', $request->user_id)->update([
                                'name' => $request->name,
                                'username' => $request->username,
                                'email' => $request->email,
                            ]);
                            return response()->json(['message' => 'user is updated successfully'], 200);
                        } else {
                            return response()->json(["error" => $validator->messages()], 404);
                        }
                    }
                } else {
                    return response()->json(["error" => $validator->messages()], 404);
                }
            }


        }
    }
}
