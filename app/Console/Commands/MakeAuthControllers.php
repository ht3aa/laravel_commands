<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeAuthControllers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "make:authcontrollers";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "generate all auth controllers for auth.routes()";

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->call("make:controller", [
            "name" => "Auth\ResetPasswordController",
        ]);
        $this->call("make:controller", [
            "name" => "Auth\ForgotPasswordController",
        ]);
        $this->call("make:controller", [
            "name" => "Auth\LoginController",
        ]);

        $this->call("make:controller", [
            "name" => "Auth\RegisterController",
        ]);
        $paths = [
            app_path("Http/Controllers/Auth/ForgotPasswordController.php"),
            app_path("Http/Controllers/Auth/LoginController.php"),
            app_path("Http/Controllers/Auth/ResetPasswordController.php"),
            app_path("Http/Controllers/Auth/RegisterController.php"),
        ];

        dd($paths);
        $codes = [
            '<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail()
    {
    }

}
    
        ',
            '<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view("forms.login");
    }
    
    public function login(Request $request)
    {
        // validate method will run the rules on the data
        // and if it false it will redirect back to the form
        validator($request->all(), [
            "email" => ["required", "email"],
        ])->validate();

        if (
            Auth::attempt(
                [
                    "email" => $request->input("email"),
                    "password" => $request->input("password"),
                ],
                $request->filled("remember")
            )
        ) {
            return redirect("/");
        }
        return redirect()->back();
    }

    public function logout()
    {
        Auth::logout();
        return redirect("/");
    }
}
    
        ',
            '<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function showResetForm()
    {
    }
    
    public function reset()
    {
    }
}
    
        ',
            '<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view("forms.register");
    }
    
    public function register(Request $request)
    {
        // validate method will run the rules on the data
        // and if it false it will redirect back to the form
        validator($request->all(), [
            "name" => ["required"],
            "email" => ["required", "email"],
        ])->validate();

        $user = User::create([
            "name" => $request->input("name"),
            "email" => $request->input("email"),
            "password" => Hash::make($request->input("password")),
        ]);
        Auth::login($user);

        return redirect("/");
    }
}
    
        ',
        ];
        for ($i = 0; $i < 4; $i++) {
            File::put($paths[$i], $codes[$i]);
        }
    }
}
