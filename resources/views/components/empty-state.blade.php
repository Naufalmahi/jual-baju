<div class="empty-state-wrapper fade-in">
    <div class="empty-icon" style="background:{{ $bg ?? 'var(--primary-lighter)' }};color:{{ $color ?? 'var(--primary)' }}">
        <i class="bi {{ $icon ?? 'bi-inbox' }}"></i>
    </div>
    <h5>{{ $title ?? 'Tidak Ada Data' }}</h5>
    <p>{{ $message ?? 'Belum ada data yang tersedia.' }}</p>
    @if(isset($actionUrl) && isset($actionLabel))
        <a href="{{ $actionUrl }}" class="btn-primary-custom">
            <i class="bi {{ $actionIcon ?? 'bi-plus' }}"></i> {{ $actionLabel }}
        </a>
    @endif
</div>