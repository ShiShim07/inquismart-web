<!DOCTYPE html>
<html>
<head>
    <title>FAQ Management — InquiSmart Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-4xl mx-auto py-10 px-4">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">FAQ Management</h1>
            <p class="text-sm text-gray-400 mt-1">Add, edit, or remove frequently asked questions</p>
        </div>
        <a href="{{ route('admin.dashboard') }}"
           class="text-blue-600 hover:underline text-sm">← Back to Dashboard</a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Add New FAQ Form --}}
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <h2 class="text-sm font-bold text-gray-500 uppercase mb-4">Add New FAQ</h2>
        <form action="{{ route('admin.faqs.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="text-xs text-gray-500 block mb-1">Question</label>
                <input type="text" name="question" value="{{ old('question') }}"
                       placeholder="e.g. What is your return policy?"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-400">
                @error('question')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="text-xs text-gray-500 block mb-1">Answer</label>
                <textarea name="answer" rows="3"
                          placeholder="Type the answer here..."
                          class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-400">{{ old('answer') }}</textarea>
                @error('answer')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-xl text-sm font-semibold hover:bg-blue-700">
                + Add FAQ
            </button>
        </form>
    </div>

    {{-- FAQ List --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-bold text-gray-500 uppercase">
                All FAQs
                <span class="ml-2 bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs font-semibold">
                    {{ $faqs->total() }}
                </span>
            </h2>
        </div>

        @forelse($faqs as $faq)
            <div class="px-6 py-4 border-b border-gray-50 hover:bg-gray-50 transition">

                {{-- View Mode --}}
                <div id="view-{{ $faq->id }}">
                    <p class="font-semibold text-gray-800 text-sm">{{ $faq->question }}</p>
                    <p class="text-gray-500 text-sm mt-1 leading-relaxed">{{ $faq->answer }}</p>
                    <div class="flex gap-3 mt-3">
                        <button onclick="toggleEdit({{ $faq->id }})"
                            class="text-xs text-blue-600 hover:underline font-semibold">
                            ✏️ Edit
                        </button>
                        <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST"
                              onsubmit="return confirm('Delete this FAQ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:underline font-semibold">
                                🗑 Delete
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Edit Mode (hidden by default) --}}
                <div id="edit-{{ $faq->id }}" class="hidden">
                    <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-2">
                            <input type="text" name="question" value="{{ $faq->question }}"
                                   class="w-full border border-blue-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-400">
                        </div>
                        <div class="mb-3">
                            <textarea name="answer" rows="3"
                                      class="w-full border border-blue-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-400">{{ $faq->answer }}</textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-blue-700">
                                Save
                            </button>
                            <button type="button" onclick="toggleEdit({{ $faq->id }})"
                                class="text-gray-500 text-xs hover:underline">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        @empty
            <div class="px-6 py-12 text-center text-gray-400">
                <p class="text-4xl mb-2">❓</p>
                <p>No FAQs yet. Add one above!</p>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($faqs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $faqs->links() }}
            </div>
        @endif
    </div>

</div>

<script>
    function toggleEdit(id) {
        document.getElementById('view-' + id).classList.toggle('hidden');
        document.getElementById('edit-' + id).classList.toggle('hidden');
    }
</script>

</body>
</html>