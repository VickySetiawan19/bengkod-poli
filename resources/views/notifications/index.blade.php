<x-layouts.app title="Daftar Notifikasi">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-base-content flex items-center gap-2">
                    <i class="fas fa-bell text-primary"></i> Daftar Notifikasi
                </h1>
                <p class="text-sm text-base-content/60">
                    Semua riwayat pemberitahuan untuk akun Anda.
                </p>
            </div>
            @if($unreadCount > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-primary btn-sm gap-2">
                        <i class="fas fa-check-double"></i> Tandai Semua Sebagai Dibaca
                    </button>
                </form>
            @endif
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow-sm text-sm">
                <i class="fas fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body p-4 sm:p-6 divide-y divide-base-200">
                @forelse($notifications as $notif)
                    <div class="py-4 first:pt-0 last:pb-0 flex flex-col md:flex-row md:items-center justify-between gap-4 {{ $notif->read_at ? 'opacity-70' : 'bg-primary/5 p-4 rounded-xl border-l-4 border-primary' }}">
                        <div class="space-y-1 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm text-base-content">
                                    {{ $notif->data['title'] ?? 'Notifikasi' }}
                                </span>
                                @if(!$notif->read_at)
                                    <span class="badge badge-primary badge-xs">Baru</span>
                                @else
                                    <span class="badge badge-ghost badge-xs">Sudah Dibaca</span>
                                @endif
                                <span class="text-xs text-base-content/50">
                                    &bull; {{ $notif->created_at->diffForHumans() }} ({{ $notif->created_at->format('d M Y H:i') }})
                                </span>
                            </div>
                            <p class="text-sm text-base-content/80">
                                {{ $notif->data['message'] ?? '' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @if(isset($notif->data['action_url']))
                                <a href="{{ $notif->data['action_url'] }}" class="btn btn-sm btn-primary gap-1">
                                    <span>Buka Rincian</span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            @endif
                            @if(!$notif->read_at)
                                <form action="{{ route('notifications.markAsRead', $notif->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-ghost text-base-content/70 hover:text-primary" title="Tandai sudah dibaca">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-base-content/60">
                        <i class="fas fa-bell-slash text-4xl mb-3 opacity-30"></i>
                        <h3 class="font-semibold text-lg">Belum Ada Notifikasi</h3>
                        <p class="text-sm text-base-content/50">Pemberitahuan aktivitas poli atau pembayaran akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
            @if($notifications->hasPages())
                <div class="card-footer p-4 border-t border-base-200">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
