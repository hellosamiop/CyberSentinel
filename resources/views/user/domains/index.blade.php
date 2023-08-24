@extends('theme::layouts.app')
@section('content')
    <div class="py-20 mx-auto max-w-7xl">
        <div class="mx-auto px-4 py-4">
            <a href="{{ route('domains.create') }}"
               class="bg-wave-500 hover:bg-wave-700 text-white font-bold py-2 px-4 rounded-full">
                Add Domain
            </a>
        </div>
        <div class="container bg-white mx-auto p-5 border" style="border-radius: 15px">
            <div style="overflow-x: auto">
                <table class="w-full">
                    <thead class="border-b">
                    <tr class="text-left">
                        <th class="py-2 px-4">Name</th>
                        <th class="py-2 px-4">Domain URL</th>
                        <th class="py-2 px-4">Industry</th>
                        <th class="py-2 px-4">Verified</th>
                        <th class="py-2 px-4">Score</th>
                        <th class="py-2 px-4">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($domains as $domain)
                        <tr>
                            <td class="py-2 px-4">{{ $domain->name }}</td>
                            <td class="py-2 px-4">{{ $domain->domain_url }}</td>
                            <td class="py-2 px-4">{{ $domain->industry->name ?? 'N/A' }}</td>
                            <td class="py-2 px-4"><span class="rounded-full" style="background-color: {{ $domain->verified ? '#84ca2a' : '#d4d4d4' }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                            <td class="py-2 px-4">{{ $domain->score ?? 'N/A'}}</td>
                            <td class="py-2 px-4 flex flex-col sm:flex-row sm:gap-5">
                                <a href="{{ route('domains.show', $domain) }}"
                                   class="bg-wave-500 hover:bg-wave-700 text-white font-bold py-2 px-4 rounded-full"
                                >View</a>
                                <a href="{{ route('domains.edit', $domain) }}"
                                   class="bg-indigo-400 hover:bg-indigo-400 text-white font-bold py-2 px-4 rounded-full"
                                >Edit</a>
                                <button type="button" onclick="deleteDomain({{ $domain->id }})"
                                        class="bg-red-500 hover:bg-wave-700 text-white font-bold py-2 px-4 rounded-full"
                                >Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        function deleteDomain(id) {
            Swal.fire({
                title: 'Are you sure you want to remove this domain?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#95d148',
                cancelButtonColor: '#d4d4d4',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = '{{ route('domains.destroy', 'domain_id') }}'
                    url = url.replace('domain_id', id)

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    }).then(response => {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Your domain has been deleted.',
                            icon: 'success',
                            confirmButtonColor: '#95d148',
                            confirmButtonText: 'OK'
                        }).then((r) => {
                            window.location.reload()
                        })
                    })
                }
            })
        }
    </script>
@endpush
