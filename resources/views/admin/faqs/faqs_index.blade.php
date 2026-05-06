@extends('admin.layout')
@section('title', 'FAQ Management')

@section('content')
<div class="row g-3">
    <!-- Add FAQ Form -->
    <div class="col-md-4">
        <div class="stat-card">
            <h6 style="font-weight:700;color:#1A1A2E;margin-bottom:16px;">
                <i class="bi bi-plus-circle me-2 text-primary"></i>Add New FAQ
            </h6>
            <form method="POST" action="{{ route('admin.faqs.store') }}">
                @csrf
                <div class="mb-3">
                    <label style="font-size:12px;font-weight:600;color:#1A1A2E;">Question</label>
                    <input type="text" name="question" class="form-control form-control-sm mt-1" placeholder="Enter question..." value="{{ old('question') }}" required>
                    @error('question')<div class="text-danger" style="font-size:11px;">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label style="font-size:12px;font-weight:600;color:#1A1A2E;">Answer</label>
                    <textarea name="answer" rows="4" class="form-control form-control-sm mt-1" placeholder="Enter answer..." required>{{ old('answer') }}</textarea>
                    @error('answer')<div class="text-danger" style="font-size:11px;">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label style="font-size:12px;font-weight:600;color:#1A1A2E;">Keywords</label>
                    <input type="text" name="keywords" class="form-control form-control-sm mt-1" placeholder="e.g. warranty, iphone, apple" value="{{ old('keywords') }}">
                    <div style="font-size:11px;color:#666;margin-top:4px;">Separate with commas</div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm w-100" style="border-radius:8px;">
                    <i class="bi bi-plus me-1"></i> Add FAQ
                </button>
            </form>
        </div>
    </div>

    <!-- FAQ List -->
    <div class="col-md-8">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 style="font-weight:700;color:#1A1A2E;margin:0;">
                    <i class="bi bi-question-circle me-2 text-primary"></i>
                    FAQ List <span class="badge bg-primary ms-1">{{ $faqs->total() }}</span>
                </h6>
            </div>

            @forelse($faqs as $faq)
            <div class="p-3 mb-3 rounded-3" style="background:#F8F9FA;border:1px solid #E8ECEF;">
                <div class="d-flex justify-content-between align-items-start">
                    <div style="flex:1;">
                        <div style="font-size:14px;font-weight:600;color:#1A1A2E;margin-bottom:6px;">
                            <i class="bi bi-question-circle text-primary me-1"></i>{{ $faq->question }}
                        </div>
                        <div style="font-size:13px;color:#666;line-height:1.5;margin-bottom:8px;">
                            {{ $faq->answer }}
                        </div>
                        @if($faq->keywords)
                        <div>
                            @foreach(explode(',', $faq->keywords) as $keyword)
                            <span class="badge" style="background:#E3F2FD;color:#1565C0;font-size:10px;margin-right:4px;">{{ trim($keyword) }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="d-flex gap-1 ms-2">
                        <button class="btn btn-sm btn-outline-primary" style="border-radius:6px;font-size:11px;"
                            data-bs-toggle="modal" data-bs-target="#editFaq{{ $faq->id }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" onsubmit="return confirm('Delete this FAQ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:6px;font-size:11px;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editFaq{{ $faq->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content" style="border-radius:16px;">
                        <div class="modal-header">
                            <h6 class="modal-title fw-bold">Edit FAQ</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.faqs.update', $faq) }}">
                            @csrf @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label style="font-size:12px;font-weight:600;">Question</label>
                                    <input type="text" name="question" class="form-control form-control-sm mt-1" value="{{ $faq->question }}" required>
                                </div>
                                <div class="mb-3">
                                    <label style="font-size:12px;font-weight:600;">Answer</label>
                                    <textarea name="answer" rows="3" class="form-control form-control-sm mt-1" required>{{ $faq->answer }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label style="font-size:12px;font-weight:600;">Keywords</label>
                                    <input type="text" name="keywords" class="form-control form-control-sm mt-1" value="{{ $faq->keywords }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-5">
                <i class="bi bi-question-circle fs-1 d-block mb-2"></i>
                No FAQs yet. Add your first FAQ!
            </div>
            @endforelse

            <div class="mt-3">{{ $faqs->links() }}</div>
        </div>
    </div>
</div>
@endsection