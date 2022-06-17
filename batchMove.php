<?php

set_time_limit(0);

$pwd = "/Users/xiao/Downloads/gyy";

$out_path = "/Users/xiao/Downloads/gyy/out";
$des_path = "/Users/xiao/Downloads/gyy/out/collection";

$handler = opendir($out_path);  

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
    $fa_path = $out_path . "/" . $value . "/contigs.fa";
    $cp_fa_path = $des_path . "/" . $value . ".fa";
    $res = copy($fa_path, $cp_fa_path);
    log($res);
}

function logger($logthis)
{
    echo $logthis . PHP_EOL;
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