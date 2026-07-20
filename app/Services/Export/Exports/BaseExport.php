<?php

namespace App\Services\Export\Exports;

use Illuminate\Database\Eloquent\Collection;

abstract class BaseExport
{
    protected string $exportType;
    
    abstract protected function prepareData(Collection $data): array;
    
    abstract protected function getHeaders(): array;
    
    public function prepare(Collection $data): array
    {
        return [
            'rows' => $this->prepareData($data),
            'headers' => $this->getHeaders()
    ];
    }
}