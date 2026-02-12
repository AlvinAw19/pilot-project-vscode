<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Products seed.
 */
class ProductsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * @return void
     */
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        // seller_id 2 = Alice Seller, seller_id 3 = Bob Seller
        // Categories: 1=Electronics, 2=Computers, 3=Gaming, 4=Books, 5=Fashion,
        //   6=Home & Kitchen, 7=Sports & Outdoors, 8=Beauty & Health,
        //   9=Toys & Kids, 10=Automotive, 11=Pet Supplies, 12=Others
        $baseProducts = [
            [
                'category_id' => 1, 'seller_id' => 2,
                'name' => 'iPhone 15 Pro Max 256GB', 'slug' => 'iphone-15-pro-max-256gb',
                'description' => 'Latest iPhone with Pro camera system, A17 Pro chip, and titanium design. Capture stunning photos and videos with advanced computational photography.',
                'image_link' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=400&h=300&fit=crop', 'stock' => 25, 'price' => 1199.00,
                'created' => $now, 'modified' => $now,
            ],
            [
                'category_id' => 1, 'seller_id' => 2,
                'name' => 'Sony WH-1000XM5 Headphones', 'slug' => 'sony-wh-1000xm5-headphones',
                'description' => 'Industry-leading noise canceling headphones with 30-hour battery life and premium sound quality.',
                'image_link' => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=400&h=300&fit=crop', 'stock' => 30, 'price' => 349.00,
                'created' => $now, 'modified' => $now,
            ],
            [
                'category_id' => 2, 'seller_id' => 2,
                'name' => 'MacBook Pro 16-inch M3 Max', 'slug' => 'macbook-pro-16-inch-m3-max',
                'description' => 'Powerful laptop for professional creators with M3 Max chip, 32GB RAM, and stunning Liquid Retina XDR display.',
                'image_link' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=400&h=300&fit=crop', 'stock' => 10, 'price' => 3499.00,
                'created' => $now, 'modified' => $now,
            ],
            [
                'category_id' => 3, 'seller_id' => 3,
                'name' => 'PlayStation 5 Console', 'slug' => 'playstation-5-console',
                'description' => 'Next-generation gaming console with ultra-high speed SSD, Ray Tracing, and 4K gaming. Includes DualSense controller.',
                'image_link' => 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=400&h=300&fit=crop', 'stock' => 8, 'price' => 499.00,
                'created' => $now, 'modified' => $now,
            ],
            [
                'category_id' => 4, 'seller_id' => 3,
                'name' => 'The Midnight Library by Matt Haig', 'slug' => 'the-midnight-library',
                'description' => 'A novel about all the choices that go into a life well lived, from the internationally bestselling author.',
                'image_link' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400&h=300&fit=crop', 'stock' => 40, 'price' => 16.99,
                'created' => $now, 'modified' => $now,
            ],
            [
                'category_id' => 5, 'seller_id' => 3,
                'name' => 'Nike Air Max 270', 'slug' => 'nike-air-max-270',
                'description' => 'Revolutionary Air Max shoe with visible Air unit and sleek design. Maximum comfort for all-day wear.',
                'image_link' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&h=300&fit=crop', 'stock' => 35, 'price' => 150.00,
                'created' => $now, 'modified' => $now,
            ],
            [
                'category_id' => 6, 'seller_id' => 2,
                'name' => 'KitchenAid Stand Mixer', 'slug' => 'kitchenaid-stand-mixer',
                'description' => '5-quart tilt-head stand mixer with 10 speeds and multiple attachments. Perfect for baking enthusiasts.',
                'image_link' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=300&fit=crop', 'stock' => 12, 'price' => 379.00,
                'created' => $now, 'modified' => $now,
            ],
            [
                'category_id' => 7, 'seller_id' => 3,
                'name' => 'Peloton Bike+', 'slug' => 'peloton-bike-plus',
                'description' => 'Premium indoor cycling bike with 24" HD touchscreen, live and on-demand classes, and advanced metrics.',
                'image_link' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=300&fit=crop', 'stock' => 3, 'price' => 2495.00,
                'created' => $now, 'modified' => $now,
            ],
            [
                'category_id' => 8, 'seller_id' => 2,
                'name' => 'CeraVe Moisturizing Cream', 'slug' => 'cerave-moisturizing-cream',
                'description' => 'Dermatologist-recommended moisturizer with ceramides and hyaluronic acid for 24-hour hydration.',
                'image_link' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=400&h=300&fit=crop', 'stock' => 50, 'price' => 18.99,
                'created' => $now, 'modified' => $now,
            ],
            [
                'category_id' => 9, 'seller_id' => 3,
                'name' => 'LEGO Creator 3-in-1 Deep Sea', 'slug' => 'lego-creator-3in1-deep-sea',
                'description' => 'Build an angler fish, swimming crab, or squid with this fun 3-in-1 set. Perfect for creative builders.',
                'image_link' => 'https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?w=400&h=300&fit=crop', 'stock' => 20, 'price' => 12.99,
                'created' => $now, 'modified' => $now,
            ],
        ];

        // Generate 100 additional products
        $additionalProducts = [];
        $categories = range(1, 12); // 12 categories
        $sellers = [2, 3]; // Alice, Bob
        $productTemplates = [
            1 => ['Samsung Galaxy S24', 'Google Pixel 8 Pro', 'AirPods Pro 2', 'Bose QuietComfort Ultra', 'Canon EOS R6', 'Apple Watch Series 9', 'JBL Charge 5 Speaker', 'Anker PowerBank 20K', 'Logitech MX Master 3S', 'Samsung Galaxy Buds'],
            2 => ['Dell XPS 15', 'HP Spectre x360', 'Lenovo ThinkPad X1', 'ASUS ZenBook Pro', 'Microsoft Surface Pro', 'Razer Blade 15', 'Acer Predator Helios', 'MSI Creator Z16', 'LG Gram 17', 'MacBook Air M2'],
            3 => ['Nintendo Switch OLED', 'Steam Deck', 'Xbox Series X', 'Meta Quest 3', 'Gaming Keyboard RGB', 'Razer DeathAdder Mouse', 'Elgato Stream Deck', 'HyperX Cloud III', 'Corsair Void RGB Headset', 'Nintendo Switch Lite'],
            4 => ['Atomic Habits', 'Sapiens', 'The Pragmatic Programmer', 'Clean Code', 'Dune', 'Project Hail Mary', 'Educated', 'Thinking Fast and Slow', 'The Alchemist', 'Design Patterns'],
            5 => ['Levi\'s 501 Jeans', 'Adidas Ultraboost 22', 'Ray-Ban Aviator Sunglasses', 'North Face Puffer Jacket', 'Converse Chuck Taylor', 'Gucci Leather Belt', 'Zara Midi Dress', 'Uniqlo Down Jacket', 'New Balance 574', 'H&M Cashmere Sweater'],
            6 => ['Instant Pot Duo 7-in-1', 'Dyson V15 Vacuum', 'Nespresso Vertuo Plus', 'Le Creuset Dutch Oven', 'iRobot Roomba', 'Air Fryer Pro', 'Vitamix Blender', 'Philips Hue Starter Kit', 'IKEA KALLAX Shelf', 'Cuisinart Coffee Maker'],
            7 => ['Yoga Mat Premium', 'Bowflex Dumbbells', 'Hydro Flask Bottle', 'Osprey Backpack 40L', 'Garmin Fenix 7 Watch', 'Coleman Tent 4-Person', 'TRX Suspension Trainer', 'Resistance Bands Set', 'Foam Roller', 'Jump Rope Pro'],
            8 => ['The Ordinary Niacinamide', 'La Mer Moisturizer', 'Dyson Airwrap', 'Oral-B iO Toothbrush', 'Theragun Mini', 'SK-II Essence', 'Olaplex Hair Kit', 'Neutrogena Sunscreen SPF70', 'Laneige Lip Mask', 'Vitamin C Serum'],
            9 => ['Hot Wheels Track Set', 'Barbie Dreamhouse', 'Pokemon Cards Booster', 'Magnetic Building Blocks', 'Baby Monitor Pro', 'Graco Car Seat 4-in-1', 'Play-Doh Mega Set', 'Nerf Elite Blaster', 'Fisher-Price Walker', 'Crayola Art Set 150pc'],
            10 => ['Michelin Pilot Sport Tires', 'Chemical Guys Wash Kit', 'Dash Cam Pro 4K', 'Car Phone Mount', 'LED Interior Lights', 'Tire Inflator Portable', 'Car Vacuum Cleaner', 'Seat Covers Premium', 'Windshield Sun Shade', 'Car Air Freshener Set'],
            11 => ['Royal Canin Dog Food', 'Cat Tree Tower', 'Automatic Pet Feeder', 'Dog Harness No-Pull', 'Cat Litter Self-Clean', 'Pet Bed Orthopedic', 'Dog Chew Toys Pack', 'Fish Tank Starter Kit', 'Pet Grooming Kit', 'Bird Cage Deluxe'],
            12 => ['Desk Lamp LED', 'Wall Clock Modern', 'Scented Candle Set', 'Throw Blanket Sherpa', 'Picture Frame Set', 'Desk Organizer Wood', 'Portable Fan USB', 'Plant Pot Ceramic', 'Stainless Steel Bottle', 'Umbrella Windproof']
        ];

        // Unsplash images per category
        $imageUrls = [
            1 => ['https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1565849904461-04a58ad377e0?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1589492477829-5e65395b66cc?w=400&h=300&fit=crop'],
            2 => ['https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1587614295999-6c1f4fcfb2e9?w=400&h=300&fit=crop'],
            3 => ['https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1511512578047-dfb367046420?w=400&h=300&fit=crop'],
            4 => ['https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400&h=300&fit=crop'],
            5 => ['https://images.unsplash.com/photo-1445205170230-053b83016050?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400&h=300&fit=crop'],
            6 => ['https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400&h=300&fit=crop'],
            7 => ['https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=400&h=300&fit=crop'],
            8 => ['https://images.unsplash.com/photo-1556228720-195a672e8a03?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=400&h=300&fit=crop'],
            9 => ['https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1544569226-44165ff6e324?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1555252333-9f8e92e65df9?w=400&h=300&fit=crop'],
            10 => ['https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=400&h=300&fit=crop'],
            11 => ['https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1601758228041-f3b2795255f1?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=400&h=300&fit=crop'],
            12 => ['https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1455390582262-044cdead277a?w=400&h=300&fit=crop', 'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?w=400&h=300&fit=crop']
        ];

        for ($i = 1; $i <= 100; $i++) {
            $categoryId = $categories[array_rand($categories)];
            $sellerId = $sellers[array_rand($sellers)];
            $productName = $productTemplates[$categoryId][array_rand($productTemplates[$categoryId])] . ' #' . $i;
            $slug = strtolower(str_replace([' ', "'", '"', '#', '&'], ['-', '', '', '', 'and'], $productName));
            $price = rand(10, 500) + (rand(0, 99) / 100);
            $stock = rand(5, 200);
            $image_link = $imageUrls[$categoryId][array_rand($imageUrls[$categoryId])];
            
            $additionalProducts[] = [
                'category_id' => $categoryId,
                'seller_id' => $sellerId,
                'name' => $productName,
                'slug' => $slug,
                'description' => "Premium quality {$productName}. Exceptional performance and durability with outstanding features.",
                'image_link' => $image_link,
                'stock' => $stock,
                'price' => $price,
                'created' => $now,
                'modified' => $now,
            ];
        }

        $data = array_merge($baseProducts, $additionalProducts);

        $table = $this->table('products');
        $table->insert($data)->save();
    }
}
