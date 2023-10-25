"c:\program files\php\php.exe" %0
pause
<?php
if(!file_exists("items.json"))
{
	$endpoint = readline("Enter endpoint for item json: (http://???/db/items) ");
	$itemData = file_get_contents($endpoint);
	file_put_contents("items.json", $itemData);
}
else
	$itemData = file_get_contents("items.json");

$items = json_decode($itemData, true);


foreach($items as $item)
{
	if($item["Type"] < 80 || $item["Type"] > 89)
		continue;

	$bg = imagecreatefrompng("bg_" . max(1, $item["Quality"]) . ".png");
	$fg = imagecreatefrompng("fg_" . max(1, $item["Quality"]) . ".png");
	
	$img = imagecreatetruecolor(imagesx($bg), imagesy($bg));
	imagealphablending($img, true);
	imagealphablending($bg, true);
	imagealphablending($fg, true);

	imagecopy($img, $bg, 0, 0, 0, 0, imagesx($img), imagesy($img));


	if(is_file("../face/cardhead_" . $item["Id"] . ".png"))
		$face = imagecreatefrompng("../face/cardhead_" . $item["Id"] . ".png");
	else
		$face = imagecreatefrompng("../face/" . $item["Icon"] . ".png");
	if($face)
	{
		imagecopy($img, $face, (int)((imagesx($img)-imagesx($face))/2), 0, 0, 0, imagesx($img), imagesy($img));
	}
	else
		print_r($item);
	imagecopy($img, $fg, 0, 0, 0, 0, imagesx($img), imagesy($img));

	$slot = imagecreatefrompng("card_" . $item["Type"] . ".png");
	if(!$slot)
		print_r($item);
	imagecopyresampled($img, $slot, 5, 5, 0, 0, 17, 17, imagesx($slot), imagesy($slot));



	imagesavealpha($img, true);
	imagepng($img, "../cards/" . $item["Id"] . ".png");

	imagedestroy($fg);
	imagedestroy($bg);
	imagedestroy($img);
	imagedestroy($slot);
	if($face)
		imagedestroy($face);
	
}

?>