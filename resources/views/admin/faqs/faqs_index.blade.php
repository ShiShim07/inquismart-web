@extends('admin.layout')
@section('title', 'FAQ Management')

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-weight:700;color:#0F172A;margin:0;">FAQ Management</h5>
        <p style="font-size:13px;color:var(--text-muted);margin:2px 0 0;">Add, edit, or remove frequently asked questions</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" style="font-size:13px;color:#1565C0;text-decoration:none;">
        ← Back to Dashboard
    </a>
</div>

{{-- Add New FAQ --}}
<div class="card-surface mb-4">
    <h6 class="section-header mb-3">
        <i class="bi bi-plus-circle" style="color:#1565C0;"></i> Add New FAQ
    </h6>
    <form action="{{ route('admin.faqs.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">Question</label>
            <input type="text" name="question" value="{{ old('question') }}"
                placeholder="e.g. What is your return policy?"
                style="width:100%;padding:11px 14px;border:1.5px solid #E2E8F0;border-radius:9px;font-size:14px;color:#0F172A;background:#F8FAFC;outline:none;font-family:inherit;"
                onfocus="this.style.borderColor='#1565C0'" onblur="this.style.borderColor='#E2E8F0'">
            @error('question')
                <p style="color:#DC2626;font-size:12px;margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">Answer</label>
            <textarea name="answer" rows="3"
                placeholder="Type the answer here..."
                style="width:100%;padding:11px 14px;border:1.5px solid #E2E8F0;border-radius:9px;font-size:14px;color:#0F172A;background:#F8FAFC;outline:none;font-family:inherit;resize:vertical;"
                onfocus="this.style.borderColor='#1565C0'" onblur="this.style.borderColor='#E2E8F0'">{{ old('answer') }}</textarea>
            @error('answer')
                <p style="color:#DC2626;font-size:12px;margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit"
            style="padding:10px 22px;background:#1565C0;color:white;border:none;border-radius:9px;font-size:13.5px;font-weight:600;cursor:pointer;font-family:inherit;display:inline-flex;align-items:center;gap:7px;"
            onmouseover="this.style.background='#0D47A1'" onmouseout="this.style.background='#1565C0'">
            <i class="bi bi-plus-lg"></i> Add FAQ
        </button>
    </form>
</div>

{{-- FAQ List --}}
<div class="card-surface">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h6 class="section-header">
            <i class="bi bi-question-circle" style="color:#1565C0;"></i> All FAQs
        </h6>
        <span style="background:#EFF6FF;color:#1E40AF;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:600;">
            {{ $faqs->total() }} items
        </span>
    </div>

    @forelse($faqs as $faq)
    <div style="border-bottom:1px solid #F1F5F9;padding:18px 0;" id="faq-item-{{ $faq->id }}">

        {{-- View Mode --}}
        <div id="view-{{ $faq->id }}">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;">
                <div style="flex:1;">
                    <p style="font-size:14px;font-weight:600;color:#0F172A;margin:0 0 6px;">{{ $faq->question }}</p>
                    <p style="font-size:13.5px;color:var(--text-muted);margin:0;line-height:1.6;">{{ $faq->answer }}</p>
                </div>
                <div style="display:flex;gap:8px;flex-shrink:0;">
                    <button onclick="toggleEdit({{ $faq->id }})"
                        style="padding:6px 14px;background:#EFF6FF;color:#1565C0;border:none;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit;">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </button>
                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST"
                          onsubmit="return confirm('Delete this FAQ?')" style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            style="padding:6px 14px;background:#FEF2F2;color:#DC2626;border:none;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit;">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Edit Mode --}}
        <div id="edit-{{ $faq->id }}" style="display:none;">
            <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-2">
                    <input type="text" name="question" value="{{ $faq->question }}"
                        style="width:100%;padding:10px 13px;border:1.5px solid #1565C0;border-radius:9px;font-size:14px;color:#0F172A;background:white;outline:none;font-family:inherit;margin-bottom:8px;"
                    >
                    <textarea name="answer" rows="3"
                        style="width:100%;padding:10px 13px;border:1.5px solid #1565C0;border-radius:9px;font-size:14px;color:#0F172A;background:white;outline:none;font-family:inherit;resize:vertical;">{{ $faq->answer }}</textarea>
                </div>
                <div style="display:flex;gap:8px;">
                    <button type="submit"
                        style="padding:8px 18px;background:#1565C0;color:white;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;">
                        <i class="bi bi-check-lg me-1"></i>Save
                    </button>
                    <button type="button" onclick="toggleEdit({{ $faq->id }})"
                        style="padding:8px 18px;background:#F1F5F9;color:#64748B;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;">
                        Cancel
                    </button>
                </div>
            </form>
        </div>

    </div>
    @empty
    <div style="text-align:center;padding:48px;color:var(--text-muted);">
        <i class="bi bi-question-circle" style="font-size:32px;display:block;margin-bottom:10px;color:var(--text-xs);"></i>
        No FAQs yet. Add one above!
    </div>
    @endforelse

    @if($faqs->hasPages())
    <div style="padding-top:16px;">
        {{ $faqs->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
function toggleEdit(id) {
    const view = document.getElementById('view-' + id);
    const edit = document.getElementById('edit-' + id);
    view.style.display = view.style.display === 'none' ? 'block' : 'none';
    edit.style.display = edit.style.display === 'none' ? 'block' : 'none';
}
</script>
@endpush

@endsection