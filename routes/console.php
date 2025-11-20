<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('build', function () {
    $this->info('Running `npm run build`...');

    $process = Process::fromShellCommandline('npm run build');
    $process->setTimeout(null);
    $process->setIdleTimeout(null);

    $process->run(function (string $type, string $buffer): void {
        $this->output->write($buffer);
    });

    if (! $process->isSuccessful()) {
        throw new ProcessFailedException($process);
    }

    $this->info('Asset build completed.');
})->purpose('Build front-end assets via Vite');
