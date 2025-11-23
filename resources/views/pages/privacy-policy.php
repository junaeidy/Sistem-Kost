<?php 
$pageTitle = 'Kebijakan Privasi';
?>

<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-6">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                <i class="fas fa-shield-alt text-blue-600 mr-3"></i>
                Kebijakan Privasi
            </h1>
            <p class="text-gray-600">
                Terakhir diperbarui: <?= date('d F Y') ?>
            </p>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="prose max-w-none">
                
                <!-- Introduction -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">1. Pendahuluan</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Sistem Kost ("kami", "kita", atau "milik kami") berkomitmen untuk melindungi privasi Anda. 
                        Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi 
                        informasi pribadi Anda ketika Anda menggunakan platform kami.
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        Dengan menggunakan layanan Sistem Kost, Anda menyetujui pengumpulan dan penggunaan informasi 
                        sesuai dengan kebijakan ini.
                    </p>
                </section>

                <!-- Information Collection -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">2. Informasi yang Kami Kumpulkan</h2>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">2.1 Informasi Pribadi</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Kami mengumpulkan informasi pribadi yang Anda berikan secara langsung, termasuk:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Nama lengkap</li>
                        <li>Alamat email</li>
                        <li>Nomor telepon</li>
                        <li>Nomor KTP (untuk verifikasi owner)</li>
                        <li>Alamat tempat tinggal</li>
                        <li>Foto profil</li>
                        <li>Informasi pembayaran</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 mb-3">2.2 Informasi Properti</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Untuk owner kost, kami juga mengumpulkan:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Detail properti kost (alamat, fasilitas, harga)</li>
                        <li>Foto properti dan kamar</li>
                        <li>Dokumen verifikasi kepemilikan</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 mb-3">2.3 Informasi Otomatis</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Kami secara otomatis mengumpulkan informasi tertentu saat Anda menggunakan layanan kami:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Alamat IP</li>
                        <li>Jenis browser dan perangkat</li>
                        <li>Halaman yang dikunjungi</li>
                        <li>Waktu dan tanggal akses</li>
                        <li>Log aktivitas sistem</li>
                    </ul>
                </section>

                <!-- Use of Information -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">3. Penggunaan Informasi</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Kami menggunakan informasi yang dikumpulkan untuk:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Menyediakan, mengoperasikan, dan memelihara layanan kami</li>
                        <li>Memproses transaksi dan pembayaran</li>
                        <li>Memverifikasi identitas owner kost</li>
                        <li>Mengirim notifikasi tentang booking dan pembayaran</li>
                        <li>Meningkatkan pengalaman pengguna</li>
                        <li>Mendeteksi dan mencegah penipuan atau penyalahgunaan</li>
                        <li>Mengirim update, promosi, dan informasi terkait layanan (dengan persetujuan Anda)</li>
                        <li>Mematuhi kewajiban hukum</li>
                    </ul>
                </section>

                <!-- Information Sharing -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">4. Pembagian Informasi</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Kami tidak menjual informasi pribadi Anda kepada pihak ketiga. Kami hanya membagikan informasi Anda dalam situasi berikut:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li><strong>Dengan Owner Kost:</strong> Informasi penyewa dibagikan kepada owner untuk keperluan booking</li>
                        <li><strong>Dengan Penyewa:</strong> Informasi kontak owner dibagikan kepada penyewa yang melakukan booking</li>
                        <li><strong>Penyedia Layanan:</strong> Pihak ketiga yang membantu kami mengoperasikan layanan (payment gateway, hosting, dll)</li>
                        <li><strong>Kepatuhan Hukum:</strong> Jika diwajibkan oleh hukum atau proses hukum</li>
                        <li><strong>Perlindungan Hak:</strong> Untuk melindungi hak, properti, atau keselamatan kami dan pengguna lain</li>
                    </ul>
                </section>

                <!-- Data Security -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">5. Keamanan Data</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Kami menggunakan berbagai langkah keamanan untuk melindungi informasi pribadi Anda:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Enkripsi data sensitif</li>
                        <li>Akses terbatas ke data pribadi</li>
                        <li>Pemantauan sistem keamanan secara berkala</li>
                        <li>Penyimpanan data di server yang aman</li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed">
                        Namun, tidak ada metode transmisi melalui internet atau penyimpanan elektronik yang 100% aman. 
                        Kami berusaha menggunakan cara yang dapat diterima secara komersial untuk melindungi informasi pribadi Anda.
                    </p>
                </section>

                <!-- User Rights -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">6. Hak Pengguna</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Anda memiliki hak untuk:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Mengakses dan memperbarui informasi pribadi Anda</li>
                        <li>Meminta penghapusan data pribadi Anda</li>
                        <li>Menarik persetujuan pemrosesan data</li>
                        <li>Meminta salinan data pribadi Anda</li>
                        <li>Menolak pemrosesan data untuk tujuan pemasaran</li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed">
                        Untuk melaksanakan hak-hak ini, silakan hubungi kami di <a href="mailto:privacy@sistemkost.com" class="text-blue-600 hover:underline">privacy@sistemkost.com</a>
                    </p>
                </section>

                <!-- Cookies -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">7. Cookies</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Kami menggunakan cookies dan teknologi pelacakan serupa untuk:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Mempertahankan sesi login Anda</li>
                        <li>Mengingat preferensi Anda</li>
                        <li>Menganalisis penggunaan layanan kami</li>
                        <li>Meningkatkan fungsionalitas website</li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed">
                        Anda dapat mengatur browser Anda untuk menolak cookies, namun beberapa fitur website mungkin tidak berfungsi dengan baik.
                    </p>
                </section>

                <!-- Changes to Policy -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">8. Perubahan Kebijakan</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Kami akan memberi tahu Anda tentang 
                        perubahan dengan memposting kebijakan baru di halaman ini dan memperbarui tanggal "Terakhir diperbarui".
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        Anda disarankan untuk meninjau Kebijakan Privasi ini secara berkala untuk setiap perubahan.
                    </p>
                </section>

                <!-- Contact -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">9. Hubungi Kami</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini, silakan hubungi kami:
                    </p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700 mb-2">
                            <i class="fas fa-envelope text-blue-600 mr-2"></i>
                            Email: <a href="mailto:privacy@sistemkost.com" class="text-blue-600 hover:underline">privacy@sistemkost.com</a>
                        </p>
                        <p class="text-gray-700 mb-2">
                            <i class="fas fa-phone text-blue-600 mr-2"></i>
                            Telepon: +62 812-3456-7890
                        </p>
                        <p class="text-gray-700">
                            <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                            Alamat: Jl. Setia Budi No. 123, Medan, Sumatera Utara
                        </p>
                    </div>
                </section>

            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6 text-center">
            <a href="<?= url('/') ?>" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-home mr-2"></i>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
