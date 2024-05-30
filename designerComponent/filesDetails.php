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

// Check if project ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect to master page or display an error message
    header('Location: files.php');
    exit;
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




if (isset($_GET['delete'])) {

    $delete_id = $_GET['delete'];
    $delete_product_image = $conn->prepare("SELECT * FROM `projects` WHERE id = ?");
    $delete_product_image->execute([$delete_id]);
    $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
    unlink('../uploadedFiles/' . $fetch_delete_image['image']);
    $delete_product = $conn->prepare("DELETE FROM `projects` WHERE id = ?");
    $delete_product->execute([$delete_id]);
    header('location:files.php');

}

// Retrieve project details from the database based on ID
$project_id = $_GET['id'];
$select_project = $conn->prepare("SELECT * FROM `projects` WHERE id = ?");
$select_project->execute([$project_id]);

// Check if project exists
if ($select_project->rowCount() == 0) {
    // Project not found, redirect or display an error message
    header('Location: files.php');
    exit;
}

// Fetch project details
$fetch_project = $select_project->fetch(PDO::FETCH_ASSOC);

$project_files = $conn->prepare("SELECT * FROM `projectfiles` WHERE project_id = ?");
$project_files->execute([$project_id]);
$fetch_project_files = $project_files->fetchAll(PDO::FETCH_ASSOC);

$select_print = $conn->prepare("SELECT * FROM `print`");
$select_prints = $conn->prepare("SELECT * FROM `admin`");

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
   
   <link rel="icon" href="../alwan.png" type="image/png">
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

.show-products {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.project {
    width: 100%;
    /* Adjust the width as needed */
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    overflow: hidden;
}

.thumbnail img {
    width: 100%;
    height: auto;
    object-fit: cover;
    /* Ensure the image covers the entire container */
}

.project-info {
    padding: 20px;
}

.project-info p {
    margin: 0;
    font-size: 19px;
    line-height: 1.6;
    padding-left: 20px;
}

.project-info .description {
    font-weight: bold;
}



.project-info .btn {
   
    display: block;
    margin: 0 auto;
    background-color: green;
    color: black;
    border: 1px solid white;
    padding: 10px;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
}

.project-info .btn:hover {
    background-color: black;
    color: white;
}

.delete-btn {
    color: black;
    margin: 0 auto;
    display: block;
    
}

.delete-btn:hover {
    background-color: black;
    color: white;
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


    <section class="show-products">
        <div class="project">



            <!-- Display remaining data and buttons below the image -->
            <div class="project-info">

                <div class="container mt-5">


                <?php
$select_project = $conn->prepare("SELECT * FROM `projects` WHERE id = ?");
$select_project->execute([$project_id]);
$fetch_project = $select_project->fetch(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="mb-3">
        <label for="admin_name">Admin Name:</label>
        <input type="text" name="admin_name" id="admin_name" class="form-control form-control-lg" style="width:50%" value="<?php echo htmlspecialchars($fetch_project['admin_name']); ?>" readonly>
    </div>
  
    
</div>
<?php
$project_details = $conn->prepare("SELECT * FROM `projectdetails` WHERE project_id = ?");
$project_details->execute([$project_id]);
$project_details_list = $project_details->fetchAll(PDO::FETCH_ASSOC);
?>
 


                                        </div>





                </div>

      <div class="project-info">
      
                  
                   
                        <div class="flex-btn">
                        

                            <a href="files.php?delete=<?=$fetch_project['id'];?>" class="delete-btn"
                                style=" text-decoration: none;"
                                onclick="return confirm('delete this project?');">delete</a>
                        </div>
                    </form>
      </div>




            </div>
        </div>
    </section>

    <section class="show-products" style="padding-top: 0;">
        <div class="box-container">
            <?php
if ($admin_Id == '') {
    echo '<p class="empty">Please login to see your projects</p>';
} else {

    if ($project_files->rowCount() > 0) {
        foreach ($fetch_project_files as $file) {
            ?>
            <div class="box">
                <?php if (pathinfo($file['file_path'], PATHINFO_EXTENSION) === 'pdf'): ?>
                    <a href="../uploadedFiles/<?=$file['file_path'];?>" target="_blank"
        style="color:red;font-size:2rem; text-align:center; text-decoration:none; display:block;">

        <i class="fa-solid fa-file-pdf"
            style="font-size:16rem; margin-top:5%; object-fit:contain;  text-align:center; width:100%; color:red;">
        </i>
        <?=$file['file_path'];?>
    </a>


                <?php elseif (pathinfo($file['file_path'], PATHINFO_EXTENSION) === 'psd'): ?>

              <a href="../uploadedFiles/<?=$file['file_path'];?>"
                  style="color:red;font-size :2rem; text-align:center; text-decoration:none; ">
                <img src="../psd.png" alt="">
                <?=$file['file_path'];?>
                    </a>

                <?php else: ?>

                <a href="../uploadedFiles/<?=$file['file_path'];?>"
                    style="color:red;font-size :2rem; text-align:center; text-decoration:none; ">
                    <img src="../uploadedFiles/<?=$file['file_path'];?>" alt="">
                    <?=$file['file_path'];?>
                </a>
                <?php endif;?>
            </div>
            <?php
}
    } else {
        echo '<p class="empty">No Files added yet!</p>';
    }
}
?>
        </div>
    </section>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>