<?php

namespace App\Console\Commands;

use App\UserExtension;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class LinkUserWithExtension extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extension:link {extension_id} {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link an user with an extension';

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
        $userExtension                  = new UserExtension();
        $userExtension->extension_id    = $this->argument('extension_id');
        $userExtension->user_id         = $this->argument('user_id');
        $userExtension->save();
    }
}
