<div class="custom-dropdown" x-data="{
    open: false,
    openUp: {{ isset($dropup) && $dropup ? 'true' : 'false' }},
    dropdownId: Math.random().toString(36).substr(2, 9),
    closeOthers() {
        window.dispatchEvent(new CustomEvent('close-all-dropdowns', { detail: { except: this.dropdownId } }));
    }
}"
@keydown.escape.window="open = false"
@click.away="open = false"
@close-all-dropdowns.window="if ($event.detail.except !== dropdownId) open = false">
    @if(isset($buttonText))
        <button type="button" class="btn btn-dark custom-dropdown-btn"
            @click="closeOthers(); open = !open;"
            :aria-expanded="open"
            style="vertical-align: middle;">
            <i class="fe fe-save fe-16 mr-2"></i> {{ $buttonText }}
        </button>
    @else
        <button type="button" class="btn btn-sm btn-icon custom-dropdown-btn"
            @click="closeOthers(); open = !open;"
            :aria-expanded="open"
            style="vertical-align: middle;">
            <i class="fas fa-ellipsis-h"></i>
        </button>
    @endif
    <div class="custom-dropdown-menu"
        x-show="open"
        x-transition
        x-cloak
        @click.away="open = false"
        :style="openUp ? 'bottom: 100%; top: auto;' : 'top: 100%; bottom: auto;'">
        @php $first = true; @endphp
        @foreach($items as $item)
            @if(isset($item['divider']) && $item['divider'])
                @php $first = true; @endphp
                @continue
            @endif
            @if(!isset($item['label']))
                @continue
            @endif
            @if(!$first)
                <div style="height: 2px;"></div>
            @endif
            @php $first = false; @endphp
            @php
                $iconClass = $item['label'] === 'Reject'
                    ? 'fe fe-x-circle fe-16 text-danger'
                    : $item['icon'];
            @endphp

            @if(isset($item['is_form']) && $item['is_form'])
                <form action="{{ $item['form_action'] }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="custom-dropdown-item d-flex align-items-center {{ $item['class'] ?? '' }}"
                        style="padding: 0.35rem 0.75rem; gap: 0.35rem; align-items: center; background:none; border:none; width:100%; text-align:left;">
                        <span style="display: flex; align-items: center; justify-content: center; min-width: 22px; height: 22px;">
                            <i class="{{ $iconClass }}" style="font-size: 1.05em; width: 18px; height: 18px; line-height: 1; display: flex; align-items: center; justify-content: center;"></i>
                        </span>
                        <span style="flex:1; display: flex; align-items: center;">{{ $item['label'] }}</span>
                    </button>
                </form>
            @else
                <a href="{{ $item['href'] ?? '#' }}" class="custom-dropdown-item d-flex align-items-center {{ $item['class'] ?? '' }}"
                   {!! isset($item['attrs']) ? $item['attrs'] : '' !!}
                   @if(isset($item['class']) && !empty($item['class']))
                       @click="open = false"
                   @endif
                   style="padding: 0.35rem 0.75rem; gap: 0.35rem; align-items: center;">
                    <span style="display: flex; align-items: center; justify-content: center; min-width: 22px; height: 22px;">
                        <i class="{{ $iconClass }}" style="font-size: 1.05em; width: 18px; height: 18px; line-height: 1; display: flex; align-items: center; justify-content: center;"></i>
                    </span>
                    <span style="flex:1; display: flex; align-items: center;">{{ $item['label'] }}</span>
                </a>
            @endif
        @endforeach
    </div>
</div>
<style>
.custom-dropdown-item {
    transition: background 0.15s, color 0.15s;
    border-radius: 4px;
    cursor: pointer;
    color: #22223b !important;
    font-weight: 400;
}
.custom-dropdown-item:hover, .custom-dropdown-item:focus {
    background: #f0f1f3 !important;
    color: #4a90e2 !important;
    text-decoration: none;
}
.custom-dropdown-item.danger-action:hover, .custom-dropdown-item.danger-action:focus {
    background: #fbeaea !important;
    color: #e3342f !important;
}
</style>