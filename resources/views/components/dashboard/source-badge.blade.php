@props(['imported', 'keepsLocalEdits'])

@if (! $imported)
    <span class="badge bg-green-lt">Manual</span>
@elseif ($keepsLocalEdits)
    <span class="badge bg-yellow-lt">Local edits kept</span>
@else
    <span class="badge bg-blue-lt">Imported</span>
@endif
