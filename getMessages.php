<?php
header("Cache-Control: no-cache");
header("Content-Type: text/event-stream");

if (!file_exists('buffer.txt'))
	file_put_contents('buffer.txt', '');

clearstatcache(); //To update filesize()
$fileHeadPos = filesize('buffer.txt');


while (true) {
	clearstatcache();
	
	if ($fileHeadPos != filesize('buffer.txt')) { //Buffer has been changed
		$v = file_get_contents('buffer.txt');

		$v2 = explode("%$\n", substr($v, $fileHeadPos));
		
		foreach ($v2 as $v2a) {
			$v2a = htmlentities($v2a);
			if (!empty($v2a)) {
				echo "data: $v2a\n\n";
				ob_end_flush();
				flush();
			}
		}

		$fileHeadPos = filesize('buffer.txt');
	}

	usleep(100000); //Check period (in server-side, microseconds)
}
?>
