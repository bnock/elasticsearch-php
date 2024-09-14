<?php

namespace BNock\ElasticsearchPHP\Models;

use BNock\ElasticsearchPHP\Enumerations\GeoShapeType;

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
