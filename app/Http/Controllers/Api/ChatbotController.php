<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatHistory;

class ChatbotController extends Controller
{
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

    private array $responses = [
        'warranty'    => ["📱 **Warranty Information:**\n\niPhones come with a **1-year manufacturer warranty** covering hardware defects. Samsung Galaxy units also have a 1-year warranty.\n\nNote: Accidental damage (dropped screens, water damage) is **not covered** by the standard warranty unless you have AppleCare+ or extended coverage.\n\nBring your unit and purchase receipt to our shop for warranty claims."],
        'repair'      => ["🔧 **Repair Services:**\n\n• **Minor repairs** (screen, battery, charging port): 1–2 hours\n• **Major repairs** (motherboard, water damage): 1–3 business days\n\nYou can track your repair status under **My Tickets** in the app."],
        'payment'     => ["💳 **Accepted Payment Methods:**\n\n• Cash\n• GCash\n• Maya (PayMaya)\n• All major credit cards (Visa, Mastercard, Amex)\n• Debit cards\n\n0% installment available for purchases of **PHP 5,000 and above**."],
        'location'    => ["📍 **Store Location:**\n\n2nd Floor, Greenhills Shopping Center\nSan Juan City, Metro Manila\n\nLook for the NAN Cellphone Shop signage! 😊"],
        'hours'       => ["🕙 **Store Hours:**\n\nMonday to Sunday\n**10:00 AM – 8:00 PM**\n\nOpen every day including holidays!"],
        'return'      => ["🔄 **Return & Exchange Policy:**\n\nWe accept returns within **7 days** of purchase, provided:\n✅ Original receipt\n✅ Complete original packaging\n✅ No physical damage\n✅ All accessories included"],
        'stock'       => ["📦 **Product Availability:**\n\nWe carry the latest:\n• iPhones\n• Samsung Galaxy (S & A series)\n• AirPods\n• Accessories\n\nStock changes daily — visit us or submit a ticket for availability!"],
        'installment' => ["💰 **Installment Plans:**\n\n**0% interest** available!\n• Minimum: PHP 5,000\n• Partner banks: BDO, BPI, Metrobank\n• Terms: 3, 6, or 12 months"],
        'tradein'     => ["🔁 **Trade-In / Buy-Back:**\n\n1. Bring your old unit\n2. We evaluate condition\n3. Best buy-back price given\n4. Apply credit to new purchase\n\nAll brands accepted! 😊"],
        'ticket'      => ["🎫 **Submit a Support Ticket:**\n\n1. Tap **Submit Ticket** on the home screen\n2. Enter subject and describe your concern\n3. Submit — staff responds within 24 hours!\n\nTrack status under **My Tickets**."],
        'greeting'    => [
            "Hello! 👋 Welcome to NAN Cellphone Shop support! I'm **InquiBot**.\n\nHow can I help you today?\n• Warranties • Repairs • Payments\n• Returns • Location & Hours",
            "Hi there! 😊 I'm InquiBot. What can I help you with today?",
        ],
        'thanks'      => ["You're welcome! 😊 Anything else I can help with?", "Happy to help! 👍", "Glad I could assist! 😊"],
        'goodbye'     => ["Goodbye! 👋 Thank you for contacting NAN Cellphone Shop!", "Take care! 😊 We're always here to help!"],
        'price'       => ["💰 **Pricing:**\n\nPrices vary by model. For current prices:\n📱 Visit our shop at Greenhills\n📩 Or submit a ticket with the specific model!"],
        'status'      => ["🔍 **Check Ticket/Repair Status:**\n\n1. Open the app\n2. Tap **My Tickets**\n3. Select your ticket for full status and staff response"],

        // UPDATED: Removed "flagged for staff" — bot-only na, redirect to Submit Ticket
        'fallback'    => [
            "I'm not quite sure about that. 🤔\n\nI can help with:\n• Warranties • Repairs • Payments\n• Returns • Location & Hours • Trade-in\n\nFor other concerns, please submit a support ticket using the button below.",
            "Hmm, I don't have information on that yet. 😅\n\nPlease submit a support ticket so our staff can assist you within 24 hours!",
        ],
    ];

    /** POST /api/chatbot/message */
    public function message(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $userMessage = trim($request->input('message'));
        $intent      = $this->detectIntent(strtolower($userMessage));
        $reply       = $this->getResponse($intent);
        $isFallback  = ($intent === 'fallback');
        $user        = $request->user();

        if ($user) {
            // Save customer message
            ChatHistory::create([
                'user_id'          => $user->id,
                'role'             => 'user',
                'message'          => $userMessage,
                'intent'           => null,
                'is_read_by_staff' => false,
                'needs_human'      => false,
            ]);

            // Save bot reply
            ChatHistory::create([
                'user_id'          => $user->id,
                'role'             => 'bot',
                'message'          => $reply,
                'intent'           => $intent,
                'is_read_by_staff' => false,
                'needs_human'      => $isFallback,
            ]);
        }

        return response()->json([
            'reply'       => $reply,
            'intent'      => $intent,   // Flutter uses this to detect fallback
            'needs_human' => $isFallback,
        ]);
    }

    /** GET /api/chatbot/history */
    public function history(Request $request)
    {
        $history = ChatHistory::where('user_id', $request->user()->id)
            ->whereIn('role', ['user', 'bot']) // bot-only — exclude staff role
            ->orderBy('created_at', 'asc')
            ->limit(100)
            ->get(['role', 'message', 'intent', 'needs_human', 'created_at']);

        return response()->json($history);
    }

    /** DELETE /api/chatbot/clear */
    public function clear(Request $request)
    {
        ChatHistory::where('user_id', $request->user()->id)->delete();
        return response()->json(['message' => 'Chat history cleared.']);
    }

    private function detectIntent(string $text): string
    {
        $scores = [];
        foreach ($this->intents as $intent => $keywords) {
            $score = 0;
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) $score += strlen($keyword);
            }
            if ($score > 0) $scores[$intent] = $score;
        }
        if (empty($scores)) return 'fallback';
        arsort($scores);
        return array_key_first($scores);
    }

    private function getResponse(string $intent): string
    {
        $pool = $this->responses[$intent] ?? $this->responses['fallback'];
        return $pool[array_rand($pool)];
    }
}