<?php

use Node;
use BFS;

$map = [
    [0, 0, 0, 0, 0, 1, 0, 0],
    [0, 'C', 0, 0, 0, 1, 0, 0],
    [0, 1, 1, 0, 'Z', 0, 0, 0],
    [0, 1, 1, 1, 0, 0, 0, 0],
    [0, 'X', 1, 0, 0, 0, 0, 0],
    [0, 0, 1, 'Y', 0, 0, 0, 0],
    [0, 0, 0, 0, 0, 0, 0, 0],
];


// $map = [
//     [0, 0, 0, 0, 'Y', 1, 0, 0],
//     [0, 'C', 0, 1, 1, 1, 0, 0],
//     [0, 1, 1, 1, 'X', 0, 0, 0],
// ];

// $map = [
//     [0, 0, 0, 0, 'Y', 1, 0, 0],
//     [0, 'C', 0, 1, 1, 1, 0, 0],
// ];

function solution($map)
{
    $m = count($map);
    $n = count($map[0]);

    $catName = 'C';
    $mousesName = ['X', 'Y', 'Z'];

    if ($m <= 0 || $m > 20 || $n <= 0 || $n > 30) {
        return '無解';
    }

    $mousesAndCatCoordinate = searchMousesAndCatCoordinate($map, $mousesName, $catName);

    $catCoordinate = $mousesAndCatCoordinate['cat'];
    $mousesCoordinate = $mousesAndCatCoordinate['mouses'];

    $catToMouses = [];
    foreach ($mousesCoordinate as $key => $value) {

        $Vs = new Node($catCoordinate['x'], $catCoordinate['y']);
        $Vs->setStep(0);
        $Vd = new Node($mousesCoordinate[$key]['x'], $mousesCoordinate[$key]['y']);

        $bfs = new BFS($map, $m, $n, $Vs, $Vd, $mousesCoordinate);

        $catStep = $bfs->BFS();

        if (!$catStep) {
            return '無解';
        }

        $catToMouses[$key] = $catStep;
    }

    foreach ($mousesCoordinate as $mousekey => $mouseValue) {

        $newMousesCoordinate = $mousesCoordinate;
        unset($newMousesCoordinate[$mousekey]);

        foreach ($newMousesCoordinate as $newMousekey => $newMouseValue) {
            $Vs = new Node($mouseValue['x'], $mouseValue['y']);
            $Vs->setStep(0);
            $Vd = new Node($newMouseValue['x'], $newMouseValue['y']);

            $bfs = new BFS($map, $m, $n, $Vs, $Vd, $mousesCoordinate);

            $mouseStep = $bfs->BFS();
            $mouseToMouses[$mousekey][$newMousekey] = $mouseStep;
        }
    }

    $bestMouse = array_search(min($catToMouses), $catToMouses);
    $data = $catToMouses[$bestMouse] . $bestMouse;

    if (!isset($mouseToMouses)) {
        return $data;
    }

    while (count($mouseToMouses) != 1) {
        $nextBestMouse = array_search(min($mouseToMouses[$bestMouse]), $mouseToMouses[$bestMouse]);
        $data .= $mouseToMouses[$bestMouse][$nextBestMouse] . $nextBestMouse;
        unset($mouseToMouses[$bestMouse]);
        foreach ($mouseToMouses as $key => $value) {
            unset($mouseToMouses[$key][$bestMouse]);
        }
        $bestMouse = $nextBestMouse;
    }
    return $data;
}

function searchMousesAndCatCoordinate($map, $mousesName, $catName)
{
    $catCoordinate = [];
    $mousesCoordinate = [];

    foreach ($map as $mapKey => $item) {
        $catExist = array_search($catName, $item);

        if ($catExist) {
            $catCoordinate = [
                'x' => $mapKey,
                'y' => $catExist
            ];
        }

        foreach ($mousesName as $mouseKey => $mouseName) {
            $mouseExist = array_search($mouseName, $item);
            if ($mouseExist) {
                $mousesCoordinate[$mouseName] = [
                    'x' =>  $mapKey,
                    'y' =>  $mouseExist,
                ];
            }
        }
    }

    return [
        'cat'  => $catCoordinate,
        'mouses' => $mousesCoordinate,
    ];
}

echo solution($map);
?>