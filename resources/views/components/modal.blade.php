<div class="modal-custom-overlay" id="{{ $id ?? 'modal-overlay' }}" style="display:none" x-cloak>
    <div class="modal-custom" @click.outside="closeModal()">
        <div class="modal-custom-header">
            <h5>{{ $title ?? 'Modal' }}</h5>
            <button class="modal-close" onclick="document.getElementById('{{ $id ?? 'modal-overlay' }}').style.display='none'">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <div class="modal-custom-body">
            {{ $slot ?? '' }}
        </div>
        @if(isset($footer))
        <div class="modal-custom-footer">
            {{ $footer }}
        </div>
        @endif
    </div>
</div>