@php($kept = App\Helpers\ScheduleRuns::KEPT)

<x-dashboard-layout title="Scheduled tasks">
    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Schedule</th>
                        <th>Next run</th>
                        <th>Last result</th>
                        <th>Last {{ $kept }} runs</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        @php($latestRun = $task['runs'][0] ?? null)
                        <tr>
                            <td><code>{{ $task['name'] }}</code></td>
                            <td><code>{{ $task['expression'] }}</code></td>
                            <td title="{{ $task['nextRunAt']->toDayDateTimeString() }}">{{ $task['nextRunAt']->diffForHumans() }}</td>
                            <td>
                                @if (! $latestRun)
                                    <span class="status status-secondary">Never run</span>
                                @else
                                    <span @class(['status', 'status-green' => $latestRun['succeeded'], 'status-red' => ! $latestRun['succeeded']])>
                                        <span @class(['status-dot', 'status-dot-animated' => $task['everyMinute']])></span>
                                        {{ $latestRun['succeeded'] ? 'Passed' : 'Failed' }}
                                    </span>
                                    <div class="text-secondary small">{{ Carbon\Carbon::parse($latestRun['ran_at'])->diffForHumans() }}</div>
                                @endif
                            </td>
                            <td>
                                @if ($task['runs'])
                                    <div class="tracking tracking-squares" aria-label="{{ collect($task['runs'])->where('succeeded', false)->count() }} of the last {{ count($task['runs']) }} runs failed">
                                        @foreach (array_reverse($task['runs']) as $run)
                                            <div @class(['tracking-block', 'bg-success' => $run['succeeded'], 'bg-danger' => ! $run['succeeded']]) data-bs-toggle="tooltip" data-bs-placement="top" title="{{ Carbon\Carbon::parse($run['ran_at'])->toDayDateTimeString() }}: {{ $run['succeeded'] ? 'passed' : 'failed' }}"></div>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @php($latestFailure = collect($task['runs'])->firstWhere('succeeded', false))
                        @if ($latestFailure)
                            <tr>
                                <td colspan="5" class="border-top-0 pt-0">
                                    <details>
                                        <summary class="text-danger">Latest failure, {{ Carbon\Carbon::parse($latestFailure['ran_at'])->toDayDateTimeString() }}</summary>
                                        <pre class="mt-2 mb-0">{{ $latestFailure['output'] ?: 'No output.' }}</pre>
                                    </details>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>
