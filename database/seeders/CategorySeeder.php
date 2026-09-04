<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $parents = [
            ['name' => 'Elektronik & Gadget', 'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
            ['name' => 'TV & Peralatan Elektronik Rumah', 'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
            ['name' => 'Peralatan Dapur', 'icon' => 'M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A2.704 2.704 0 003 15.546V12a9 9 0 0118 0v3.546z'],
            ['name' => 'Fashion Pria', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ['name' => 'Fashion Wanita', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ['name' => 'Fashion Muslim', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
            ['name' => 'Kesehatan & Kecantikan', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0zM12 8a4 4 0 100 8 4 4 0 000-8z'],
            ['name' => 'Ibu, Bayi & Anak', 'icon' => 'M12 4.354a4 4 0 110 7.292 4 4 0 010-7.292zM15 21H9v-2a4 4 0 014-4h0a4 4 0 014 4v2z'],
            ['name' => 'Rumah & Living', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['name' => 'Otomotif', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
            ['name' => 'Olahraga & Outdoor', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
            ['name' => 'Makanan & Minuman', 'icon' => 'M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A2.704 2.704 0 003 15.546V12a9 9 0 0118 0v3.546z'],
            ['name' => 'Hobi & Koleksi', 'icon' => 'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['name' => 'Buku & Alat Tulis', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
            ['name' => 'Mainan & Games', 'icon' => 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664zM21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['name' => 'Hewan Peliharaan', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
            ['name' => 'Fotografi & Videografi', 'icon' => 'M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z M15 13a3 3 0 11-6 0 3 3 0 016 0z'],
            ['name' => 'Industri & Peralatan', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
        ];

        $subcategories = [
            'Elektronik & Gadget' => [
                'Smartphone', 'iPhone', 'Android', 'Samsung', 'Xiaomi', 'OPPO', 'Vivo',
                'Realme', 'Infinix', 'Tecno', 'Huawei', 'Honor', 'ASUS', 'Google Pixel',
                'Nothing Phone', 'Gaming Phone', 'Feature Phone',
                'Tablet', 'iPad', 'Tablet Android', 'Windows Tablet', 'E-Reader',
                'Laptop', 'Gaming Laptop', 'Business Laptop', 'Ultrabook', 'MacBook', 'Chromebook',
                'Komputer Desktop', 'PC Gaming', 'Mini PC', 'All-in-One PC',
                'Monitor', 'Monitor Gaming', 'Monitor Ultrawide',
                'Komponen PC', 'Processor', 'Motherboard', 'VGA / GPU', 'RAM', 'SSD', 'HDD',
                'Power Supply', 'PC Case', 'CPU Cooler', 'Case Fan', 'Thermal Paste',
                'Keyboard', 'Mouse', 'Keyboard Gaming', 'Mouse Gaming', 'Mouse Pad',
                'Webcam', 'USB Hub', 'Laptop Stand', 'Cooling Pad', 'Docking Station',
                'Printer', 'Scanner', 'Printer Ink', 'Toner',
                'Storage', 'Flashdisk', 'Memory Card', 'External HDD', 'External SSD',
                'Router', 'WiFi Router', 'Mesh WiFi', 'Modem', 'Access Point', 'LAN Cable',
                'CCTV', 'Smart CCTV', 'IP Camera',
                'Smartwatch', 'Smart Band', 'Apple Watch', 'Samsung Galaxy Watch',
                'Headphone', 'Headphone Gaming', 'Headphone Bluetooth', 'Earphone',
                'TWS', 'Earbuds', 'Bluetooth Speaker', 'Portable Speaker', 'Soundbar',
                'Kamera', 'Kamera Mirrorless', 'Kamera DSLR', 'Action Camera', 'Drone',
                'Lensa Kamera', 'Tripod Kamera', 'Gimbal Kamera',
                'Console', 'PlayStation', 'Xbox', 'Nintendo', 'Game Controller', 'Game Accessories',
                'Powerbank', 'Wireless Powerbank', 'Fast Charging Powerbank', 'Power Station',
                'Charger', 'Fast Charger', 'Wireless Charger', 'Car Charger',
                'Kabel Data', 'USB Cable', 'Lightning Cable', 'USB-C Cable',
                'Aksesoris Smartphone', 'Phone Case', 'Screen Protector', 'Phone Holder', 'Ring Holder',
                'Aksesoris Laptop', 'Laptop Sleeve', 'Laptop Bag', 'Laptop Charger',
                'Aksesoris Komputer', 'Mouse Pad Gaming', 'Webcam HD',
                'UPS', 'Battery', 'Adapter', 'OTG',
            ],
            'TV & Peralatan Elektronik Rumah' => [
                'Smart TV', 'Televisi', 'LED TV', 'OLED TV', 'QLED TV', 'Android TV',
                'Home Theater', 'Soundbar', 'TV Box', 'Streaming Device', 'Remote Control',
                'Proyektor', 'Projector Mini', 'Screen Proyektor',
                'Kulkas', 'Kulkas 2 Pintu', 'Kulkas Mini', 'Freezer',
                'Mesin Cuci', 'Mesin Cuci Front Load', 'Mesin Cuci Top Load',
                'AC', 'AC Split', 'AC Portable', 'AC Window',
                'Kipas Angin', 'Kipas Angin Portable', 'Kipas Angin Berdiri',
                'Vacuum Cleaner', 'Robot Vacuum', 'Hand Vacuum',
                'Setrika', 'Setrika Uap',
                'Dispenser', 'Water Dispenser', 'Water Heater',
                'Air Purifier', 'Humidifier',
            ],
            'Peralatan Dapur' => [
                'Rice Cooker', 'Rice Cooker Multi', 'Slow Cooker',
                'Air Fryer', 'Air Fryer Oven',
                'Microwave', 'Microwave Oven',
                'Oven', 'Oven Listrik', 'Toaster Oven',
                'Kompor', 'Kompor Induksi', 'Kompor Gas', 'Kompor Portable',
                'Blender', 'Blender Portable', 'Blender Multi',
                'Mixer', 'Stand Mixer', 'Hand Mixer',
                'Coffee Maker', 'Espresso Machine', 'French Press', 'Pour Over',
                'Juicer', 'Slow Juicer',
                'Peralatan Masak', 'Panci', 'Wajan', 'Pisau Dapur', 'Talenan',
                'Peralatan Makan', 'Piring', 'Mangkuk', 'Gelas', 'Sendok',
            ],
            'Fashion Pria' => [
                'Kaos', 'Kaos Polos', 'Kaos Graphic',
                'Kemeja', 'Kemeja Formal', 'Kemeja Casual',
                'Polo Shirt',
                'Jaket', 'Jaket Denim', 'Jaket Parasut', 'Jaket Kulit', 'Hoodie',
                'Sweater', 'Hoodie Pria',
                'Celana', 'Celana Chino', 'Celana Formal',
                'Jeans', 'Jeans Slim Fit', 'Jeans Regular',
                'Celana Pendek', 'Celana Pendek Cargo',
                'Pakaian Dalam', 'Kaos Dalam', 'Celana Dalam',
                'Sepatu Pria', 'Sneakers Pria', 'Formal Pria', 'Boots Pria', 'Sandals Pria',
                'Sandal Pria', 'Flip Flop Pria',
                'Tas Pria', 'Tas Ransel Pria', 'Tas Selempang Pria', 'Tas Laptop',
                'Jam Tangan Pria', 'Jam Tangan Digital', 'Jam Tangan Analog',
                'Aksesoris Pria', 'Gelang Pria', 'Kacamata Pria', 'Dompet Pria',
            ],
            'Fashion Wanita' => [
                'Dress', 'Dress Casual', 'Dress Formal', 'Maxi Dress',
                'Blouse', 'Blouse Casual', 'Blouse Kantor',
                'Kaos Wanita', 'Kaos Polos Wanita',
                'Kemeja Wanita',
                'Jaket Wanita', 'Cardigan', 'Hoodie Wanita',
                'Rok', 'Rok Mini', 'Rok Midi', 'Rok Panjang',
                'Celana Wanita', 'Celana Chino Wanita', 'Celana Kulot',
                'Jeans Wanita', 'Skinny Jeans', 'Mom Jeans',
                'Pakaian Dalam Wanita', 'Bra', 'Celana Dalam Wanita',
                'Sepatu Wanita', 'Sneakers Wanita', 'Heels', 'Flat Shoes', 'Sandals Wanita',
                'Sandal Wanita', 'Slide Sandals',
                'Tas Wanita', 'Tas Tangan', 'Tas Ransel Wanita', 'Tas Selempang Wanita',
                'Jam Tangan Wanita', 'Jam Tangan Fashion',
                'Perhiasan', 'Kalung Wanita', 'Gelang Wanita', 'Anting', 'Cincin Wanita',
                'Aksesoris Wanita', 'Kacamata Wanita', 'Bando', 'Jepit Rambut',
            ],
            'Fashion Muslim' => [
                'Hijab', 'Hijab Segi Empat', 'Hijab Pashmina', 'Hijab Instan', 'Hijab Syar\'i',
                'Pashmina', 'Pashmina Rajut', 'Pashmina Polos',
                'Mukena', 'Mukena Travel', 'Mukena Anak',
                'Sajadah', 'Sajadah Travel', 'Sajadah Anak',
                'Baju Muslim Pria', 'Baju Koko', 'Baju Koko Lengan Pendek',
                'Baju Muslim Wanita', 'Gamis', 'Gamis Modern', 'Gamis Syar\'i',
                'Koko', 'Kemeja Koko', 'Baju Koko Bordir',
                'Gamis Pria', 'Gamis Katun',
                'Abaya', 'Abaya Modern', 'Abaya Syar\'i',
                'Sarung', 'Sarung Wadimor', 'Sarung Atlas', 'Sarung Anak',
                'Perlengkapan Haji & Umrah', 'Ihram', 'Mukena Haji', 'Sajadah Haji',
            ],
            'Kesehatan & Kecantikan' => [
                'Skincare', 'Serum', 'Moisturizer', 'Sunscreen', 'Cleanser', 'Toner',
                'Face Mask', 'Eye Cream', 'Exfoliator',
                'Makeup', 'Foundation', 'Concealer', 'Powder', 'Blush On', 'Lipstick', 'Mascara',
                'Kosmetik', 'Beauty Blender', 'Makeup Brush', 'Makeup Remover',
                'Parfum', 'Parfum Pria', 'Parfum Wanita', 'Eau de Toilette',
                'Body Care', 'Body Lotion', 'Body Wash', 'Deodorant',
                'Hair Care', 'Shampoo', 'Conditioner', 'Hair Mask', 'Hair Oil',
                'Perawatan Wajah', 'Face Serum', 'Face Wash', 'Face Toner',
                'Perawatan Tubuh', 'Body Scrub', 'Hand Cream', 'Foot Cream',
                'Perawatan Pria', 'Skincare Pria', 'Grooming',
                'Alat Kesehatan', 'Termometer', 'Tensimeter', 'Pulse Oximeter',
                'Vitamin', 'Vitamin C', 'Vitamin D', 'Multivitamin',
                'Suplemen', 'Protein', 'Fish Oil', 'Collagen',
                'Produk Kesehatan', 'Masker Medis', 'Hand Sanitizer', 'Obat Herbal',
            ],
            'Ibu, Bayi & Anak' => [
                'Popok Bayi', 'Popok Bayi Ukuran S', 'Popok Bayi Ukuran M',
                'Susu Formula', 'Susu Bayi', 'Susu Anak',
                'Makanan Bayi', 'MPASI', 'Bubur Bayi', 'Snack Bayi',
                'Perlengkapan Makan Bayi', 'Botol Susu', 'Dot', 'Bottle Warmer',
                'Perlengkapan Mandi Bayi', 'Bak Mandi Bayi', 'Shampoo Bayi',
                'Pakaian Bayi', 'Baju Bayi', 'Celana Bayi', 'Sleepwear Bayi',
                'Fashion Anak', 'Baju Anak', 'Celana Anak', 'Sepatu Anak',
                'Mainan Bayi', 'Baby Walker', 'Baby Carrier', 'Gymini',
                'Stroller', 'Stroller Compact', 'Stroller Jogging',
                'Car Seat', 'Car Seat Bayi', 'Car Seat Anak',
                'Perlengkapan Ibu', 'Breast Pump', 'Nursing Pillow', 'Maternity Wear',
                'Kehamilan & Menyusui', 'Test Pack', 'Vitamin Ibu Hamil',
            ],
            'Rumah & Living' => [
                'Furniture', 'Meubel', 'Furniture Minimalis',
                'Sofa', 'Sofa Minimalis', 'Sofa Bed', 'Sofa Kulit',
                'Meja', 'Meja Kerja', 'Meja Makan', 'Meja TV', 'Meja Rias',
                'Kursi', 'Kursi Kerja', 'Kursi Makan', 'Kursi Gaming',
                'Tempat Tidur', 'Kasur', 'Kasur Spring Bed', 'Kasur Lipat', 'Bingkai Tempat Tidur',
                'Lemari', 'Lemari Pakaian', 'Lemari Dapur', 'Lemari Sepatu',
                'Dekorasi Rumah', 'Vas', 'Jam Dinding', 'Poster', 'Wall Art',
                'Lampu', 'Lampu LED', 'Lampu Gantung', 'Lampu Meja', 'Lampu Hias',
                'Karpet', 'Karpet Bulu', 'Karpet Vinyl', 'Karpet Outdoor',
                'Gorden', 'Gorden Blackout', 'Gorden Vitrase', 'Gorden Anak',
                'Sprei', 'Sprei Katun', 'Sprei Waterproof', 'Sprei Motif',
                'Bantal', 'Bantal Tidur', 'Bantal Sofa', 'Bantal Leher',
                'Peralatan Kebersihan', 'Sapu', 'Pel', 'Ember', 'Kain Pel',
                'Organisasi Rumah', 'Rak Sepatu', 'Rak Baju', 'Storage Box',
                'Peralatan Kamar Mandi', 'Shower', 'Keran Air', 'Aksesoris Kamar Mandi',
                'Taman & Outdoor', 'Pot Tanaman', 'Rumput Sintetis', 'Outdoor Furniture',
            ],
            'Otomotif' => [
                'Motor', 'Motor Sport', 'Motor Matic', 'Motor Bebek', 'Motor Listrik',
                'Mobil', 'Mobil SUV', 'Mobil Sedan', 'Mobil MPV', 'Mobil Listrik',
                'Sparepart Motor', 'Kampas Rem Motor', 'Ban Motor', 'Oli Motor',
                'Sparepart Mobil', 'Kampas Rem Mobil', 'Filter Udara', 'Busi Mobil',
                'Oli Motor', 'Oli Mesin Motor', 'Oli Gardan', 'Oli Rem',
                'Oli Mobil', 'Oli Mesin Mobil', 'Oli Transmisi', 'Oli Rem Mobil',
                'Ban Motor', 'Ban Tubeless', 'Ban Tubetype', 'Ban Racing',
                'Ban Mobil', 'Ban Radial', 'Ban Tubeless Mobil',
                'Helm', 'Helm Full Face', 'Helm Half Face', 'Helm Modular',
                'Aksesoris Motor', 'Jok Motor', 'Spion Motor', 'Knalpot',
                'Aksesoris Mobil', 'Sarung Jok', 'Karpet Mobil', 'Parfum Mobil',
                'Audio Mobil', 'Head Unit', 'Speaker Mobil', 'Amplifier Mobil',
                'Perawatan Kendaraan', 'Cairan Perawatan', 'Wax Mobil', 'Shampoo Mobil',
                'Peralatan Bengkel', 'Kunci Ring', 'Obeng', 'Tang',
            ],
            'Olahraga & Outdoor' => [
                'Fitness', 'Dumbbell', 'Barbell', 'Resistance Band', 'Yoga Mat',
                'Gym', 'Gym Gloves', 'Weight Belt', 'Kettlebell',
                'Sepak Bola', 'Bola Sepak', 'Jersey Sepak Bola', 'Sepatu Bola',
                'Futsal', 'Bola Futsal', 'Jersey Futsal', 'Sepatu Futsal',
                'Badminton', 'Raket Badminton', 'Shuttlecock', 'Sepatu Badminton',
                'Basket', 'Bola Basket', 'Jersey Basket', 'Sepatu Basket',
                'Voli', 'Bola Voli', 'Jersey Voli',
                'Running', 'Sepatu Lari', 'Running Watch', 'Hydration Belt',
                'Sepeda', 'Sepeda Gunung', 'Sepeda Road Bike', 'Sepeda Lipat',
                'Helm Sepeda', 'Aksesoris Sepeda', 'Sarung Tangan Sepeda',
                'Camping', 'Tenda', 'Sleeping Bag', 'Matras Camping',
                'Hiking', 'Tas Hiking', 'Sepatu Hiking', 'Trekking Pole',
                'Fishing', 'Pancing', 'Joran', 'Kail',
                'Swimming', 'Kacamata Renang', 'Baju Renang', 'Topi Renang',
                'Adventure', 'Kompas', 'GPS Outdoor', 'Headlamp',
                'Sportswear', 'Jersey Olahraga', 'Celana Training', 'Jaket Olahraga',
            ],
            'Makanan & Minuman' => [
                'Makanan Ringan', 'Keripik', 'Crackers', 'Cookies', 'Biskuit',
                'Minuman', 'Minuman Ringan', 'Minuman Bersoda', 'Minuman Vitamin',
                'Kopi', 'Kopi Bubuk', 'Kopi Instan', 'Kopi Specialty', 'Kopi Beans',
                'Teh', 'Teh Hijau', 'Teh Hitam', 'Teh Herbal', 'Teh Celup',
                'Snack', 'Snack Bar', 'Trail Mix', 'Dried Fruit',
                'Makanan Instan', 'Mie Instan', 'Nasi Instan', 'Sereal',
                'Bumbu Masak', 'Bumbu Instan', 'Saus', 'Minyak Goreng',
                'Bahan Makanan', 'Tepung', 'Gula', 'Garam', 'Penyedap',
                'Produk Organik', 'Organik sayur', 'Organik buah', 'Organik beras',
                'Frozen Food', 'Sosis', 'Nugget', 'French Fries', 'Kentang Beku',
                'Makanan Tradisional', 'Rendang', 'Kerupuk', 'Kue Tradisional',
            ],
            'Hobi & Koleksi' => [
                'Action Figure', 'Action Figure Anime', 'Action Figure Marvel',
                'Diecast', 'Hot Wheels', 'Miniature Car', 'Model Kit',
                'Koleksi', 'Koin Koleksi', 'Perangko', 'Banknote',
                'Trading Card', 'Pokemon Card', 'Yu-Gi-Oh Card', 'Magic Card',
                'Merchandise', 'Merchandise Anime', 'Merchandise Band', 'Merchandise Game',
                'Anime', 'Figurine Anime', 'Poster Anime', 'Manga',
                'K-Pop', 'Album K-Pop', 'Photocards', 'Merchandise K-Pop',
                'Model Kit', 'Gunpla', 'Model Kit Tamia', 'Scale Model',
                'Barang Antik', 'Antik Vintage', 'Retro Collectible',
            ],
            'Buku & Alat Tulis' => [
                'Buku', 'Buku Bestseller', 'Buku Terbaru', 'Buku Diskon',
                'Novel', 'Novel Indonesia', 'Novel Terjemahan', 'Novel Romantis',
                'Buku Pendidikan', 'Buku SD', 'Buku SMP', 'Buku SMA', 'Buku Kuliah',
                'Buku Anak', 'Buku Cerita Anak', 'Buku Mewarnai', 'Buku Dongeng',
                'Komik', 'Komik Indonesia', 'Komik Jepang', 'Manhwa',
                'Alat Tulis', 'Pensil', 'Pulpen', 'Spidol', 'Penghapus',
                'Stationery', 'Kertas', 'Buku Tulis', 'Buku Sketsa', 'Clipboard',
                'Perlengkapan Sekolah', 'Tas Sekolah', 'Pensil Case', 'Penggaris',
                'Perlengkapan Kantor', 'Stapler', 'Lem Kertas', 'Binder', 'Map',
            ],
            'Mainan & Games' => [
                'Mainan Anak', 'Mainan Edukasi', 'Mainan Outdoor', 'Mainan Balok',
                'Board Game', 'Monopoly', 'Uno', 'Card Game', 'Board Game Strategy',
                'Puzzle', 'Puzzle Kayu', 'Puzzle 3D', 'Puzzle Jigsaw',
                'LEGO', 'LEGO City', 'LEGO Technic', 'LEGO Star Wars', 'LEGO Friends',
                'Remote Control', 'RC Car', 'RC Drone', 'RC Boat',
                'Mainan Edukasi', 'STEM Toy', 'Coding Toy', 'Science Kit',
                'Video Games', 'PS5 Games', 'Xbox Games', 'Nintendo Games',
            ],
            'Hewan Peliharaan' => [
                'Makanan Kucing', 'Dry Cat Food', 'Wet Cat Food', 'Cat Treats',
                'Makanan Anjing', 'Dry Dog Food', 'Wet Dog Food', 'Dog Treats',
                'Perlengkapan Kucing', 'Kandang Kucing', 'Cat Litter', 'Scratching Post',
                'Perlengkapan Anjing', 'Kandang Anjing', 'Leash Anjing', 'Dog Collar',
                'Aquarium', 'Aquarium Air Tawar', 'Aquarium Laut', 'Filter Aquarium',
                'Ikan Hias', 'Ikan Cupang', 'Ikan Mas Koki', 'Ikan Guppy',
                'Burung', 'Makanan Burung', 'Kandang Burung', 'Sangkar Burung',
                'Hewan Peliharaan Lainnya', 'Hamster', 'Kelinci', 'Kura-kura',
            ],
            'Fotografi & Videografi' => [
                'Kamera', 'Kamera Mirrorless', 'Kamera DSLR', 'Kamera Compact',
                'Lensa', 'Lensa Wide', 'Lensa Tele', 'Lensa Prime', 'Lensa Macro',
                'Tripod', 'Tripod Carbon', 'Tripod Mini', 'Monopod',
                'Lighting', 'Ring Light', 'Softbox', 'LED Panel', 'Flash',
                'Mikrofon', 'Mic Shotgun', 'Mic Wireless', 'Mic Lavalier',
                'Gimbal', 'Gimbal Camera', 'Gimbal Phone',
                'Background Studio', 'Backdrop', 'Green Screen', 'Light Stand',
                'Aksesoris Kamera', 'Memory Card Kamera', 'Battery Kamera', 'Camera Bag',
            ],
            'Industri & Peralatan' => [
                'Peralatan Teknik', 'Alat Listrik', 'Multitester', 'Tangga',
                'Power Tools', 'Bor Listrik', 'Gerinda', 'Mesin Potong', 'Mesin Las',
                'Hand Tools', 'Kunci Inggris', 'Kunci Pas', 'Obeng Set', 'Tang Set',
                'Alat Ukur', 'Meteran', 'Waterpass', 'Infrared Thermometer', 'Caliper',
                'Safety Equipment', 'Helm Proyek', 'Sepatu Safety', 'Sarung Tangan', 'Masker Safety',
                'Mesin', 'Mesin Bordir', 'Mesin Jahit', 'Mesin Cetak',
                'Peralatan Bengkel', 'Kunci Ring Set', 'Dongkrak', 'Trikot',
            ],
        ];

        // Step 1: Create or find parent categories
        $parentIds = [];
        foreach ($parents as $p) {
            $slug = Str::slug($p['name']);
            $existing = Category::where('slug', $slug)->first();
            if ($existing) {
                $existing->update([
                    'icon' => $p['icon'],
                    'parent_id' => null,
                    'is_active' => true,
                ]);
                $parentIds[$p['name']] = $existing->id;
            } else {
                $cat = Category::create([
                    'name' => $p['name'],
                    'slug' => $slug,
                    'icon' => $p['icon'],
                    'is_active' => true,
                ]);
                $parentIds[$p['name']] = $cat->id;
            }
        }

        // Step 2: Map old flat categories (IDs 1-8) to their new parent
        $existingMap = [
            1  => 'Elektronik & Gadget',   // Smartphone
            2  => 'Elektronik & Gadget',   // iPhone
            3  => 'Elektronik & Gadget',   // Android
            4  => 'Elektronik & Gadget',   // Laptop
            5  => 'Elektronik & Gadget',   // Smartwatch
            6  => 'Elektronik & Gadget',   // Audio General → Audio parent
            7  => 'Elektronik & Gadget',   // Aksesoris
            8  => 'Elektronik & Gadget',   // Powerbank
        ];

        foreach ($existingMap as $oldId => $parentName) {
            $cat = Category::find($oldId);
            if ($cat && isset($parentIds[$parentName])) {
                $cat->update(['parent_id' => $parentIds[$parentName]]);
            }
        }

        // Step 3: Also move any existing children from old seeder to their correct parents
        $oldParentSlugs = [
            'smartphone-tablet' => 'Elektronik & Gadget',
            'laptop-computer' => 'Elektronik & Gadget',
            'computer-components' => 'Elektronik & Gadget',
            'computer-accessories' => 'Elektronik & Gadget',
            'gaming' => 'Elektronik & Gadget',
            'wearable' => 'Elektronik & Gadget',
            'camera-photography' => 'Fotografi & Videografi',
            'tv-home-entertainment' => 'TV & Peralatan Elektronik Rumah',
            'accessories' => 'Elektronik & Gadget',
            'power-charging' => 'Elektronik & Gadget',
            'networking' => 'Elektronik & Gadget',
            'smart-home' => 'Rumah & Living',
            'office-printing' => 'Elektronik & Gadget',
            'automotive-technology' => 'Otomotif',
            'electronics-other' => 'Industri & Peralatan',
        ];

        foreach ($oldParentSlugs as $oldSlug => $newParentName) {
            $oldParent = Category::where('slug', $oldSlug)->whereNull('parent_id')->first();
            if ($oldParent && isset($parentIds[$newParentName])) {
                // Move children to new parent
                Category::where('parent_id', $oldParent->id)->update(['parent_id' => $parentIds[$newParentName]]);
                // Delete old empty parent
                if ($oldParent->products()->count() === 0 && $oldParent->children()->count() === 0) {
                    $oldParent->delete();
                }
            }
        }

        // Step 3b: Ensure legacy slugs exist as children for ProductSeeder compatibility
        // ProductSeeder looks up: smartphone, iphone, android, laptop, smartwatch, audio, aksesoris, powerbank
        $legacySlugs = [
            'audio' => 'Audio',
        ];
        foreach ($legacySlugs as $slug => $name) {
            $exists = Category::where('slug', $slug)->first();
            if (!$exists) {
                Category::create([
                    'name' => $name,
                    'slug' => $slug,
                    'parent_id' => $parentIds['Elektronik & Gadget'],
                    'is_active' => true,
                ]);
            }
        }

        // Step 4: Create subcategories
        foreach ($subcategories as $parentName => $subs) {
            $parentId = $parentIds[$parentName] ?? null;
            if (!$parentId) continue;

            foreach ($subs as $subName) {
                $slug = Str::slug($subName);

                // Check if subcategory already exists under this parent
                $existing = Category::where('slug', $slug)->where('parent_id', $parentId)->first();
                if ($existing) continue;

                // Check if name/slug exists at all (might be an old flat category)
                $anyExisting = Category::where('slug', $slug)->first();
                if ($anyExisting) {
                    $anyExisting->update(['parent_id' => $parentId]);
                } else {
                    Category::create([
                        'name' => $subName,
                        'slug' => $slug,
                        'parent_id' => $parentId,
                        'is_active' => true,
                    ]);
                }
            }
        }

        // Step 5: Deactivate empty old parent categories that weren't reassigned
        $oldSlugsToClean = ['audio'];
        foreach ($oldSlugsToClean as $slug) {
            $cat = Category::where('slug', $slug)->whereNull('parent_id')->first();
            if ($cat && $cat->products()->count() === 0 && $cat->children()->count() === 0) {
                $cat->delete();
            }
        }
    }
}
