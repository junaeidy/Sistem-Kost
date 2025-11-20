<?php 
$pageTitle = $pageTitle ?? 'Profil Saya';
$owner = $owner ?? [];
?>

<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Profil Saya</h2>
        <p class="text-gray-600 mt-1">Kelola informasi profil dan keamanan akun Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Photo Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Foto Profil</h3>
                
                <div class="flex flex-col items-center">
                    <?php if (!empty($owner['profile_photo'])): ?>
                        <img src="<?= asset($owner['profile_photo']) ?>" 
                             alt="Profile Photo" 
                             class="w-32 h-32 rounded-full object-cover border-4 border-blue-100 mb-4">
                    <?php else: ?>
                        <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center border-4 border-blue-100 mb-4">
                            <span class="text-4xl text-white font-bold">
                                <?= strtoupper(substr($owner['name'], 0, 1)) ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <p class="text-center font-semibold text-gray-800 mb-1"><?= e($owner['name']) ?></p>
                    <p class="text-center text-sm text-gray-600 mb-4"><?= e($owner['email']) ?></p>

                    <?php if (!empty($owner['profile_photo'])): ?>
                        <form action="<?= url('/owner/profile/delete-photo') ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus foto profil?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                                <i class="fas fa-trash mr-1"></i>Hapus Foto
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md p-6 mt-6 text-white">
                <div class="flex items-center mb-2">
                    <i class="fas fa-user-tie text-2xl mr-3"></i>
                    <div>
                        <p class="text-sm opacity-90">Role</p>
                        <p class="font-bold text-lg">Owner</p>
                    </div>
                </div>
                <div class="border-t border-blue-400 opacity-50 my-3"></div>
                <div class="text-sm">
                    <p class="opacity-90">Bergabung sejak</p>
                    <p class="font-semibold"><?= date('d F Y', strtotime($owner['created_at'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Forms -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Update Profile Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user-edit text-blue-600 mr-2"></i>
                    Informasi Profil
                </h3>

                <form action="<?= url('/owner/profile/update') ?>" method="POST" enctype="multipart/form-data">
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
                               value="<?= e($owner['name']) ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Email (readonly) -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" 
                               id="email" 
                               value="<?= e($owner['email']) ?>"
                               readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
                    </div>

                    <!-- Phone -->
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               required
                               value="<?= e($owner['phone']) ?>"
                               placeholder="08xxxxxxxxxx"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Address -->
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat
                        </label>
                        <textarea id="address" 
                                  name="address" 
                                  rows="3"
                                  placeholder="Alamat lengkap Anda"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= e($owner['address'] ?? '') ?></textarea>
                    </div>

                    <!-- Profile Photo -->
                    <div class="mb-4">
                        <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Foto Profil Baru
                        </label>
                        <input type="file" 
                               id="profile_photo" 
                               name="profile_photo" 
                               accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-lock text-blue-600 mr-2"></i>
                    Ubah Password
                </h3>

                <form action="<?= url('/owner/profile/update-password') ?>" method="POST">
                    <?= csrf_field() ?>

                    <!-- Current Password -->
                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Lama <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               required
                               placeholder="Masukkan password lama"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                               placeholder="Minimal 6 karakter"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                               minlength="6"
                               placeholder="Ulangi password baru"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <i class="fas fa-key mr-2"></i>Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
