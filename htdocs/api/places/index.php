<?php

require_once '../../common.php';
Auth\Internal::grantAccess();
$places=genTree(\SmartHome\Places::getRootPlaces(PDO::FETCH_OBJ));
httpResponse::json($places);

function genTree($roots) {
    $places=$roots;
    foreach ($places as $key=>$value) {
        $child=\SmartHome\Places::getPlaceChild($value->id,PDO::FETCH_OBJ);
        if(sizeof($child)>0) {
            $places[$key]->state=['opened'=>true];
            $places[$key]->children=$child;
            genTree($child);
        }
    }
    return $places;
}