<?php

namespace BNock\ElasticsearchPHP\Credentials;

use Illuminate\Support\Collection;

class Basic
{
    public function __construct(protected Collection $hosts, protected string $username, protected string $password)
    {
    }

    public function addHost(string $host): static
    {
        $this->hosts->add($host);

        return $this;
    }

    public function getHosts(): Collection
    {
        return $this->hosts;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
