<?php 
$pageTitle = $pageTitle ?? 'Profil Admin';
$user = $user ?? [];
?>

<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Profil Admin</h2>
        <p class="text-gray-600 mt-1">Kelola informasi profil dan keamanan akun Admin</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Akun</h3>
                
                <div class="flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center border-4 border-red-100 mb-4">
                        <i class="fas fa-user-shield text-3xl text-white"></i>
                    </div>

                    <p class="text-center font-semibold text-gray-800 mb-1"><?= e($user['email']) ?></p>
                    <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                        Administrator
                    </span>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-md p-6 mt-6 text-white">
                <div class="flex items-center mb-2">
                    <i class="fas fa-shield-alt text-2xl mr-3"></i>
                    <div>
                        <p class="text-sm opacity-90">Role</p>
                        <p class="font-bold text-lg">Admin</p>
                    </div>
                </div>
                <div class="border-t border-red-400 opacity-50 my-3"></div>
                <div class="text-sm">
                    <p class="opacity-90">Status Akun</p>
                    <p class="font-semibold"><?= ucfirst($user['status']) ?></p>
                </div>
                <div class="border-t border-red-400 opacity-50 my-3"></div>
                <div class="text-sm">
                    <p class="opacity-90">Bergabung sejak</p>
                    <p class="font-semibold"><?= date('d F Y', strtotime($user['created_at'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Forms -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Update Profile Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user-edit text-red-600 mr-2"></i>
                    Informasi Profil
                </h3>

                <form action="<?= url('/admin/profile/update') ?>" method="POST">
                    <?= csrf_field() ?>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required
                               value="<?= e($user['email']) ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>

                    <!-- Role (readonly) -->
                    <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            Role
                        </label>
                        <input type="text" 
                               id="role" 
                               value="<?= ucfirst($user['role']) ?>"
                               readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-1">Role tidak dapat diubah</p>
                    </div>

                    <!-- Status (readonly) -->
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <input type="text" 
                               id="status" 
                               value="<?= ucfirst($user['status']) ?>"
                               readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-lock text-red-600 mr-2"></i>
                    Ubah Password
                </h3>

                <form action="<?= url('/admin/profile/update-password') ?>" method="POST">
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
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
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
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
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
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
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

            <!-- Security Tips -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-blue-800 mb-2">
                    <i class="fas fa-info-circle mr-1"></i>Tips Keamanan
                </h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Gunakan password yang kuat dengan kombinasi huruf, angka, dan simbol</li>
                    <li>• Jangan membagikan password Anda kepada siapapun</li>
                    <li>• Ubah password secara berkala untuk keamanan maksimal</li>
                    <li>• Logout dari akun setelah selesai menggunakan sistem</li>
                </ul>
            </div>
        </div>
    </div>
</div>
