@props(['headers' => []])

<div class="overflow-x-auto rounded-lg border border-green-200 shadow-sm">
    <table class="min-w-full divide-y divide-green-200">
        <thead class="bg-gradient-to-r from-green-600 to-green-700">
            <tr>
                @foreach($headers as $header)
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                    {{ $header }}
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            {{ $slot }}
        </tbody>
    </table>
</div>
