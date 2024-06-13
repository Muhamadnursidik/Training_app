<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Tools\Appconfig\Model;

class InitConfig extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::truncate();
        $arrayVar = [
            [
                "key" => "app_name",
                "label" => "Nama Aplikasi",
                "tipe" => "string",
                "options" => "",
                "fileallow" => "",
                "value" => "TRAINING APLIKASI",
            ],
            [
                "key" => "app_sort_name",
                "label" => "Nama Singkatan Aplikasi",
                "tipe" => "string",
                "options" => "",
                "fileallow" => "",
                "value" => "TRAINING",
            ],
            [
                "key" => "upload_allowed",
                "label" => "File Upload yang Diperbolehkan Pada Form",
                "tipe" => "string",
                "options" => "",
                "fileallow" => "",
                "value" => ".pdf,.doc,.docx,.xls,.xlsx,.txt,.pptx,.ppt,.jpeg,.jpg,.png",
            ],
            [
                "key" => "upload_allowed_valid",
                "label" => "File Upload yang Diperbolehkan Untuk Validasi",
                "tipe" => "string",
                "options" => "",
                "fileallow" => "",
                "value" => "pdf,doc,docx,xls,xlsx,txt,pptx,ppt,jpeg,jpg,png",
            ],
            [
                "key" => "upload_max_size_(Kb)",
                "label" => "Ukuran Max. File Upload (Kb)",
                "tipe" => "string",
                "options" => "",
                "fileallow" => "",
                "value" => "20000",
            ],
            [
                "key" => "app_desc",
                "label" => "Deskripsi Aplikasi",
                "tipe" => "string",
                "options" => "",
                "fileallow" => "",
                "value" =>"Training Aplikasi",
            ],
            [
                "key" => "client_name",
                "label" => "Nama Client",
                "tipe" => "string",
                "options" => "",
                "fileallow" => "",
                "value" => "PT Bank xxx",
            ],
            [
                "key" => "storage_via",
                "label" => "Penyimpanan",
                "tipe" => "string",
                "options" => "",
                "fileallow" => "",
                "value" => "local",
            ],
            [
                "key" => "send_email",
                "label" => "Pengiriman Email",
                "tipe" => "boolean",
                "options" => "",
                "fileallow" => "",
                "value" => "false",
            ],
            [
                "key" => "single_device",
                "label" => "Satu Device",
                "tipe" => "boolean",
                "options" => "",
                "fileallow" => "",
                "value" => "false",
            ],
            [
                "key" => "login_max_attemp",
                "label" => "Max. Gagal Login",
                "tipe" => "string",
                "options" => "",
                "fileallow" => "",
                "value" => "3",
            ],
            [
                "key" => "login_with_captcha",
                "label" => "Login Menggunakan Captcha",
                "tipe" => "boolean",
                "options" => "",
                "fileallow" => "",
                "value" => "false",
            ],
            [
                "key" => "login_auth_with",
                "label" => "Login Menggunakan",
                "tipe" => "string",
                "options" => "",
                "fileallow" => "",
                "value" => "username",
            ],
            [
                "key" => "auto_backup_db",
                "label" => "Auto Backup Database",
                "tipe" => "boolean",
                "options" => "",
                "fileallow" => "",
                "value" => "false",
            ],
            [
                "key" => "auto_backup_period",
                "label" => "Periode Backup Database",
                "tipe" => "dropdown",
                "options" => "weekly;monthly",
                "fileallow" => "",
                "value" => "monthly",
            ],
            [
                "key" => "logo_file",
                "label" => "Upload Logo (.png)",
                "tipe" => "upload",
                "options" => "",
                "fileallow" => ".png",
                "value" => "logo.png",
            ],
            [
                "key" => "icon_file",
                "label" => "Upload Icon (.icon)",
                "tipe" => "upload",
                "options" => "",
                "fileallow" => ".ico",
                "value" => "",
            ],
        ];

        Model::createOne([
            'code' => 'data',
            'config' => json_encode($arrayVar),
            'status' => true
        ]);

        $this->command->line("Init Config Default");
    }
}
