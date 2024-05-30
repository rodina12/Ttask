<?php

include '../sharedComponents/connect.php';

session_start();

if(isset($_SESSION['admin_Id'])){
   $admin_Id = $_SESSION['admin_Id'];
}else{
   $admin_Id = '';
};

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $number = $_POST['number'];
    $password = $_POST['pass'];

    // Sanitize input
    $number = preg_replace('/\D/', '', $number);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    if (!empty($number) && !empty($password)) {
        // Logic for login using prepared statement
        $select_user = $conn->prepare("SELECT * FROM `admin` WHERE number = ? AND password = ?");
        $select_user->execute([$number, $password]);
        $row = $select_user->fetch(PDO::FETCH_ASSOC);

        if ($select_user->rowCount() > 0) {
            // Update user status
            $status = "Active now";
            $sql2 = $conn->prepare("UPDATE admin SET status = ? WHERE unique_id = ?");
            if ($sql2->execute([$status, $row['unique_id']])) {
                $_SESSION['admin_Id'] = $row['Id'];
                $_SESSION['unique_id'] = $row['unique_id'];
                header('location:files.php');
                exit; // Ensure no further code execution after redirection
            } else {
                $message[] = 'Something went wrong. Please try again!';
            }
        } else {
            $message[] = 'Incorrect number or password!';
        }
    } else {
        $message[] = 'All input fields are required!';
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


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Designer Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="icon" href="../alwan.png" type="image/png">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin.css">

</head>
<?php include '../sharedComponents/loginHeader.php' ?>

<body style="background-image: url('../img2.avif')">

    <section class="form-container">

        <form action="" method="post" style="background-color: rgba(255, 255, 255, 0.15);">
            <h3 style="color:white">Designers login</h3>
            <input type="number" name="number" required placeholder="enter your number" class="box" maxlength="50"
                oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="pass" required placeholder="enter your password" class="box" maxlength="50"
                oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" value="login now" name="submit" class="btn btn-primary" style="width: 50%;margin: 0 auto;">
           

        </form>

    </section>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- custom js file link  -->
    <script src="../javascript/script.js"></script>

</body>

</html>