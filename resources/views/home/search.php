<div class="min-h-screen py-8">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Cari Kost</h1>
        
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="q" placeholder="Kata kunci..."
                       class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="number" name="min_price" placeholder="Harga min"
                       class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="number" name="max_price" placeholder="Harga max"
                       class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
            </form>
        </div>
        
        <p class="text-gray-600 text-center py-12">
            Fitur pencarian akan tersedia setelah implementasi modul Tenant selesai.
        </p>
    </div>
</div>
