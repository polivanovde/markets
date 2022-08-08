<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use http\Env\Response;
use Illuminate\Contracts\View\View;

class UserController extends Controller
{
    /**
     * Показать профиль конкретного пользователя.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        $user = User::find($id);
        return $user;
    }
}
