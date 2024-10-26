<?php

[, $directory] = $argv;
@rmdir($directory . '/export/sheets');
@mkdir($directory . '/export/sheets', 0777, true);

$cardFiles = array_values(array_filter(scandir($directory . '/export/cards'), fn(string $file) => $file !== '.' && $file !== '..'));

$cardsPerRow = 3;
$cardsPerColumn = 6;
$padding = 30;

$sheetNumber = 0;

foreach ($cardFiles as $cardNumber => $cardFile) {
    $image = imagecreatefrompng($directory . "/export/cards/$cardFile");
    $imageWidth = imagesx($image);
    $imageHeight = imagesy($image);

    $rowNumber = floor($cardNumber / $cardsPerRow);
    $rowNumber -= $cardsPerColumn * floor($rowNumber / $cardsPerColumn);
    $columnNumber = $cardNumber % $cardsPerRow;

    $sheetWidth = $imageWidth * $cardsPerRow + $padding * ($cardsPerRow + 1);
    $sheetHeight = $imageHeight * $cardsPerColumn + $padding * ($cardsPerColumn + 1);

    if ($cardNumber % ($cardsPerRow * $cardsPerColumn) === 0) {
        if (isset($sheet)) {
            imagepng($sheet, $directory . "/export/sheets/$sheetNumber.png");
            chmod($directory . "/export/sheets/$sheetNumber.png", 0777);
            $sheetNumber++;
        }
        echo "Sheet $sheetNumber\n";
        $sheet = imagecreatetruecolor($sheetWidth, $sheetHeight);
        $white = imagecolorallocate($sheet, 255, 255, 255);
        imagefill($sheet, 0, 0, $white);
    }
    echo "\n row $rowNumber column $columnNumber\n";
    $dst_x = $columnNumber * $imageWidth + ($padding * (1 + $columnNumber));
    $dst_y = $rowNumber * $imageHeight + ($padding * (1 + $rowNumber));
    imagecopy(
        $sheet,
        $image,
        $dst_x,
        $dst_y,
        0,
        0,
        $imageWidth,
        $imageHeight
    );

    $black = imagecolorallocate($sheet, 0, 0, 0);
    imageline($sheet, $dst_x - $padding, $dst_y - $padding / 2, $sheetWidth, $dst_y - $padding / 2, $black);
    imageline($sheet, $dst_x - $padding, $dst_y + $imageHeight - $padding, $sheetWidth, $dst_y + $imageHeight - $padding, $black);
}
imagepng($sheet, $directory . "/export/sheets/$sheetNumber.png");
chmod($directory . "/export/sheets/$sheetNumber.png", 0777);
