<x-dashboard-layout title="Concerts">
    <x-slot:actions>
        <a href="{{ route('dashboard.concerts.create') }}" class="btn btn-primary">New concert</a>
    </x-slot:actions>

    <div class="card">
        @if ($concerts->isEmpty())
            <div class="empty">
                <div class="empty-icon">{!! $mdi('music-note-outline', 'icon', 48) !!}</div>
                <p class="empty-title">No concerts yet</p>
                <p class="empty-subtitle text-secondary">Add one here, or wait for the next concert import.</p>
                <div class="empty-action">
                    <a href="{{ route('dashboard.concerts.create') }}" class="btn btn-primary">New concert</a>
                </div>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Concert</th>
                            <th>Date</th>
                            <th>Ensemble</th>
                            <th>Source</th>
                            <th class="w-1"><span class="visually-hidden">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($concerts as $concert)
                            <tr @class(['text-secondary' => $concert->start_datetime->isPast()])>
                                <td>
                                    {{ $concert->name }}
                                    <div class="small text-secondary">{{ $concert->location }}</div>
                                </td>
                                <td>{{ $concert->start_datetime->format('j M Y') }}</td>
                                <td>{{ $concert->ensemble }}</td>
                                <td><x-dashboard.source-badge :imported="$concert->external_id !== null" :keepsLocalEdits="$concert->keep_local_edits" /></td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('dashboard.concerts.edit', $concert) }}" class="btn btn-sm">Edit</a>
                                        <x-dashboard.confirm-delete :action="route('dashboard.concerts.destroy', $concert)" :name="$concert->name" :imported="$concert->external_id !== null" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($concerts->hasPages())
                <div class="card-footer">{{ $concerts->links('pagination::bootstrap-5') }}</div>
            @endif
        @endif
    </div>
</x-dashboard-layout>
