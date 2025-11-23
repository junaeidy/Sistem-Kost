<?php

namespace App\Controllers;

use Core\Controller;

/**
 * Pages Controller
 * Handles static pages like privacy policy, terms, FAQ
 */
class PagesController extends Controller
{
    /**
     * Privacy Policy Page
     */
    public function privacyPolicy()
    {
        $this->view('pages/privacy-policy', [
            'title' => 'Kebijakan Privasi'
        ]);
    }

    /**
     * Terms and Conditions Page
     */
    public function termsConditions()
    {
        $this->view('pages/terms-conditions', [
            'title' => 'Syarat & Ketentuan'
        ]);
    }

    /**
     * FAQ Page
     */
    public function faq()
    {
        $this->view('pages/faq', [
            'title' => 'FAQ - Pertanyaan yang Sering Diajukan'
        ]);
    }
}
