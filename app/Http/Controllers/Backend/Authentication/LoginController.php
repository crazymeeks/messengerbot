<?php

namespace App\Http\Controllers\Backend\Authentication;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    
    const BACKEND_LOGGED_IN_SESSION_NAME = 'backend_user';

    public function __invoke()
    {
        return view('backend.pages.authentication.index');
    }

    public function postLogin(Request $request)
    {


        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = new AdminUser();
        $result = $user->findOne(['email' => $request->username]);

        if ($result) {
            if ($this->validatePassword($request->password, $result->password)) {
                $request->session()->put(self::BACKEND_LOGGED_IN_SESSION_NAME, $result);
                return redirect()->route('admin.home');
            }

            return redirect()->back()->with('error', 'Invalid account');

        }
        
        unset($result);

        $result = $user->findOne(['username' => $request->username]);

        if ($result) {
            if ($this->validatePassword($request->password, $result->password)) {
                $request->session()->put(self::BACKEND_LOGGED_IN_SESSION_NAME, $result);
                return redirect()->route('admin.home');
            }   
        }

        return redirect()->back()->with('error', 'Invalid account');
    }

    public function postLogout(Request $request)
    {

        $request->session()->forget(self::BACKEND_LOGGED_IN_SESSION_NAME);

        return redirect()->route('admin.login');

    }

    /**
     * Validate password
     *
     * @param string $password
     * @param string $hashedPassword
     * 
     * @return bool
     */
    private function validatePassword(string $password, string $hashedPassword)
    {
        return Hash::check($password, $hashedPassword);
    }
}
