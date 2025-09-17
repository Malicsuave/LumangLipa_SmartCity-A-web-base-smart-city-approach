{{-- Custom Dropdown Component --}}
<div class="dropdown">
    <div class="dropdown-menu custom-dropdown-menu {{ isset($dropup) && $dropup ? 'dropdown-menu-up' : '' }}" style="display: block; position: relative; float: none; min-width: 100%;">
        @if(isset($items) && is_array($items))
            @foreach($items as $item)
                @if(isset($item['type']) && $item['type'] === 'divider')
                    <div class="dropdown-divider"></div>
                @else
                    <a class="dropdown-item {{ $item['class'] ?? '' }}" 
                       href="{{ $item['href'] ?? 'javascript:void(0)' }}"
                       @if(isset($item['onclick']))
                           onclick="{{ $item['onclick'] }}"
                       @endif
                       @if(isset($item['target']))
                           target="{{ $item['target'] }}"
                       @endif>
                        @if(isset($item['icon']))
                            <i class="{{ $item['icon'] }} mr-2" aria-hidden="true"></i>
                        @endif
                        {{ $item['text'] ?? $item['label'] ?? '' }}
                    </a>
                @endif
            @endforeach
        @endif
    </div>
</div>
