<x-layouts.app title="Profil & Ganti Password">
    <div class="max-w-4xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-base-content flex items-center gap-2">
                <i class="fas fa-user-gear text-primary"></i> Profil & Pengaturan Akun
            </h1>
            <p class="text-sm text-base-content/60">
                Kelola informasi akun Anda dan perbarui kata sandi untuk keamanan.
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow-sm text-sm">
                <i class="fas fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- User Details Card --}}
            <div class="card bg-base-100 shadow border border-base-200">
                <div class="card-body p-6 flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-full bg-primary/10 text-primary flex items-center justify-center text-3xl font-black mb-2 ring-4 ring-primary/20">
                        {{ strtoupper(substr($user->nama ?? $user->name ?? 'U', 0, 1)) }}
                    </div>
                    <h2 class="font-bold text-lg text-base-content">{{ $user->nama ?? $user->name }}</h2>
                    <span class="badge badge-primary badge-sm uppercase font-semibold tracking-wider mt-1">
                        {{ $user->role }}
                    </span>
                    <p class="text-xs text-base-content/60 mt-1">{{ $user->email }}</p>

                    <div class="divider my-4"></div>

                    <div class="w-full text-left space-y-2 text-xs">
                        @if($user->no_rm)
                            <div class="flex justify-between py-1 border-b border-base-200">
                                <span class="text-base-content/60">No. Rekam Medis:</span>
                                <span class="font-semibold">{{ $user->no_rm }}</span>
                            </div>
                        @endif
                        @if($user->poli)
                            <div class="flex justify-between py-1 border-b border-base-200">
                                <span class="text-base-content/60">Poli:</span>
                                <span class="font-semibold">{{ $user->poli->nama_poli }}</span>
                            </div>
                        @endif
                        @if($user->no_hp)
                            <div class="flex justify-between py-1 border-b border-base-200">
                                <span class="text-base-content/60">No. HP:</span>
                                <span class="font-medium">{{ $user->no_hp }}</span>
                            </div>
                        @endif
                        @if($user->no_ktp)
                            <div class="flex justify-between py-1 border-b border-base-200">
                                <span class="text-base-content/60">No. KTP:</span>
                                <span class="font-medium">{{ $user->no_ktp }}</span>
                            </div>
                        @endif
                        @if($user->alamat)
                            <div class="py-1">
                                <span class="text-base-content/60 block">Alamat:</span>
                                <span class="font-medium text-base-content/80">{{ $user->alamat }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Change Password Form Card --}}
            <div class="md:col-span-2 card bg-base-100 shadow border border-base-200">
                <div class="card-body p-6">
                    <h2 class="card-title text-lg flex items-center gap-2 mb-2">
                        <i class="fas fa-key text-primary"></i> Ganti Password
                    </h2>
                    <p class="text-xs text-base-content/60 mb-4">
                        Pastikan kata sandi baru Anda minimal 8 karakter dan berbeda dari kata sandi sebelumnya.
                    </p>

                    <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        {{-- Password Lama --}}
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold text-sm">Password Lama <span class="text-error">*</span></span>
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="password_lama" 
                                       id="password_lama"
                                       class="input input-bordered w-full pr-10 @error('password_lama') input-error @enderror" 
                                       placeholder="Masukkan kata sandi lama Anda"
                                       required>
                                <button type="button" onclick="togglePasswordVisibility('password_lama')" class="absolute right-3 top-3 text-base-content/40 hover:text-base-content">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                            @error('password_lama')
                                <label class="label">
                                    <span class="label-text-alt text-error text-xs">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        {{-- Password Baru --}}
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold text-sm">Password Baru <span class="text-error">*</span></span>
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="password_baru" 
                                       id="password_baru"
                                       class="input input-bordered w-full pr-10 @error('password_baru') input-error @enderror" 
                                       placeholder="Minimal 8 karakter"
                                       required>
                                <button type="button" onclick="togglePasswordVisibility('password_baru')" class="absolute right-3 top-3 text-base-content/40 hover:text-base-content">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                            @error('password_baru')
                                <label class="label">
                                    <span class="label-text-alt text-error text-xs">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password Baru --}}
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold text-sm">Konfirmasi Password Baru <span class="text-error">*</span></span>
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="password_baru_confirmation" 
                                       id="password_baru_confirmation"
                                       class="input input-bordered w-full pr-10" 
                                       placeholder="Ketik ulang kata sandi baru"
                                       required>
                                <button type="button" onclick="togglePasswordVisibility('password_baru_confirmation')" class="absolute right-3 top-3 text-base-content/40 hover:text-base-content">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="btn btn-primary gap-2">
                                <i class="fas fa-floppy-disk"></i> Simpan Password Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            if (field.type === 'password') {
                field.type = 'text';
            } else {
                field.type = 'password';
            }
        }
    </script>
</x-layouts.app>
