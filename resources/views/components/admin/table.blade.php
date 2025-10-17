@props(['headers' => [], 'rows' => []])

<div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white">
                <tr>
                    @foreach($headers as $header)
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
