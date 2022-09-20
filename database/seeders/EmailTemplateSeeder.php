<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\EmailTemplate::factory()->create([
            'client_id' => '1',
            'project_id' => '1',
            'from_email' => 'js.akash.patel@gmail.com',
            'to_email' => 'js.darshan.patel@gmail.com',
            'cc' => 'jencysoftware@gmail.com',
            'bcc' => 'hr.jencysoftware@gmail.com',
            'subject' => 'Test Mail',
            'attechment' => 'files',
            'template' => 'Email',
            'template_value' => '10'
        ]);
    }
}
