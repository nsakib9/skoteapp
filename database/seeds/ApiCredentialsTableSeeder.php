<?php

use Illuminate\Database\Seeder;

class ApiCredentialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('api_credentials')->delete();

        DB::table('api_credentials')->insert([
            ['name' => 'toll_guru', 'value' => 'mnQd9NF6bqDq2BDQ92b4dP7LJd6bP8nh', 'site' => 'TollGuru'],
            ['name' => 'key', 'value' => 'AIzaSyA28L1--jXkpgSY9d-fsL2djBgkbBUyckE', 'site' => 'GoogleMap'],
            ['name' => 'server_key', 'value' => 'AIzaSyA28L1--jXkpgSY9d-fsL2djBgkbBUyckE', 'site' => 'GoogleMap'],
            ['name' => 'sid', 'value' => 'ACf64f4d6b2a55e7c56b592b6dec3919ae', 'site' => 'Twillo'],
            ['name' => 'token', 'value' => 'bc887b0e7159ab5cb0945c3fc59b345a', 'site' => 'Twillo'],
            ['name' => 'from', 'value' => '+15594238858', 'site' => 'Twillo'],
            ['name' => 'server_key', 'value' => 'AIzaSyB0efJyL4VKIbR2rTcugSC_z-m3z06hjEk', 'site' => 'FCM'],
            ['name' => 'sender_id', 'value' => '253756802947', 'site' => 'FCM'],
            ['name' => 'client_id', 'value' => '1105678852897547', 'site' => 'Facebook'],
            ['name' => 'client_secret', 'value' => '64c4d6d3dc2ba3471297c17585a60aff', 'site' => 'Facebook'],
            ['name' => 'client_id', 'value' => '409845005762-u4dmgprr97dnp7t2c7b52us660mmdv57.apps.googleusercontent.com', 'site' => 'Google'],
            ['name' => 'client_secret', 'value' => 'xlMKt7ULNXaYtGA-Mf6nq0rz', 'site' => 'Google'],
            ['name' => 'sinch_key', 'value' => 'c9ea329a-d57f-4cb3-b640-a183799ba839', 'site' => 'Sinch'],
            ['name' => 'sinch_secret_key', 'value' => 'muqN5Q/zuEeZV9ZqrTTmHg==', 'site' => 'Sinch'],
            ['name' => 'service_id', 'value' => 'com.trioangle.gofer.clientid', 'site' => 'Apple'],
            ['name' => 'team_id', 'value' => 'W89HL6566S', 'site' => 'Apple'],
            ['name' => 'key_id', 'value' => 'C3M97888J3', 'site' => 'Apple'],
            ['name' => 'database_url', 'value' => 'https://gofer-c7ed5.firebaseio.com', 'site' => 'Firebase'],
            ['name' => 'service_account', 'value' => '/resources/credentials/service_account.json', 'site' => 'Firebase'],
        ]);
    }
}
