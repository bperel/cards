<?php
function recurseRmdir($dir): bool
{
    if (!file_exists($dir)) {
        return true;
    }
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file") && !is_link("$dir/$file")) ? recurseRmdir("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}


$handle = fopen(__DIR__ . '/labels.csv', 'rb');
if ($handle) {
    recurseRmdir(__DIR__ . '/export');
    @mkdir(__DIR__ . '/export/cards', 0777, true);
    @mkdir(__DIR__ . '/export/sheets', 0777, true);
    fgets($handle);
    while (($word = fgets($handle)) !== false) {
        $image = imagecreate(1000, 100);
        imagefill($image, 0, 0, imagecolorallocate($image, 255, 255, 255));
        // $trans_colour = imagecolorallocatealpha($image, 0, 0, 0, 127);
        // imagefill($image, 0, 0, $trans_colour);

        $black = imagecolorallocate($image, 0, 0, 0);
        [$type, $flag, $name] = explode(';', str_replace("\n", '', $word));
        echo "Writing card for $name ($flag)\n";
        $flagImage = imagecreatefrompng("https://flagcdn.com/w80/$flag.png");
        $flagWidth = 48 * 1.5;
        imagecopyresized($image, $flagImage, 0, 0, 0, 0, 48 * 1.5, 48, imagesx($flagImage), imagesy($flagImage));
        $fontSize = $type === 'Country' ? 48 : 28;
        $yPos = $type === 'Country' ? $fontSize : (int)($fontSize * 1.333);
        imagettftext($image, $fontSize, 0, $flagWidth + 20, $yPos, $black, __DIR__ . '/../../calibrib.ttf', strtoupper($name));

        imagepng($image, __DIR__ . "/export/cards/$name.png");
        chmod(__DIR__ . "/export/cards/$name.png", 0777);
    }

    fclose($handle);
} else {
    echo 'Could not open ' . __DIR__ . '/labels.csv';
}
