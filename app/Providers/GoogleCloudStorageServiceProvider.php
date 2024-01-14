<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\GoogleCloudStorage\GoogleCloudStorageAdapter;
use Google\Cloud\Storage\StorageClient;

class GoogleCloudStorageServiceProvider extends ServiceProvider
{
    public function register()
    {
        app('filesystem')->extend('gcs', function ($app, $config) {
            $client = new StorageClient([
                'projectId' => $config['projectId'],
                'keyFilePath' => $config['keyFilePath'],
            ]);

            $bucket = $client->bucket($config['bucket']);
            $adapter = new GoogleCloudStorageAdapter($bucket);
            return new Filesystem($adapter);
        });
    }
}
