<header class="bg-base-100 border-b border-base-300 h-16 flex items-center px-6 gap-4 sticky top-0 z-30 shadow-sm">

    {{-- Mobile Hamburger --}}
    <button onclick="toggleSidebar()" class="btn btn-square btn-ghost lg:hidden" aria-label="Buka navigasi sidebar">
        <i class="fas fa-bars text-lg"></i>
    </button>

    {{-- Breadcrumb --}}
    <div class="flex-1">
        <div class="flex items-center gap-2 text-sm">
            <span class="text-base-content/50">Poliklinik</span>
            <i class="fas fa-chevron-right text-xs text-base-content/30"></i>
            <span class="font-semibold text-base-content">
                {{ $title ?? 'Dashboard' }}
            </span>
        </div>
    </div>

    {{-- Fullscreen --}}
    <button onclick="toggleFullscreen()" class="btn btn-square btn-ghost" aria-label="Beralih layar penuh">
        <i id="fsIcon" class="fas fa-expand w-5 h-5"></i>
    </button>

    @auth
    {{-- Notification Bell Dropdown --}}
    <div class="dropdown dropdown-end">
        <div tabindex="0" role="button" class="btn btn-ghost btn-circle relative" title="Notifikasi">
            <div class="indicator">
                <i class="fas fa-bell text-lg"></i>
                @php $unreadCount = auth()->user()->unreadNotifications()->count(); @endphp
                @if($unreadCount > 0)
                    <span class="badge badge-sm badge-error indicator-item font-bold text-white">{{ $unreadCount }}</span>
                @endif
            </div>
        </div>
        <div tabindex="0" class="dropdown-content z-[40] card card-compact w-80 sm:w-96 p-2 shadow-2xl bg-base-100 border border-base-300 mt-2">
            <div class="card-body">
                <div class="flex items-center justify-between border-b border-base-200 pb-2">
                    <span class="font-bold text-base flex items-center gap-2">
                        <i class="fas fa-bell text-primary"></i> Notifikasi
                        @if($unreadCount > 0)
                            <span class="badge badge-primary badge-sm">{{ $unreadCount }} baru</span>
                        @endif
                    </span>
                    @if($unreadCount > 0)
                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-primary hover:underline font-medium">Tandai semua dibaca</button>
                        </form>
                    @endif
                </div>
                <div class="max-h-72 overflow-y-auto space-y-2 py-1">
                    @php $recentNotifs = auth()->user()->notifications()->take(6)->get(); @endphp
                    @forelse($recentNotifs as $notif)
                        <div class="p-2.5 rounded-lg transition-colors flex flex-col gap-1 {{ $notif->read_at ? 'bg-base-200/40 text-base-content/70' : 'bg-primary/10 border-l-4 border-primary text-base-content' }}">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-xs">{{ $notif->data['title'] ?? 'Notifikasi' }}</span>
                                <span class="text-[10px] text-base-content/50">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs leading-relaxed">{{ $notif->data['message'] ?? '' }}</p>
                            <div class="flex items-center justify-between mt-1 pt-1 border-t border-base-200/50">
                                @if(isset($notif->data['action_url']))
                                    <a href="{{ $notif->data['action_url'] }}" class="text-[11px] text-primary hover:underline">Lihat &rarr;</a>
                                @else
                                    <span></span>
                                @endif
                                @if(!$notif->read_at)
                                    <form action="{{ route('notifications.markAsRead', $notif->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-[10px] text-base-content/60 hover:text-primary">Tandai dibaca</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-base-content/50">
                            <i class="fas fa-bell-slash text-2xl mb-1 opacity-40"></i>
                            <p class="text-xs">Belum ada notifikasi</p>
                        </div>
                    @endforelse
                </div>
                <div class="border-t border-base-200 pt-2 text-center">
                    <a href="{{ route('notifications.index') }}" class="btn btn-ghost btn-xs w-full text-primary">Lihat Semua Notifikasi</a>
                </div>
            </div>
        </div>
    </div>
    @endauth

    {{-- User Info & Profile Link --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity" title="Buka Profil & Ganti Password">
            <div class="text-right hidden sm:block">
                <div class="text-sm font-semibold leading-tight">
                    {{ auth()->user()->nama ?? auth()->user()->name ?? 'Pengguna' }}
                </div>
                <div class="text-xs text-base-content/50 leading-tight capitalize">
                    {{ auth()->user()->role ?? 'Admin Sistem' }}
                </div>
            </div>

            <div class="avatar">
                <div class="w-10 h-10 rounded-full bg-primary text-primary-content flex items-center justify-center font-bold">
                    <span>
                        {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->name ?? 'U', 0, 1)) }}
                    </span>
                </div>
            </div>
        </a>
    </div>

</header>