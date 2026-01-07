<?php
namespace Abdurrahaman\Installer\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Abdurrahaman\Installer\Repositories\InstallRepository;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class InstallationController extends Controller
{
    protected $installerRipo;

    public function __construct(InstallRepository $installRepository)
    {
        $this->installerRipo = $installRepository;
    }
    public function index()
    {
        try{
            return view('installer::index');
        }catch(Exception $e){
            abort(404);
        }
    }


    public function requirements()
    {
         try{
            $checks = $this->installerRipo->requirements();
            $servers = $checks['server'];
            $folders = $checks['folder'];
            return view('installer::requirements',compact('servers','folders'));
         }catch(Exception $e){
            abort(404);
         }
    }

    public function database()
    {
        try{
            if($this->installerRipo->isDbConnected()){
                return redirect()->route('install.user');
            }else{
                return view('installer::database');
            }
        }catch(Exception $e){
            abort(404);
        }

    }

    public function dbStore(Request $request)
    {
        $request->validate([
            "db_hostname" => "required|string",
            "db_port" => "required|string",
            "db_name" => "required|string",
            "db_user" => "required|string",
            "db_password" => "nullable|string",
        ]);
        try{
            $database_configs = [
                "DB_HOST" => $request->db_hostname,
                "DB_PORT" => (int) $request->db_port,
                "DB_DATABASE" => $request->db_name,
                "DB_USERNAME" => $request->db_user,
                "DB_PASSWORD" => $request->db_password,
            ];
            writeEnv($database_configs);
            if($this->installerRipo->isDbConnected())
            {
                return redirect()->route('install.user');
            }else{
                return redirect()->route('install.database');
            }
        }catch(Exception $e){
            abort(404);
        }
    }

    public function user()
    {
        return view('installer::user');
    }

    public function userStore(Request $request)
    {
        $data = $request->validate([
            "email" => "required|email",
            "password" => "required|min:8|required_with:password_confirmation|same:password_confirmation",
            "password_confirmation" => "required|min:8",
        ]);
        Artisan::call("migrate:fresh");
        $user = User::create([
            "name" => "Super Admin",
            "email" => $data['email'],
            "password" => Hash::make($data['password'])
        ]);
        if($user)
        {
            session('email',$data['email']);
            session('password',$data['password']);
            return redirect()->route('install.success');
        }else{
            return redirect()->route('install.user');
        }
    }


    public function success()
    {
        return view('installer::success');
    }

}
