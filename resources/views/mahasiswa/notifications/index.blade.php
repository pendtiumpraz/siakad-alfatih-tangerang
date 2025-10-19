@extends('layouts.mahasiswa')

@section('title', 'Pengumuman')

@section('content')
<div class="space-y-6" x-data="{ filter: 'all' }">
    <!-- Page Title -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-3">
                <svg class="w-8 h-8 text-[#4A7C59]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                <span>Pengumuman</span>
            </h1>
            <p class="text-gray-600 mt-1">Informasi dan pengumuman penting</p>
        </div>
    </div>

    <div class="islamic-divider"></div>

    <!-- Filter Tabs -->
    <div class="card-islamic p-2">
        <div class="flex space-x-2">
            <button
                @click="filter = 'all'"
                :class="filter === 'all' ? 'bg-[#4A7C59] text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="px-6 py-3 rounded-lg font-semibold transition"
            >
                Semua
            </button>
            <button
                @click="filter = 'unread'"
                :class="filter === 'unread' ? 'bg-[#4A7C59] text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="px-6 py-3 rounded-lg font-semibold transition flex items-center space-x-2"
            >
                <span>Belum Dibaca</span>
                @php
                    $unreadCount = $notifications->whereNull('read_at')->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $unreadCount }}</span>
                @endif
            </button>
            <button
                @click="filter = 'read'"
                :class="filter === 'read' ? 'bg-[#4A7C59] text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="px-6 py-3 rounded-lg font-semibold transition"
            >
                Sudah Dibaca
            </button>
        </div>
    </div>

    <!-- Notification Cards -->
    <div class="space-y-4">
        @forelse($notifications as $notification)
            @php
                $isUnread = is_null($notification->read_at);
                $borderColor = match($notification->type) {
                    'info' => 'border-blue-500',
                    'penting' => 'border-red-500',
                    'pengingat' => 'border-yellow-500',
                    'kegiatan' => 'border-green-500',
                    default => 'border-gray-500'
                };
                $bgColor = match($notification->type) {
                    'info' => 'bg-blue-50',
                    'penting' => 'bg-red-50',
                    'pengingat' => 'bg-yellow-50',
                    'kegiatan' => 'bg-green-50',
                    default => 'bg-gray-50'
                };
                $iconBgColor = match($notification->type) {
                    'info' => 'bg-blue-100',
                    'penting' => 'bg-red-100',
                    'pengingat' => 'bg-yellow-100',
                    'kegiatan' => 'bg-green-100',
                    default => 'bg-gray-100'
                };
                $iconColor = match($notification->type) {
                    'info' => 'text-blue-600',
                    'penting' => 'text-red-600',
                    'pengingat' => 'text-yellow-600',
                    'kegiatan' => 'text-green-600',
                    default => 'text-gray-600'
                };
                $badgeColor = match($notification->type) {
                    'info' => 'bg-blue-600',
                    'penting' => 'bg-red-600',
                    'pengingat' => 'bg-yellow-600',
                    'kegiatan' => 'bg-green-600',
                    default => 'bg-gray-600'
                };
            @endphp

            <div
                class="card-islamic p-6 hover:shadow-xl transition border-l-4 {{ $borderColor }} {{ $isUnread ? $bgColor : 'opacity-75' }}"
                x-show="filter === 'all' || (filter === 'unread' && {{ $isUnread ? 'true' : 'false' }}) || (filter === 'read' && {{ $isUnread ? 'false' : 'true' }})"
                x-transition
            >
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4 flex-1">
                        <div class="{{ $iconBgColor }} p-3 rounded-full">
                            @if($notification->type === 'info')
                                <svg class="w-8 h-8 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($notification->type === 'penting')
                                <svg class="w-8 h-8 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($notification->type === 'pengingat')
                                <svg class="w-8 h-8 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($notification->type === 'kegiatan')
                                <svg class="w-8 h-8 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-8 h-8 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <h3 class="text-lg font-bold text-gray-800">{{ $notification->title }}</h3>
                                @if($isUnread)
                                    <span class="inline-block px-3 py-1 {{ $badgeColor }} text-white rounded-full text-xs font-bold">
                                        Baru
                                    </span>
                                @else
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>
                            <p class="text-sm text-gray-700 mb-3 leading-relaxed">
                                {{ $notification->message }}
                            </p>
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <div class="flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ $notification->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>{{ $notification->pembuat }} ({{ $notification->pembuat_role }})</span>
                                </div>
                            </div>
                            @if($isUnread)
                                <div class="mt-4">
                                    <form action="{{ route('mahasiswa.notifications.mark-read', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-[#4A7C59] hover:text-[#D4AF37] font-semibold text-sm flex items-center space-x-1">
                                            <span>Tandai sudah dibaca</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card-islamic p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-gray-600 text-lg font-semibold mb-2">Belum ada pengumuman</p>
                <p class="text-gray-500 text-sm">Pengumuman akan muncul di sini ketika tersedia</p>
            </div>
        @endforelse
    </div>

    <!-- Islamic Quote -->
    <div class="card-islamic p-6 text-center islamic-pattern">
        <svg class="w-10 h-10 text-[#D4AF37] mx-auto mb-3" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
        </svg>
        <p class="text-lg text-[#4A7C59] font-semibold mb-2">
            وَذَكِّرْ فَإِنَّ الذِّكْرَىٰ تَنفَعُ الْمُؤْمِنِينَ
        </p>
        <p class="text-gray-600 italic text-sm">
            Dan tetaplah memberi peringatan, karena sesungguhnya peringatan itu bermanfaat bagi orang-orang yang beriman
        </p>
        <p class="text-xs text-gray-500 mt-1">(QS. Adz-Dzariyat: 55)</p>
    </div>
</div>
@endsection
