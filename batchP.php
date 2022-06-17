<?php

set_time_limit(0);

$pwd = "/Users/xiao/Downloads/gyy";

$out_path = "/Users/xiao/Downloads/gyy/out/prokka-output";

$data_path = "/Users/xiao/Downloads/gyy/out/collection";

$handler = opendir($data_path);  

while (($filename = readdir($handler)) !== false) 
{
    // 务必使用!==，防止目录下出现类似文件名“0”等情况  
    if ($filename !== "." && $filename !== "..") 
    {  
            $files[] = $filename ;  
     } 
}  

closedir($handler);  

// 打印所有文件名  
foreach ($files as  $value) {  
    $out_put_dir_segment = substr($value, 0, strpos($value, '.'));
    $des_path = $out_path . "/" . $out_put_dir_segment;
    if (!is_dir($des_path)) {
        // $arr = getGenArray($out_put_dir_segment, $files);
        // $file_1 = $arr[0];
        // $file_2 = $arr[1];
        
        // $shell = "docker run -v $pwd:/data staphb/shovill:latest shovill --outdir /data/out/$out_put_dir_segment --force --R1 /data/Rawdata/$file_1 --R2 /data/Rawdata/$file_2";
        $shell = "docker run -v /Users/xiao/Downloads/gyy/out:/data staphb/prokka:latest prokka --compliant /data/collection/$value --outdir /data/prokka-output/$out_put_dir_segment";
        logger($shell);
        echo $shell . PHP_EOL;
        shell_exec($shell);
    }

}

function logger($logthis)
{
    file_put_contents('logfile.log', date("Y-m-d H:i:s"). " " . $logthis. "\r\n", FILE_APPEND | LOCK_EX);
}

function dd($msg)
{
    die(var_export($msg));
    exit;
}

function getGenArray($seg, $files)
{
    $ret = [];
    foreach ($files as  $value) {  
        $out_put_dir_segment = substr($value, 0, strpos($value, '_'));
        if ($seg == $out_put_dir_segment) {
            $ret[] = $value;
        }
    }

    sort($ret);

    return $ret;
}