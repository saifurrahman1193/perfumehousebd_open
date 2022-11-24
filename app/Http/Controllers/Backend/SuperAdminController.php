<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Roles;
use Validator;
use Carbon\Carbon;
use Config;

class SuperAdminController extends Controller
{

    // ======================== users ==============================
    // ======================== users ==============================
    public function getUsers()
    {
        $users = DB::table('users')->get();
        $response = ["status" => "Success", "data"=> $users];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getUsersView()
    {
        $users = DB::table('users_view')->get();
        $response = ["status" => "Success", "data"=> $users];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getUsersWithRoles()
    {
        $users_with_roles = DB::table('user_roles_view')->get();
        $response = ["status" => "Success", "data"=> $users_with_roles];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getUser($userId)
    {
        $user = DB::table('users')->where('id', $userId)->select('id', 'name', 'email')->first();
        $response = ["status" => "Success", "data"=> $user];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password'=> 'required|min:6',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        // $inputs = $request->except(['token', '_method']);
        // User::create($inputs);
        $userId = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ])->id;

        if($request->has('roleIds')  )
        {
            DB::table('userroles')->where('userId', $request->id)->delete();

            foreach ($request->roleIds as $roleId)
            {
                DB::table('userroles')->insert([
                    'userId' => $userId,
                    'roleId' => $roleId,
                ]);
            }
        }

        cacheRemove();

        $response = ["status" => "Success", "data" => "User successfully saved !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:users,email,'.$request->id.',id',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        // User::where('id', $request->id)->update($request->except(['token', '_method', 'id', 'password',  'roleIds']));
        User::find($request->id)->update($request->all());

        if($request->has('password') and $request->password != null )
        {
            User::where('id', $request->id)->update([ 'password' => bcrypt($request->password) ]);
        }

        if($request->has('roleIds')  )
        {
            // dd($request->roleIds);
            DB::table('userroles')->where('userId', $request->id)->delete();

            foreach ($request->roleIds as $roleId)
            {
                DB::table('userroles')->insert([
                    'userId' => $request->id,
                    'roleId' => $roleId,
                ]);
            }
        }
        cacheRemove();

        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteUser($userId)
    {
        DB::table('users')->where('id', $userId)->delete();

        cacheRemove();
        $response = ["status" => "Success", "data" => "User Successfully Deleted !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    // ======================== users ==============================
    // ======================== users ==============================




    // ======================== roles ==============================
    // ======================== roles ==============================
    public function getRoles()
    {
        $roles = DB::table('roles')->get();
        $response = ["status" => "Success", "data"=> $roles];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getRole($roleId)
    {
        $role = DB::table('roles')->where('roleId', $roleId)->first();
        $response = ["status" => "Success", "data"=> $role];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'roleName' => 'required|string|unique:roles',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->except(['token', '_method']);
        Roles::create($inputs);

        cacheRemove();
        $response = ["status" => "Success", "data" => "Role successfully saved !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'roleName' => 'required|string|unique:roles,roleName,'.$request->roleId.',roleId',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        // Roles::where('roleId', $request->roleId)->update($request->except(['token', '_method', 'roleId']));
        Roles::find( $request->roleId)->update($request->all());

        if($request->has('moduleIds')  )
        {
            DB::table('rolemodules')->where('roleId', $request->roleId)->delete();

            foreach ($request->moduleIds as $moduleId)
            {
                DB::table('rolemodules')->insert([
                    'roleId' => $request->roleId,
                    'moduleId' => $moduleId,
                ]);
            }
        }


        cacheRemove();
        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteRole($roleId)
    {
        DB::table('roles')->where('roleId', $roleId)->delete();

        cacheRemove();
        $response = ["status" => "Success", "data" => "Role Successfully Deleted !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }




    // ======================== roles ==============================
    // ======================== roles ==============================


    // ======================== modules ==============================
    // ======================== modules ==============================
    public function getModules()
    {
        $modules = DB::table('modules')->get();
        $response = ["status" => "Success", "data"=> $modules];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getModule($moduleId)
    {
        $module = DB::table('modules')->where('moduleId', $moduleId)->get();

        $response = ["status" => "Success", "data"=> $module];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getRoleModules()
    {
        $rolemodules = DB::table('rolemodules_view')->get();
        $response = ["status" => "Success", "data"=> $rolemodules];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }
    // ======================== modules ==============================
    // ======================== modules ==============================


    // ======================== backup ==============================
    // ======================== backup ==============================
    public function storageBackup()
    {

        // php.ini has enabled extension called ext-zip.
        $zip_file = 'storage_backup.zip';

            $zip = new \ZipArchive();
            $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            $path = 'uploads' ;

            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            foreach ($files as $name => $file)
            {
                // We're skipping all subfolders
                if (!$file->isDir()) {
                    $filePath     = $file->getRealPath();

                    // extracting filename with substr/strlen
                    $relativePath = 'uploads/' . substr($filePath, strlen($path) + 1);

                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();



        return response()->download($zip_file);
        // $response = ["status" => "Success"];
    }

    public function storageBackupDelete()
    {
        try {
            \unlink('storage_backup.zip');
        } catch (\Throwable $th) {
        }
    }

    public function serverDBBackup()
    {
        try {
            $database = config('app.db');

            // dd(config('app.db'));
            $user = config('app.dbuser');
            $pass = config('app.dbpass');
            $host = config('app.dbhost');
            $dir = 'server_db_backup.sql';

            try {
                unlink($dir);
            } catch (\Throwable $th) {
            }

            // echo "<h3>Backing up database to `<code>{$dir}</code>`</h3>";
            // mysqldump -u [user name] –p [password] [options] [database_name] [tablename] > [dumpfilename.sql]
            // --add-drop-database --databases
            exec("mysqldump  --user={$user} --password={$pass} --host={$host} --events --routines --triggers  {$database}  --result-file={$dir} 2>&1", $output);

            $tableViewsCounts = DB::select('SELECT count(TABLE_NAME) AS TOTALNUMBEROFTABLES FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ?', [$database]);
            $tableViewsCounts = $tableViewsCounts[0]->TOTALNUMBEROFTABLES;

            $viewsCounts = DB::select('SELECT count(TABLE_NAME) AS TOTALNUMBEROFVIEWS FROM INFORMATION_SCHEMA.TABLES WHERE  TABLE_TYPE LIKE "VIEW" AND TABLE_SCHEMA = ?', [$database]);
            $viewsCounts = $viewsCounts[0]->TOTALNUMBEROFVIEWS;

            $tablesCount = $tableViewsCounts-$viewsCounts;


            $proceduresCounts = DB::select('SELECT count(TYPE) AS proceduresCounts FROM mysql.proc WHERE  TYPE="PROCEDURE" AND db = ?', [$database]);
            $proceduresCounts = $proceduresCounts[0]->proceduresCounts;

            $functionsCounts = DB::select('SELECT count(TYPE) AS functionsCounts FROM mysql.proc WHERE  TYPE="FUNCTION" AND db = ?', [$database]);
            $functionsCounts = $functionsCounts[0]->functionsCounts;


            $init_command = PHP_EOL.'-- '.$database.' Database Backup Generated time = '.YmdTodmYPmDay(\Carbon\Carbon::now()). PHP_EOL.PHP_EOL.PHP_EOL.
                            '-- =============Objects Counting================= '.PHP_EOL.PHP_EOL.
                            '-- Total Tables + Views = '.$tableViewsCounts.PHP_EOL.
                            '-- Total Tables = '.$tablesCount.PHP_EOL.
                            '-- Total Views = '.$viewsCounts.PHP_EOL.PHP_EOL.
                            '-- Total Procedures = '.$proceduresCounts.PHP_EOL.
                            '-- Total Functions = '.$functionsCounts.PHP_EOL.
                            PHP_EOL.PHP_EOL.
                            'SET FOREIGN_KEY_CHECKS=0; '. PHP_EOL.
                            'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";'. PHP_EOL.
                            'START TRANSACTION;'. PHP_EOL.
                            'SET time_zone = "+06:00";'.PHP_EOL.
                            'drop database if exists '.$database.';'. PHP_EOL.
                            'CREATE DATABASE IF NOT EXISTS '.$database.' DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;'. PHP_EOL.
                            'use '.$database.';'.PHP_EOL;

            $data = file_get_contents($dir);

            $append_command = PHP_EOL.'SET FOREIGN_KEY_CHECKS=1;'.PHP_EOL.'COMMIT;'.PHP_EOL;
            // dd($data);
            file_put_contents ( $dir , $init_command.$data.$append_command);


            return response()->download($dir);
        } catch (\Throwable $th) {
        }
    }

    public function dbBackupDelete()
    {
        try {
            \unlink('server_db_backup.sql');
        } catch (\Throwable $th) {
        }
    }


    // ======================== backup ==============================
    // ======================== backup ==============================



}
