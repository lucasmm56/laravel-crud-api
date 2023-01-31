<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PDOException;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return $users;
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        try {

            $user = $request->validate([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required|confirmed|min:6',
            ]);

            $user['password'] = Hash::make($user['password']);

            User::create($user);
            return [
                'status' => 1,
                'msg' => 'Usuário cadastrado com sucesso'
            ];
        } catch (PDOException $exc) {
            return [
                'status' => 0,
                'msg' => 'Este email já está em uso'
            ];
        } catch (ValidationException $exc) {
            return [
                'status' => 0,
                'msg' => 'Senhas inválidas'
            ];
        } catch (Exception $exc) {
            return [
                'status' => 0,
                'msg' => 'Dados não cadastrados, tente novamente ' . $exc
            ];
        }
    }

    public function show(User $user)
    {
        return $user;
    }

    public function edit(User $user)
    {
    }

    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required|confirmed|min:6',
            ]);

            $request['password'] = Hash::make($user['password']);

            $user->update($request->all());

            return [
                'status' => 1,
                'msg' => 'Usuário editado com sucesso',
                $user
            ];
        } catch (PDOException $exc) {
            return [
                'status' => 0,
                'msg' => 'Este email já está em uso'
            ];
        } catch (ValidationException $exc) {
            return [
                'status' => 0,
                'msg' => 'Senhas inválidas'
            ];
        } catch (Exception $exc) {
            return [
                'status' => 0,
                'msg' => 'Dados não cadastrados, tente novamente ' . $exc
            ];
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        return [
            'status' => 1,
            'msg' => 'Usuário deletado com sucesso'
        ];
    }
}
