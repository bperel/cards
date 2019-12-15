<?php
function writeCentered($image, $xOffset, $y, $size, $angle, $color, $word) {
    $fontPath = __DIR__.'/../../tahomscb.ttf';
    $textPositionX = $angle === 0
        ? $xOffset
        : imagesx($image) + $xOffset;
    imagettftext($image, $size, $angle, $textPositionX, $y, $color, $fontPath, $word);
}

@rmdir(__DIR__.'/export');
@mkdir(__DIR__.'/export/cards', 0777, true);
@mkdir(__DIR__.'/export/sheets', 0777, true);

$handle = fopen(__DIR__.'/words.txt', 'rb');
if ($handle) {
    $i = 0;
    while (($word = fgets($handle)) !== false) {
        $word = str_replace("\n", '', $word);
        echo "Writing card for $word\n";

        if ($i % 2 === 0) {
            $image = imagecreatefrompng(__DIR__.'/card.png');
            imagesavealpha($image, true);
            $trans_colour = imagecolorallocatealpha($image, 0, 0, 0, 127);
            imagefill($image, 0, 0, $trans_colour);
            writeCentered($image, 30, 70, 14, 0, 0, $word);
        }
        else {
            $cardNumber = ($i - 1) / 2;
            writeCentered($image, -30, 150, 14, 180, 0, $word);
            imagepng($image, __DIR__."/export/cards/$cardNumber.png");
        }

        $i++;
    }

    fclose($handle);
} else {
    echo 'Could not open '.__DIR__.'/words.txt';
}
