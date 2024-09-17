<?php

namespace BNock\ElasticsearchPHP\Models;

use BNock\ElasticsearchPHP\Enumerations\GeoShapeType;
use BNock\ElasticsearchPHP\Exceptions\ElasticsearchException;
use Illuminate\Support\Collection;

class GeoShape
{
    /**
     * Constructor.
     *
     * @param GeoShapeType $type
     * @param Collection $coordinates
     * @throws ElasticsearchException
     */
    public function __construct(protected GeoShapeType $type, protected Collection $coordinates)
    {
        if ($this->coordinates->isEmpty()) {
            throw new ElasticsearchException('Coordinates must not be empty');
        }
    }

    /**
     * @return GeoShapeType
     */
    public function getType(): GeoShapeType
    {
        return $this->type;
    }

    /**
     * @return Collection
     */
    public function getCoordinates(): Collection
    {
        return $this->coordinates;
    }
}
