<thead>
<tr>
    <th>
        <a href="{{ request()->fullUrlWithQuery(['renewal_sort' => 'barangay_id', 'renewal_direction' => request('renewal_sort') == 'barangay_id' && request('renewal_direction') == 'asc' ? 'desc' : 'asc', 'tab' => 'renewal']) }}" 
           class="text-decoration-none d-flex align-items-center" style="color: inherit;">
            Barangay ID
            @if(request('renewal_sort') == 'barangay_id')
                @if(request('renewal_direction') == 'asc')
                    <i class="fe fe-chevron-up ml-1"></i>
                @else
                    <i class="fe fe-chevron-down ml-1"></i>
                @endif
            @else
                <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
            @endif
        </a>
    </th>
    <th>
        <a href="{{ request()->fullUrlWithQuery(['renewal_sort' => 'name', 'renewal_direction' => request('renewal_sort') == 'name' && request('renewal_direction') == 'asc' ? 'desc' : 'asc', 'tab' => 'renewal']) }}" 
           class="text-decoration-none d-flex align-items-center" style="color: inherit;">
            Name
            @if(request('renewal_sort') == 'name')
                @if(request('renewal_direction') == 'asc')
                    <i class="fe fe-chevron-up ml-1"></i>
                @else
                    <i class="fe fe-chevron-down ml-1"></i>
                @endif
            @else
                <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
            @endif
        </a>
    </th>
    <th>
        <a href="{{ request()->fullUrlWithQuery(['renewal_sort' => 'type', 'renewal_direction' => request('renewal_sort') == 'type' && request('renewal_direction') == 'asc' ? 'desc' : 'asc', 'tab' => 'renewal']) }}" 
           class="text-decoration-none d-flex align-items-center" style="color: inherit;">
            Type
            @if(request('renewal_sort') == 'type')
                @if(request('renewal_direction') == 'asc')
                    <i class="fe fe-chevron-up ml-1"></i>
                @else
                    <i class="fe fe-chevron-down ml-1"></i>
                @endif
            @else
                <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
            @endif
        </a>
    </th>
    <th>
        <a href="{{ request()->fullUrlWithQuery(['renewal_sort' => 'age', 'renewal_direction' => request('renewal_sort') == 'age' && request('renewal_direction') == 'asc' ? 'desc' : 'asc', 'tab' => 'renewal']) }}" 
           class="text-decoration-none d-flex align-items-center" style="color: inherit;">
            Age/Gender
            @if(request('renewal_sort') == 'age')
                @if(request('renewal_direction') == 'asc')
                    <i class="fe fe-chevron-up ml-1"></i>
                @else
                    <i class="fe fe-chevron-down ml-1"></i>
                @endif
            @else
                <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
            @endif
        </a>
    </th>
    <th>
        <a href="{{ request()->fullUrlWithQuery(['renewal_sort' => 'issued_date', 'renewal_direction' => request('renewal_sort') == 'issued_date' && request('renewal_direction') == 'asc' ? 'desc' : 'asc', 'tab' => 'renewal']) }}" 
           class="text-decoration-none d-flex align-items-center" style="color: inherit;">
            Issued Date
            @if(request('renewal_sort') == 'issued_date')
                @if(request('renewal_direction') == 'asc')
                    <i class="fe fe-chevron-up ml-1"></i>
                @else
                    <i class="fe fe-chevron-down ml-1"></i>
                @endif
            @else
                <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
            @endif
        </a>
    </th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
@foreach($pendingRenewal as $resident)
@php
    $isLast = $loop->last;
@endphp
<tr>
    <td><strong>{{ $resident->barangay_id }}</strong></td>
    <td>
        <div class="d-flex align-items-center">
            <div class="avatar avatar-sm mr-2">
                @if($resident->photo)
                    <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}" class="avatar-img rounded-circle">
                @else
                    <div class="avatar-letter rounded-circle bg-warning">{{ substr($resident->first_name, 0, 1) }}</div>
                @endif
            </div>
            <div>
                <strong>{{ $resident->last_name }}, {{ $resident->first_name }}</strong>
                @if($resident->middle_name)
                    {{ substr($resident->middle_name, 0, 1) }}.
                @endif
                {{ $resident->suffix }}
            </div>
        </div>
    </td>
    <td>
        {{ $resident->type_of_resident }}
    </td>
    <td>
        {{ \Carbon\Carbon::parse($resident->birthdate)->age }} years old
        <br><small class="text-muted">{{ $resident->sex }}</small>
    </td>
    <td>{{ $resident->id_issued_at ? $resident->id_issued_at->format('M d, Y') : 'N/A' }}</td>
    <td>
        @php
            $dropdownItems = [];
            $dropdownItems[] = [
                'label' => 'Manage ID',
                'icon' => 'fe fe-credit-card fe-16 text-primary',
                'href' => route('admin.residents.id.show', $resident->id),
            ];
            $dropdownItems[] = [
                'label' => 'Issue New ID (Renew)',
                'icon' => 'fe fe-check-circle fe-16 text-success',
                'class' => '',
                'attrs' => 'onclick="return confirm(\'Are you sure you want to issue a new ID for this resident?\')"',
                'href' => '',
                'is_form' => true,
                'form_action' => route('admin.residents.id.issue', $resident->id),
            ];
            $dropdownItems[] = [
                'label' => 'Preview ID',
                'icon' => 'fe fe-image fe-16 text-info',
                'href' => route('admin.residents.id.preview', $resident->id),
            ];
            $dropdownItems[] = [
                'label' => 'Download ID',
                'icon' => 'fe fe-download fe-16 text-success',
                'href' => route('admin.residents.id.download', $resident->id),
            ];
            $dropdownItems[] = ['divider' => true];
            $dropdownItems[] = [
                'label' => 'Revoke ID',
                'icon' => 'fe fe-x-circle fe-16 text-danger',
                'attrs' => 'onclick="return confirm(\'Are you sure you want to revoke this resident\\\'s ID? This action cannot be undone.\')"',
                'href' => '',
                'is_form' => true,
                'form_action' => route('admin.residents.id.revoke', $resident->id),
            ];
            $dropdownItems[] = [
                'label' => 'Remove from Renewal Queue',
                'icon' => 'fe fe-minus-circle fe-16 text-warning',
                'class' => '',
                'attrs' => 'onclick="return confirm(\'Remove this resident from the renewal queue?\')"',
                'href' => '',
                'is_form' => true,
                'form_action' => route('admin.residents.id.remove-renewal', $resident->id),
            ];
        @endphp
        @include('components.custom-dropdown', ['items' => $dropdownItems, 'dropup' => $isLast])
    </td>
</tr>
@endforeach
</tbody> 