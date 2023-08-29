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
                        <th class="py-2 px-4">Status</th>
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
                            <td class="py-2 px-4">{{ $scan->scan_status ?? ''}}</td>
                            <td class="py-2 px-4">{{ $scan->domain->industry->name ?? 'N/A' }}</td>
                            <td class="py-2 px-4">{{ \Carbon\Carbon::parse($scan->created_at)->toDateString()}}</td>
                            <td class="py-2 px-4">{{ $scan->score ?? 'N/A'}}</td>
                            <td class="py-2 px-4 flex flex-col sm:flex-row sm:gap-5">
                                <a href="{{ route('domains.show', $scan->domain->id) }}"
                                   class="bg-wave-500 hover:bg-wave-700 text-white font-bold py-2 px-4 rounded-full"
                                >View</a>
                                <a href="{{ route('scans.report', $scan->id) }}"
                                   class="bg-indigo-400 hover:bg-indigo-400 text-white font-bold py-2 px-4 rounded-full"
                                >Report</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
