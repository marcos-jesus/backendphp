<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CreateAccount extends Controller
{
    public function createAccount(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        try {
            $user = User::create($validatedData);
            return response()->json(['message' => 'Conta criada com sucesso!', $user], 201);
        } catch (\Exception $e) {

            return response()->json(['message' => 'Falha ao criar a conta!'], 400);
        }

    }
}
