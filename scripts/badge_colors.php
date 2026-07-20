<?php
$files = array_merge(
    glob(__DIR__ . '/../app/Filament/Resources/*/Tables/*Table.php'),
    glob(__DIR__ . '/../app/Filament/Resources/*/RelationManagers/*.php')
);
$needle = '->badge()';
$repl = '->badge()->color(fn ($state) => \\App\\Helpers\\BadgeColors::for($state))';
$c = 0;
foreach ($files as $f) {
    $content = file_get_contents($f);
    if (strpos($content, 'BadgeColors::for') !== false) continue; // déjà fait
    if (strpos($content, $needle) === false) continue;
    $content = str_replace($needle, $repl, $content);
    file_put_contents($f, $content);
    $c++;
}
echo "Fichiers badges colorés: $c\n";
