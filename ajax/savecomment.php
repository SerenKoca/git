<?php
   
namespace Kocas\Git;
    include_once(__DIR__ . '/../classes/Comment.php');
    use Kocas\Git\Comment;
    session_start();
    
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: login.php");
        exit;
    }

    

    if(!empty($_POST)){
        //new comment
        $c = new Comment();
        
      
        $c->setProductId($_POST['productId']);
        $c->setText($_POST['text']);
        $c->setUserId(22); //$_SESSION

        // save()
        $c->save();

        //succes toegevoegd
        $response = [
            'status' => 'success',
            'body' => htmlspecialchars($c->getText()),
            'message' => 'Comment saved'
        ];

        header('Content-Type: application/json');
        echo json_encode($response); //{"status":"success","body":"<script>alert('xss')</script>","message":"Comment saved"}
    }