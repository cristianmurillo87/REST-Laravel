<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Estratificacion\User as User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
      /*  Model::unguard();
        
        $users = array(
            ['usuario'=>'cristian.murillo' ],
            ['usuario'=>'jorge.castano'],
            ['usuario'=>'armando-larrota'],
            ['usuario'=>'william.ruales'],
            ['usuario'=>'luz.nunez'],
            ['usuario'=>'edmundo.rengifo'],
            ['usuario'=>'milena.barreto'],
            ['usuario'=>'yolanda.bolanos'],
        );
        
        foreach ($users as $user) {
            User::where('usuario',$user['usuario'])->update(['password'=>\Hash::make($user['usuario'])]);
        }
        
        Model::reguard();
        // $this->call(UsersTableSeeder::class);
    }*/
}
