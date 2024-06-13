@foreach ($result['data'] as $item)
<tr>
    <td>#@first_letter($item->user_name){{ strtoupper(substr($item->id, -5)) }}</td>
    <td class="text-start"><a href="{{ route('kmanagement.coaching.show', ['id' => encrypt($item->id)]) }}">{{ $item->name }} </a></td>
    <td>@format_date($item->created_at)</td>
    <td>
        <div class="badge rounded-pill bg-@priority_color($item->priority) fs-12" id="ticket-priority">
            @priority_status($item->priority)</div>
    </td>
    <td>
        <div class="badge rounded-pill bg-@coaching_color_status($item->status) fs-12" id="ticket-status-2">@coaching_label_status($item->status)</div>
    </td>
</tr>
@endforeach