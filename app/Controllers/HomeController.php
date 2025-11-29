<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\KostModel;
use App\Models\ReviewModel;

/**
 * Home Controller
 * Handles public pages
 */
class HomeController extends Controller
{
    private $kostModel;
    private $reviewModel;

    public function __construct()
    {
        $this->kostModel = new KostModel();
        $this->reviewModel = new ReviewModel();
    }

    /**
     * Homepage
     */
    public function index()
    {
        // Get featured kost (latest 6)
        $featuredKost = $this->kostModel->search([
            'available_only' => true,
            'sort_by' => 'created_at',
            'sort_order' => 'DESC'
        ]);
        
        $featuredKost = array_slice($featuredKost, 0, 6);

        $this->view('home/index', [
            'title' => 'Beranda',
            'featuredKost' => $featuredKost
        ]);
    }

    /**
     * Search kost (Public - No Auth Required)
     */
    public function search()
    {
        // Get filters from query string (support both homepage and search page parameters)
        $q = $_GET['q'] ?? ''; // Keyword from homepage
        $gender = $_GET['gender'] ?? ''; // Gender from homepage
        $price = $_GET['price'] ?? ''; // Price range from homepage (e.g., "500000-1000000")
        $location = $_GET['location'] ?? ''; // Location
        $facilities = $_GET['facilities'] ?? ''; // Facilities from homepage
        
        // Parse price range if exists
        $minPrice = '';
        $maxPrice = '';
        if (!empty($price) && strpos($price, '-') !== false) {
            list($minPrice, $maxPrice) = explode('-', $price);
        }
        
        $filters = [
            'q' => $q,
            'location' => $location,
            'gender' => $gender,
            'gender_type' => $gender, // Alias for compatibility
            'price' => $price,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'facilities' => $facilities,
            'sort_by' => $_GET['sort_by'] ?? 'created_at',
            'sort_order' => $_GET['sort_order'] ?? 'DESC',
            'available_only' => isset($_GET['available_only']) ? true : false
        ];

        // Search kost
        $kostList = [];
        $isSearching = false;

        // If any filter is applied, perform search
        if (!empty($filters['q']) || !empty($filters['location']) || !empty($filters['gender']) || 
            !empty($filters['price']) || !empty($filters['facilities'])) {
            $isSearching = true;
            $kostList = $this->kostModel->search($filters);
        } else {
            // Show all active kost by default
            $kostList = $this->kostModel->search(['available_only' => true]);
        }

        $this->view('home/search', [
            'title' => 'Cari Kost',
            'kostList' => $kostList,
            'filters' => $filters,
            'isSearching' => $isSearching,
            'totalResults' => count($kostList)
        ]);
    }

    /**
     * Kost detail (Public - No Auth Required)
     */
    public function detail($id)
    {
        // Get kost detail with rooms
        $kost = $this->kostModel->getDetailWithRooms($id);

        if (!$kost) {
            $this->flash('error', 'Kost tidak ditemukan.');
            $this->redirect(url('/search'));
            return;
        }

        // Check if kost is active
        if ($kost['status'] !== 'active') {
            $this->flash('error', 'Kost ini tidak tersedia.');
            $this->redirect(url('/search'));
            return;
        }

        // Parse facilities
        if (!empty($kost['facilities'])) {
            $kost['facilities_array'] = json_decode($kost['facilities'], true);
        } else {
            $kost['facilities_array'] = [];
        }

        // Parse room facilities for each room
        foreach ($kost['kamar'] as &$kamar) {
            if (!empty($kamar['facilities'])) {
                $kamar['facilities_array'] = json_decode($kamar['facilities'], true);
            } else {
                $kamar['facilities_array'] = [];
            }
        }

        // Get similar kost (same location or gender type)
        $similarKost = $this->kostModel->search([
            'location' => $kost['location'],
            'available_only' => true
        ]);

        // Remove current kost from similar list
        $similarKost = array_filter($similarKost, function($k) use ($id) {
            return $k['id'] != $id;
        });

        // Limit to 4 similar kost
        $similarKost = array_slice($similarKost, 0, 4);

        // Get review statistics
        $reviewStats = $this->reviewModel->getKostStats($id);

        // Get reviews with pagination
        $perPage = 5;
        $page = max(1, (int) ($_GET['review_page'] ?? 1));
        $offset = ($page - 1) * $perPage;
        $reviews = $this->reviewModel->findByKostId($id, $perPage, $offset);
        $totalReviews = $reviewStats['total_reviews'];
        $totalPages = $totalReviews > 0 ? (int) ceil($totalReviews / $perPage) : 1;

        $this->view('home/detail', [
            'title' => $kost['name'],
            'kost' => $kost,
            'similarKost' => $similarKost,
            'reviewStats' => $reviewStats,
            'reviews' => $reviews,
            'reviewPagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $totalReviews,
                'per_page' => $perPage
            ]
        ]);
    }
}
