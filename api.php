<?php


$ml = $_GET["ml"];


switch ($ml) {
    case 'initkm':
        print_r(initkm());
        break;
    case 'initimei':
        $user = $_GET["user"];
        print_r(initimei($user));
        break;
    case 'deletewj':
        print_r(deletewj($_GET["n"]));
        break;
    case 'yanz':
        print_r(yanz());
        break;
    case 'bao':
        $rc4 = $_GET["rc4"];
        print_r(bao($rc4,$_GET["token"]));
        break;
    case 'getgg':
        $token = $_GET["token"];
        print_r(getgg($token));
        break;
    case 'plsc':
        $s = $_GET["s"];
        print_r(plsc($s));
        break;
    case 'num':
        print_r(num());
        break;   
    case 'gg':
        print_r(gg());
        break;  
    case 'tj':
        $gg = $_GET["gg"];
        $bb = $_GET["bb"];
        $lj = $_GET["lj"];
        $gxnr = $_GET["gxnr"];
        print_r(tj($gg,$bb,$lj,$gxnr));
        break; 
    case 'cx':
        $imei = $_GET["imei"];
        $token = $_GET["token"];
        $a = $_GET["a"];
        print_r(cx($imei,$token,$a));
        break;
    case 'uk':
        $km = $_GET["km"];
        $imei = $_GET["imei"];
        $token = $_GET["token"];
        $a = $_GET["a"];
        print_r(uk($imei,$km,$token,$a));
        break;
    case 'login':
        $user = $_GET["user"];
        $pass = $_GET["pass"];
        print_r(login($user,$pass));
        break; 
    case 'addkm':
        $n = $_GET["n"];
        $s = $_GET["s"];
        print_r(addkm($n,$s));
        break;
    case 'xgmm':
        $y = $_GET["y"];
        $s = $_GET["s"];
        print_r(xgmm($y,$s));
        break;
    case 'deletekm':
        $km=$_GET["km"];
        print_r(deletekm($km));
        break;
    case 'up':
        print_r(up());
        break;
    default:
        print_r(json_encode(["code"=>-99,"msg"=>"未知命令","text"=>"未知命令"],JSON_UNESCAPED_UNICODE));
        break;
}




function gg(){
    $y = json_decode(yanz(),TRUE);
    $code = $y["code"];
    if($code==0){
        $user = $y["user"];
        include("config.php");
        $conn = new mysqli($servername, $username, $password, $dbname);
        $sql = "select * from admin where user = '$user'";
        $result = $conn->query($sql);  
        $row = $result->fetch_assoc();
        $gg = $row["gg"];
        $bb = $row["bb"];
        $lj = $row["lj"];
        $gxnr = $row["gxnr"];
    }else{
        $code = -999;
        $text = "未知错误";
    }
       $fh = [
        "code"=>$code,
        "gg"=>$gg,
        "bb"=>$bb,
        "lj"=>$lj,
        "gxnr"=>$gxnr
        ];
    return json_encode($fh,JSON_UNESCAPED_UNICODE); 
}





function yanz(){
    $user = $_COOKIE["adminuser"];
    $passmd5 = $_COOKIE["adminpass"];
    
    if(!$user){
        $code = -1;
    }else if(!$passmd5){
        $code = -2;
    }else{
        include("config.php");
        $conn = new mysqli($servername, $username, $password, $dbname);
        $sql = "select * from admin where user = '$user'";
        $result = $conn->query($sql);   
        if($result->num_rows>0){
            $row = $result->fetch_assoc();
            $pass = $row["pass"];
            if($passmd5 == md5($pass)){
                $code = 0;
                $token = $row["token"];
                $rc4 = $row["rc4"];
                $quota = $row["quota"];
            }else{
                $code = -1;
            }
            
            
        }else{
            $code = 3;
        }
        $conn->close();
    }
    $fh = [
        "code"=>$code,
        "user"=>$user,
        "token"=>$token,
        "rc4"=>$rc4,
        ];
    return json_encode($fh);
}






function login($user , $pass){
    include("config.php");
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "select * from admin where user = '$user' and pass = '$pass'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
    setcookie("adminuser", $user, time()+36000);
    setcookie("adminpass", md5($pass), time()+36000);
    $code = 0 ;
    $text = "登陆成功";
    }else{
    $code =-1;
    $text = "账号或密码错误";
    
    }
        $fh = array(
            "code" => $code,
            "text" => $text
        );
    return json_encode($fh);
}


function up(){
    if($_FILES){
        $filename = $_FILES["avatar"]["name"];
        $filetype = $_FILES["avatar"]["type"];
        $filesize = $_FILES["avatar"]["size"];
        $tmp_name = $_FILES["avatar"]["tmp_name"];
        
        if($filetype != "application/octet-stream"){
            $code = -2;
            $text = "只能上传lua文件哦";
        }else if($filesize > 5242880){
            $code = -3;
            $text = "只能上传小于5m的文件";
        }else{
                $y = json_decode(yanz(),TRUE);
                $code = $y["code"];
                if($code==0){
                if(!is_dir("upload")){
                mkdir ("upload",0777);
                }
                move_uploaded_file($tmp_name,"upload/" .$filename);
                $code = 0;
                $text = "上传成功";
                }else{
                    $code = -5;
                    $text = "系统错误";
                }
        }
    }else{
        $code = -1;
        $text = "未收到文件";
    }
    $fh = [
    "code"=>$code,
    "text"=>$text,
    ];
    return json_encode($fh); 
}

function deletewj($n){
    $y = json_decode(yanz(),TRUE);
    $code = $y["code"];
    if($code==0){
        $user = $y["user"];
        if(file_exists("upload")){
            
            $res = unlink("upload/".$n);
            if($res){
                $code = 0;
                $text = "删除成功";
            }else{
                $code = -2;
                $text = "删除失败";
            }
            
        }else{
            $code = -1;
            $text = "用户文件夹不存在";
        }
        
    }else{
        $code= -1;
        $text = "未登录";
    }
    
        $fh = [
        "code"=>$code,
        "text"=>$text
        ];
    return json_encode($fh,JSON_UNESCAPED_UNICODE);
}


function num(){
    $y = json_decode(yanz(),TRUE);
    $code = $y["code"];
    if($code==0){
        $user = $y["user"];
        include("config.php");
        $conn = new mysqli($servername, $username, $password, $dbname);
        $sql = "select * from km ";
        $result = $conn->query($sql);
        $kmzn = $result->num_rows;
        
        
        $sql = "select * from km where time2 = '' ";
        $result = $conn->query($sql);
        $kmwn = $result->num_rows;
        
        $sql = "select * from imei";
        $result = $conn->query($sql);
        $zun = $result->num_rows;
        
        $wl = [];
        
        $w = scandir('upload/');
            foreach ($w as $a) {
            if($a != '.' and $a != '..'){
                $wj = "http://".$_SERVER['SERVER_NAME']."/upload/".$a;
                array_push($wl,["n"=>$a,"lj"=>$wj]);
                }
            } 

        $time = date("Y-m-d");
        $sql = "select * from imei where regtime = '$time' ";
        $result = $conn->query($sql);
        $jun = $result->num_rows;
        
        $code = 0;
        
    }else{
        $code = -999;
        $text = "未知错误";
    }
    
       $fh = [
        "code"=>$code,
        "kmzn"=>$kmzn,
        "kmwn"=>$kmwn,
        "jun"=>$jun,
        "zun"=>$zun,
        "wl"=>$wl,
        ];
    return json_encode($fh,JSON_UNESCAPED_UNICODE); 
}


function tj($gg,$bb,$lj,$gxnr){
    if(!$gg){
        $code = -1;
        $text = "请输入公告";
    }else if(!$bb){
        $code = -1;
        $text = "请输入软件版本";
    }else if(!$lj){
        $code = -1;
        $text = "请输入更新地址";
    }else if(!$gxnr){
        $code = -1;
        $text = "请输入更新内容";
    }else{
    $y = json_decode(yanz(),TRUE);
    $code = $y["code"];
    if($code==0){
        $user = $y["user"];
        include("config.php");
        $conn = new mysqli($servername, $username, $password, $dbname);
        $sql = "update admin set gg = '$gg',bb = '$bb',lj='$lj',gxnr='$gxnr' where user = '$user'";
        $conn->query($sql);   
        $code = 0;
        $text = "保存成功";
    }else{
        $code = -999;
        $text = "未知错误";
    }
    
    }
        $fh = [
        "code"=>$code,
        "text"=>$text
        ];
    return json_encode($fh,JSON_UNESCAPED_UNICODE);   
}

function initkm(){
    include("config.php");
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "select * from km ";
    $result = $conn->query($sql);
    $fh = array();
    while($row = $result->fetch_assoc()) {
        $time = Sec2Time($row["time"]);
        
        if($row["time2"]){
            $time2= date("Y-m-d H:i:s",$row["time2"]);
        }else{
            $time2 = "未使用";
        }
        
        $a =array(
            "id" => $row["id"],
            "km"=>$row["km"],
            "time"=>$time,
            "time2"=>$time2
            );
        
        array_push($fh,$a);
    }
    $conn->close();
    return json_encode($fh);
}

function initimei($user){
    include("config.php");
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "select * from imei ";
    $result = $conn->query($sql);
    $fh = array();
    while($row = $result->fetch_assoc()) {
        $time = date("Y-m-d H:i:s",$row["time"]);
        $a =array(
            "id" => $row["id"],
            "km"=>$row["km"],
            "time"=>$time,
            "imei"=>$row["imei"]
            );
        
        array_push($fh,$a);
    }
    $conn->close();
    return json_encode($fh);
}




function plsc($s){
    $y = json_decode(yanz(),TRUE);
    $code = $y["code"];
    if($code==0){
        $user = $y["user"];
        include("config.php");
        $conn = new mysqli($servername, $username, $password, $dbname);
    if($s == 1){
        $sql = "delete from km where isyong = '1'";
        $conn->query($sql); 
        $code = 0;
        $text = "已经删除所有已使用卡密";
    }else if($s == 2){
        $sql = "delete from km where isyong = '0'";
        $conn->query($sql); 
        $code = 0;
        $text = "已经删除所有未使用卡密";
    }else if($s == 3){
        $sql = "delete from km ";
        $conn->query($sql); 
        $code = 0;
        $text = "已经删除全部卡密";
    }else{
        $code = -1;
        $text = "错误";
    }
    
    }else{
        $code = -2;
        $text = "请登录";
    }
        $fh = [
        "code"=>$code,
        "text"=>$text,
        ];
    return json_encode($fh);    
}

function deletekm($km){
    $y = json_decode(yanz(),TRUE);
    $code = $y["code"];
    if($code==0){
        $user = $y["user"];
        include("config.php");
        $conn = new mysqli($servername, $username, $password, $dbname);
        $sql = "select * from km where  km = '$km'";
        $result = $conn->query($sql);   
        if($result->num_rows>0){
            $sql = "delete from km where  km = '$km'";
            $conn->query($sql); 
            $code = 0;
            $text = "删除成功";
        }else{
            $code = -1;
            $text = "未知错误2";
        }
        
        
    }else{
        $code = -999;
        $text = "未知错误";
    }
    $fh = [
        "code"=>$code,
        "text"=>$text,
        ];
    return json_encode($fh);    
}

function addkm($n,$s){
    $y = json_decode(yanz(),TRUE);
    $code = $y["code"];
    if($code == 0){
        switch ($n) {
            case 1:
                $num = 1;
                break;
            case 2:
                $num = 5;
                break;
            case 3:
                $num = 10;
                break;
            case 4:
                $num = 50;
                break;
            case 5:
                $num = 100;
                break;
                
            default:
                $num = 0;
                break;
        }
        
        switch ($s) {
            case 1:
                $t = 3600;
                break;
            case 2:
                $t = 86400;
                break;
            case 3:
                $t = 604800;
                break;
            case 4:
                $t = 2678400;
                break;
            case 5:
                $t = 31556926;
                break;
            default:
                $t = 0;
                break;
        }
        
        if($num != 0 and $t != 0){
            $user = $y["user"];
            include("config.php");
            $conn = new mysqli($servername, $username, $password, $dbname);
                for ($i = 0; $i < $num; $i++) {
                    $km = make_password();
                    $sql = "insert into km(km,time,time2) values ('$km','$t','')";
                    $conn->query($sql); 
                }
                
                $code = 0;
                $text = "已经添加".$num."张卡密";
            
            $conn->close();
        }else{
            $code = -998;
            $text = "未知错误";
        }
        
    }else{
        $code = -999;
        $text = "未知错误";
    }
    $fh = [
        "code"=>$code,
        "text"=>$text,
        ];
    return json_encode($fh);
}


function bao($rc4,$token){
    $y = json_decode(yanz(),TRUE);
    $code = $y["code"];
    if($code==0){
        $user = $y["user"];
        include("config.php");
        $conn = new mysqli($servername, $username, $password, $dbname);
        $sql = "update admin set rc4 = '$rc4',token='$token' where user = '$user'";
        $conn->query($sql);   
        $code = 0;
        $text = "保存成功";
    }else{
        $code = -999;
        $text = "未知错误";
    }
        $fh = [
        "code"=>$code,
        "text"=>$text
        ];
    return json_encode($fh,JSON_UNESCAPED_UNICODE);
}




function getgg($token){
    
    if(!$token){
        $code = -1;
        $msg = "请输入token";
    }else{
        include("config.php");
        $conn = new mysqli($servername, $username, $password, $dbname);
        $sql = "select * from admin where token = '$token'";
        $result = $conn->query($sql);   
        if($result->num_rows>0){
            $row = $result->fetch_assoc();
            $gg = $row["gg"];
            $bb = $row["bb"];
            $lj = $row["lj"];
            $gxnr = $row["gxnr"];
            $code = 0;
            $msg = "获取成功";
        }else{
            $code = -2;
            $msg = "无效token";
        }
        
        
    }
    
    
    $fh = [
        "code"=>$code,
        "msg"=>$msg,
        "gg"=>$gg,
        "bb"=>$bb,
        "lj"=>$lj,
        "gxnr"=>$gxnr
        ];
    return json_encode($fh,JSON_UNESCAPED_UNICODE); 
}






function cx($imei,$token,$a){
    $time = -999999;
    if(!$imei){
        $code = -1;
        $msg = "请提交imei";
    }else if(!$token){
        $code = -2;
        $msg = "请提交token";
    }else if(!$a){
        $code = -7;
        $msg = "缺少参数";
    }else{
        include("config.php");
        $conn = new mysqli($servername, $username, $password, $dbname);
        $sql = "select * from admin where token = '$token'";
        $result = $conn->query($sql);   
        if($result->num_rows>0){
            $row = $result->fetch_assoc();
            $rc4 = $row["rc4"];
            $user = $row["user"];
            $jmei = jie($rc4,$imei);
            if(jia($rc4,"2389256983") == $a){
            $sql = "select * from imei where imei = '$jmei'";
            $result = $conn->query($sql);
            if($result->num_rows>0){
                $row = $result->fetch_assoc();
                if(!$row["km"]){
                    $time2 = "未激活，请使用卡密充值";
                }else{
                    $time = $row["time"] - time();
                    if($time > 0 ){
                        $time2 = Sec2Time($row["time"] - time());
                    }else{
                        $time2 = "已过期";
                    }
                    
                }
                
            }else{
                $time2 = "未激活，请使用卡密充值";
            }
            $time2 = jia($rc4,$time2);
            $msg = "获取成功";
            $b = jia($rc4,"ziyia.cn");
            $msg = jia($rc4,$msg);
            }else{
                $msg = "rc4或token错误";
            }
            
        }else{
            $msg = "token无效";
        }
        
        
    }
    
    $fh = [
        "msg"=>$msg,
        "time2"=>$time2,
        "b"=>$b
        ];
    
    return json_encode($fh,JSON_UNESCAPED_UNICODE);
}




function uk($imei,$km,$token,$a){
    if(!$imei){
        $code = -1;
        $msg = "请提交imei";
    }else if(!$token){
        $code = -2;
        $msg = "请提交token";
    }else if(!$km){
        $code = -3;
        $msg = "请提交卡密";
    }else{
        include("config.php");
        $conn = new mysqli($servername, $username, $password, $dbname);
        $sql = "select * from admin where token = '$token'";
        $result = $conn->query($sql);   
        if($result->num_rows>0){
            $row = $result->fetch_assoc();
            $user = $row["user"];
            $rc4 = $row['rc4'];
            if(jia($rc4,"2389256983") == $a){
            $sql = "select * from km where km = '$km'";
            $result = $conn->query($sql);
            if($result->num_rows>0){
                $row = $result->fetch_assoc();
                if(!$row["time2"]){
                    $kmtime = $row["time"];
                    $sql = "select * from imei where imei = '$imei'";
                    $result = $conn->query($sql);
                    if($result->num_rows>0){
                        $row = $result->fetch_assoc();
                        if($row["time"]>time()){
                            
                            $time = $row["time"] + $kmtime;
                        }else{
                            $time = time()+$kmtime;
                        }
                        
                        $sql = "update imei set km ='$km',time = '$time' where  imei = '$imei'";
                        $conn->query($sql);
                        
                        $sql = "update km set isyong = '1', time2 = '$time' where  km = '$km'";
                        $conn->query($sql); 
                        
                        $code = 0;
                        $msg = "使用成功";
                        
                    }else{
                        $time = time()+$kmtime;
                        $regtime = date("Y-m-d");
                        $sql = "insert into imei(imei, km, time,regtime) values ('$imei', '$km','$time','$regtime')";
                        $conn->query($sql);
                        
                        $sql = "update km set isyong = '1', time2 = '$time' where km = '$km'";
                        $conn->query($sql); 
                        
                        $code = 0;
                        $msg = "使用成功";
                    }
                }else{
                    $code = -6;
                    $msg = "卡密已被使用";
                }
                
                
            }else{
                $code = -5;
                $msg = "卡密无效";
            }
            
            }else{
                 $code = -7;
                $msg = "rc4无效";
            }
            
        }else{
            $code = -4;
            $msg = "token无效";
        }
        
        
    }
    $fh = [
        "code"=>$code,
        "msg"=>$msg
        ];
    
    return json_encode($fh,JSON_UNESCAPED_UNICODE);
    
}


function Sec2Time($time)

{

    if (is_numeric($time)) {

        $value = array(

            "years" => 0, "days" => 0, "hours" => 0,

            "minutes" => 0, "seconds" => 0,

        );

        $t = '';

        if ($time >= 31556926) {

            $value["years"] = floor($time / 31556926);

            $time = ($time % 31556926);

            $t .= $value["years"] . "年";

        }

        if ($time >= 86400) {

            $value["days"] = floor($time / 86400);

            $time = ($time % 86400);

            $t .= $value["days"] . "天";

        }

        if ($time >= 3600) {

            $value["hours"] = floor($time / 3600);

            $time = ($time % 3600);

            $t .= $value["hours"] . "小时";

        }

        if ($time >= 60) {

            $value["minutes"] = floor($time / 60);

            $time = ($time % 60);

            $t .= $value["minutes"] . "分";

        }

        $value["seconds"] = floor($time);

        //return (array) $value;
        if($value["seconds"] != 0){
            $t .= $value["seconds"] . "秒";
        }

        return $t;

    } else {

        return (bool) false;

    }

}






function make_password( $length = 15 ){
    // 密码字符集，可任意添加你需要的字符

    $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 

    'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's', 

    't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D', 

    'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O', 

    'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z', 

    '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

    // 在 $chars 中随机取 $length 个数组元素键名

    $keys = array_rand($chars, $length); 

    $password = '';

    for($i = 0; $i < $length; $i++)

    {

        // 将 $length 个数组元素连接成字符串

        $password .= $chars[$keys[$i]];

    }

    return $password;

}


function jia($key,$data){
    return bin2hex(rc4($key,$data));
}
function jie($key,$data){
    return rc4($key,hex2bin($data));
}



function rc4($pwd, $data)
{
    $cipher      = '';
    $key[]       = "";
    $box[]       = "";
    $pwd_length  = strlen($pwd);
    $data_length = strlen($data);
    for ($i = 0; $i < 256; $i++) {
        $key[$i] = ord($pwd[$i % $pwd_length]);
        $box[$i] = $i;
    }
    for ($j = $i = 0; $i < 256; $i++) {
        $j       = ($j + $box[$i] + $key[$i]) % 256;
        $tmp     = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for ($a = $j = $i = 0; $i < $data_length; $i++) {
        $a       = ($a + 1) % 256;
        $j       = ($j + $box[$a]) % 256;
        $tmp     = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $k       = $box[(($box[$a] + $box[$j]) % 256)];
        $cipher .= chr(ord($data[$i]) ^ $k);
    }
    return $cipher;
}





