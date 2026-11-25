<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Admin\FamTourRepository;
use Illuminate\Support\Facades\Log;

class FamToursSyncEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'famTour:sync-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza los emails para FamTour';

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
     * @return mixed
     */
    public function handle(FamTourRepository $famTourRepo)
    {
        Log::info('Comienza el comando ' . $this->signature);
        $famTourRepo->syncEmails();
        Log::info('Finalizo el comando ' . $this->signature);
    }
}
