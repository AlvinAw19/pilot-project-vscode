<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Reviews seed.
 *
 * Only items with delivery_status = 'delivered' can be reviewed.
 *
 * Delivered order_items:
 *   ID 1 — Charlie bought Earbuds (order 1, product 1)
 *   ID 2 — Charlie bought Clean Code (order 2, product 5)
 *   ID 3 — Diana bought Mechanical Keyboard (order 3, product 3)
 *   ID 6 — Diana bought Water Bottle (order 4, product 8)
 *   ID 7 — Diana bought Pragmatic Programmer (order 4, product 6)
 *   ID 8 — Eve bought Yoga Mat (order 5, product 9)
 *
 * Not all delivered items need a review — leave some without to test the "Leave Review" button.
 */
class ReviewsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * @return void
     */
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        // Base reviews with specific comments
        $baseReviews = [
            // Charlie reviews iPhone 15 Pro Max — 5 stars
            [
                'user_id' => 4, 'product_id' => 1, 'order_item_id' => 1,
                'rating' => 5,
                'comment' => 'Absolutely stunning camera! The Pro camera system captures incredible detail and the titanium build feels premium. Battery life is exceptional - easily lasts all day with heavy use. Face ID is lightning fast. Worth every penny!',
                'image_link' => 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=600&h=400&fit=crop',
                'created' => $now, 'modified' => $now,
            ],
            // Charlie reviews The Midnight Library — 4 stars
            [
                'user_id' => 4, 'product_id' => 10, 'order_item_id' => 2,
                'rating' => 4,
                'comment' => 'Beautiful and thought-provoking story about life choices. Haig\'s writing is poetic and the concept is fascinating. Made me reflect on my own life decisions. A bit slow in the middle but the ending was worth it.',
                'image_link' => null,
                'created' => $now, 'modified' => $now,
            ],
            // Diana reviews MacBook Pro 16-inch — 5 stars
            [
                'user_id' => 5, 'product_id' => 2, 'order_item_id' => 3,
                'rating' => 5,
                'comment' => 'This laptop is a beast! M3 Max chip handles everything I throw at it - video editing, 3D rendering, coding. The display is gorgeous and the battery life is incredible. Finally made the switch from Windows and never looking back!',
                'image_link' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600&h=400&fit=crop',
                'created' => $now, 'modified' => $now,
            ],
            // Diana reviews Sony WH-1000XM5 — 4 stars
            [
                'user_id' => 5, 'product_id' => 5, 'order_item_id' => 6,
                'rating' => 4,
                'comment' => 'Best noise canceling headphones I\'ve owned. The sound quality is excellent with great bass response. Comfortable for long flights. Only complaint is they get warm after hours of use, but that\'s minor.',
                'image_link' => null,
                'created' => $now, 'modified' => $now,
            ],
            // Diana reviews Sapiens — 3 stars
            [
                'user_id' => 5, 'product_id' => 11, 'order_item_id' => 7,
                'rating' => 3,
                'comment' => 'Interesting perspective on human history, but Harari\'s conclusions feel speculative at times. Some chapters are fascinating, others feel like philosophical musings. Good read but not as groundbreaking as the hype suggests.',
                'image_link' => null,
                'created' => $now, 'modified' => $now,
            ],
            // Eve reviews PlayStation 5 — 5 stars
            [
                'user_id' => 6, 'product_id' => 3, 'order_item_id' => 8,
                'rating' => 5,
                'comment' => 'Gaming has never been this immersive! The DualSense controller feedback is incredible - you can feel different textures and impacts. Loading times are insanely fast. Spider-Man 2 looks absolutely gorgeous. Best console ever!',
                'image_link' => 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=600&h=400&fit=crop',
                'created' => $now, 'modified' => $now,
            ],
            // Eve reviews Levi\'s 501 Jeans — 4 stars
            [
                'user_id' => 6, 'product_id' => 12, 'order_item_id' => 9,
                'rating' => 4,
                'comment' => 'Classic fit and quality you expect from Levi\'s. Comfortable right out of the box and the denim is substantial. They\'ve held up well after multiple washes. The red tab is a nice touch. Great jeans!',
                'image_link' => 'https://images.unsplash.com/photo-1604176354204-9268737828e4?w=600&h=400&fit=crop',
                'created' => $now, 'modified' => $now,
            ],
        ];

        // Generate 50 additional reviews with unique order_item_ids
        $additionalReviews = [];
        $users = [4, 5, 6]; // Charlie, Diana, Eve (buyers)
        $comments = [
            5 => [
                'Absolutely incredible! Exceeded all my expectations. Best purchase I\'ve made this year!',
                'Outstanding quality and performance. Fast shipping and excellent packaging. Highly recommend!',
                'Perfect in every way! The attention to detail is amazing. Will definitely buy again.',
                'Exceptional value for money. Works flawlessly and looks beautiful. Five stars deserved!',
                'Life-changing product! The quality is unmatched and customer service was fantastic.',
                'Blown away by the quality! Every feature works perfectly. Worth every penny.',
                'Incredible craftsmanship and functionality. Arrived quickly and works like a dream.',
                'Absolutely love it! The design is gorgeous and performance is top-notch. Perfect purchase!'
            ],
            4 => [
                'Very impressed with the quality. Good value for money and arrived quickly.',
                'Solid product that does exactly what it promises. Happy with my purchase.',
                'Great quality and fast delivery. Minor issues but overall very satisfied.',
                'Good product with excellent features. Packaging was secure and delivery was prompt.',
                'Well-made and functional. Met my expectations and good customer service.',
                'Quality is better than expected. Works well and looks great. Recommended.',
                'Satisfied with the purchase. Good performance and reasonable price point.',
                'Nice product with good build quality. Fast shipping and secure packaging.'
            ],
            3 => [
                'Decent product but has some room for improvement. Does the basic job well.',
                'Average quality for the price. Works okay but nothing special to write home about.',
                'Meets expectations but doesn\'t exceed them. Fair value for money spent.',
                'Okay product with mixed feelings. Some features work well, others could be better.',
                'Not bad but not great either. Functional but lacks some polish.',
                'Mediocre performance. Does what it needs to do but feels underwhelming.',
                'Acceptable quality but overpriced for what you get. Could be better.',
                'Basic functionality works. Nothing impressive but gets the job done.'
            ],
            2 => [
                'Disappointed with the quality. Expected much better for the price paid.',
                'Poor build quality and performance issues. Not worth the money.',
                'Below average product. Had high hopes but they weren\'t met.',
                'Regret this purchase. Quality doesn\'t match the description.',
                'Not impressed at all. Cheap materials and poor craftsmanship.',
                'Waste of money. Doesn\'t work as advertised and poor customer support.',
                'Very disappointing. Looks cheap and feels flimsy. Would not recommend.',
                'Subpar quality and functionality. Expected better from this brand.'
            ],
            1 => [
                'Terrible product! Complete waste of money. Stay away from this.',
                'Worst purchase ever! Poor quality and doesn\'t work as described.',
                'Absolutely awful! Broke after first use. Horrible customer service.',
                'Complete disappointment! Cheap materials and terrible design.',
                'Do not buy this! False advertising and poor quality control.',
                'Regret buying this immediately. Worst product I\'ve ever purchased.',
                'Total garbage! Doesn\'t work and terrible customer support.',
                'Abysmal quality! Feels like it was made in someone\'s garage.'
            ]
        ];

        // Use order_item_ids from 10 to 59 (avoiding the base ones 1-9)
        for ($i = 10; $i <= 59; $i++) {
            $userId = $users[array_rand($users)];
            $productId = rand(1, 110); // Products 1-110 (10 base + 100 generated)
            $rating = rand(1, 5);
            $comment = $comments[$rating][array_rand($comments[$rating])];
            
            // 30% chance of having an image - use real photos from Unsplash
            $imageLink = null;
            if (rand(1, 10) <= 3) {
                $reviewImages = [
                    'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=600&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1556742111-a301076d9d18?w=600&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1556745757-8d76bdb6984b?w=600&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1607083206968-13611e3d76db?w=600&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1556742111-a301076d9d18?w=600&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1556745757-8d76bdb6984b?w=600&h=400&fit=crop'
                ];
                $imageLink = $reviewImages[array_rand($reviewImages)];
            }

            $additionalReviews[] = [
                'user_id' => $userId,
                'product_id' => $productId,
                'order_item_id' => $i,
                'rating' => $rating,
                'comment' => $comment,
                'image_link' => $imageLink,
                'created' => $now,
                'modified' => $now,
            ];
        }

        $data = array_merge($baseReviews, $additionalReviews);

        $table = $this->table('reviews');
        $table->insert($data)->save();
    }
}
