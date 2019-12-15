<?php
function writeCentered($image, $xOffset, $y, $size, $angle, $color, $word) {
    $fontPath = __DIR__.'/../../tahomscb.ttf';
    $imageWidth = imagesx($image);
    [$bottomLeftX, , $bottomRightX, , , , , ] = imagettfbbox(72, 0, $fontPath, $word);
    $textWidth = $bottomRightX - $bottomLeftX;
    $textPositionX = $angle === 0
        ? ($imageWidth + $xOffset) /2 - $textWidth / 2
        : ($imageWidth + $xOffset) /2 + $textWidth / 2 ;
    imagettftext($image, $size, $angle, $textPositionX, $y, $color, $fontPath, $word);
}
$image = imagecreatefrompng(__DIR__.'/card.png');

$imageWidth = imagesx($image);
$brown = imagecolorallocate($image, 170, 145, 125);

$handle = fopen(__DIR__.'/words.txt', 'rb');
if ($handle) {
    @rmdir(__DIR__.'/export');
    @mkdir(__DIR__.'/export/cards', 0777, true);
    @mkdir(__DIR__.'/export/sheets', 0777, true);
    while (($word = fgets($handle)) !== false) {
        $word = str_replace("\n", '', $word);
        echo "Writing card for $word\n";
        $image = imagecreatefrompng(__DIR__.'/card.png');
        imagesavealpha($image, true);
        $trans_colour = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $trans_colour);

        writeCentered($image, 0, 440, 72, 0, 0, $word);
        writeCentered($image, -300, 240, 50, 180, $brown, $word);

        imagepng($image, __DIR__."/export/cards/$word.png");
        chmod(__DIR__."/export/cards/$word.png", 0777);
    }

    fclose($handle);
} else {
    echo 'Could not open '.__DIR__.'/words.txt';
}
