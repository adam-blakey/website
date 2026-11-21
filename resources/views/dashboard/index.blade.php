@php
    use App\Http\Controllers\Dashboard\OverviewController;
    $mostVisitedCount = max([1, ...array_values($visits['posts'] ?? [])]);
@endphp

<x-dashboard-layout title="Overview">
    <div class="row row-deck row-cards mb-3">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="subheader">Visits</div>
                    <div class="d-flex align-items-baseline gap-2">
                        <div class="h1 mb-0">{{ $visits ? number_format($visits['total']) : '–' }}</div>
                        @if ($visits)
                            <span class="sparkline text-primary ms-auto" data-bs-toggle="sparkline" data-bs-values="{{ implode(',', $visits['daily']) }}" aria-label="Visits per day over the last {{ OverviewController::TREND_DAYS }} days: {{ implode(', ', $visits['daily']) }}"></span>
                        @endif
                    </div>
                    <div class="text-secondary small">
                        {{ $visits ? 'All time · last ' . OverviewController::TREND_DAYS . ' days in the chart' : (config('services.goatcounter.code') ? 'GoatCounter is unavailable' : 'GoatCounter is not set up') }}
                    </div>
                </div>
            </div>
        </div>
        @foreach ([
            ['Blog posts', $postCount, route('dashboard.posts.index')],
            ['Upcoming concerts', $upcomingConcertCount, route('dashboard.concerts.index')],
            ['Contact messages', $contactCount, null],
        ] as [$label, $count, $link])
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="subheader">{{ $label }}</div>
                        <div class="h1 mb-0">{{ number_format($count) }}</div>
                        @if ($link)
                            <a class="small" href="{{ $link }}">Manage</a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row row-cards">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Most visited posts</h2>
                </div>
                @if ($mostVisitedPosts->isEmpty())
                    <div class="card-body text-secondary">No visit counts to show.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Post</th>
                                    <th class="text-end">Visits</th>
                                    <th class="w-25"><span class="visually-hidden">Share of the most visited post</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mostVisitedPosts as $post)
                                    @php($postVisits = $visits['posts'][$post->id] ?? 0)
                                    <tr>
                                        <td><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></td>
                                        <td class="text-end">{{ number_format($postVisits) }}</td>
                                        <td>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar" style="width: {{ round($postVisits / $mostVisitedCount * 100) }}%" role="progressbar" aria-valuenow="{{ $postVisits }}" aria-valuemin="0" aria-valuemax="{{ $mostVisitedCount }}" aria-label="{{ $postVisits }} visits"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Site details</h2>
                </div>
                <div class="card-body">
                    <div class="datagrid" style="--tblr-datagrid-item-width: 12rem">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Last track played</div>
                            <div class="datagrid-content">
                                @if ($latestTrack)
                                    {{ $latestTrack->track_name }} – {{ $latestTrack->artist_name }}
                                    <div class="text-secondary small">{{ $latestTrack->played_at->diffForHumans() }}</div>
                                @else
                                    –
                                @endif
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Blog media cache</div>
                            <div class="datagrid-content">{{ number_format($blogMediaBytes / 1024 ** 2, 1) }} MB</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Resized site images</div>
                            <div class="datagrid-content">{{ number_format($resizedImageBytes / 1024 ** 2, 1) }} MB</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Errors</div>
                            <div class="datagrid-content"><a href="https://nightwatch.laravel.com" target="_blank" rel="noopener">Open Nightwatch</a></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Scheduled tasks</div>
                            <div class="datagrid-content"><a href="{{ route('dashboard.schedule') }}">View status</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
