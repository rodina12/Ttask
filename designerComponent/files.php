<?php

include '../sharedComponents/connect.php';

session_start();

if (isset($_SESSION['admin_Id'])) {
    $admin_Id = $_SESSION['admin_Id'];

    $select_admin = $conn->prepare("SELECT CONCAT(fname, ' ', lname) AS admin_name FROM `admin` WHERE Id = ?");
    $select_admin->execute([$admin_Id]);

    // Fetch the admin name from the database
    $admin_row = $select_admin->fetch(PDO::FETCH_ASSOC);

    // Check if the query returned any results
    if ($admin_row) {
        // Assign the fetched admin name to a variable
        $admin_name = $admin_row['admin_name'];
    } else {
        // Handle the case where no admin is found with the given ID
        $admin_name = ""; // Set a default value
    }

} else {
    $admin_Id = '';
    header("location: loginDesigner.php");
}

if (isset($_POST['update_payment'])) {

    $order_id = $_POST['order_id'];
    $print_id = $_POST['print_id'];
    $update_print_id = $conn->prepare("UPDATE `projects` SET print_id = ? WHERE id = ?");
    $update_print_id->execute([$print_id, $order_id]);
    $order = $_POST['order'];
    $prints_id = $_POST['prints_id'];
    $update_prints_id = $conn->prepare("UPDATE `projects` SET admin_Id = ? WHERE id = ?");
    $update_prints_id->execute([$prints_id, $order]);

    $message[] = 'project sent !';

    if(isset($message)){
        foreach ($message as $msg) {
           ?>
                   <div class="alert alert-danger alert-dismissible fade show" style="height:10%;font-size:20px" role="alert">
                       <?= $msg ?>
                       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                   </div>
           <?php
               }
        }

}

if (isset($_POST['add_product'])) {
    $canUpload = true;
    $tableData = json_decode($_POST['tableData'], true);

    $admin_name = filter_input(INPUT_POST, 'admin_name', FILTER_SANITIZE_STRING);
   
  
   

    if (!empty($_FILES['image']['name'][0])) {
        // File upload handling
        $file_count = count($_FILES['image']['name']);
        for ($i = 0; $i < $file_count; $i++) {
            $image_name = $_FILES['image']['name'][$i];
            $image_size = $_FILES['image']['size'][$i];
            $image_tmp_name = $_FILES['image']['tmp_name'][$i];
            $image_folder = '../uploadedFiles/' . $image_name;
            $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

            // Check if project with the same image name already exists
            $select_products = $conn->prepare("SELECT * FROM `projectfiles` WHERE file_path = ?");
            $select_products->execute([$image_name]);

            if ($select_products->rowCount() > 0) {
                $message[] = 'Project with the same file already exists!';
                $canUpload = false;
            } /* elseif ($image_size > 2000000) {
                $message[] = 'Image size is too large';
                $canUpload = false;
             }*/ elseif (!in_array($image_extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'psd'])) {
                $message[] = 'Invalid file format. Only JPG, JPEG, PNG, PSD , and PDF are allowed.';
                $canUpload = false;
            }

        }
        if ($canUpload) {

            $insert_product = $conn->prepare("INSERT INTO `projects` (admin_Id, admin_name) VALUES (?, ?)");
            $insert_product->execute([$admin_Id, $admin_name]);
            // Retrieve the ID of the newly inserted record
            $newlyInsertedId = $conn->lastInsertId();

   

            for ($i = 0; $i < $file_count; $i++) {
                $image_name = $_FILES['image']['name'][$i];
                $image_size = $_FILES['image']['size'][$i];
                $image_tmp_name = $_FILES['image']['tmp_name'][$i];
                $image_folder = '../uploadedFiles/' . $image_name;
                $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

                // Move uploaded file to destination folder
                if (move_uploaded_file($image_tmp_name, $image_folder)) {

                    $insert_product = $conn->prepare("INSERT INTO `projectfiles` (file_path, project_id) VALUES (?, ?)");
                    $insert_product->execute([$image_name, $newlyInsertedId]);

                    $message[] = 'Project added: ' . $image_name;

                } else {
                    $message[] = 'Failed to move uploaded file: ' . $image_name;
                }

            }
        }


    } else {
        $message[] = 'No files uploaded';
    }
    if(isset($message)){
        foreach ($message as $msg) {
           ?>
                   <div class="alert alert-danger alert-dismissible fade show" style="height:10%;font-size:20px" role="alert">
                       <?= $msg ?>
                       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                   </div>
           <?php
               }
        }

}

if (isset($_GET['delete'])) {

    $delete_id = $_GET['delete'];
    $delete_product_image = $conn->prepare("SELECT * FROM `projectfiles` WHERE project_id = ?");
    $delete_product_image->execute([$delete_id]);
    $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
    unlink('../uploadedFiles/' . $fetch_delete_image['file_path']);

    $delete_product = $conn->prepare("DELETE FROM `projects` WHERE id = ?");
    $delete_product->execute([$delete_id]);

    $delete_details = $conn->prepare("DELETE FROM `projectdetails` WHERE project_id = ?");
    $delete_details->execute([$delete_id]);

    $delete_files = $conn->prepare("DELETE FROM `projectfiles` WHERE project_id = ?");
    $delete_files->execute([$delete_id]);
    header('location:files.php');

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   
    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/style.css">

</head>
<style>
.contact .row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
    width: 70%;
    margin-left: 16%
}

.contact .row .image {
    flex: 1 1 40rem;
}

.contact .row .image img {
    width: 100%;
}

.contact .row form {

    flex: 1 1 40rem;
    padding: 2rem;
    text-align: center;
}

.contact .row form h3 {
    font-size: 2.5rem;
    color: var(--black);
    margin-bottom: 1rem;
    text-transform: capitalize;

}



.contact .row form textarea {
    height: 15rem;
    resize: none;

}

.show-products .box-container {
    display: grid;

    gap: 1.5rem;
    width: 100%;
}

.show-products .box-container .box {
    padding: 3rem 3rem;
    box-shadow: 0px 0px 10px 0px;
    border-radius: 10px;
    border: none;
    height: 100%;
}

.show-products .box-container .box p {
    font-size: 2rem;
    color: var(--light-color);
    line-height: 2;
}

.show-products .box-container .box p span {
    color: var(--black);
}

.show-products .box-container .box img {
    width: 100%;
    height: 20rem;
    object-fit: contain;
    margin-bottom: 1rem;
}
</style>

<body>
    


<header class="navbar navbar-expand-lg navbar-light bg-light" style=" font-size:16px">
        <a href="files.php" class="navbar-brand"  style="margin-left:60px;font-size:16px">Welcome <?php echo htmlspecialchars($admin_name);  ?> !</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        
        <div class="collapse navbar-collapse  justify-content-center" id="navbarSupportedContent" style="margin-left:60px">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="files.php">Projects <span class="sr-only">(current)</span></a>
                </li>
               
               
                <li class="nav-item">
                    <a class="nav-link" href="logoutDesigner.php">Logout</a>
                </li>


            </ul>
        </div>
    </header>

    <!-- add products section starts  -->
    <section class="contact">
        <h2 style="text-align:center">Add New Project</h2>
        <div class="row">
            <form action="" method="post" enctype="multipart/form-data" style="border: none;">
                <div class="mb-3">
                    <input type="text" name="admin_name" class="form-control form-control-lg" value="<?php echo htmlspecialchars($admin_name); ?>"
                        readonly>
                </div>
            
                
            
                <div class="mb-3">
                    <input type="file" name="image[]" class="form-control form-control-lg"
                        accept="image/png,image/jpg,image/jpeg,.pdf,.psd" multiple required>
                </div>
                <input type="hidden" name="tableData" id="tableDataInput">

                <input type="submit" value="Add Project" name="add_product" class="btn btn-primary">
            </form>
        </div>
    </section>




    <!-- add products section ends -->

    <!-- show products section starts  -->

    <section class="show-products" style="padding-top: 0;">
        <h1 style="text-align:center">Recent Projects</h1>
        <br>
        <div class="box-container">
            
        <?php
if ($admin_Id == '') {
    echo '<p class="empty">Please login to see your projects</p>';
} else {
    $select_products = $conn->prepare("SELECT * FROM `projects` WHERE admin_Id = ?");
    $select_products->execute([$admin_Id]);

    if ($select_products->rowCount() > 0) {
        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="box">

                <a href="filesDetails.php?id=<?=$fetch_products['id'];?>"
                    style="font-size :2rem; text-align:center; text-decoration:none; ">
                    <img src="../file2.jpg" alt="">


                </a>
                <div style="text-align:center">
                       <h3>Admin Name:<strong style="color:red"><?=$fetch_products['admin_name'];?> </strong></h3>
                  

                </div>

            </div>
            <?php
}
    } else {
        echo '<p class="empty">No projects added yet!</p>';
    }
}
?>
        </div>
    </section>



    <!-- show products section ends -->
    <!-- custom js file link  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../javascript/admin_script.js"></script>

</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
        var dropdownItems = document.querySelectorAll('.dropdown-menu .dropdown-item');
    var measure ="";
// Loop through each dropdown item and attach a click event listener
dropdownItems.forEach(function(item) {
    item.addEventListener('click', function() {
        // Get the value of the clicked dropdown item
         measure = item.getAttribute('data-value');

        // Log the selected value for testing
        console.log('Selected value:', measure);

        // Optionally, you can perform further actions based on the selected value
    });
});

function deleteRow(button) {
    var row = button.parentNode.parentNode;
    row.parentNode.removeChild(row);

    var tableData = getAllTableDataExceptFirstRow();
    console.log(tableData);

    // Convert table data to JSON
    var jsonData = JSON.stringify(tableData);
    console.log(jsonData);
    // Set the JSON data to the hidden input field
    document.getElementById("tableDataInput").value = jsonData;

}

function getAllTableDataExceptFirstRow() {
    var table = document.getElementById("projectTable");
    var data = [];

    // Loop through each row starting from the second row (index 1)
    for (var i = 1; i < table.rows.length; i++) { // Exclude the first and last row
        var row = table.rows[i];
        var rowData = {};

        // Loop through each cell in the row
        for (var j = 0; j < row.cells.length - 1; j++) { // Exclude the last cell (delete button)
            var cell = row.cells[j];
            var input = cell.querySelector("input");
            rowData[input.name] = input.value;
        }

        // Add the row data to the array
        data.push(rowData);
    }

    return data;
}







</script>

</html>