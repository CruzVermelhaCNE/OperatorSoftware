<?php

namespace App\Console\Commands;

use App\Models\Auth\User;
use App\Models\Auth\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class RemoveUserPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:removepermission {email} {permission}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove permission to User';

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
        if($user->permissions->each(function ($permission, $key) {
            if($permission->permission == $this->argument('permission')) {
                $permission->delete();
            }
        }));
    }
}
