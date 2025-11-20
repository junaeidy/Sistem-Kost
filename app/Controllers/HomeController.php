<?php

namespace App\Controllers;

use Core\Controller;

/**
 * Home Controller
 * Handles public pages
 */
class HomeController extends Controller
{
    /**
     * Homepage
     */
    public function index()
    {
        $this->view('home/index', [
            'title' => 'Beranda'
        ]);
    }

    /**
     * Search kost
     */
    public function search()
    {
        $this->view('home/search', [
            'title' => 'Cari Kost'
        ]);
    }

    /**
     * Kost detail
     */
    public function detail($id)
    {
        $this->view('home/detail', [
            'title' => 'Detail Kost',
            'id' => $id
        ]);
    }
}
