<?php
/**
 * Copyright (C) 2017 Baidu, Inc. All Rights Reserved.
 */

$pdo = new PDO('pgsql:host=gzns-ns-map-guoke48.gzns.baidu.com;dbname=road_base;port=8432','postgres','The.2014.year!');
$stmt = $pdo->prepare('select * from hs_road_link_20171025140204 limit 10');
$stmt -> execute();
while ($row = $stmt->fetch(PDO::FETCH_CLASS, PDO::FETCH_ORI_NEXT)) {
//    print_r($row);
}

$stmt2 = $pdo->prepare('select * from hs_road_link_20171025140204 limit 10', array(PDO::ATTR_CURSOR =>
    PDO::CURSOR_SCROLL));
$firstRow = $stmt2->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_FIRST);
echo $firstRow['id'] . "\r\n";
$cursor = PDO::FETCH_ORI_FIRST;
while (false !== ($row = $stmt->fetch(PDO::FETCH_ASSOC, $cursor))) {
    $id = $row['id'];
    // successive iterations we hit the "next" record
    $cursor = PDO::FETCH_ORI_NEXT;
    echo $id . "\r\n";
}