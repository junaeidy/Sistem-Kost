<?php 
$pageTitle = 'Syarat & Ketentuan';
?>

<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-6">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                <i class="fas fa-file-contract text-blue-600 mr-3"></i>
                Syarat & Ketentuan
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
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">1. Penerimaan Syarat</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Selamat datang di Sistem Kost. Dengan mengakses dan menggunakan platform ini, Anda menyetujui 
                        untuk terikat oleh Syarat dan Ketentuan berikut. Jika Anda tidak setuju dengan syarat ini, 
                        mohon untuk tidak menggunakan layanan kami.
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        Syarat dan Ketentuan ini berlaku untuk semua pengguna platform, termasuk admin, owner kost, 
                        dan penyewa (tenant).
                    </p>
                </section>

                <!-- Definitions -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">2. Definisi</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li><strong>"Platform"</strong> mengacu pada website dan aplikasi Sistem Kost</li>
                        <li><strong>"Owner"</strong> adalah pengguna yang mendaftarkan dan mengelola properti kost</li>
                        <li><strong>"Tenant/Penyewa"</strong> adalah pengguna yang mencari dan menyewa kost</li>
                        <li><strong>"Admin"</strong> adalah pengelola platform Sistem Kost</li>
                        <li><strong>"Konten"</strong> mencakup teks, gambar, data, dan informasi lain yang ditampilkan di platform</li>
                    </ul>
                </section>

                <!-- User Registration -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">3. Pendaftaran dan Akun Pengguna</h2>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">3.1 Persyaratan Pendaftaran</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Anda harus berusia minimal 17 tahun untuk membuat akun</li>
                        <li>Informasi yang diberikan harus akurat dan lengkap</li>
                        <li>Anda bertanggung jawab menjaga kerahasiaan password</li>
                        <li>Satu orang hanya diperbolehkan memiliki satu akun aktif</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 mb-3">3.2 Verifikasi Akun</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Owner kost diwajibkan untuk melalui proses verifikasi dengan menyediakan:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Foto KTP yang masih berlaku</li>
                        <li>Dokumen kepemilikan atau izin properti</li>
                        <li>Nomor telepon yang dapat dihubungi</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 mb-3">3.3 Keamanan Akun</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Anda bertanggung jawab atas semua aktivitas yang terjadi di akun Anda. Segera laporkan 
                        kepada kami jika Anda mencurigai penggunaan akun yang tidak sah.
                    </p>
                </section>

                <!-- Owner Responsibilities -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">4. Kewajiban Owner Kost</h2>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">4.1 Informasi Properti</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Memberikan informasi yang akurat tentang properti kost</li>
                        <li>Memperbarui ketersediaan kamar secara berkala</li>
                        <li>Mengunggah foto yang representatif dan tidak menyesatkan</li>
                        <li>Menetapkan harga yang jelas dan transparan</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 mb-3">4.2 Layanan kepada Penyewa</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Merespons pertanyaan penyewa dengan cepat</li>
                        <li>Memenuhi fasilitas yang telah dijanjikan</li>
                        <li>Menjaga kondisi properti sesuai dengan deskripsi</li>
                        <li>Memberikan lingkungan yang aman dan nyaman</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 mb-3">4.3 Pembayaran dan Komisi</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Owner setuju untuk membayar komisi platform sesuai dengan ketentuan yang berlaku untuk 
                        setiap transaksi yang berhasil melalui platform.
                    </p>
                </section>

                <!-- Tenant Responsibilities -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">5. Kewajiban Penyewa (Tenant)</h2>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">5.1 Booking dan Pembayaran</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Memberikan informasi yang akurat saat melakukan booking</li>
                        <li>Melakukan pembayaran sesuai dengan jadwal yang disepakati</li>
                        <li>Membaca dan memahami aturan kost sebelum booking</li>
                        <li>Menghormati kebijakan pembatalan yang berlaku</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 mb-3">5.2 Penggunaan Properti</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Menjaga kebersihan dan ketertiban kamar</li>
                        <li>Menggunakan fasilitas dengan bertanggung jawab</li>
                        <li>Mematuhi aturan yang ditetapkan owner</li>
                        <li>Melaporkan kerusakan atau masalah kepada owner</li>
                    </ul>
                </section>

                <!-- Booking and Payment -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">6. Booking dan Pembayaran</h2>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">6.1 Proses Booking</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Booking dianggap sah setelah:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Penyewa mengajukan permintaan booking</li>
                        <li>Owner menyetujui permintaan booking</li>
                        <li>Pembayaran dikonfirmasi oleh sistem</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 mb-3">6.2 Metode Pembayaran</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Platform menerima pembayaran melalui berbagai metode yang telah terintegrasi dengan 
                        payment gateway resmi. Semua transaksi dilindungi dengan enkripsi.
                    </p>

                    <!-- <h3 class="text-xl font-semibold text-gray-800 mb-3">6.3 Kebijakan Pembatalan</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Pembatalan sebelum 7 hari: Refund 100%</li>
                        <li>Pembatalan 3-7 hari sebelumnya: Refund 50%</li>
                        <li>Pembatalan kurang dari 3 hari: Tidak ada refund</li>
                        <li>Owner dapat menetapkan kebijakan pembatalan khusus</li>
                    </ul> -->
                </section>

                <!-- Content and Conduct -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">7. Konten dan Perilaku Pengguna</h2>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">7.1 Konten yang Dilarang</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Pengguna dilarang mengunggah atau membagikan konten yang:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Melanggar hukum atau hak pihak ketiga</li>
                        <li>Mengandung unsur SARA, pornografi, atau kekerasan</li>
                        <li>Menyesatkan atau penipuan</li>
                        <li>Melanggar privasi orang lain</li>
                        <li>Mengandung virus atau malware</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 mb-3">7.2 Perilaku yang Dilarang</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Melakukan transaksi di luar platform untuk menghindari biaya</li>
                        <li>Memberikan ulasan palsu atau manipulatif</li>
                        <li>Melakukan spam atau harassment</li>
                        <li>Menyalahgunakan sistem atau mencoba hack platform</li>
                    </ul>
                </section>

                <!-- Intellectual Property -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">8. Hak Kekayaan Intelektual</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Semua konten platform, termasuk desain, logo, dan kode, adalah milik Sistem Kost dan 
                        dilindungi oleh hak cipta. Pengguna tidak diperbolehkan untuk:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Menyalin, memodifikasi, atau mendistribusikan konten platform tanpa izin</li>
                        <li>Menggunakan logo atau merek Sistem Kost tanpa persetujuan tertulis</li>
                        <li>Melakukan reverse engineering pada sistem kami</li>
                    </ul>
                </section>

                <!-- Liability -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">9. Batasan Tanggung Jawab</h2>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">9.1 Peran Platform</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Sistem Kost bertindak sebagai platform perantara antara owner dan penyewa. Kami tidak 
                        bertanggung jawab atas:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Kualitas, kondisi, atau keamanan properti yang terdaftar</li>
                        <li>Perselisihan antara owner dan penyewa</li>
                        <li>Kerugian atau kerusakan yang terjadi selama masa sewa</li>
                        <li>Informasi yang tidak akurat yang diberikan oleh pengguna</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 mb-3">9.2 Gangguan Layanan</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Kami tidak bertanggung jawab atas gangguan layanan yang disebabkan oleh pemeliharaan, 
                        masalah teknis, atau force majeure.
                    </p>
                </section>

                <!-- Dispute Resolution -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">10. Penyelesaian Sengketa</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Jika terjadi perselisihan antara pengguna:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Pengguna didorong untuk menyelesaikan secara langsung terlebih dahulu</li>
                        <li>Platform dapat membantu mediasi jika diperlukan</li>
                        <li>Keputusan platform dalam mediasi bersifat final</li>
                        <li>Penyelesaian hukum tunduk pada hukum Indonesia</li>
                    </ul>
                </section>

                <!-- Termination -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">11. Penangguhan dan Penutupan Akun</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Kami berhak untuk menangguhkan atau menutup akun yang:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4 ml-4">
                        <li>Melanggar Syarat dan Ketentuan ini</li>
                        <li>Terlibat dalam aktivitas penipuan atau ilegal</li>
                        <li>Mendapat laporan pelanggaran berulang kali</li>
                        <li>Tidak aktif dalam waktu lama (lebih dari 1 tahun)</li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed">
                        Pengguna yang akunnya ditutup dapat mengajukan banding dalam 30 hari.
                    </p>
                </section>

                <!-- Changes to Terms -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">12. Perubahan Syarat dan Ketentuan</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Kami dapat memperbarui Syarat dan Ketentuan ini kapan saja. Perubahan signifikan akan 
                        diumumkan melalui email atau notifikasi di platform. Penggunaan platform setelah 
                        perubahan berarti Anda menyetujui syarat yang telah diperbarui.
                    </p>
                </section>

                <!-- Contact -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">13. Hubungi Kami</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Jika Anda memiliki pertanyaan tentang Syarat dan Ketentuan ini, silakan hubungi kami:
                    </p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700 mb-2">
                            <i class="fas fa-envelope text-blue-600 mr-2"></i>
                            Email: <a href="mailto:support@sistemkost.com" class="text-blue-600 hover:underline">support@sistemkost.com</a>
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
