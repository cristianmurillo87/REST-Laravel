<?php

namespace Estratificacion\Http\Controllers;

class JSONController
{
    public static function stringToGeoJson($string){
        $features = array();
        foreach ($string as $s) {
            array_push($features,
                array(
                    "type" => "Feature",
                    "geometry" => json_decode($s->geometry, true ),
                    "properties" => json_decode($s->properties, true)
                )
            );
        }
        
        $geojson = array(
            "type" => "FeatureCollection",
            "features" => $features
        );
        
        return $geojson;
    }
}