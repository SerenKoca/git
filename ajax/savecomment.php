<?php
include_once(__DIR__ . '/../classes/Comment.php');
session_start();

if (isset($_POST['postId'], $_POST['text'])) {
    if (isset($_SESSION['user_id'])) {
        // New comment
        $comment = new Comment();
        $comment->setPostId($_POST['postId']);
        $comment->setText($_POST['text']);
        $comment->setUserId($_SESSION['user_id']); // Ensure you get the correct user ID from the session

        // Save the comment
        if ($comment->save()) {
            $response = [
                'status' => 'success',
                'message' => 'Comment saved successfully'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Failed to save comment'
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'User is not logged in'
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
