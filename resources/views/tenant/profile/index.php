<?php 
$pageTitle = $pageTitle ?? 'Profil Saya';
$user = $user ?? [];
$tenant = $tenant ?? [];
?>

<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Profil Saya</h1>
        <p class="text-gray-600 mt-2">Kelola informasi profil dan keamanan akun Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Photo Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">Foto Profil</h3>
                </div>
                
                <div class="flex flex-col items-center p-6">
                    <?php if (!empty($tenant['profile_photo'])): ?>
                        <img src="<?= asset($tenant['profile_photo']) ?>" 
                             alt="Profile Photo" 
                             class="w-32 h-32 rounded-full object-cover border-4 border-green-100 mb-4 cursor-pointer hover:opacity-90 transition"
                             onclick="window.open(this.src, '_blank')">
                    <?php else: ?>
                        <img src="https://placehold.co/200x200?text=No+Image" 
                             alt="No Profile Photo" 
                             class="w-32 h-32 rounded-full object-cover border-4 border-green-100 mb-4">
                    <?php endif; ?>

                    <p class="text-center font-semibold text-gray-800 mb-1"><?= e($tenant['name'] ?? 'Tenant') ?></p>
                    <p class="text-center text-sm text-gray-600 mb-4"><?= e($tenant['email'] ?? ($user['email'] ?? '')) ?></p>

                    <?php if (!empty($tenant['profile_photo'])): ?>
                        <form action="<?= url('/tenant/profile/delete-photo') ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus foto profil?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                                <i class="fas fa-trash mr-1"></i>Hapus Foto
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="bg-gradient-to-br from-green-600 to-teal-600 rounded-lg shadow-lg p-6 mt-6 text-white">
                <div class="flex items-center mb-2">
                    <i class="fas fa-user text-2xl mr-3"></i>
                    <div>
                        <p class="text-sm opacity-90">Role</p>
                        <p class="font-bold text-lg">Tenant</p>
                    </div>
                </div>
                <div class="border-t border-green-400 opacity-50 my-3"></div>
                <div class="text-sm">
                    <p class="opacity-90">Status Akun</p>
                    <p class="font-semibold"><?= ucfirst($user['status'] ?? 'active') ?></p>
                </div>
                <div class="border-t border-green-400 opacity-50 my-3"></div>
                <div class="text-sm">
                    <p class="opacity-90">Bergabung sejak</p>
                    <p class="font-semibold"><?= date('d F Y', strtotime($tenant['created_at'] ?? 'now')) ?></p>
                </div>
            </div>
        </div>

        <!-- Forms -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Update Profile Form -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-user-edit mr-3"></i>
                        Informasi Profil
                    </h3>
                </div>

                <form action="<?= url('/tenant/profile/update') ?>" method="POST" enctype="multipart/form-data" class="p-6">
                    <?= csrf_field() ?>

                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               required
                               value="<?= e($tenant['name'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Email (readonly) -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" 
                               id="email" 
                               value="<?= e($tenant['email'] ?? ($user['email'] ?? '')) ?>"
                               readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
                    </div>

                    <!-- Phone -->
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            No. Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               required
                               value="<?= e($tenant['phone'] ?? '') ?>"
                               placeholder="08xxxxxxxxxx"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Address -->
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat
                        </label>
                        <textarea id="address" 
                                  name="address" 
                                  rows="3"
                                  placeholder="Alamat lengkap..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"><?= e($tenant['address'] ?? '') ?></textarea>
                    </div>

                    <!-- Profile Photo Upload -->
                    <div class="mb-4">
                        <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Foto Profil
                        </label>
                        <input type="file" 
                               id="profile_photo" 
                               name="profile_photo" 
                               accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB</p>
                    </div>

                    <button type="submit" 
                            class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- Change Password Form -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-lock mr-3"></i>
                        Ganti Password
                    </h3>
                </div>

                <form action="<?= url('/tenant/profile/update-password') ?>" method="POST" class="p-6">
                    <?= csrf_field() ?>
                    <!-- Current Password -->
                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Saat Ini <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- New Password -->
                    <div class="mb-4">
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               id="new_password" 
                               name="new_password" 
                               required
                               minlength="6"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               id="confirm_password" 
                               name="confirm_password" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <button type="submit" 
                            class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold shadow-md hover:shadow-lg">
                        <i class="fas fa-key mr-2"></i>
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
