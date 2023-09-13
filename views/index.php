<?php
    include_once "../db.php";

    global $db; // to avoid errors

    $sql = "SELECT * FROM users";
    $result = $db->query($sql);
?>
<?php include_once "../inc/header.php"; ?>

<div class="container">
    <?php
        session_start();
        if(isset($_SESSION['error_message'])) {
            echo "<div class='alert alert-danger'>{$_SESSION['error_message']}</div>";
            unset($_SESSION['error_message']);
        }
    ?>
    <h1>HEYS</h1>
    <div class="container">
        <div class="table-responsive">
            <table class="table">
                <caption>List of users</caption>
                <thead>
                <tr>
                    <th scope="col">#ID</th>
                    <th scope="col">Avatar</th>
                    <th scope="col">First</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">View</th>
<!--                    <th scope="col">Update</th>-->
                    <th scope="col">Delete</th>
                </tr>
                </thead>
                <tbody>
                <p class="text-muted text-sm">First row, cannot deleted because it has relationship with post table (RESTRICT)</p>
                <?php while($row = $result->fetch()) { ?>
                    <tr>
                        <th scope="row"><?= $row['id'] ?></th>
                        <th scope="row"><img width='150px' src="<?= '../images/'.$row['photo'] ?>" alt=""/></th>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['phone'] ?></td>
                        <!--
                        <td><a href="update.php/id={$row['id']}" class="btn btn-primary">Update</a></td>
                        <td><a href="delete.php/id={$row['id']}" class="btn btn-danger">Delete</a></td>
                        -->
                        <td><a href="view.php?id=<?=$row ['id'] ?>" class="btn btn-secondary">View</a></td>
                        <!--
                        <td><button onclick='updateRow(<?=$row ['id'] ?>)' class="btn btn-primary">Update</button></td>
                        -->
                        <td><button onclick="deleteRow(<?=$row ['id'] ?>)" class="btn btn-danger">Delete</button></td>
                    </tr>
                <?php }  ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    // function deleteRow(event)     {
    // event.target.dataset.id;

    function deleteRow(id) {
        fetch('delete.php', {
            method: "POST",
            body: {id: id},
        })
    }
    //
    // function updateRow(id) {
    //     fetch("update.php", {
    //         method: "POST",
    //         body: {id: id}
    //     })
    // }
</script>

<?php include_once "../inc/footer.php"; ?>
