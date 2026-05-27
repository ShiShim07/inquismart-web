<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Faq;

/**
 * PredictiveController
 *
 * Module 14 — Predictive Inquiry Recommendation
 *
 * As a customer types their concern in the Submit Ticket screen,
 * this endpoint suggests related FAQs or common ticket categories
 * so they may self-resolve before submitting.
 *
 * Endpoint:
 *   POST /api/predict/inquiry
 *   Body: { "text": "my iphone battery drains fast" }
 *
 * Returns:
 *   { "suggestions": [...], "matched_faqs": [...], "predicted_category": "..." }
 */
class PredictiveController extends Controller
{
    // ─── FAQ knowledge base (same as Flutter FAQ screen) ─────────────────────
    private array $faqs = [
        [
            'id'       => 1,
            'question' => 'What is the warranty period for iPhones?',
            'answer'   => 'All iPhones come with a 1-year manufacturer warranty covering hardware defects.',
            'keywords' => ['warranty', 'warrant', 'guarantee', 'coverage', 'iphone warranty'],
        ],
        [
            'id'       => 2,
            'question' => 'Do you accept installment payments?',
            'answer'   => 'Yes! 0% installment via BDO, BPI, Metrobank. Minimum PHP 5,000 purchase.',
            'keywords' => ['installment', 'install', 'monthly', '0%', 'plan', 'credit'],
        ],
        [
            'id'       => 3,
            'question' => 'What is your return policy?',
            'answer'   => 'We accept returns within 7 days with original receipt and complete packaging.',
            'keywords' => ['return', 'refund', 'exchange', 'policy', '7 day', 'replace'],
        ],
        [
            'id'       => 4,
            'question' => 'How long does repair take?',
            'answer'   => 'Minor repairs: 1–2 hours. Major repairs: 1–3 business days.',
            'keywords' => ['repair', 'fix', 'broken', 'screen', 'battery', 'how long', 'repair time'],
        ],
        [
            'id'       => 5,
            'question' => 'What payment methods do you accept?',
            'answer'   => 'Cash, GCash, Maya, and all major credit/debit cards.',
            'keywords' => ['payment', 'pay', 'gcash', 'maya', 'cash', 'credit card'],
        ],
        [
            'id'       => 6,
            'question' => 'Do you buy second-hand phones?',
            'answer'   => 'Yes! We accept trade-ins. Bring your unit for evaluation.',
            'keywords' => ['trade', 'second hand', 'buy', 'old phone', 'sell', 'trade-in'],
        ],
        [
            'id'       => 7,
            'question' => 'Where is NAN Cellphone Shop located?',
            'answer'   => '2nd Floor, Greenhills Shopping Center, San Juan City, Metro Manila.',
            'keywords' => ['location', 'address', 'where', 'located', 'branch', 'greenhills'],
        ],
        [
            'id'       => 8,
            'question' => 'What are your store hours?',
            'answer'   => 'Monday to Sunday, 10:00 AM – 8:00 PM.',
            'keywords' => ['hours', 'open', 'close', 'schedule', 'store hours', 'operating'],
        ],
        [
            'id'       => 9,
            'question' => 'Do you have Samsung Galaxy available?',
            'answer'   => 'Yes, we carry the latest Samsung Galaxy lineup. Visit us for current stocks.',
            'keywords' => ['samsung', 'galaxy', 'android', 'stock', 'available'],
        ],
        [
            'id'       => 10,
            'question' => 'Do you have AirPods in stock?',
            'answer'   => 'Yes! AirPods Pro 2nd gen, AirPods 3rd gen, and AirPods Max available.',
            'keywords' => ['airpods', 'earphones', 'earbuds', 'headphones', 'apple audio'],
        ],
    ];

    // ─── Inquiry category hints ───────────────────────────────────────────────
    private array $categories = [
        'Warranty inquiry'     => ['warranty', 'warrant', 'guarantee', 'covered', 'coverage'],
        'Repair request'       => ['repair', 'fix', 'broken', 'not working', 'screen', 'battery', 'damage'],
        'Payment question'     => ['payment', 'pay', 'gcash', 'maya', 'installment', 'cash', 'credit'],
        'Return / refund'      => ['return', 'refund', 'exchange', 'replace', 'money back'],
        'Stock availability'   => ['stock', 'available', 'unit', 'iphone', 'samsung', 'airpods'],
        'Trade-in / buy-back'  => ['trade', 'second hand', 'old phone', 'buy back', 'sell'],
        'Store information'    => ['location', 'address', 'hours', 'open', 'close', 'where', 'branch'],
        'Installment plan'     => ['installment', '0%', 'monthly plan', 'credit card', 'zero interest'],
    ];

    /**
     * POST /api/predict/inquiry
     */
    public function suggest(Request $request)
    {
        $request->validate(['text' => 'required|string|min:3|max:2000']);

        $text  = strtolower(trim($request->input('text')));
        $words = preg_split('/\s+/', $text);

        // 1. Match FAQs by keyword score
        $matchedFaqs = $this->matchFaqs($text);

        // 2. Predict the inquiry category
        $predictedCategory = $this->predictCategory($text);

        // 3. Build human-readable suggestions
        $suggestions = $this->buildSuggestions($matchedFaqs, $predictedCategory);

        // 4. Optionally: look up similar past tickets (M14 predictive learning)
        $similarTickets = $this->findSimilarTickets($text);

        return response()->json([
            'suggestions'        => $suggestions,
            'matched_faqs'       => $matchedFaqs,
            'predicted_category' => $predictedCategory,
            'similar_tickets'    => $similarTickets,
        ]);
    }

    private function matchFaqs(string $text): array
    {
        $results = [];

        foreach ($this->faqs as $faq) {
            $score = 0;
            foreach ($faq['keywords'] as $keyword) {
                if (str_contains($text, $keyword)) {
                    $score += strlen($keyword); // longer keyword = stronger match
                }
            }
            if ($score > 0) {
                $results[] = [
                    'id'       => $faq['id'],
                    'question' => $faq['question'],
                    'answer'   => $faq['answer'],
                    'score'    => $score,
                ];
            }
        }

        // Sort by score desc, return top 3
        usort($results, fn($a, $b) => $b['score'] <=> $a['score']);
        return array_slice($results, 0, 3);
    }

    private function predictCategory(string $text): ?string
    {
        $scores = [];

        foreach ($this->categories as $category => $keywords) {
            $score = 0;
            foreach ($keywords as $kw) {
                if (str_contains($text, $kw)) {
                    $score += strlen($kw);
                }
            }
            if ($score > 0) {
                $scores[$category] = $score;
            }
        }

        if (empty($scores)) return null;

        arsort($scores);
        return array_key_first($scores);
    }

    private function buildSuggestions(array $matchedFaqs, ?string $predictedCategory): array
    {
        $suggestions = [];

        if ($predictedCategory) {
            $suggestions[] = "This looks like a {$predictedCategory} — check our FAQ first!";
        }

        foreach ($matchedFaqs as $faq) {
            $suggestions[] = $faq['question'];
        }

        if (empty($suggestions)) {
            $suggestions[] = 'Try searching our FAQ for quick answers before submitting.';
        }

        return array_slice($suggestions, 0, 3);
    }

    private function findSimilarTickets(string $text): array
    {
        // Look for resolved tickets with matching words (simple predictive learning)
        $words = collect(explode(' ', $text))
            ->filter(fn($w) => strlen($w) > 3)
            ->take(3)
            ->values();

        if ($words->isEmpty()) return [];

        $query = Ticket::where('status', 'Resolved')
            ->whereNotNull('staff_response');

        foreach ($words as $word) {
            $query->where(function ($q) use ($word) {
                $q->where('subject', 'like', "%{$word}%")
                  ->orWhere('description', 'like', "%{$word}%");
            });
        }

        return $query->select(['subject', 'staff_response'])
            ->limit(2)
            ->get()
            ->map(fn($t) => [
                'subject'        => $t->subject,
                'staff_response' => $t->staff_response,
            ])
            ->toArray();
    }
}