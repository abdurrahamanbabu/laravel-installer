<?php
namespace Abdurrahaman\Installer\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;

class InstallRepository {

    public function isDbConnected()
    {
        try{
            $db = DB::connection()->getPdo();
            if(DB::connection()->getDatabaseName())
            {
                return true;
            }else{
                return false;
            }
        }catch(Exception $e){
            return false;
        }
    }
    public function check($boolean, $message, $help = '', $fatal = false)
    {
        if ($boolean) {
            return array('type' => 'success', 'message' => $message);
        } else {
            return array('type' => 'error', 'message' => $help);
        }
    }

    public function myVersionCompair($ver1, $ver2, $operator = null)
    {
        $p = '#(\.0+)+($|-)#';
        $ver1 = preg_replace($p, '', $ver1);
        $ver2 = preg_replace($p, '', $ver2);
        return isset($operator) ?
            version_compare($ver1, $ver2, $operator) :
            version_compare($ver1, $ver2);
    }

    public function requirements()
    {

        $post_max_size = config('spondon.post_max_size', '64M');
        $upload_max_filesize = config('spondon.upload_max_filesize', '32M');
        $memory_limit = config('spondon.memory_limit', '128M');
        $max_execution_time = config('spondon.max_execution_time', 300);
        if (is_numeric($max_execution_time)) {
            $max_execution_time = (int) $max_execution_time;
        }
        $server[] = $this->check((dirname($_SERVER['REQUEST_URI']) != '/' && str_replace('\\', '/', dirname($_SERVER['REQUEST_URI'])) != '/'), 'Installation directory is valid.', 'Please use root directory or point your sub directory to domain/subdomain to install.', true);
        $server[] = $this->check($this->myVersionCompair(phpversion(), config('spondonit.php_version', '7.2.0'), '>='), sprintf('Min PHP version ' . config('spondonit.php_version', '7.2.0') . ' (%s)', 'Current Version ' . phpversion()), 'Current Version ' . phpversion(), true);
        $server[] = $this->check(extension_loaded('fileinfo'), 'Fileinfo PHP extension enabled.', 'Install and enable Fileinfo extension.', true);
        $server[] = $this->check(extension_loaded('ctype'), 'Ctype PHP extension enabled.', 'Install and enable Ctype extension.', true);
        $server[] = $this->check(extension_loaded('json'), 'JSON PHP extension enabled.', 'Install and enable JSON extension.', true);
        $server[] = $this->check(extension_loaded('openssl'), 'OpenSSL PHP extension enabled.', 'Install and enable OpenSSL extension.', true);
        $server[] = $this->check(extension_loaded('tokenizer'), 'Tokenizer PHP extension enabled.', 'Install and enable Tokenizer extension.', true);
        $server[] = $this->check(extension_loaded('mbstring'), 'Mbstring PHP extension enabled.', 'Install and enable Mbstring extension.', true);
        $server[] = $this->check(extension_loaded('zip'), 'Zip archive PHP extension enabled.', 'Install and enable Zip archive extension.', true);
        $server[] = $this->check(class_exists('finfo'), 'Finfo is installed.', 'Install Finfo (mandatory for read or write file).', true);
        $server[] = $this->check(class_exists('PDO'), 'PDO is installed.', 'Install PDO (mandatory for Eloquent).', true);
        $server[] = $this->check(extension_loaded('curl'), 'CURL is installed.', 'Install and enable CURL.', true);
        $server[] = $this->check(ini_get('allow_url_fopen'), 'allow_url_fopen is on.', 'Turn on allow_url_fopen.', true);
        $server[] = $this->check(ini_get('allow_url_fopen'), 'allow_url_fopen is on.', 'Turn on allow_url_fopen.', true);
        $server[] = $this->check($this->compareInt($this->getIniSize('post_max_size'), $this->convertToInt($post_max_size)),  'Current Post Max Size ' . ini_get('post_max_size'), sprintf('Min Post Max Size ' . $post_max_size . ' (%s)', 'Current size ' . ini_get('post_max_size')),true);
        $server[] = $this->check($this->compareInt($this->getIniSize('memory_limit'), $this->convertToInt($memory_limit)), 'Current Memory Limit ' . ini_get('memory_limit'), sprintf('Min Memory Limit ' . $memory_limit . ' (%s)', 'Current limit ' . ini_get('memory_limit')),true);
        $server[] = $this->check($this->compareInt($this->getIniSize('upload_max_filesize'), $this->convertToInt($upload_max_filesize)), 'Current Upload Max File Size ' . ini_get('upload_max_filesize'), sprintf('Min Upload Max File Size ' . $upload_max_filesize . ' (%s)', 'Current size ' . ini_get('upload_max_filesize')),true);
        $server[] = $this->check($this->compareInt($this->getIniSize('max_execution_time'), $max_execution_time),  'Current Max Execution time ' . ini_get('max_execution_time'), sprintf('Min Max Excecution time ' . $max_execution_time . ' (%s)', 'Current time ' . ini_get('max_execution_time')),true);

        $folder[] = $this->check(is_writable(base_path('/.env')), 'File .env is writable', 'File .env is not writable', true);
        $folder[] = $this->check(is_writable(base_path("/storage/app")), 'Folder /storage/app is writable', 'Folder /storage/app is not writable', true);
        $folder[] = $this->check(is_writable(base_path("/storage/framework")), 'Folder /storage/framework is writable', 'Folder /storage/framework is not writable', true);
        $folder[] = $this->check(is_writable(base_path("/storage/logs")), 'Folder /storage/logs is writable', 'Folder /storage/logs is not writable', true);
        $folder[] = $this->check(is_writable(base_path("/bootstrap/cache")), 'Folder /bootstrap/cache is writable', 'Folder /bootstrap/cache is not writable', true);
        $folder[] = $this->check(is_writable(base_path("/modules_statuses.json")), 'File modules_statuses.json is writable', 'File modules_statuses.json is not writable', true);



        return ['server' => $server, 'folder' => $folder];
    }

    protected function getIniSize($var)
    {
        if (is_numeric($size = ini_get($var))) {
            return (int) $size;
        }

        return $this->convertToInt($size);
    }

    protected function compareInt($int1, $int2){
        if($int1 < 0){
            return true;
        }
        return $int1 >= $int2;
    }

    protected function convertToInt($size){
        $metric = strtoupper(substr($size, -1));
        $size = (int) $size;

        return match ($metric) {
            'K' => $size * 1024,
            'M' => $size * 1048576,
            'G' => $size * 1073741824,
            default => $size,
        };
    }

    protected function getMaxUploadFileSize()
    {
        if (is_numeric($postMaxSize = ini_get('upload_max_filesize'))) {
            return (int) $postMaxSize;
        }

        $metric = strtoupper(substr($postMaxSize, -1));
        $postMaxSize = (int) $postMaxSize;

        return match ($metric) {
            'K' => $postMaxSize * 1024,
            'M' => $postMaxSize * 1048576,
            'G' => $postMaxSize * 1073741824,
            default => $postMaxSize,
        };
    }

    protected function getMemoryLimit()
    {
        if (is_numeric($postMaxSize = ini_get('memory_limit'))) {
            return (int) $postMaxSize;
        }

        $metric = strtoupper(substr($postMaxSize, -1));
        $postMaxSize = (int) $postMaxSize;

        return match ($metric) {
            'K' => $postMaxSize * 1024,
            'M' => $postMaxSize * 1048576,
            'G' => $postMaxSize * 1073741824,
            default => $postMaxSize,
        };
    }
}
