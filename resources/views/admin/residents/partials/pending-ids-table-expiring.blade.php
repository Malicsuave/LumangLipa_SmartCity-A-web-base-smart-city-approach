<thead>
<tr>
    <th>
        <a href="{{ request()->fullUrlWithQuery(['expiring_sort' => 'barangay_id', 'expiring_direction' => request('expiring_sort') == 'barangay_id' && request('expiring_direction') == 'asc' ? 'desc' : 'asc', 'tab' => 'expiring']) }}" 
           class="text-decoration-none d-flex align-items-center" style="color: inherit;">
            Barangay ID
            @if(request('expiring_sort') == 'barangay_id')
                @if(request('expiring_direction') == 'asc')
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
        <a href="{{ request()->fullUrlWithQuery(['expiring_sort' => 'name', 'expiring_direction' => request('expiring_sort') == 'name' && request('expiring_direction') == 'asc' ? 'desc' : 'asc', 'tab' => 'expiring']) }}" 
           class="text-decoration-none d-flex align-items-center" style="color: inherit;">
            Name
            @if(request('expiring_sort') == 'name')
                @if(request('expiring_direction') == 'asc')
                    <i class="fe fe-chevron-up ml-1"></i>
                @else
                    <i class="fe fe-chevron-down ml-1"></i>
                @endif
            @else
                <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
            @endif
        </a>
    </th>
    <th>Type</th>
    <th>Age/Gender</th>
    <th>Expiry Date</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
@foreach($expiringSoon as $resident)
    @php $isLast = $loop->last; @endphp
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
        <td>{{ $resident->type_of_resident }}</td>
        <td>
            {{ \Carbon\Carbon::parse($resident->birthdate)->age }} years old
            <br><small class="text-muted">{{ $resident->sex }}</small>
        </td>
        <td>{{ $resident->id_expires_at ? $resident->id_expires_at->format('M d, Y') : 'N/A' }}</td>
        <td>
            @php
                $dropdownItems = [];
                $dropdownItems[] = [
                    'label' => 'Manage ID',
                    'icon' => 'fe fe-credit-card fe-16 text-primary',
                    'href' => route('admin.residents.id.show', $resident->id),
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
            @endphp
            @include('components.custom-dropdown', ['items' => $dropdownItems, 'dropup' => $isLast])
        </td>
    </tr>
@endforeach
</tbody> 