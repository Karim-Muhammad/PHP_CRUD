<?php
    if($_SERVER["REQUEST_METHOD"] != "POST") {
        echo "You are not allowed to access this page";
//        header("Location: $VIEWS_PATH/view.php?id={$_GET['id']}");
        header("Location: ../views/view.php?id={$_GET['id']}");
        exit();
    }


    include_once ("../db.php");
    global $db;

    $user = $db->query("SELECT * FROM users WHERE id={$_POST['id']}")->fetch();
    if(!$user) {
        echo "No User with this ID to Update!";
        header("Location: update.php");
        exit();
    }
    // print_r($user);

    $errors = [];
    $old_values = [];

    foreach(['full_name', 'email', 'password', 'phone'] as $key) {
        if(!isset($_POST[$key]) || empty($_POST[$key])) {
            global $errors, $old_values;

            $errors[$key] = "The $key is required";
        }
        $old_values[$key] = $_POST[$key];
    }


    if(count($errors) > 0) {
        session_start();
        $_SESSION['errors'] = $errors;
        $_SESSION['old_values'] = $old_values;
//        header("Location: $VIEWS_PATH/../../views/view.php?id={$_POST['id']}");
        header("Location: ../views/view.php?id={$_POST['id']}");
        exit();
    }



    $formData = $_POST;
    $full_name = $formData['full_name'];
    $email = $formData['email'];
    $password = $formData['password'];
    $phone = $formData['phone'];
    $saved_photo_name = $user["photo"] ? $user["photo"] : "default.png";

//    if(!isset($_FILES['photo']['name']) || empty($_FILES['photo']['name'])) {
    if(!empty($_FILES['photo']['name'])) {
        global $saved_photo_name;
        if($saved_photo_name != "default.png")
            unlink('../images/'.$user["photo"]); // first delete old photo
        echo $saved_photo_name;
        $photo_name = $_FILES['photo']['name'];
        $tmp_photo = $_FILES['photo']['tmp_name'];

        $saved_photo_name = date("Uv")."-$full_name-".$photo_name;
        move_uploaded_file($tmp_photo, "../images/$saved_photo_name");
    }


    $stmt = $db->prepare("UPDATE users SET name=:full_name, email=:email, password=:password, phone=:phone, photo=:photo WHERE id={$_POST['id']}");
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':photo', $saved_photo_name);

    $stmt->execute();

    header("Location: ../index.php");


