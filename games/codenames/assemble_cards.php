<?php

@rmdir(__DIR__.'/export/sheets');
@mkdir(__DIR__.'/export/sheets', 0777, true);

$cardFiles = array_values(array_filter(scandir(__DIR__.'/export/cards'), function(string $file) {
    return $file !== '.' && $file !== '..';
}));

$cardsPerRow = 3;
$cardsPerColumn = 6;
$padding = 30;

$sheetNumber = 0;
foreach($cardFiles as $cardNumber => $cardFile) {
    $image = imagecreatefrompng(__DIR__."/export/cards/$cardFile");
    $imageWidth = imagesx($image);
    $imageHeight = imagesy($image);

    $rowNumber = floor($cardNumber / $cardsPerRow) ;
    $rowNumber -= $cardsPerColumn * floor($rowNumber / $cardsPerColumn);
    $columnNumber = $cardNumber % $cardsPerRow;

    if ($cardNumber % ($cardsPerRow * $cardsPerColumn) === 0) {
        if (isset($sheet)) {
            imagepng($sheet, __DIR__."/export/sheets/$sheetNumber.png");
            chmod(__DIR__."/export/sheets/$sheetNumber.png", 0777);
            $sheetNumber++;
        }
        echo "Sheet $sheetNumber\n";
        $sheet = imagecreatetruecolor(
            $imageWidth * $cardsPerRow + $padding * ($cardsPerRow + 1),
            $imageHeight * $cardsPerColumn + $padding * ($cardsPerColumn + 1)
        );
        imagesavealpha($sheet, true);
        $trans_colour = imagecolorallocatealpha($sheet, 0, 0, 0, 127);
        imagefill($sheet, 0, 0, $trans_colour);
    }
    echo "\n row $rowNumber column $columnNumber\n";
    imagecopy(
        $sheet,
        $image,
        $columnNumber * $imageWidth + ($padding * (1 + $columnNumber)),
        $rowNumber * $imageHeight + ($padding * (1 + $rowNumber)),
        0,
        0,
        $imageWidth,
        $imageHeight
    );
}
imagepng($sheet, __DIR__."/export/sheets/$sheetNumber.png");
