<?php

    if($_SERVER['REQUEST_METHOD'] != "POST") {
        header("Location: index.php");
        exit();
    }

    include_once("db.php");

    global $db;

    if(!isset($_POST['id']) || !is_numeric($_GET['id']) ) {
        header("Location: index.php");
        exit();
    }

    $is_exist_id_query = "SELECT * FROM users WHERE id={$_POST['id']}";
    $is_exist_id = $row = $db->query($is_exist_id_query)->fetch();

    echo "<h1>$is_exist_id</h1>";

    if(!$is_exist_id) {
        header("Location: index.php");
        exit();
    }

    $id = $_POST['id'];
    $sql = "DELETE FROM users WHERE id={$id}";

    $db->exec($sql);

    // Delete Image of the user from the folder(images)
    $photo = $row['photo']; // fetch() return array of one row

    unlink('images/'.$photo); // delete the image from the folder

    // if there are multiple users have same image, and you deleted this one image, the other users will not have this image
    // so actually you need when you upload an img, to rename it to be unique, like the id of the user + user_name + timestamp + image_name

//    try {
//        $db->exec($sql);
//        echo "DONE!";
//    }catch (Exception $exception) {
//        echo "Something went Wrong!";
//        exit();
//    } finally {
//        // header("Location: index.php"); // Redirect to index.php
//    }
