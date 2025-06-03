<?php
echo "write name game \n";

$game = trim(fgets(STDIN));

$data = ['last_game' => $game];
file_put_contents(__DIR__ . '/test.json', json_encode($data));
echo 'Хочеш побачити останню гру? (yes/no)\n';
$answer = trim(fgets(STDIN));
if($answer === 'yes'){
    $saved = json_decode(file_get_contents(__DIR__ . '/test.json'), true);
    echo "last game saved: " . $saved['last_game'] . "\n";
}else{
    echo " okey \n";
}
