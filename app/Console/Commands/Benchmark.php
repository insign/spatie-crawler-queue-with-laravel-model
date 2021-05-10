<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Benchmark extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Benchmark test';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $times = [];

        $grupo = [];

        $samples = range(-20000, 20000);

        $algos = hash_algos();

        $progressBar = $this->output->createProgressBar(count($algos));

        $progressBar->start();
        $progressBar->setFormat('verbose');
        foreach ($algos as $algo) {
            $startTime = microtime(TRUE);

            foreach ($samples as $sample) {
                $hash = hash($algo, $sample);
                $grupo[] = $hash;
            }
            $endTime = microtime(TRUE);
            $time = number_format($endTime - $startTime , 4);

            $sample = strlen($hash) > 50 ? substr($hash,0,50)."..." : $hash;

            $times[$time] = [$algo, $time, $sample, strlen($hash)];
            $progressBar->advance();
        }

        $progressBar->finish();

        ksort($times);

        $this->newLine(2);

        $this->table(['Algo', 'Time', 'Sample', 'Size'], $times);

        $this->alert(count($grupo));

        return 0;
    }
}
