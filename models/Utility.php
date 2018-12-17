<?php

class Utility {

    /**
     * Redirect to a specific page
     * @param $path
     */
    public function redirect($path) {
        header('Location: ' . $path );
        die();
    }

    /**
     * Clean Special Characters
     * @param $string
     * @return null|string|string[]
     */
    public function cleanSpecialChars($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-.]/', '', $string); // Removes special chars.
        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }
}