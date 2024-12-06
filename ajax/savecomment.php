<?php
namespace Kocas\Git;

include_once(__DIR__ . '/../classes/Comment.php');
require_once __DIR__ . '/../bootstrap.php';
use Kocas\Git\Comment;

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    error_log("Gebruiker is niet ingelogd.");
    echo json_encode(['status' => 'error', 'message' => 'U bent niet ingelogd.']);
    exit;
}

if (!empty($_POST)) {
    try {
        $c = new Comment();
        $c->setProductId($_POST['productId']);
        $c->setText($_POST['text']);
        $c->setUserId($_SESSION['user_id']); // Zorg ervoor dat $_SESSION['user_id'] is ingesteld

        if ($c->save()) {
            $response = [
                'status' => 'success',
                'body' => htmlspecialchars($c->getText()),
                'message' => 'Comment saved'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Comment kon niet worden opgeslagen.'
            ];
        }
    } catch (\Exception $e) {
        error_log("Fout bij het opslaan van comment: " . $e->getMessage());
        $response = [
            'status' => 'error',
            'message' => 'Er is een fout opgetreden: ' . $e->getMessage()
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
