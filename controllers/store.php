<?php

    if($_SERVER['REQUEST_METHOD'] != "POST") {
        header("Location: index.php");
        exit();
    }

    $errors = [];
    $old_values = [];

    foreach(['full_name', 'email', 'password', 'phone'] as $key) {
        if(!isset($_POST[$key]) || empty($_POST[$key])) {
            global $errors, $old_values;

            $errors[$key] = "The $key is required";
        }
        $old_values[$key] = $_POST[$key];
    }

    if(!isset($_FILES['photo']) || empty($_FILES['photo']['name'])) {
        global $errors;
        $errors['photo'] = "The photo is required";
    }

    if(count($errors) > 0) {
        session_start();
        $_SESSION['errors'] = $errors;
        $_SESSION['old_values'] = $old_values;
        header("Location: create.php");
        exit();
    }


    include_once("db.php");

    global $db;

    $formData = $_POST;
    $full_name = $formData['full_name'];
    $email = $formData['email'];
    $password = $formData['password'];
    $phone = $formData['phone'];

    $photo = $_FILES['photo']['name'];
    $tmp = $_FILES['photo']['tmp_name'];
    $path_photo = date("Uv")."-$full_name-".$photo; // 1627384738v-ahmed-ahmed.jpg
    move_uploaded_file($tmp, 'images/'.$path_photo);

    $date = date("Y-m-d H:i:s");

    $stmt = $db->prepare('INSERT INTO users (name, email, password, phone, photo) VALUES (:full_name, :email, :password, :phone, :photo)');
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':photo', $path_photo);

    $stmt->execute();



    echo "DONE!";
    header("Location: index.php");


