<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.faqs.index', compact('faqs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'keywords' => 'nullable|string|max:255',
        ]);

        Faq::create($request->all());
        return back()->with('success', 'FAQ added successfully!');
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'keywords' => 'nullable|string|max:255',
        ]);

        $faq->update($request->all());
        return back()->with('success', 'FAQ updated successfully!');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ deleted successfully!');
    }
}