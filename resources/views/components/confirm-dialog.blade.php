<div class="confirm-overlay" id="{{ $id ?? 'confirm-overlay' }}" style="display:none" x-cloak>
    <div class="confirm-dialog fade-in">
        <div class="confirm-icon" style="background:{{ $bg ?? '#fee2e2' }};color:{{ $color ?? 'var(--danger)' }}">
            <i class="bi {{ $icon ?? 'bi-exclamation-triangle' }}"></i>
        </div>
        <h5>{{ $title ?? 'Konfirmasi' }}</h5>
        <p>{{ $message ?? 'Apakah kamu yakin ingin melanjutkan?' }}</p>
        <div class="confirm-actions">
            <button class="btn-outline-custom" onclick="document.getElementById('{{ $id ?? 'confirm-overlay' }}').style.display='none'">
                <i class="bi bi-x"></i> Batal
            </button>
            <button class="btn-primary-custom" id="{{ $confirmId ?? 'confirm-btn' }}" style="background:{{ $confirmBg ?? 'var(--danger)' }}">
                <i class="bi bi-check"></i> {{ $confirmLabel ?? 'Ya, Hapus' }}
            </button>
        </div>
    </div>
</div>