<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatHistory;

/**
 * ChatbotController
 *
 * Module 3 (Mobile) + Module 16 (Chatbot Engine)
 * Rule-based NLP chatbot for NAN Cellphone Shop customer inquiries.
 *
 * Endpoints:
 *   POST /api/chatbot/message  — send a message, get a reply
 *   GET  /api/chatbot/history  — retrieve past chat messages
 *   DELETE /api/chatbot/clear  — clear chat history
 */
class ChatbotController extends Controller
{
    // ─── Intent → Keywords mapping ────────────────────────────────────────────
    private array $intents = [
        'warranty'    => ['warranty', 'warrant', 'guarantee', 'coverage', 'covered', 'apple care', 'applecare'],
        'repair'      => ['repair', 'fix', 'fixing', 'broken', 'damaged', 'screen replacement', 'battery replacement', 'how long', 'repair time'],
        'payment'     => ['payment', 'pay', 'gcash', 'maya', 'cash', 'credit card', 'debit card', 'installment', 'how to pay', 'payment method'],
        'location'    => ['location', 'address', 'where', 'located', 'branch', 'direction', 'how to get'],
        'hours'       => ['hours', 'open', 'close', 'closing', 'opening', 'schedule', 'operating hours', 'store hours'],
        'return'      => ['return', 'refund', 'exchange', 'replace', 'replacement', 'money back', 'policy', '7 day'],
        'stock'       => ['stock', 'available', 'availability', 'unit', 'iphone', 'samsung', 'galaxy', 'android', 'airpods', 'accessories'],
        'installment' => ['installment', 'install', '0%', 'zero interest', 'monthly', 'plan', 'credit'],
        'tradein'     => ['trade', 'trade-in', 'trade in', 'buy back', 'second hand', 'used', 'old phone', 'sell'],
        'ticket'      => ['ticket', 'concern', 'complaint', 'complain', 'report', 'submit', 'issue', 'problem'],
        'greeting'    => ['hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening', 'sup', 'yo'],
        'thanks'      => ['thank', 'thanks', 'thank you', 'salamat', 'appreciate', 'appreciated'],
        'goodbye'     => ['bye', 'goodbye', 'see you', 'take care', 'later'],
        'price'       => ['price', 'how much', 'cost', 'magkano', 'budget', 'cheap', 'affordable'],
        'status'      => ['status', 'update', 'tracking', 'follow up', 'where is my', 'where is the'],
    ];

    // ─── Responses per intent ─────────────────────────────────────────────────
    private array $responses = [
        'warranty' => [
            "📱 **Warranty Information:**\n\niPhones come with a **1-year manufacturer warranty** covering hardware defects. Samsung Galaxy units also have a 1-year warranty.\n\nNote: Accidental damage (dropped screens, water damage) is **not covered** by the standard warranty unless you have AppleCare+ or extended coverage.\n\nBring your unit and purchase receipt to our shop for warranty claims.",
        ],
        'repair' => [
            "🔧 **Repair Services:**\n\n• **Minor repairs** (screen, battery, charging port): 1–2 hours\n• **Major repairs** (motherboard, water damage): 1–3 business days\n\nYou can track your repair status under **My Tickets** in the app. Our technicians will notify you once your unit is ready.",
        ],
        'payment' => [
            "💳 **Accepted Payment Methods:**\n\n• Cash\n• GCash\n• Maya (PayMaya)\n• All major credit cards (Visa, Mastercard, Amex)\n• Debit cards\n\n0% installment is available for purchases of **PHP 5,000 and above** via BDO, BPI, and Metrobank.",
        ],
        'location' => [
            "📍 **Store Location:**\n\n2nd Floor, Greenhills Shopping Center\nSan Juan City, Metro Manila\n\nLandmarks: Near the food court, beside the main atrium. Look for the NAN Cellphone Shop signage! 😊",
        ],
        'hours' => [
            "🕙 **Store Hours:**\n\nMonday to Sunday\n**10:00 AM – 8:00 PM**\n\nWe are open every day including holidays. See you soon!",
        ],
        'return' => [
            "🔄 **Return & Exchange Policy:**\n\nWe accept returns within **7 days** of purchase, provided:\n✅ Original receipt is presented\n✅ Complete original packaging\n✅ No physical damage (scratches, dents)\n✅ All accessories included\n\nExchanges are subject to stock availability. Visit us at the shop to process your return.",
        ],
        'stock' => [
            "📦 **Product Availability:**\n\nWe carry the latest lineup of:\n• iPhones (latest series)\n• Samsung Galaxy (S-series, A-series)\n• AirPods (Pro 2nd gen, 3rd gen, Max)\n• Accessories and cases\n\nStock changes daily! Message us or visit the shop for current availability.",
        ],
        'installment' => [
            "💰 **Installment Plans:**\n\n**0% interest** installment available!\n\n• Minimum purchase: PHP 5,000\n• Partner banks: BDO, BPI, Metrobank\n• Terms: 3, 6, or 12 months\n\nBring a valid ID and your credit card. Our staff will assist you with the application.",
        ],
        'tradein' => [
            "🔁 **Trade-In / Buy-Back:**\n\nYes, we accept trade-ins! Here's how:\n\n1. Bring your old unit to the shop\n2. Our team evaluates the condition\n3. We give you the best buy-back price\n4. Apply the credit to your new purchase\n\nWe accept all brands. Better condition = better price! 😊",
        ],
        'ticket' => [
            "🎫 **Submit a Support Ticket:**\n\nTo report a concern:\n1. Tap **Submit Ticket** on the home screen\n2. Enter your subject and describe your concern\n3. Submit — our staff responds within 24 hours!\n\nYou can track your ticket status under **My Tickets**.",
        ],
        'greeting' => [
            "Hello! 👋 Welcome to NAN Cellphone Shop support! I'm **InquiBot**, your virtual assistant.\n\nHow can I help you today? You can ask me about:\n• Warranties\n• Repairs\n• Payments & installment\n• Returns & exchange\n• Store location & hours",
            "Hi there! 😊 I'm InquiBot, the NAN CS assistant. What can I help you with today?",
        ],
        'thanks' => [
            "You're welcome! 😊 Is there anything else I can help you with?",
            "Happy to help! Let me know if you have more questions. 👍",
            "Glad I could assist! Don't hesitate to ask if you need anything else. 😊",
        ],
        'goodbye' => [
            "Goodbye! 👋 Thank you for contacting NAN Cellphone Shop. Have a great day!",
            "Take care! 😊 Feel free to reach out anytime. We're always here to help!",
        ],
        'price' => [
            "💰 **Pricing:**\n\nPrices vary by model and unit. For current prices:\n\n📱 Visit our shop at Greenhills Shopping Center\n📩 Or submit a ticket with the specific model you're interested in and we'll get back to you with pricing!\n\nWe always offer competitive prices and price-match guarantees on select items.",
        ],
        'status' => [
            "🔍 **Check Ticket Status:**\n\nTo check the status of your ticket or repair:\n\n1. Open the app\n2. Tap **My Tickets**\n3. Select your ticket to see the full status and staff response\n\nYou'll also receive a notification when your ticket is updated!",
        ],
        'fallback' => [
            "I'm not quite sure about that. 🤔\n\nHere's what I can help with:\n• Warranties & coverage\n• Repairs & timelines\n• Payment & installment\n• Returns & exchange policy\n• Store location & hours\n• Trade-in & buy-back\n\nOr tap **Submit Ticket** to reach our staff directly!",
            "Hmm, I don't have information on that yet. 😅 Would you like to submit a ticket so our staff can assist you? Tap **Submit Ticket** on the home screen.",
        ],
    ];

    /**
     * POST /api/chatbot/message
     * Receives a user message, detects intent, returns a reply.
     */
    public function message(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $userMessage = trim($request->input('message'));
        $intent      = $this->detectIntent(strtolower($userMessage));
        $reply       = $this->getResponse($intent);

        // Save to chat history
        $userId = Auth::id();
        if ($userId) {
            ChatHistory::create([
                'user_id'  => $userId,
                'role'     => 'user',
                'message'  => $userMessage,
            ]);
            ChatHistory::create([
                'user_id'  => $userId,
                'role'     => 'bot',
                'message'  => $reply,
                'intent'   => $intent,
            ]);
        }

        return response()->json([
            'reply'  => $reply,
            'intent' => $intent,
        ]);
    }

    /**
     * GET /api/chatbot/history
     * Returns past chat messages for the authenticated user.
     */
    public function history(Request $request)
    {
        $history = ChatHistory::where('user_id', Auth::id())
            ->orderBy('created_at', 'asc')
            ->limit(50)
            ->get(['role', 'message', 'intent', 'created_at']);

        return response()->json($history);
    }

    /**
     * DELETE /api/chatbot/clear
     * Clears the authenticated user's chat history.
     */
    public function clear(Request $request)
    {
        ChatHistory::where('user_id', Auth::id())->delete();
        return response()->json(['message' => 'Chat history cleared.']);
    }

    // ─── Intent Detection ─────────────────────────────────────────────────────
    private function detectIntent(string $text): string
    {
        $scores = [];

        foreach ($this->intents as $intent => $keywords) {
            $score = 0;
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    // Longer keyword match = higher confidence
                    $score += strlen($keyword);
                }
            }
            if ($score > 0) {
                $scores[$intent] = $score;
            }
        }

        if (empty($scores)) {
            return 'fallback';
        }

        // Return the intent with the highest score
        arsort($scores);
        return array_key_first($scores);
    }

    // ─── Response Selector ────────────────────────────────────────────────────
    private function getResponse(string $intent): string
    {
        $pool = $this->responses[$intent] ?? $this->responses['fallback'];
        return $pool[array_rand($pool)];
    }
}