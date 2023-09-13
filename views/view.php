<?php include_once("../db.php") ?>
<?php include_once("../inc/header.php") ?>

<?php
    global $db;
    $user_row = "";
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM users WHERE id={$id}";
        $user_row = $db->query($sql)->fetch();

        // Search for user with this ID
        if(!$user_row) {
            // if there is no user with this ID, redirect to index.php
            session_start();
            $_SESSION['error_message'] = "No user with this ID!";

            header("Location: ../index.php");
            exit();
        }
    }

    $fields = ["full_name" => "", "email" => "", "password" => "", "phone" => "", "photo" => ""];
    $errors = $old_values = $fields;

    session_start();
    if (isset($_SESSION['errors'])) {
        global $errors;
        foreach ($_SESSION['errors'] as $key => $value) {
            $errors[$key] = $value;
        }

        unset($_SESSION['errors']); // delete the session after we use it, next time if there is an error, we will create a new session
    }

    if (isset($_SESSION['old_values'])) {
        global $old_values;
        foreach ($_SESSION['old_values'] as $key => $value) {
            $old_values[$key] = $value;
        }

        unset($_SESSION['old_values']); // delete the session after we use it, next time if there is an error, we will create a new session
    }

?>

<div class="container">
    <div>
        <img class="ml-auto rounded-circle" width="200" height="200" src="../images/<?=$user_row['photo']?>" alt=""/>
    </div>
    <form method="post" action="../controllers/update.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?=$user_row['id']?>">

        <div class="mb-3">
            <label for="full-name" class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" id="full-name" value="<?=$user_row['name']?>">
            <?php if(!empty($errors['full_name'])) : ?>
                <span class="alert alert-danger d-block my-1"><?=$errors['full_name']?></span>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" value="<?=$user_row['email']?>">
            <?php if(!empty($errors['email'])) : ?>
                <span class="alert alert-danger d-block my-1"><?=$errors['email']?></span>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password" value="<?=$user_row['password']?>">
            <?php if(!empty($errors['password'])) : ?>
                <span class="alert alert-danger d-block my-1"><?=$errors['password']?></span>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" id="phone" max="12" value="<?=$user_row['phone']?>">
            <?php if(!empty($errors['phone'])) : ?>
                <span class="alert alert-danger d-block my-1"><?=$errors['phone']?></span>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <input name="photo" type="file" class="form-control"/>
            <?php if(!empty($errors['photo'])) : ?>
                <span class="alert alert-danger d-block my-1"><?=$errors['photo']?></span>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<?php include_once("../inc/footer.php") ?>
