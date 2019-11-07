<?php

namespace App\Console\Commands;

use App\Extension;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateExtension extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extension:create {number} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Extension';

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
        $extension           = new Extension();
        $extension->number   = $this->argument('number');
        $extension->password = $this->argument('password');
        $extension->save();
    }
}
