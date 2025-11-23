<?php 
$pageTitle = 'FAQ - Pertanyaan yang Sering Diajukan';
?>

<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-6">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                <i class="fas fa-question-circle text-blue-600 mr-3"></i>
                FAQ - Pertanyaan yang Sering Diajukan
            </h1>
            <p class="text-gray-600">
                Temukan jawaban untuk pertanyaan umum tentang Sistem Kost
            </p>
        </div>

        <!-- Search FAQ -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="relative">
                <input type="text" 
                       id="faqSearch"
                       placeholder="Cari pertanyaan..." 
                       class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>
            </div>
        </div>

        <!-- FAQ Categories -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-2">
                <button onclick="filterFAQ('all')" class="faq-filter-btn active px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Semua
                </button>
                <button onclick="filterFAQ('general')" class="faq-filter-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Umum
                </button>
                <button onclick="filterFAQ('tenant')" class="faq-filter-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Penyewa
                </button>
                <button onclick="filterFAQ('owner')" class="faq-filter-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Owner
                </button>
                <button onclick="filterFAQ('payment')" class="faq-filter-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Pembayaran
                </button>
            </div>
        </div>

        <!-- FAQ Content -->
        <div class="bg-white rounded-lg shadow-md p-8">
            
            <!-- General Questions -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                    Pertanyaan Umum
                </h2>
                
                <div class="space-y-4">
                    <!-- FAQ Item -->
                    <div class="faq-item border border-gray-200 rounded-lg" data-category="general">
                        <button onclick="toggleFAQ(this)" class="w-full p-4 text-left flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="font-semibold text-gray-800">Apa itu Sistem Kost?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-4 pt-0 text-gray-700 leading-relaxed">
                            Sistem Kost adalah platform digital yang menghubungkan pemilik kost (owner) dengan pencari kost (penyewa/tenant) 
                            di wilayah Medan. Platform ini memudahkan proses pencarian, booking, dan pembayaran kost secara online dengan 
                            aman dan terpercaya.
                        </div>
                    </div>

                    <div class="faq-item border border-gray-200 rounded-lg" data-category="general">
                        <button onclick="toggleFAQ(this)" class="w-full p-4 text-left flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="font-semibold text-gray-800">Apakah gratis menggunakan Sistem Kost?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-4 pt-0 text-gray-700 leading-relaxed">
                            Untuk penyewa, pendaftaran dan pencarian kost sepenuhnya gratis. Untuk owner kost, kami mengenakan 
                            komisi kecil untuk setiap transaksi yang berhasil melalui platform sebagai biaya layanan dan maintenance.
                        </div>
                    </div>

                    <div class="faq-item border border-gray-200 rounded-lg" data-category="general">
                        <button onclick="toggleFAQ(this)" class="w-full p-4 text-left flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="font-semibold text-gray-800">Bagaimana cara mendaftar?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-4 pt-0 text-gray-700 leading-relaxed">
                            <p class="mb-2">Anda dapat mendaftar dengan cara:</p>
                            <ol class="list-decimal list-inside space-y-1 ml-4">
                                <li>Klik tombol "Daftar" di halaman utama</li>
                                <li>Pilih jenis akun (Penyewa atau Owner)</li>
                                <li>Isi formulir pendaftaran dengan data yang akurat</li>
                                <li>Verifikasi email Anda</li>
                                <li>Login dan lengkapi profil Anda</li>
                            </ol>
                        </div>
                    </div>

                    <div class="faq-item border border-gray-200 rounded-lg" data-category="general">
                        <button onclick="toggleFAQ(this)" class="w-full p-4 text-left flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="font-semibold text-gray-800">Apakah data saya aman?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-4 pt-0 text-gray-700 leading-relaxed">
                            Ya, kami sangat serius dalam melindungi data pribadi Anda. Semua data dienkripsi dan disimpan dengan aman. 
                            Kami tidak akan membagikan informasi pribadi Anda kepada pihak ketiga tanpa persetujuan Anda. 
                            Silakan baca Kebijakan Privasi kami untuk informasi lebih detail.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tenant Questions -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-user text-green-600 mr-3"></i>
                    Untuk Penyewa
                </h2>
                
                <div class="space-y-4">
                    <div class="faq-item border border-gray-200 rounded-lg" data-category="tenant">
                        <button onclick="toggleFAQ(this)" class="w-full p-4 text-left flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="font-semibold text-gray-800">Bagaimana cara mencari kost?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-4 pt-0 text-gray-700 leading-relaxed">
                            <p class="mb-2">Anda dapat mencari kost dengan beberapa cara:</p>
                            <ul class="list-disc list-inside space-y-1 ml-4">
                                <li>Gunakan fitur pencarian di halaman utama dengan memasukkan lokasi</li>
                                <li>Filter berdasarkan tipe gender (putra/putri/campur)</li>
                                <li>Filter berdasarkan range harga</li>
                                <li>Filter berdasarkan fasilitas yang diinginkan (WiFi, AC, dll)</li>
                                <li>Lihat detail properti untuk informasi lengkap</li>
                            </ul>
                        </div>
                    </div>

                    <div class="faq-item border border-gray-200 rounded-lg" data-category="tenant">
                        <button onclick="toggleFAQ(this)" class="w-full p-4 text-left flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="font-semibold text-gray-800">Bagaimana proses booking kost?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-4 pt-0 text-gray-700 leading-relaxed">
                            <p class="mb-2">Proses booking sangat mudah:</p>
                            <ol class="list-decimal list-inside space-y-1 ml-4">
                                <li>Pilih kost yang Anda inginkan</li>
                                <li>Lihat detail kamar yang tersedia</li>
                                <li>Klik tombol "Booking"</li>
                                <li>Isi data booking (tanggal mulai, durasi)</li>
                                <li>Tunggu konfirmasi dari owner</li>
                                <li>Lakukan pembayaran setelah disetujui</li>
                                <li>Terima konfirmasi booking Anda</li>
                            </ol>
                        </div>
                    </div>

                    <div class="faq-item border border-gray-200 rounded-lg" data-category="tenant">
                        <button onclick="toggleFAQ(this)" class="w-full p-4 text-left flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="font-semibold text-gray-800">Apakah saya bisa membatalkan booking?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-4 pt-0 text-gray-700 leading-relaxed">
                            <p class="mb-2">Ya, Anda dapat membatalkan booking dengan ketentuan:</p>
                            <ul class="list-disc list-inside space-y-1 ml-4">
                                <li>Pembatalan lebih dari 7 hari sebelum tanggal mulai: Refund 100%</li>
                                <li>Pembatalan 3-7 hari sebelum tanggal mulai: Refund 50%</li>
                                <li>Pembatalan kurang dari 3 hari: Tidak ada refund</li>
                            </ul>
                            <p class="mt-2">*Kebijakan dapat berbeda tergantung aturan owner kost</p>
                        </div>
                    </div>

                    <div class="faq-item border border-gray-200 rounded-lg" data-category="tenant">
                        <button onclick="toggleFAQ(this)" class="w-full p-4 text-left flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="font-semibold text-gray-800">Bagaimana jika ada masalah dengan kost?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-4 pt-0 text-gray-700 leading-relaxed">
                            Jika Anda mengalami masalah, segera laporkan melalui dashboard Anda atau hubungi customer service kami. 
                            Kami akan membantu mediasi antara Anda dan owner untuk menyelesaikan masalah dengan adil.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Owner Questions -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-building text-purple-600 mr-3"></i>
                    Untuk Owner Kost
                </h2>
                
                <div class="space-y-4">
                    <div class="faq-item border border-gray-200 rounded-lg" data-category="owner">
                        <button onclick="toggleFAQ(this)" class="w-full p-4 text-left flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="font-semibold text-gray-800">Bagaimana cara mendaftarkan kost saya?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-4 pt-0 text-gray-700 leading-relaxed">
                            <p class="mb-2">Langkah-langkah mendaftarkan kost:</p>
                            <ol class="list-decimal list-inside space-y-1 ml-4">
                                <li>Daftar sebagai Owner kost</li>
                                <li>Lengkapi verifikasi identitas (KTP)</li>
                                <li>Tunggu persetujuan admin (1-2 hari kerja)</li>
                                <li>Setelah disetujui, tambahkan properti kost Anda</li>
                                <li>Upload foto-foto kost yang menarik</li>
                                <li>Isi detail kamar dan harga</li>
                                <li>Publikasikan kost Anda</li>
                            </ol>
                        </div>
                    </div>

                    <div class="faq-item border border-gray-200 rounded-lg" data-category="owner">
                        <button onclick="toggleFAQ(this)" class="w-full p-4 text-left flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="font-semibold text-gray-800">Berapa biaya untuk mendaftarkan kost?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-4 pt-0 text-gray-700 leading-relaxed">
                            Pendaftaran kost gratis. Kami hanya mengenakan komisi kecil (5-10%) dari setiap transaksi booking 
                            yang berhasil melalui platform. Tidak ada biaya tersembunyi atau biaya bulanan.
                        </div>
                    </div>

                    <div class="faq-item border border-gray-200 rounded-lg" data-category="owner">
                        <button onclick="toggleFAQ(this)" class="w-full p-4 text-left flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="font-semibold text-gray-800">Bagaimana cara mengelola booking?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-4 pt-0 text-gray-700 leading-relaxed">
                            Semua booking dapat dikelola melalui dashboard owner. Anda akan menerima notifikasi untuk setiap 
                            permintaan booking baru. Anda dapat menerima, menolak, atau meminta informasi tambahan dari calon penyewa. 
                            Anda juga dapat melihat riwayat transaksi dan pembayaran.
                        </div>
                    </div>

                    <div class="faq-item border border-gray-200 rounded-lg" data-category="owner">
                        <button onclick="toggleFAQ(this)" class="w-full p-4 text-left flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="font-semibold text-gray-800">Kapan saya menerima pembayaran?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-4 pt-0 text-gray-700 leading-relaxed">
                            Pembayaran akan ditransfer ke rekening Anda setelah penyewa check-in atau maksimal 3 hari kerja 
                            setelah tanggal mulai sewa. Kami menahan pembayaran untuk memastikan kualitas layanan dan 
                            menghindari penipuan.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Questions -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-credit-card text-orange-600 mr-3"></i>
                    Pembayaran
                </h2>
                
                <div class="space-y-4">
                    <div class="faq-item border border-gray-200 rounded-lg" data-category="payment">
                        <button onclick="toggleFAQ(this)" class="w-full p-4 text-left flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="font-semibold text-gray-800">Metode pembayaran apa saja yang tersedia?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-4 pt-0 text-gray-700 leading-relaxed">
                            <p class="mb-2">Kami menerima berbagai metode pembayaran melalui Midtrans:</p>
                            <ul class="list-disc list-inside space-y-1 ml-4">
                                <li>Transfer Bank (BCA, Mandiri, BNI, BRI, dll)</li>
                                <li>Kartu Kredit/Debit</li>
                                <li>E-Wallet (GoPay, OVO, DANA, dll)</li>
                                <li>Virtual Account</li>
                                <li>Alfamart/Indomaret</li>
                            </ul>
                        </div>
                    </div>

                    <div class="faq-item border border-gray-200 rounded-lg" data-category="payment">
                        <button onclick="toggleFAQ(this)" class="w-full p-4 text-left flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="font-semibold text-gray-800">Apakah pembayaran aman?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-4 pt-0 text-gray-700 leading-relaxed">
                            Ya, semua transaksi pembayaran diproses melalui payment gateway Midtrans yang tersertifikasi PCI-DSS. 
                            Data kartu kredit Anda tidak disimpan di server kami dan semua transaksi dienkripsi dengan standar 
                            keamanan internasional.
                        </div>
                    </div>

                    <div class="faq-item border border-gray-200 rounded-lg" data-category="payment">
                        <button onclick="toggleFAQ(this)" class="w-full p-4 text-left flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="font-semibold text-gray-800">Berapa lama proses refund?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-4 pt-0 text-gray-700 leading-relaxed">
                            Proses refund membutuhkan waktu 5-14 hari kerja tergantung bank Anda. Kami akan memproses permintaan 
                            refund maksimal 3 hari kerja setelah pembatalan disetujui. Notifikasi akan dikirim melalui email.
                        </div>
                    </div>

                    <div class="faq-item border border-gray-200 rounded-lg" data-category="payment">
                        <button onclick="toggleFAQ(this)" class="w-full p-4 text-left flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="font-semibold text-gray-800">Bagaimana jika pembayaran gagal?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-4 pt-0 text-gray-700 leading-relaxed">
                            Jika pembayaran gagal, silakan coba lagi atau gunakan metode pembayaran lain. Pastikan saldo atau 
                            limit kartu Anda mencukupi. Jika masalah berlanjut, hubungi customer service kami untuk bantuan.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Still Have Questions -->
            <div class="bg-blue-50 rounded-lg p-6 text-center">
                <i class="fas fa-headset text-5xl text-blue-600 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Masih Ada Pertanyaan?</h3>
                <p class="text-gray-700 mb-4">
                    Tim customer service kami siap membantu Anda 24/7
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="mailto:support@sistemkost.com" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-envelope mr-2"></i>
                        Email Kami
                    </a>
                    <a href="https://wa.me/6281234567890" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fab fa-whatsapp mr-2"></i>
                        WhatsApp
                    </a>
                </div>
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

<script>
// Toggle FAQ Answer
function toggleFAQ(button) {
    const answer = button.nextElementSibling;
    const icon = button.querySelector('i');
    
    answer.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}

// Filter FAQ by Category
let currentFilter = 'all';

function filterFAQ(category) {
    currentFilter = category;
    const items = document.querySelectorAll('.faq-item');
    const buttons = document.querySelectorAll('.faq-filter-btn');
    
    // Update button styles
    buttons.forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    event.target.classList.remove('bg-gray-200', 'text-gray-700');
    event.target.classList.add('bg-blue-600', 'text-white');
    
    // Filter items
    items.forEach(item => {
        if (category === 'all' || item.dataset.category === category) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// Search FAQ
document.getElementById('faqSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const items = document.querySelectorAll('.faq-item');
    
    items.forEach(item => {
        const question = item.querySelector('button span').textContent.toLowerCase();
        const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
        
        if (question.includes(searchTerm) || answer.includes(searchTerm)) {
            if (currentFilter === 'all' || item.dataset.category === currentFilter) {
                item.style.display = 'block';
            }
        } else {
            item.style.display = 'none';
        }
    });
});
</script>

<style>
.faq-item button i {
    transition: transform 0.3s ease;
}

.rotate-180 {
    transform: rotate(180deg);
}
</style>
