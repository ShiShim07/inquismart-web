<?php

namespace App\Services;

/**
 * SentimentService
 *
 * Module 13 — AI-enhanced Customer Sentiment Analysis
 * Classifies ticket text as: Positive, Negative, or Neutral
 * Uses keyword-based NLP classification (no external API needed)
 *
 * Panel requirement:
 *   - Changed from Urgent/Frustrated/Neutral
 *   - Now uses: Positive / Negative / Neutral
 */
class SentimentService
{
    // ─── Negative keywords (strongest signal) ─────────────────────────────────
    private array $negativeKeywords = [
        // Anger / frustration
        'broken', 'defective', 'damaged', 'faulty', 'not working', 'doesnt work',
        'does not work', "doesn't work", 'stopped working', 'dead', 'dead on arrival',
        'doa', 'useless', 'garbage', 'trash', 'terrible', 'horrible', 'awful',
        'worst', 'unacceptable', 'disgusting', 'pathetic', 'ridiculous',

        // Urgency
        'urgent', 'emergency', 'asap', 'immediately', 'right now', 'right away',
        'need now', 'need immediately',

        // Frustration with service
        'no response', 'not responding', 'ignored', 'rude', 'bad service',
        'poor service', 'unprofessional', 'incompetent', 'unhelpful',
        'waste of time', 'wasted my time', 'disappointed', 'disappointment',
        'frustrated', 'frustrating', 'angry', 'mad', 'furious', 'upset',
        'fed up', 'sick of', 'tired of',

        // Product issues
        'overheating', 'overheat', 'battery draining', 'battery drain',
        'screen crack', 'cracked', 'shattered', 'not charging', 'wont charge',
        "won't charge", 'slow', 'lagging', 'freezing', 'frozen', 'crashing',
        'crash', 'error', 'bug', 'glitch', 'corrupt', 'corrupted',

        // Money issues
        'scam', 'fraud', 'ripped off', 'overcharged', 'wrong charge',
        'refund denied', 'no refund',

        // Negative sentiment intensifiers (when combined)
        'never', 'never again', 'avoid', 'do not buy', 'dont buy',
    ];

    // ─── Positive keywords ────────────────────────────────────────────────────
    private array $positiveKeywords = [
        // Praise
        'thank', 'thanks', 'thank you', 'thank you so much', 'grateful',
        'appreciate', 'appreciated', 'helpful', 'very helpful', 'excellent',
        'great', 'awesome', 'amazing', 'fantastic', 'wonderful', 'superb',
        'outstanding', 'perfect', 'satisfied', 'happy', 'pleased', 'impressed',

        // Good experience
        'fast', 'quick', 'efficient', 'professional', 'friendly', 'kind',
        'patient', 'accommodating', 'nice', 'good service', 'great service',
        'best service', 'highly recommend', 'recommend', 'will come back',
        'will return', 'five star', '5 star', '5/5',

        // Positive resolution
        'resolved', 'fixed', 'working now', 'works great', 'works perfectly',
        'issue resolved', 'problem solved', 'all good', 'good condition',
    ];

    // ─── Neutral / inquiry keywords (default when no signal is strong) ────────
    private array $neutralKeywords = [
        'how', 'what', 'where', 'when', 'inquiry', 'ask', 'asking',
        'question', 'check', 'status', 'tracking', 'follow up', 'follow-up',
        'update', 'information', 'info', 'details', 'process', 'procedure',
        'available', 'availability', 'stock', 'price', 'how much',
    ];

    /**
     * Analyze text and return sentiment with score breakdown.
     *
     * @param string $text  The ticket subject + description combined
     * @return array{
     *   sentiment: string,
     *   score: array{positive: int, negative: int, neutral: int},
     *   confidence: string
     * }
     */
    public function analyze(string $text): array
    {
        $lower = strtolower($text);

        $negScore  = $this->countMatches($lower, $this->negativeKeywords);
        $posScore  = $this->countMatches($lower, $this->positiveKeywords);
        $neutScore = $this->countMatches($lower, $this->neutralKeywords);

        // Apply weights — negative language is weighted higher
        // because customers in distress matter most for prioritization
        $weightedNeg  = $negScore * 2;
        $weightedPos  = $posScore * 1.5;
        $weightedNeut = $neutScore * 1;

        $total = $weightedNeg + $weightedPos + $weightedNeut;

        if ($total === 0.0) {
            $sentiment  = 'Neutral';
            $confidence = 'low';
        } elseif ($weightedNeg > $weightedPos && $weightedNeg > $weightedNeut) {
            $sentiment  = 'Negative';
            $confidence = $negScore >= 3 ? 'high' : 'medium';
        } elseif ($weightedPos > $weightedNeg && $weightedPos > $weightedNeut) {
            $sentiment  = 'Positive';
            $confidence = $posScore >= 2 ? 'high' : 'medium';
        } else {
            $sentiment  = 'Neutral';
            $confidence = 'medium';
        }

        return [
            'sentiment'  => $sentiment,
            'score'      => [
                'positive' => $posScore,
                'negative' => $negScore,
                'neutral'  => $neutScore,
            ],
            'confidence' => $confidence,
        ];
    }

    /**
     * Convenience method — returns just the sentiment label.
     */
    public function getSentiment(string $text): string
    {
        return $this->analyze($text)['sentiment'];
    }

    private function countMatches(string $text, array $keywords): int
    {
        $count = 0;
        foreach ($keywords as $kw) {
            if (str_contains($text, $kw)) {
                $count++;
            }
        }
        return $count;
    }
}