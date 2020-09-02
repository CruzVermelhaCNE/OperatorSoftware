<?php

namespace App\Console\Commands;

use App\Models\Extension;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class DeleteExtension extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extension:delete {number}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete an Extension';

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
    public function handle()
    {
        //
        $extension           = Extension::where('number','=',$this->argument('number'));
        $extension->delete();
    }
}
