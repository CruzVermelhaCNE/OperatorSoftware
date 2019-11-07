<?php

namespace App\Console\Commands;

use App\User;
use App\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AddUserPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:addpermission {email} {permission}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add permission to User';

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
        $user           = User::where('email',$this->argument('email'))->first();
        $permission     = new Permission();
        $permission->fill([
            "user_id" => $user->id,
            "permission" => $this->argument('permission')
        ]);
        $permission->save();
    }
}
