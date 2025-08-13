<?php

namespace App\Filament\Resources\Executors\Pages;

use App\Filament\Resources\Executors\ExecutorResource;
use App\Models\Company;
use App\Services\IExecutorService;
use Filament\Resources\Pages\CreateRecord;

class CreateExecutor extends CreateRecord
{
    protected static string $resource = ExecutorResource::class;

}
