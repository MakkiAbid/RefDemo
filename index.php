<?php
    
    include "config.php";
    include "./include/header.php";


    $email = $password = null;
    $errors = array('email' => '', 'password' => '');

    if(isset($_POST['submit'])) {

        //check email
        if(!empty($_POST['email'])) {
            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $email = $_POST['email'];   
            }else {
                $errors['email'] = 'Please Enter Valid Email';
            }
        }else {
            $errors['email'] = 'Field Cannot be empty!';
        }

        //check password
        if(!empty($_POST['password'])){
            if(preg_match('/^[a-zA-Z0-9]{6,20}+$/', $_POST['password'])){
                $password = $_POST['password'];
            }else{
                $errors['password'] = 'Password Must contain Uppercase,lowercase & Numerics!';
            }
        }else {
            $errors['password'] = 'Field Cannot be empty!';
        }

        if(array_filter($errors)) {
            // echo "INVALID FORM";
        }else {

            $email = mysqli_real_escape_string($conn,stripcslashes($email));


            $query = "SELECT * from users where email = '$email'";
            $results = mysqli_fetch_assoc(mysqli_query($conn,$query));
            if(!empty($results)){
                if(password_verify($password, $results['password'])){
                    unset($results['password']);
                    $_SESSION['user'] = $results;
                    header('Location: dashboard.php');
                }else{
                    $errors['main'] = 'Credentials are invalid.';
                }
            }else{
                    $errors['main'] = 'User does not exists in our system.';

            }
        }

    } //main if ends here
    

?>

<form class="card cell-8 offset-2" action="" method="POST">
    <div class="form-group">
        <div class="card-header"> 
            <h1 class="title text-light offset-4 stylish-title pt-5 mr-10">Ref Demo</h1>
        </div>    
            <div class="card-content p-2 mr-10">
                <?php if(!empty($errors['main'])): ?>
                    <p class="remark alert"><?= $errors['main']; ?></p>
                <?php endif; ?>
                <?php if(!empty($_GET['message']) == 'success'): ?>
                    <p class="remark success">Registered Successfully please login.</p>
                <?php endif; ?>
                <div class="row mb-2">
                    <div class="cell-sm-6 offset-3">
                        <label><strong>Email</strong></label>
                            <input name="email" class="rounded" type="Email">
                            <?php if(!empty($errors['email'])): ?>
                                <p class="remark alert"><?= $errors['email']; ?></p>
                            <?php endif; ?>
                    </div>  
                </div>
                <div class="row mb-2">
                    <div class="cell-sm-6 offset-3">
                            <label><strong>Password</strong></label>
                            <input name="password" class="rounded" type="password">
                            <?php if(!empty($errors['password'])): ?>
                                <p class="remark alert"><?= $errors['password']; ?></p>
                            <?php endif; ?>
                    </div>
                    <div class="cell-sm-6 offset-3">
                            <a href="signup.php" class="tiny-text mt-2">Register Account</a>
                    </div>
                </div>
                <div class="row mb-10">
                    <button name="submit" type="submit" class="button primary rounded cell-sm-6 offset-3">Log In</button>
                </div>
                <div class="card-footer">
                    <?php include "./include/footer.php"; ?>
                </div> 
    </div>     
</form>


