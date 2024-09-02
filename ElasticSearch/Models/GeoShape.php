<?php

namespace App\Services\ElasticSearch\Models;

use App\Services\ElasticSearch\Enumerations\GeoShapeType;

class GeoShape
{
    /**
     * Constructor.
     *
     * @param GeoShapeType $type
     * @param array $coordinates
     */
    public function __construct(protected GeoShapeType $type, protected array $coordinates)
    {
    }

    /**
     * @return GeoShapeType
     */
    public function getType(): GeoShapeType
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getCoordinates(): array
    {
        return $this->coordinates;
    }
}
