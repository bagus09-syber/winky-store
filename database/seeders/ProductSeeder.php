<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $smartphone = Category::where('slug', 'smartphone')->first();
        $iphone = Category::where('slug', 'iphone')->first();
        $android = Category::where('slug', 'android')->first();
        $laptop = Category::where('slug', 'laptop')->first();
        $smartwatch = Category::where('slug', 'smartwatch')->first();
        $audio = Category::where('slug', 'audio')->first();
        $aksesoris = Category::where('slug', 'aksesoris')->first();
        $powerbank = Category::where('slug', 'powerbank')->first();

        $apple = Brand::where('slug', 'apple')->first();
        $samsung = Brand::where('slug', 'samsung')->first();
        $xiaomi = Brand::where('slug', 'xiaomi')->first();
        $oppo = Brand::where('slug', 'oppo')->first();
        $vivo = Brand::where('slug', 'vivo')->first();
        $asus = Brand::where('slug', 'asus')->first();
        $lenovo = Brand::where('slug', 'lenovo')->first();
        $anker = Brand::where('slug', 'anker')->first();
        $spigen = Brand::where('slug', 'spigen')->first();

        // ─── 1. iPhone 16 Pro Max ─────────────────────────
        $p = Product::create([
            'category_id' => $iphone->id,
            'brand_id' => $apple->id,
            'name' => 'iPhone 16 Pro Max',
            'slug' => 'iphone-16-pro-max',
            'sku' => 'APL-IP16PM-001',
            'short_description' => 'iPhone terbaru dengan chip A18 Pro, kamera 48MP, dan desain titanium.',
            'description' => 'iPhone 16 Pro Max hadir dengan chip A18 Pro yang paling canggih, layar Super Retina XDR 6.9 inci, kamera utama 48MP dengan teknologi Fusion, dan desain titanium yang ringan dan tahan lama. Baterai tahan sepanjang hari dan mendukung USB-C.',
            'price' => 19999000,
            'sale_price' => 15999000,
            'stock' => 50,
            'image' => 'iphone-16-pro-max.jpg',
            'is_featured' => true,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '256GB - Natural Titanium',
            'sku' => 'APL-IP16PM-256-NT',
            'price' => 15999000,
            'stock' => 15,
            'attributes' => ['storage' => '256GB', 'color' => 'Natural Titanium', 'ram' => '8GB'],
        ]);
        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '512GB - Desert Titanium',
            'sku' => 'APL-IP16PM-512-DT',
            'price' => 18999000,
            'stock' => 20,
            'attributes' => ['storage' => '512GB', 'color' => 'Desert Titanium', 'ram' => '8GB'],
        ]);
        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '1TB - Black Titanium',
            'sku' => 'APL-IP16PM-1TB-BT',
            'price' => 21999000,
            'stock' => 15,
            'attributes' => ['storage' => '1TB', 'color' => 'Black Titanium', 'ram' => '8GB'],
        ]);

        // ─── 2. Samsung Galaxy S24 Ultra ──────────────────
        $p = Product::create([
            'category_id' => $android->id,
            'brand_id' => $samsung->id,
            'name' => 'Samsung Galaxy S24 Ultra',
            'slug' => 'samsung-galaxy-s24-ultra',
            'sku' => 'SAM-S24U-001',
            'short_description' => 'Smartphone Android premium dengan S Pen, kamera 200MP, dan Galaxy AI.',
            'description' => 'Samsung Galaxy S24 Ultra adalah smartphone flagship terbaik Samsung dengan layar Dynamic AMOLED 2X 6.8 inci, kamera 200MP, S Pen built-in, dan fitur Galaxy AI yang revolusioner. Body titanium, baterai 5000mAh, dan fast charging 45W.',
            'price' => 16999000,
            'sale_price' => 13499000,
            'stock' => 40,
            'image' => 'samsung-galaxy-s24-ultra.jpg',
            'is_featured' => true,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '256GB - Titanium Black',
            'sku' => 'SAM-S24U-256-TB',
            'price' => 13499000,
            'stock' => 15,
            'attributes' => ['storage' => '256GB', 'color' => 'Titanium Black', 'ram' => '12GB'],
        ]);
        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '512GB - Titanium Gray',
            'sku' => 'SAM-S24U-512-TG',
            'price' => 15999000,
            'stock' => 15,
            'attributes' => ['storage' => '512GB', 'color' => 'Titanium Gray', 'ram' => '12GB'],
        ]);
        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '1TB - Titanium Violet',
            'sku' => 'SAM-S24U-1TB-TV',
            'price' => 18499000,
            'stock' => 10,
            'attributes' => ['storage' => '1TB', 'color' => 'Titanium Violet', 'ram' => '12GB'],
        ]);

        // ─── 3. Xiaomi 14 Ultra ───────────────────────────
        $p = Product::create([
            'category_id' => $android->id,
            'brand_id' => $xiaomi->id,
            'name' => 'Xiaomi 14 Ultra',
            'slug' => 'xiaomi-14-ultra',
            'sku' => 'XIA-14U-001',
            'short_description' => 'Smartphone kamera terbaik dengan Leica Summilux lens dan Snapdragon 8 Gen 3.',
            'description' => 'Xiaomi 14 Ultra hadir dengan kamera 50MP Leica Summilux quad-camera, layar LTPO AMOLED 6.73 inci 2K, Snapdragon 8 Gen 3, baterai 5000mAh dengan hyper charge 90W. Desain premium dengan bahan keramik.',
            'price' => 10999000,
            'sale_price' => 8999000,
            'stock' => 35,
            'image' => 'xiaomi-14-ultra.jpg',
            'is_featured' => true,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '256GB - Black',
            'sku' => 'XIA-14U-256-BK',
            'price' => 8999000,
            'stock' => 20,
            'attributes' => ['storage' => '256GB', 'color' => 'Black', 'ram' => '12GB'],
        ]);
        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '512GB - White',
            'sku' => 'XIA-14U-512-WH',
            'price' => 10499000,
            'stock' => 15,
            'attributes' => ['storage' => '512GB', 'color' => 'White', 'ram' => '16GB'],
        ]);

        // ─── 4. OPPO Find X8 Pro ──────────────────────────
        $p = Product::create([
            'category_id' => $android->id,
            'brand_id' => $oppo->id,
            'name' => 'OPPO Find X8 Pro',
            'slug' => 'oppo-find-x8-pro',
            'sku' => 'OPP-FX8P-001',
            'short_description' => 'Smartphone flagship OPPO dengan kamera Hasselblad dan AI eraser.',
            'description' => 'OPPO Find X8 Pro menghadirkan kamera dual periscope 50MP dengan Hasselblad Color Science, layar LTPO AMOLED 6.78 inci 120Hz, MediaTek Dimensity 9400, dan baterai 5910mAh. Fitur AI Magic Eraser dan Google Gemini built-in.',
            'price' => 11999000,
            'sale_price' => 9999000,
            'stock' => 30,
            'image' => 'oppo-find-x8-pro.jpg',
            'is_featured' => true,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '256GB - Space Black',
            'sku' => 'OPP-FX8P-256-SB',
            'price' => 9999000,
            'stock' => 15,
            'attributes' => ['storage' => '256GB', 'color' => 'Space Black', 'ram' => '12GB'],
        ]);
        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '512GB - Pearl White',
            'sku' => 'OPP-FX8P-512-PW',
            'price' => 11499000,
            'stock' => 15,
            'attributes' => ['storage' => '512GB', 'color' => 'Pearl White', 'ram' => '16GB'],
        ]);

        // ─── 5. Vivo X200 Pro ─────────────────────────────
        $p = Product::create([
            'category_id' => $android->id,
            'brand_id' => $vivo->id,
            'name' => 'Vivo X200 Pro',
            'slug' => 'vivo-x200-pro',
            'sku' => 'VIV-X200P-001',
            'short_description' => 'Smartphone Vivo dengan ZEISS APO telephoto dan chip Dimensity 9400.',
            'description' => 'Vivo X200 Pro dibekali kamera ZEISS APO telephoto 200MP, layar AMOLED 6.78 inci 2K 120Hz, MediaTek Dimensity 9400, dan baterai 6000mAh. Desain elegan dengan Gorilla Glass Victus 2 dan IP68 water resistance.',
            'price' => 9999000,
            'sale_price' => 8499000,
            'stock' => 30,
            'image' => 'vivo-x200-pro.jpg',
            'is_featured' => false,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '256GB - Cosmic Black',
            'sku' => 'VIV-X200P-256-CB',
            'price' => 8499000,
            'stock' => 15,
            'attributes' => ['storage' => '256GB', 'color' => 'Cosmic Black', 'ram' => '12GB'],
        ]);
        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '512GB - Sapphire Blue',
            'sku' => 'VIV-X200P-512-SB',
            'price' => 9999000,
            'stock' => 15,
            'attributes' => ['storage' => '512GB', 'color' => 'Sapphire Blue', 'ram' => '16GB'],
        ]);

        // ─── 6. ASUS ROG Phone 9 Pro ──────────────────────
        $p = Product::create([
            'category_id' => $smartphone->id,
            'brand_id' => $asus->id,
            'name' => 'ASUS ROG Phone 9 Pro',
            'slug' => 'asus-rog-phone-9-pro',
            'sku' => 'ASUS-ROG9P-001',
            'short_description' => 'Smartphone gaming terbaik dengan Snapdragon 8 Elite dan cooling system canggih.',
            'description' => 'ASUS ROG Phone 9 Pro adalah smartphone gaming paling powerful dengan Snapdragon 8 Elite, layar AMOLED 6.78 inci 185Hz, AeroActive Cooler X, dan baterai 5800mAh. Dirancang untuk gamer profesional dengan AirTrigger sensors dan RGB lighting.',
            'price' => 13999000,
            'sale_price' => null,
            'stock' => 25,
            'image' => 'asus-rog-phone-9-pro.jpg',
            'is_featured' => true,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '512GB - Phantom Black',
            'sku' => 'ASUS-ROG9P-512-PB',
            'price' => 13999000,
            'stock' => 15,
            'attributes' => ['storage' => '512GB', 'color' => 'Phantom Black', 'ram' => '24GB'],
        ]);
        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '1TB - Storm White',
            'sku' => 'ASUS-ROG9P-1TB-SW',
            'price' => 16499000,
            'stock' => 10,
            'attributes' => ['storage' => '1TB', 'color' => 'Storm White', 'ram' => '24GB'],
        ]);

        // ─── 7. MacBook Air M4 ────────────────────────────
        $p = Product::create([
            'category_id' => $laptop->id,
            'brand_id' => $apple->id,
            'name' => 'MacBook Air M4 15"',
            'slug' => 'macbook-air-m4-15',
            'sku' => 'APL-MBA15-M4-001',
            'short_description' => 'Laptop ultra tipis dengan chip M4, Liquid Retina display, dan baterai 18 jam.',
            'description' => 'MacBook Air 15 inci dengan chip M4 menawarkan performa luar biasa dalam desain ultra tipis. Layar Liquid Retina 15.3 inci, kamera 12MP Center Stage, MagSafe charging, dan baterai hingga 18 jam. Tersedia dalam Midnight, Starlight, Space Gray, dan Silver.',
            'price' => 22999000,
            'sale_price' => 19999000,
            'stock' => 30,
            'image' => 'macbook-air-m4-15.jpg',
            'is_featured' => true,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '256GB - Midnight',
            'sku' => 'APL-MBA15-256-MD',
            'price' => 19999000,
            'stock' => 10,
            'attributes' => ['storage' => '256GB', 'color' => 'Midnight', 'ram' => '16GB'],
        ]);
        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '512GB - Starlight',
            'sku' => 'APL-MBA15-512-SL',
            'price' => 22499000,
            'stock' => 10,
            'attributes' => ['storage' => '512GB', 'color' => 'Starlight', 'ram' => '16GB'],
        ]);
        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '1TB - Space Gray',
            'sku' => 'APL-MBA15-1TB-SG',
            'price' => 25499000,
            'stock' => 10,
            'attributes' => ['storage' => '1TB', 'color' => 'Space Gray', 'ram' => '24GB'],
        ]);

        // ─── 8. ASUS ROG Zephyrus G16 ─────────────────────
        $p = Product::create([
            'category_id' => $laptop->id,
            'brand_id' => $asus->id,
            'name' => 'ASUS ROG Zephyrus G16',
            'slug' => 'asus-rog-zephyrus-g16',
            'sku' => 'ASUS-ROG-G16-001',
            'short_description' => 'Laptop gaming tipis dengan RTX 4070, layar OLED 240Hz, dan Intel Core Ultra 9.',
            'description' => 'ASUS ROG Zephyrus G16 adalah laptop gaming ultra tipis dengan GPU NVIDIA RTX 4070, layar ROG Nebula OLED 16 inci 240Hz, Intel Core Ultra 9 185H, dan RAM 32GB LPDDR5X. Sistem pendingin Tri-Fan technology untuk performa maksimal.',
            'price' => 28999000,
            'sale_price' => 25999000,
            'stock' => 20,
            'image' => 'asus-rog-zephyrus-g16.jpg',
            'is_featured' => true,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $p->id,
            'name' => 'RTX 4070 - Eclipse Gray',
            'sku' => 'ASUS-ROG-G16-4070-EG',
            'price' => 25999000,
            'stock' => 10,
            'attributes' => ['gpu' => 'RTX 4070', 'color' => 'Eclipse Gray', 'ram' => '32GB', 'storage' => '1TB SSD'],
        ]);
        ProductVariant::create([
            'product_id' => $p->id,
            'name' => 'RTX 4080 - Platinum',
            'sku' => 'ASUS-ROG-G16-4080-PL',
            'price' => 33999000,
            'stock' => 10,
            'attributes' => ['gpu' => 'RTX 4080', 'color' => 'Platinum', 'ram' => '32GB', 'storage' => '2TB SSD'],
        ]);

        // ─── 9. AirPods Pro 3 ─────────────────────────────
        $p = Product::create([
            'category_id' => $audio->id,
            'brand_id' => $apple->id,
            'name' => 'AirPods Pro 3',
            'slug' => 'airpods-pro-3',
            'sku' => 'APL-APP3-001',
            'short_description' => 'Earbuds nirkabel terbaik dengan Active Noise Cancellation dan Adaptive Audio.',
            'description' => 'AirPods Pro 3 menghadirkan chip H3, Active Noise Cancellation yang lebih baik, Adaptive Audio, dan Personalized Spatial Audio. USB-C charging, baterai hingga 6 jam, dan IP54 water resistance.',
            'price' => 3999000,
            'sale_price' => 3299000,
            'stock' => 60,
            'image' => 'airpods-pro-3.jpg',
            'is_featured' => true,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $p->id,
            'name' => 'USB-C - White',
            'sku' => 'APL-APP3-USB-WH',
            'price' => 3299000,
            'stock' => 60,
            'attributes' => ['color' => 'White', 'connector' => 'USB-C'],
        ]);

        // ─── 10. Samsung Galaxy Buds3 Pro ──────────────────
        $p = Product::create([
            'category_id' => $audio->id,
            'brand_id' => $samsung->id,
            'name' => 'Samsung Galaxy Buds3 Pro',
            'slug' => 'samsung-galaxy-buds3-pro',
            'sku' => 'SAM-GB3P-001',
            'short_description' => 'Earbuds Samsung dengan Active Noise Cancellation dan 360 Audio.',
            'description' => 'Samsung Galaxy Buds3 Pro menawarkan ANC canggih, 360 Audio, Hi-Fi 24bit audio quality, dan desain baru yang lebih nyaman. IP57 water resistance dan baterai hingga 7 jam.',
            'price' => 2999000,
            'sale_price' => 2499000,
            'stock' => 50,
            'image' => 'samsung-galaxy-buds3-pro.jpg',
            'is_featured' => false,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $p->id,
            'name' => 'Silver',
            'sku' => 'SAM-GB3P-SLV',
            'price' => 2499000,
            'stock' => 25,
            'attributes' => ['color' => 'Silver'],
        ]);
        ProductVariant::create([
            'product_id' => $p->id,
            'name' => 'Blue',
            'sku' => 'SAM-GB3P-BLU',
            'price' => 2499000,
            'stock' => 25,
            'attributes' => ['color' => 'Blue'],
        ]);

        // ─── 11. Samsung Galaxy Watch Ultra ────────────────
        $p = Product::create([
            'category_id' => $smartwatch->id,
            'brand_id' => $samsung->id,
            'name' => 'Samsung Galaxy Watch Ultra',
            'slug' => 'samsung-galaxy-watch-ultra',
            'sku' => 'SAM-GWU-001',
            'short_description' => 'Smartwatch tahan banting dengan titanium case dan GPS dual-frequency.',
            'description' => 'Samsung Galaxy Watch Ultra dirancang untuk petualangan ekstrem dengan titanium grade 4, 10ATM water resistance, GPS dual-frequency, dan baterai hingga 60 jam. Fitur kesehatan lengkap termasuk血压, SpO2, dan sleep tracking.',
            'price' => 7999000,
            'sale_price' => 6499000,
            'stock' => 30,
            'image' => 'samsung-galaxy-watch-ultra.jpg',
            'is_featured' => false,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '47mm - Titanium Gray',
            'sku' => 'SAM-GWU-47-TG',
            'price' => 6499000,
            'stock' => 15,
            'attributes' => ['size' => '47mm', 'color' => 'Titanium Gray'],
        ]);
        ProductVariant::create([
            'product_id' => $p->id,
            'name' => '47mm - Silver',
            'sku' => 'SAM-GWU-47-SLV',
            'price' => 6499000,
            'stock' => 15,
            'attributes' => ['size' => '47mm', 'color' => 'Silver'],
        ]);

        // ─── 12. Anker Prime 20000mAh ─────────────────
        $p = Product::create([
            'category_id' => $powerbank->id,
            'brand_id' => $anker->id,
            'name' => 'Anker Prime 20000mAh',
            'slug' => 'anker-prime-20000mah',
            'sku' => 'ANK-PRIME-20K',
            'short_description' => 'Power bank 20000mAh dengan fast charging 65W dan layar LCD.',
            'description' => 'Anker Prime 20000mAh adalah power bank premium dengan output USB-C 65W yang mampu mengisi laptop. Layar LCD menampilkan sisa daya dan watt output. Bisa mengisi 3 device sekaligus.',
            'price' => 899000,
            'sale_price' => 699000,
            'stock' => 80,
            'image' => 'anker-prime-20000mah.jpg',
            'is_featured' => false,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $p->id,
            'name' => 'Black',
            'sku' => 'ANK-PRIME-20K-BK',
            'price' => 699000,
            'stock' => 80,
            'attributes' => ['color' => 'Black'],
        ]);

        // ─── 13. Spigen Tough Armor Case ──────────────────────────
        $p = Product::create([
            'category_id' => $aksesoris->id,
            'brand_id' => $spigen->id,
            'name' => 'Spigen Tough Armor Case iPhone 16 Pro Max',
            'slug' => 'spigen-tough-armor-iphone-16-pro-max',
            'sku' => 'SPG-TA-IP16PM',
            'short_description' => 'Case pelindung premium dengan kickstand dan perlindungan military-grade.',
            'description' => 'Spigen Tough Armor adalah case pelindung terbaik untuk iPhone 16 Pro Max dengan sertifikasi MIL-STD-810G. Terbuat dari dual-layer TPU dan polycarbonate dengan kickstand terintegrasi.',
            'price' => 499000,
            'sale_price' => 399000,
            'stock' => 100,
            'image' => 'spigen-tough-armor-iphone-16-pro-max.jpg',
            'is_featured' => false,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $p->id,
            'name' => 'Black',
            'sku' => 'SPG-TA-IP16PM-BK',
            'price' => 399000,
            'stock' => 50,
            'attributes' => ['color' => 'Black'],
        ]);
        ProductVariant::create([
            'product_id' => $p->id,
            'name' => 'Gunmetal',
            'sku' => 'SPG-TA-IP16PM-GM',
            'price' => 399000,
            'stock' => 50,
            'attributes' => ['color' => 'Gunmetal'],
        ]);

        // ─── 14. Xiaomi Smart Band 9 ──────────────────────
        $p = Product::create([
            'category_id' => $smartwatch->id,
            'brand_id' => $xiaomi->id,
            'name' => 'Xiaomi Smart Band 9',
            'slug' => 'xiaomi-smart-band-9',
            'sku' => 'XIA-SMB9-001',
            'short_description' => 'Smartband affordable dengan AMOLED display dan 150+ workout modes.',
            'description' => 'Xiaomi Smart Band 9 hadir dengan layar AMOLED 1.62 inci, 150+ workout modes, blood oxygen monitoring, heart rate tracking, dan baterai hingga 21 hari. Water resistant 5ATM.',
            'price' => 599000,
            'sale_price' => 449000,
            'stock' => 100,
            'image' => 'xiaomi-smart-band-9.jpg',
            'is_featured' => false,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $p->id,
            'name' => 'Black',
            'sku' => 'XIA-SMB9-BK',
            'price' => 449000,
            'stock' => 50,
            'attributes' => ['color' => 'Black'],
        ]);
        ProductVariant::create([
            'product_id' => $p->id,
            'name' => 'Gold',
            'sku' => 'XIA-SMB9-GD',
            'price' => 449000,
            'stock' => 50,
            'attributes' => ['color' => 'Gold'],
        ]);

        // ─── 15. Lenovo LOQ 15 ────────────────────────────
        $p = Product::create([
            'category_id' => $laptop->id,
            'brand_id' => $lenovo->id,
            'name' => 'Lenovo LOQ 15IRX9',
            'slug' => 'lenovo-loq-15irx9',
            'sku' => 'LEN-LOQ15-001',
            'short_description' => 'Laptop gaming entry-level dengan RTX 4050 dan Intel Core i7.',
            'description' => 'Lenovo LOQ 15IRX9 adalah laptop gaming entry-level yang powerful dengan NVIDIA RTX 4050, Intel Core i7-14650HX, RAM 16GB DDR5, dan layar IPS 15.6 FHD 144Hz. Sistem pendingin dual fan dengan Army AI Engine.',
            'price' => 13999000,
            'sale_price' => 11999000,
            'stock' => 25,
            'image' => 'lenovo-loq-15irx9.jpg',
            'is_featured' => false,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $p->id,
            'name' => 'RTX 4050 - Storm Grey',
            'sku' => 'LEN-LOQ15-4050-SG',
            'price' => 11999000,
            'stock' => 15,
            'attributes' => ['gpu' => 'RTX 4050', 'color' => 'Storm Grey', 'ram' => '16GB', 'storage' => '512GB SSD'],
        ]);
        ProductVariant::create([
            'product_id' => $p->id,
            'name' => 'RTX 4060 - Storm Grey',
            'sku' => 'LEN-LOQ15-4060-SG',
            'price' => 14499000,
            'stock' => 10,
            'attributes' => ['gpu' => 'RTX 4060', 'color' => 'Storm Grey', 'ram' => '16GB', 'storage' => '512GB SSD'],
        ]);
    }
}
