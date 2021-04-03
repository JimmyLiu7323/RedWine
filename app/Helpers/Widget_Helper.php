<?php
namespace App\Helpers;
use Illuminate\Support\Facades\DB;
class Widget_Helper{
    public function __construct(){

    }
    public static function createID()
    {
        $id="";
        $enstr="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $str_s = str_split($enstr);
        for($i=1;$i<=4;$i++)
        {
            $rnd = rand(0,51);
            $id .=$str_s[$rnd];
        }

        $id .= time();

        for($i=1;$i<=4;$i++)
        {
            $rnd = rand(0,51);
            $id .=$str_s[$rnd];
        }
        return $id;
    }

    public static function inject_check($sql_str){
        if(preg_match('/select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|into|load_file|outfile/',$sql_str)){
            return true;
        } // 進行過濾 
        return false;
    }
    public static function xss_check($sql_str){
        if(preg_match('/<script|<\/script/',$sql_str)){
            return true;
        } // 進行過濾 
        return false;   
    }

    public static function verifyDate($date,$strict=true)
    {
        $dateTime=\DateTime::createFromFormat('Y-m-d H:i',$date);
        if($strict){
            $errors=\DateTime::getLastErrors();
            if(!empty($errors['warning_count'])){
                return false;
            }
        }
        return $dateTime!==false;
    }

    public static function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $str .= "\n"; // In case the searched variable is in the last line without \n
                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}={$envValue}\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                }

            }
        }

        $str = substr($str, 0, -1);
        if (!file_put_contents($envFile, $str)) return false;
        return true;
    }
}
?>