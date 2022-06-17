<?php

set_time_limit(0);
date_default_timezone_set("PRC");
$pwd = "/Users/xiao/Downloads/gyy";


// $out_path = "/Users/xiao/Downloads/gyy/out";

$out_path = realpath(__DIR__ . "/out");

$data_path = realpath('./data/');

$handler = opendir($data_path);  

while (($filename = readdir($handler)) !== false) 
{
    // 务必使用!==，防止目录下出现类似文件名“0”等情况  
    if ($filename !== "." && $filename !== ".." && $filename !== ".DS_Store") 
    {  
            $files[] = $filename ;  
     } 
}  

closedir($handler);  

// 打印所有文件名  
foreach ($files as  $value) {  
    $out_put_dir_segment = substr($value, 0, strrpos($value, '.'));
    $des_path = $out_path . "/" . $out_put_dir_segment;
    // dd($des_path);
    if (!is_dir($des_path)) {
        // $arr = getGenArray($out_put_dir_segment, $files);
        $file_1 = $out_put_dir_segment . ".fa";
        // $file_2 = $arr[1];
        // mkdir($des_path);
        //$shell = "docker run -v $pwd:/data staphb/shovill:latest shovill --outdir /data/out/$out_put_dir_segment --force --R1 /data/Rawdata/$file_1 --R2 /data/Rawdata/$file_2";
        //没有文件不一定没有在执行
        if (hasLock($out_put_dir_segment)) {
            logger($out_put_dir_segment . "is process by other program .. continue");
            continue;
        }
        lock($out_put_dir_segment);
        $shell = 'apptainer exec -B $PWD CrisprCasFinder.simg perl /usr/local/CRISPRCasFinder/CRISPRCasFinder.pl -so /usr/local/CRISPRCasFinder/sel392v2.so -cf /usr/local/CRISPRCasFinder/CasFinder-2.0.3 -drpt /usr/local/CRISPRCasFinder/supplementary_files/repeatDirection.tsv -rpts /usr/local/CRISPRCasFinder/supplementary_files/Repeat_List.csv -cas -def G -out ' . $out_path .'/'.$out_put_dir_segment. ' -in '.'./data/'.$file_1;
        // dd($shell);
        logger($shell);
        echo $shell . PHP_EOL;
        shell_exec($shell);
        deleteLock($out_put_dir_segment);
    }

}

function hasLock($key) {
    $key_path = realpath(__DIR__ . "/" . $key . ".lock");
    if ($key_path === false) {
        return false;
    }

    return true;
}

function lock($key) {
    $res = fopen($key . ".lock", "w");
    if ($res == false) {
        return false;
    }

    return true;
}

function deleteLock($key) {
    $key_path = realpath(__DIR__ . "/" . $key . ".lock");
    if ($key_path) {
        unlink($key_path);
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
