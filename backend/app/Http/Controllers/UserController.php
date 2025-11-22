<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getProfile($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() != $id && !auth()->user()->hasRole(['chef', 'resp'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['user' => $user]);
    }

    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() != $id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'telephone' => 'sometimes|string|max:20',
            'current_password' => 'required_with:password',
            'password' => 'sometimes|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->has('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'Current password is incorrect'], 422);
            }
            $user->password = Hash::make($request->password);
        }

        $user->update($request->except(['password', 'current_password', 'password_confirmation']));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    public function deleteAccount($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() != $id && !auth()->user()->hasRole('chef')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Account deleted successfully']);
    }
}
