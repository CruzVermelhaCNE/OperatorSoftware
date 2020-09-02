<?php

namespace App\Console\Commands;

use App\Models\Auth\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class DeleteUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:delete {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete an User';

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
        $user           = User::where('email','=',$this->argument('email'))->first();
        $user->delete();
    }
}
