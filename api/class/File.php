<?php

class File
{
    const FOPEN_READ                     = 'rb';
    const FOPEN_WRITE_CREATE_DESTRUCTIVE = 'wb';
    const FOPEN_WRITE_CREATE             = 'ab';
    const DIR_WRITE_MODE                 = 0777;

    public static function readFile($file)
    {
        if (!file_exists($file)) {
            return FALSE;
        }

        if (function_exists('file_get_contents')) {
            return file_get_contents($file);
        }

        if (!$fp = @fopen($file, self::FOPEN_READ)) {
            return FALSE;
        }

        flock($fp, LOCK_SH);

        $data = '';
        if (filesize($file) > 0) {
            $data =& fread($fp, filesize($file));
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        return $data;
    }

    public static function writeFile($path, $data, $mode = self::FOPEN_WRITE_CREATE_DESTRUCTIVE)
    {
        if (!$fp = @fopen($path, $mode)) {
            return FALSE;
        }

        flock($fp, LOCK_EX);
        fwrite($fp, $data);
        flock($fp, LOCK_UN);
        fclose($fp);

        return TRUE;
    }

    public static function deleteFiles($path, $del_dir = FALSE, $level = 0)
    {
        // Trim the trailing slash
        $path = rtrim($path, DIRECTORY_SEPARATOR);

        if (!$current_dir = @opendir($path)) {
            return FALSE;
        }

        while (FALSE !== ($filename = @readdir($current_dir))) {
            if ($filename != "." and $filename != "..") {
                if (is_dir($path . DIRECTORY_SEPARATOR . $filename)) {
                    // Ignore empty folders
                    if (substr($filename, 0, 1) != '.') {
                        self::deleteFiles($path . DIRECTORY_SEPARATOR . $filename, $del_dir, $level + 1);
                    }
                } else {
                    @unlink($path . DIRECTORY_SEPARATOR . $filename);
                }
            }
        }
        @closedir($current_dir);

        if ($del_dir == TRUE AND $level > 0) {
            return @rmdir($path);
        }

        return TRUE;
    }

    public static function getDirFileInfo(
        $source_dir, $top_level_only = TRUE,
        $_recursion = FALSE
    ) {
        static $_fileData = [];
        $relative_path = $source_dir;

        if ($fp = @opendir($source_dir)) {
            // reset the array and make sure $source_dir has a trailing slash
            // on the initial call
            if ($_recursion === FALSE) {
                $_fileData = [];

                $source_dir = rtrim(
                        realpath($source_dir),
                        DIRECTORY_SEPARATOR
                    ) . DIRECTORY_SEPARATOR;
            }

            // foreach (scandir($source_dir, 1) as $file)
            // In addition to being PHP5+, scandir() is simply not as fast
            while (FALSE !== ($file = readdir($fp))) {
                if (@is_dir($source_dir . $file)
                    && strncmp($file, '.', 1) !== 0
                    && $top_level_only === FALSE
                ) {
                    self::getDirFileInfo($source_dir . $file . DIRECTORY_SEPARATOR, $top_level_only, TRUE);
                } elseif (strncmp($file, '.', 1) !== 0) {
                    $_fileData[$file] = self::getFileInfo($source_dir . $file);

                    $_fileData[$file]['relative_path'] = $relative_path;
                }
            }

            return $_fileData;
        } else {
            return FALSE;
        }
    }

    public static function getFileInfo($file, $returned_values = ['name', 'server_path', 'size', 'date'])
    {
        if (!file_exists($file)) {
            return FALSE;
        }

        if (is_string($returned_values)) {
            $returned_values = explode(',', $returned_values);
        }

        $fileInfo = [];

        foreach ($returned_values as $key) {
            switch ($key) {
                case 'name':
                    $fileInfo['name'] = substr(strrchr($file, DIRECTORY_SEPARATOR), 1);
                    break;
                case 'server_path':
                    $fileInfo['server_path'] = $file;
                    break;
                case 'size':
                    $fileInfo['size'] = filesize($file);
                    break;
                case 'date':
                    $fileInfo['date'] = filemtime($file);
                    break;
                case 'readable':
                    $fileInfo['readable'] = is_readable($file);
                    break;
                case 'writable':
                    // There are known problems using is_weritable on IIS.  It may not be reliable - consider fileperms()
                    $fileInfo['writable'] = is_writable($file);
                    break;
                case 'executable':
                    $fileInfo['executable'] = is_executable($file);
                    break;
                case 'fileperms':
                    $fileInfo['fileperms'] = fileperms($file);
                    break;
            }
        }

        return $fileInfo;
    }

    public static function isReallyWritable($file)
    {
        // If we're on a Unix server with safe_mode off we call is_writable
        if (DIRECTORY_SEPARATOR == '/' && @ini_get("safe_mode") == FALSE) {
            return is_writable($file);
        }

        // For windows servers and safe_mode "on" installations we'll actually
        // write a file then read it.  Bah...
        if (is_dir($file)) {
            $file = rtrim($file, '/') . '/' . md5(mt_rand(1, 100) . mt_rand(1, 100));

            if (($fp = @fopen($file, self::FOPEN_WRITE_CREATE)) === FALSE) {
                return FALSE;
            }

            fclose($fp);
            @chmod($file, self::DIR_WRITE_MODE);
            @unlink($file);
            return TRUE;
        } elseif (!is_file($file) || ($fp = @fopen($file, self::FOPEN_WRITE_CREATE)) === FALSE) {
            return FALSE;
        }

        fclose($fp);
        return TRUE;
    }
}