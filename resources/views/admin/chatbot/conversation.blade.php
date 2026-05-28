@extends('admin.layout')
@section('title', 'Conversation with ' . $customer->name)

@section('content')
<div class="container-fluid py-3" style="max-width:860px;">

    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.chatbot.logs') }}" class="btn btn-sm btn-outline-secondary">
            ← Back to Logs
        </a>
        <div>
            <h5 class="mb-0 fw-bold">{{ $customer->name }}</h5>
            <small class="text-muted">{{ $customer->email }}</small>
        </div>
        <span class="badge bg-primary ms-auto">{{ $messages->count() }} messages</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Chat Window --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-0">
            <div id="chat-window" style="height:520px; overflow-y:auto; padding:20px; background:#f8f9fa;">
                @forelse($messages as $msg)
                    @if($msg->role === 'user')
                        {{-- Customer message (right) --}}
                        <div class="d-flex justify-content-end mb-3">
                            <div style="max-width:70%;">
                                <div class="bg-primary text-white rounded-3 px-3 py-2" style="border-radius:18px 18px 4px 18px !important;">
                                    {{ $msg->message }}
                                </div>
                                <div class="text-end mt-1">
                                    <small class="text-muted">{{ $msg->created_at->format('M d, h:i A') }}</small>
                                </div>
                            </div>
                            <div class="ms-2 d-flex align-items-end mb-3">
                                <div style="width:32px;height:32px;background:#0d6efd;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:600;">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                            </div>
                        </div>
                    @elseif($msg->role === 'bot')
                        {{-- Bot message (left) --}}
                        <div class="d-flex mb-3">
                            <div class="me-2 d-flex align-items-end mb-3">
                                <div style="width:32px;height:32px;background:#6c757d;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:13px;">
                                    🤖
                                </div>
                            </div>
                            <div style="max-width:70%;">
                                <div class="bg-white border rounded-3 px-3 py-2" style="border-radius:18px 18px 18px 4px !important;">
                                    {!! nl2br(e($msg->message)) !!}
                                    @if($msg->needs_human)
                                        <div class="mt-1">
                                            <span class="badge bg-warning text-dark" style="font-size:10px;">⚡ Needs Human</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-1">
                                    <small class="text-muted">InquiBot • {{ $msg->created_at->format('M d, h:i A') }}</small>
                                    @if($msg->intent)
                                        <span class="badge bg-light text-secondary ms-1" style="font-size:10px;">{{ $msg->intent }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @elseif($msg->role === 'staff')
                        {{-- Staff message (right, green) --}}
                        <div class="d-flex justify-content-end mb-3">
                            <div style="max-width:70%;">
                                <div class="text-white rounded-3 px-3 py-2" style="background:#198754;border-radius:18px 18px 4px 18px !important;">
                                    {{ $msg->message }}
                                </div>
                                <div class="text-end mt-1">
                                    <small class="text-muted">
                                        Staff
                                        @if($msg->staff)• {{ $msg->staff->name }}@endif
                                        • {{ $msg->created_at->format('M d, h:i A') }}
                                    </small>
                                </div>
                            </div>
                            <div class="ms-2 d-flex align-items-end mb-3">
                                <div style="width:32px;height:32px;background:#198754;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:13px;">
                                    👤
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-center text-muted py-5">No messages yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Staff Reply Form --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h6 class="mb-0 fw-semibold">💬 Reply as Staff</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.chatbot.reply', $customer->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <textarea
                        name="message"
                        class="form-control @error('message') is-invalid @enderror"
                        rows="3"
                        placeholder="Type your reply to {{ $customer->name }}..."
                        required
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">This reply will appear in the customer's chatbot screen.</small>
                    <button type="submit" class="btn btn-success px-4">
                        Send Reply ➤
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Auto-scroll to bottom of chat
    const chatWindow = document.getElementById('chat-window');
    if (chatWindow) {
        chatWindow.scrollTop = chatWindow.scrollHeight;
    }
</script>
@endpush