<?php

namespace App\Services\ElasticSearch\Enumerations;

enum GeoShapeType: string
{
    case Point = 'point';
    case LineString = 'linestring';
    case Polygon = 'polygon';
    case MultiPoint = 'multipoint';
    case MultiLineString = 'multilinestring';
    case MultiPolygon = 'multipolygon';
    case GeometryCollection = 'geometrycollection';
    case Envelope = 'envelope';
}
