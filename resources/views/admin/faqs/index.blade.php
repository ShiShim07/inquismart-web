@extends('admin.layout')
@section('title', 'FAQ Management')

@section('content')

<div class="row g-4">
    {{-- Left: Add New FAQ --}}
    <div class="col-md-4">
        <div class="surface surface-pad fade-up">
            <h6 class="section-title mb-4"><i class="bi bi-plus-circle-fill"></i> Add New FAQ</h6>
            <form action="{{ route('admin.faqs.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label-sm">Question</label>
                    <input type="text" name="question" value="{{ old('question') }}"
                           placeholder="e.g. What is your return policy?"
                           class="form-ctrl">
                    @error('question')
                        <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="form-label-sm">Answer</label>
                    <textarea name="answer" rows="5"
                              placeholder="Type the answer here..."
                              class="form-ctrl" style="resize:vertical;">{{ old('answer') }}</textarea>
                    @error('answer')
                        <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn-primary-custom w-100" style="justify-content:center;">
                    <i class="bi bi-plus-lg"></i> Add FAQ
                </button>
            </form>
        </div>
    </div>

    {{-- Right: FAQ List --}}
    <div class="col-md-8">
        <div class="surface fade-up delay-1">
            <div class="surface-pad" style="border-bottom:1px solid var(--border);padding-bottom:16px;">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="section-title"><i class="bi bi-question-circle-fill"></i> All FAQs</h6>
                    <span class="chip chip-neutral">{{ $faqs->total() }} total</span>
                </div>
            </div>

            @forelse($faqs as $faq)
                <div style="padding:18px 24px;border-bottom:1px solid var(--border);transition:background 0.14s;" onmouseover="this.style.background='#F7F9FF'" onmouseout="this.style.background='transparent'">

                    {{-- View Mode --}}
                    <div id="view-{{ $faq->id }}">
                        <div style="font-weight:600;font-size:14px;color:var(--text-1);margin-bottom:5px;">
                            {{ $faq->question }}
                        </div>
                        <div style="font-size:13.5px;color:var(--text-2);line-height:1.6;">
                            {{ $faq->answer }}
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button onclick="toggleEdit({{ $faq->id }})" class="btn-outline-custom" style="font-size:12px;padding:5px 12px;">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </button>
                            <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST"
                                  onsubmit="return confirm('Delete this FAQ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="display:inline-flex;align-items:center;gap:4px;padding:5px 12px;background:#FFF0F2;color:var(--danger);border:1px solid #FFD0D8;border-radius:8px;font-size:12px;font-family:'DM Sans',sans-serif;font-weight:600;cursor:pointer;transition:all 0.15s;" onmouseover="this.style.background='#FFD0D8'" onmouseout="this.style.background='#FFF0F2'">
                                    <i class="bi bi-trash-fill"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Edit Mode --}}
                    <div id="edit-{{ $faq->id }}" style="display:none;">
                        <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-2">
                                <label class="form-label-sm">Question</label>
                                <input type="text" name="question" value="{{ $faq->question }}" class="form-ctrl">
                            </div>
                            <div class="mb-3">
                                <label class="form-label-sm">Answer</label>
                                <textarea name="answer" rows="4" class="form-ctrl" style="resize:vertical;">{{ $faq->answer }}</textarea>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn-primary-custom" style="font-size:12.5px;padding:7px 16px;">
                                    <i class="bi bi-check-lg"></i> Save
                                </button>
                                <button type="button" onclick="toggleEdit({{ $faq->id }})" class="btn-ghost" style="font-size:12.5px;">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            @empty
                <div style="text-align:center;padding:56px;color:var(--text-3);">
                    <i class="bi bi-question-circle" style="font-size:36px;display:block;margin-bottom:12px;opacity:0.3;"></i>
                    No FAQs yet. Add one on the left!
                </div>
            @endforelse

            @if($faqs->hasPages())
                <div style="padding:14px 20px;border-top:1px solid var(--border);">
                    {{ $faqs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleEdit(id) {
    const viewEl = document.getElementById('view-' + id);
    const editEl = document.getElementById('edit-' + id);
    const isEditing = editEl.style.display !== 'none';
    viewEl.style.display = isEditing ? 'block' : 'none';
    editEl.style.display = isEditing ? 'none' : 'block';
}
</script>
@endpush

@endsection
