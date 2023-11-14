<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $products = [
            [
                'id' => 1,
                'name' => 'cooler master mm711',
                'slug' => 'cooler-master-mm711',
                'image' => 'mouse3-cooler-master-mm711.png'
            ],
            [
                'id' => 2,
                'name' => 'LAPTOP ASUS TUF 15.6 i7-12650H SSD1TB RAM16',
                'slug' => 'LAPTOP-ASUS-TUF-15-6-i7-12650H-SSD1TB-RAM16',
                'image' => 'laptop1-i7-3070.png'
            ],
            [
                'id' => 3,
                'name' => 'LAPTOP LENOVO IP FLEX 5 14T i5-1235U SSD512 RAM8',
                'slug' => 'LAPTOP-LENOVO-IP-FLEX-5-14Ti5-1235USSD512RAM8',
                'image' => 'laptop2-lenovo.png'
            ],
            [
                'id' => 4,
                'name' => 'GPU ASUS TUF GTX1650S-O4G-GAMING',
                'slug' => 'GPU-ASUS-TUF-GTX1650S-O4G-GAMING',
                'image' => 'GPU-AS-1650S-O4-300.png'
            ],
            [
                'id' => 5,
                'name' => 'GPU GIGABYTE RTX4070-GAMING-OC-12GD REV1.0',
                'slug' => 'GPU-GIGABYTE-RTX4070-GAMING-OC-12GD',
                'image' => 'GPU-GB-4070-OC-5.png'
            ],
            [
                'id' => 6,
                'name' => 'KEYBOARD T-DAGGER BATTLESHIP T-TGK301',
                'slug' => 'KEYBOARD-T-DAGGER-BATTLESHIP-T-TGK301',
                'image' => 'KB-T-TGK301-MCA-30.png'
            ],
            [
                'id' => 7,
                'name' => 'KEYBOARD COOLERMASTER MK750 RGB MECHANICAL',
                'slug' => 'KEYBOARD-COOLERMASTER-MK750-RGB-MECHANICAL',
                'image' => 'KB-MK750-M-RED-5.png'
            ],
            [
                'id' => 8,
                'name' => 'MOUSE LENOVO M500 GAMING RGB',
                'slug' => 'MOUSE-LENOVO-M500-GAMING-RGB',
                'image' => 'MS-M500-RGB-300.png'
            ],
            [
                'id' => 9,
                'name' => 'MICROPHONE SILVERLINE MM-202 MULTIMEDIA',
                'slug' => 'MICROPHONE-SILVERLINE-MM-202-MULTIMEDIA',
                'image' => 'MIC-SL-MM-202-30.png'
            ],
        ];

        foreach ($products as $product) {
            Product::create([
                'id' =>  $product['id'],
                'name' =>  $product['name'],
                'slug' =>  $product['slug'],
                'image' =>  $product['image'],
            ]);
        }
    }
}
