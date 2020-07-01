<?php
$ranges = [
    [1, 5],
    [4, 7],
    [9, 12],
    [11, 20],
    [2, 3]
];
function merge($range1, $range2)
{
    list($al, $ar) = $range1;
    list($bl, $br) = $range2;

    $min = min($al, $bl);
    $max = max($ar, $br);
    if ($ar < $bl || $br < $al) {
        return false;
    } else {
        return [$min, $max];
    }
}

function mergeTo(&$ret, $range)
{
    $hasMerged = false;
    foreach ($ret as $index => $item) {
        if ($mergedRange = merge($item, $range)) {
            $hasMerged = true;
            unset($ret[$index]);
            mergeTo($ret, $mergedRange);
            break;
        }
    }
    // 如果range 和 f(n)中任意一个区间都没有交集，push到f(n)中
    if (!$hasMerged) {
        $ret[] = $range;
    }
}

$ret = [];
foreach ($ranges as $range) {
    mergeTo($ret, $range);
}
