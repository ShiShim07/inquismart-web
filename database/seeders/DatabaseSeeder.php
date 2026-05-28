<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Faq;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin Account
        User::create([
            'name' => 'NAN Admin',
            'email' => 'admin@nancellphone.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '0927-2167888',
        ]);

        // Create Staff Account
        User::create([
            'name' => 'Staff jeric',
            'email' => 'staff@nancellphone.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
            'phone' => '0907-1307888',
        ]);

        // Create Sample Customers
        $customer1 = User::create([
            'name' => 'Jericho Robles',
            'email' => 'jrobles@gmail.com',
            'password' => Hash::make('Alijan07'),
            'role' => 'customer',
            'phone' => '09171234567',
        ]);

        $customer2 = User::create([
            'name' => 'kurt',
            'email' => 'kurt@gmail.com',
            'password' => Hash::make('kurt1234'),
            'role' => 'customer',
            'phone' => '09281234567',
        ]);

        $customer3 = User::create([
            'name' => 'Shemayrie B.',
            'email' => 'shemayrie@gmail.com',
            'password' => Hash::make('shemayrie123'),
            'role' => 'customer',
            'phone' => '09391234567',
        ]);

        // Create Sample Tickets
        // Updated: Urgent/Frustrated → Negative, per panel requirement
        Ticket::create([
            'user_id' => $customer1->id,
            'subject' => 'iPhone 15 warranty question',
            'description' => 'I bought an iPhone 15 last month and I want to know if it has warranty coverage for screen damage.',
            'status' => 'Resolved',
            'sentiment' => 'Neutral',
            'staff_response' => 'Hello! Your iPhone 15 comes with a 1-year manufacturer warranty covering hardware defects. Screen damage from accidents is not covered unless you have AppleCare+.',
            'responded_at' => now()->subHours(2),
        ]);

        Ticket::create([
            'user_id' => $customer2->id,
            'subject' => 'Samsung Galaxy S24 price inquiry - URGENT',
            'description' => 'I need to know ASAP the price of Samsung Galaxy S24 Ultra. I need to buy it today for a gift!',
            'status' => 'Processing',
            'sentiment' => 'Negative', // was: Urgent
        ]);

        Ticket::create([
            'user_id' => $customer3->id,
            'subject' => 'Earphones availability',
            'description' => 'Do you have AirPods Pro 2nd gen in stock? I have been trying to reach you through Facebook but no reply.',
            'status' => 'Pending',
            'sentiment' => 'Negative', // was: Frustrated
        ]);

        Ticket::create([
            'user_id' => $customer1->id,
            'subject' => 'Installment payment inquiry',
            'description' => 'Can I buy a phone on installment? What are the requirements?',
            'status' => 'Resolved',
            'sentiment' => 'Neutral',
            'staff_response' => 'Yes! We accept 0% installment via BDO, BPI, and Metrobank credit cards. Minimum purchase of PHP 5,000.',
            'responded_at' => now()->subDay(),
        ]);

        Ticket::create([
            'user_id' => $customer2->id,
            'subject' => 'Defective phone - need replacement NOW',
            'description' => 'I bought a phone 3 days ago and it keeps restarting. This is unacceptable! I need a replacement immediately!',
            'status' => 'Pending',
            'sentiment' => 'Negative', // was: Urgent
        ]);

        // Create Sample FAQs
        $faqs = [
            ['question' => 'What is the warranty period for iPhones?', 'answer' => 'All iPhones come with 1-year manufacturer warranty covering hardware defects. Screen damage from accidents is not covered unless you have AppleCare+.', 'keywords' => 'warranty, iphone, apple'],
            ['question' => 'Do you accept installment payments?', 'answer' => 'Yes! We accept 0% installment via BDO, BPI, and Metrobank credit cards. Minimum purchase of PHP 5,000 required.', 'keywords' => 'installment, payment, credit card'],
            ['question' => 'What is your return policy?', 'answer' => 'We accept returns within 7 days of purchase with original receipt and complete packaging. Item must be in original condition.', 'keywords' => 'return, refund, policy'],
            ['question' => 'Do you have Samsung Galaxy available?', 'answer' => 'Yes, we carry the latest Samsung Galaxy lineup including S24 series, A series, and more. Visit us or message us for current stocks.', 'keywords' => 'samsung, galaxy, android'],
            ['question' => 'How long does repair take?', 'answer' => 'Minor repairs (screen replacement, battery) take 1-2 hours. Major repairs may take 1-3 business days.', 'keywords' => 'repair, fix, screen, battery'],
            ['question' => 'Do you buy second-hand phones?', 'answer' => 'Yes! We accept trade-ins and buy second-hand units. Bring your unit for evaluation and we will give you the best price.', 'keywords' => 'trade-in, second hand, buy, sell'],
            ['question' => 'What payment methods do you accept?', 'answer' => 'We accept cash, GCash, Maya (PayMaya), and all major credit/debit cards.', 'keywords' => 'payment, gcash, maya, cash, card'],
            ['question' => 'Do you have AirPods in stock?', 'answer' => 'Yes! We have AirPods Pro (2nd gen), AirPods 3rd gen, and AirPods Max available. Stock may vary.', 'keywords' => 'airpods, apple, earphones, headphones'],
            ['question' => 'Where is NaN Cellphone Shop located?', 'answer' => 'We are located at the 2nd Floor, Greenhills Shopping Center, San Juan City, Metro Manila.', 'keywords' => 'location, address, where, greenhills'],
            ['question' => 'What are your store hours?', 'answer' => 'We are open Monday to Sunday, 10:00 AM to 8:00 PM.', 'keywords' => 'hours, open, schedule, time'],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}