<div class="skeleton-product-grid">
    @for($i = 0; $i < ($count ?? 4); $i++)
        @include('components.skeleton-card')
    @endfor
</div>