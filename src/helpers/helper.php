<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Abdurrahaman\Installer\Repositories\InstallRepository;

if (!function_exists('writeEnv')) {
    function writeEnv($data = array())
    {
        foreach ($data as $key => $value) {
            if (env($key) === $value) {
                unset($data[$key]);
            }
        }

        if (!count($data)) {
            return false;
        }

        // write only if there is change in content

        $env = file_get_contents(base_path() . '/.env');
        $env = explode("\n", $env);
        foreach ((array) $data as $key => $value) {
            foreach ($env as $env_key => $env_value) {
                $entry = explode("=", $env_value, 2);
                if ($entry[0] === $key) {
                    $env[$env_key] = $key . "=" . (is_string($value) ? '"' . $value . '"' : $value);
                } else {
                    $env[$env_key] = $env_value;
                }
            }
        }
        $env = implode("\n", $env);
        file_put_contents(base_path() . '/.env', $env);
        return true;
    }
}

if (!function_exists('isEnableConnection')) {
    function isEnableConnection()
    {
        $connected = @fsockopen("www.google.com", 80);
        if ($connected) {
            fclose($connected);
            return true;
        }

        return false;
    }
}

if(!function_exists('extentionLoaded'))
{
    function extentionLoaded($extention) : bool
    {
        return extension_loaded($extention) ? true:false;
    }
}

if (!function_exists('curlInit')) {

    function curlInit($url, $postData = array())
    {
        $url  = preg_replace("/\r|\n/", "", $url);
        try {
            $response = Http::timeout(3)->acceptJson()->get($url);
            if ($response->successful()) {
                return $response->json();
            }

            return [];
        } catch (\Exception $e) {
            Session::flash('error',$e->getMessage());
        }
        return [
            'goto' => $url . '&from=browser'
        ];
    }
}

if (!function_exists('arrayGetValue')) {

    function arrayGetValue($params, $key, $default = null)
    {
        return (isset($params[$key]) && $params[$key]) ? $params[$key] : $default;
    }
}

if (!function_exists('byteSize')) {
    function byteSize($size, $precision = 2)
    {
        $size = is_numeric($size) ? $size : 0;

        $base = log($size, 1024);
        $suffixes = array('Bytes', 'KB', 'MB', 'GB', 'TB');

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
}


if(!function_exists('moduleVerify'))
{
    function moduleVerify($file, $type = null)
    {

        $filename = pathinfo($file, PATHINFO_FILENAME);

        $repo = App::make(InstallRepository::class);

        $params = [
            'name' => $filename,
            'item_id' => config('app.item'),
            'envatouser' => Storage::exists('.account_email') ? Storage::get('.account_email') : null,
            'tariq' => true,
        ];

        if ($type) {
            $params += [
                $type => true,
            ];
        }

        return $repo->installModule($params);
    }

}


if(!function_exists('isModuleVerified'))
{
    function isModuleVerified($plugin)
    {

        $license = file_get_contents(base_path('Modules/'.$plugin->name.'/License'));
        if($plugin->purchase_code == base64_decode($license))
        {
            return true;
        }
        return false;
    }
}

if(!function_exists(function: 'isModuleEnable'))
{
    function isModuleEnable($module_name)
    {

       try{
            if(file_exists(base_path('modules_statuses.json')))
            {
                $hasModule = app('plugins')->where('name',$module_name)->first();
                $service_provider = "Modules/{$module_name}/App/Providers/{$module_name}ServiceProvider.php";
                $plugin_list = json_decode(file_get_contents(base_path('modules_statuses.json')));                
                if(!empty($hasModule) && file_exists(base_path($service_provider)) == true && $plugin_list->$module_name == true)
                {
                    return true;
                }
            }            
            return false;
       }catch(\Exception $e){
            return false;
       }
    }
}