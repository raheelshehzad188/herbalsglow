<?php

namespace App\Jobs;

use App\Models\ShopifyImportJob;
use App\Services\Shopify\ShopifyImporter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessShopifyImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;
    public $tries = 25;

    public int $importJobId;
    public int $storeId;

    public function __construct(int $importJobId, int $storeId)
    {
        $this->importJobId = $importJobId;
        $this->storeId = $storeId;
    }

    public function handle(ShopifyImporter $importer)
    {
        $job = ShopifyImportJob::withoutStore()
            ->where('id', $this->importJobId)
            ->where('store_id', $this->storeId)
            ->first();
        if (!$job) {
            return;
        }
        $importer->tick($job, 20);
        $job->refresh();
        if (config('queue.default') === 'sync') {
            return;
        }
        if (in_array($job->status, ['running', 'queued'], true) && !$job->cancel_requested) {
            self::dispatch($this->importJobId, $this->storeId)->delay(now()->addSeconds(1));
        }
    }
}
