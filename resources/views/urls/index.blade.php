<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            URLs ({{ $roleName }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($urls->isEmpty())
                        <p>No URLs available with your visibility rules.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left border-b">
                                        <th class="py-2 pe-4">Code</th>
                                        <th class="py-2 pe-4">Original URL</th>
                                        <th class="py-2 pe-4">Created By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($urls as $url)
                                        <tr class="border-b">
                                            <td class="py-2 pe-4">{{ $url->short_code }}</td>
                                            <td class="py-2 pe-4 break-all">{{ $url->original_url }}</td>
                                            <td class="py-2 pe-4">{{ $url->creator?->name ?? 'System' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
