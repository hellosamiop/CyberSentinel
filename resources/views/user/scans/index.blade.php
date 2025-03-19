@php use Carbon\Carbon; @endphp
@extends('theme::layouts.app')
@section('content')
    <div class="py-20 mx-auto max-w-7xl">
        <div class="mx-auto px-4 py-4">
            <a href="{{ route('scans.create') }}"
               class="bg-wave-500 hover:bg-wave-700 text-white font-bold py-2 px-4 rounded-full">
                New Scan
            </a>
        </div>
        <div class="container bg-white mx-auto p-5 border" style="border-radius: 15px">
            <div style="overflow-x: auto">
                <table class="w-full">
                    <thead class="border-b">
                    <tr class="text-left">
                        <th class="py-2 px-4">ID</th>
                        <th class="py-2 px-4">Domain URL</th>
                        <th class="py-2 px-6">Status</th>
                        <th class="py-2 px-4">Type</th>
                        <th class="py-2 px-4">Date</th>
                        <th class="py-2 px-4">Score</th>
                        <th class="py-2 px-4">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($scans as $scan)
                        <tr>
                            <td class="py-2 px-4">{{ $scan->id }}</td>
                            <td class="py-2 px-4">{{ $scan->domain->domain_url }}</td>
                            <td class="py-2 pl-4">
                                <div class="flex items-center">
                                    @if(!$scan->isComplete())
                                        <img width="50" height="50" src="{{asset('assets/images/loading.gif')}}" alt="">
                                    @endif
                                    <div class="mt-3">
                                        <span id="scan-status-{{ $scan->id }}">{{ $scan->isComplete() ? 'Complete' : 'In Progress' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 px-4">{{ $scan->domain->industry->name ?? 'N/A' }}</td>
                            <td class="py-2 px-4">{{ Carbon::parse($scan->created_at)->toDateString() }}</td>
                            <td class="py-2 px-4">{{ $scan->score ?? 'N/A' }}</td>
                            <td class="py-2 px-4 flex flex-col sm:flex-row sm:gap-5">
                                @if(!$scan->isComplete())
                                    <img width="50" height="50" src="{{asset('assets/images/loading.gif')}}" alt="">
                                @else
                                    <a href="{{ route('domains.show', $scan->domain->id) }}"
                                       class="bg-wave-500 hover:bg-wave-700 text-white font-bold py-2 px-4 rounded-full">
                                        View
                                    </a>
                                    <a href="{{ route('scans.report', $scan->id) }}"
                                       class="bg-indigo-400 hover:bg-indigo-400 text-white font-bold py-2 px-4 rounded-full">
                                        Report
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function updateScanStatuses() {
            fetch('{{ route("scans.status") }}')
                .then(response => response.json())
                .then(statuses => {
                    for (const [scanId, status] of Object.entries(statuses)) {
                        const statusElement = document.getElementById('scan-status-' + scanId);
                        if (statusElement && statusElement.innerText !== status) {
                            statusElement.innerText = status;
                        }
                    }
                })
                .catch(error => console.error('Error fetching scan statuses:', error));
        }

        setInterval(updateScanStatuses, 2000);
    </script>
@endsection
