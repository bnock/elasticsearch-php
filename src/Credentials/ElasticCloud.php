<?php

namespace BNock\ElasticsearchPHP\Credentials;

class ElasticCloud
{
    public function __construct(protected string $cloudId, protected string $apiKey)
    {
    }

    public function getCloudId(): string
    {
        return $this->cloudId;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }
}
