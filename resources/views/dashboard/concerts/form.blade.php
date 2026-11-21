<x-dashboard-layout :title="$concert->exists ? 'Edit concert' : 'New concert'" pretitle="Concerts">
    <form method="POST" action="{{ $concert->exists ? route('dashboard.concerts.update', $concert) : route('dashboard.concerts.store') }}" class="card">
        @csrf
        @if ($concert->exists)
            @method('PUT')
        @endif

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <x-dashboard.field name="name" label="Name" :value="$concert->name" required />
                </div>
                <div class="col-md-6">
                    <x-dashboard.field name="slug" label="Slug" :value="$concert->slug" required hint="Letters, numbers, dashes and underscores." />
                </div>
                <div class="col-md-4">
                    <x-dashboard.field name="ensemble" label="Ensemble" type="select" required>
                        @foreach (array_keys(App\Models\Concert::ENSEMBLES) as $ensemble)
                            <option value="{{ $ensemble }}" @selected(old('ensemble', $concert->ensemble) === $ensemble)>{{ $ensemble }}</option>
                        @endforeach
                    </x-dashboard.field>
                </div>
                <div class="col-md-4">
                    <x-dashboard.field name="start_datetime" label="Starts" type="datetime-local" :value="$concert->start_datetime?->format('Y-m-d\TH:i')" required />
                </div>
                <div class="col-md-4">
                    <x-dashboard.field name="end_datetime" label="Ends" type="datetime-local" :value="$concert->end_datetime?->format('Y-m-d\TH:i')" />
                </div>
                <div class="col-md-6">
                    <x-dashboard.field name="location" label="Venue" :value="$concert->exists ? $concert->location : null" required />
                </div>
                <div class="col-md-6">
                    <x-dashboard.field name="address" label="Address" :value="$concert->address" />
                </div>
            </div>

            <x-dashboard.field name="description" label="Description" type="textarea" :value="$concert->description" rows="5" />

            <div class="row">
                <div class="col-md-4">
                    <x-dashboard.field name="event_url" label="Event page" type="url" :value="$concert->event_url" />
                </div>
                <div class="col-md-4">
                    <x-dashboard.field name="ticket_url" label="Tickets" type="url" :value="$concert->ticket_url" />
                </div>
                <div class="col-md-4">
                    <x-dashboard.field name="poster_url" label="Poster image" type="url" :value="$concert->poster_url" />
                </div>
            </div>

            @if ($concert->external_id !== null)
                <x-dashboard.keep-local-edits />
            @endif
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('dashboard.concerts.index') }}" class="btn">Cancel</a>
            <button type="submit" class="btn btn-primary">Save concert</button>
        </div>
    </form>
</x-dashboard-layout>
