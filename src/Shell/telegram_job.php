<?php
function send2Telegram($id, $msg, $token = '', $silent = false) {
	$result = false;
    $data = array(
        'chat_id' => $id,
        'text' => $msg,
        'parse_mode' => 'html',
        'disable_web_page_preview' => true,
        'disable_notification' => $silent
    );
    if($token != '') 
	{
		$ch = curl_init('https://api.telegram.org/bot'.$token.'/sendMessage');
		curl_setopt_array($ch, array(
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $data
		));
		$result = curl_exec($ch);
		curl_close($ch);
    }
	return $result;
}
$pathLogs = dirname(__FILE__).'/../../logs/';
$logFile = file($pathLogs.'login-'.date('Ymd').'.log');
$logLineFile = $pathLogs.'login-telegram.log';
$lastModifiedLine = 0;
if(file_exists($logLineFile))
{
	$lastModifiedLine = file_get_contents($logLineFile);
}
$lastLineOfLogFile = count($logFile);
if((int)$lastModifiedLine != $lastLineOfLogFile)
{
	try {
		$result = json_decode(send2Telegram('@alsokviet', 'User login failed in Alsok Vietnam', '5104828774:AAF_Qgv-9VdQYrrn775yL2pIrCDnxlqa-fA'), true);
		if(isset($result) && $result['ok'])
		{
			var_dump(file_put_contents($logLineFile, $lastLineOfLogFile));
		}
	} 
	catch(Exception $error)
	{
		var_dump($error);
	}
	
}
else
{
	echo 'Nothing';
}
echo PHP_EOL;