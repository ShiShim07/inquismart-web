@extends('admin.layout')
@section('title', 'FAQ Management')

@section('content')

<div class="row g-4">
    {{-- Add FAQ Form --}}
    <div class="col-md-4">
        <div class="card-surface" style="position:sticky;top:84px;">
            <h6 class="section-header mb-4">
                <i class="bi bi-plus-circle"></i> Add New FAQ
            </h6>
            <form method="POST" action="{{ route('admin.faqs.store') }}">
                @csrf
                <div class="mb-3">
                    <label>Question</label>
                    <input type="text" name="question" class="form-control form-control-sm"
                        placeholder="e.g. How do I claim warranty?" value="{{ old('question') }}" required>
                    @error('question')
                        <div style="color:#DC2626;font-size:11.5px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label>Answer</label>
                    <textarea name="answer" rows="4" class="form-control form-control-sm"
                        placeholder="Provide a clear, helpful answer..." required>{{ old('answer') }}</textarea>
                    @error('answer')
                        <div style="color:#DC2626;font-size:11.5px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label>Keywords <span style="color:var(--text-muted);font-weight:400;">(comma-separated)</span></label>
                    <input type="text" name="keywords" class="form-control form-control-sm"
                        placeholder="e.g. warranty, repair, iphone" value="{{ old('keywords') }}">
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-plus-lg"></i> Add FAQ
                </button>
            </form>
        </div>
    </div>

    {{-- FAQ List --}}
    <div class="col-md-8">
        <div class="card-surface">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="section-header">
                    <i class="bi bi-chat-square-text"></i>
                    FAQ List
                    <span style="background:#EFF6FF;color:#1E40AF;font-size:11px;font-weight:600;padding:2px 8px;border-radius:20px;border:1px solid #BFDBFE;">
                        {{ $faqs->total() }}
                    </span>
                </h6>
            </div>

            @forelse($faqs as $faq)
            <div style="padding:16px;margin-bottom:12px;background:var(--surface);border-radius:10px;border:1px solid var(--border);">
                <div class="d-flex justify-content-between align-items-start">
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:14px;font-weight:600;color:var(--text-primary);margin-bottom:7px;">
                            <i class="bi bi-question-circle-fill me-1" style="color:var(--accent);font-size:13px;"></i>
                            {{ $faq->question }}
                        </div>
                        <div style="font-size:13px;color:var(--text-muted);line-height:1.55;margin-bottom:10px;">
                            {{ $faq->answer }}
                        </div>
                        @if($faq->keywords)
                        <div style="display:flex;flex-wrap:wrap;gap:5px;">
                            @foreach(explode(',', $faq->keywords) as $keyword)
                            <span style="background:#EFF6FF;color:#1E40AF;border:1px solid #BFDBFE;font-size:10.5px;font-weight:500;padding:2px 8px;border-radius:5px;">
                                {{ trim($keyword) }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="d-flex gap-1 ms-3 flex-shrink-0">
                        <button class="btn btn-outline-primary btn-sm"
                            data-bs-toggle="modal" data-bs-target="#editFaq{{ $faq->id }}" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}"
                            onsubmit="return confirm('Delete this FAQ?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Edit Modal --}}
            <div class="modal fade" id="editFaq{{ $faq->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">Edit FAQ</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.faqs.update', $faq) }}">
                            @csrf @method('PUT')
                            <div class="modal-body" style="padding:22px;">
                                <div class="mb-3">
                                    <label>Question</label>
                                    <input type="text" name="question" class="form-control form-control-sm" value="{{ $faq->question }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Answer</label>
                                    <textarea name="answer" rows="3" class="form-control form-control-sm" required>{{ $faq->answer }}</textarea>
                                </div>
                                <div>
                                    <label>Keywords</label>
                                    <input type="text" name="keywords" class="form-control form-control-sm" value="{{ $faq->keywords }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @empty
            <div style="text-align:center;padding:52px;color:var(--text-muted);">
                <i class="bi bi-chat-square-text" style="font-size:32px;display:block;margin-bottom:10px;color:var(--text-xs);"></i>
                <div style="font-weight:500;margin-bottom:4px;">No FAQs yet</div>
                <div style="font-size:12.5px;">Add your first FAQ using the form on the left</div>
            </div>
            @endforelse

            @if($faqs->hasPages())
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
                {{ $faqs->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
