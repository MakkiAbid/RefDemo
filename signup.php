<?php
    
    include "config.php";
    include "./include/header.php";
    include "include/functions.php";


     if(!empty($_GET['ref_token'])){
        $token = $_GET['ref_token'];
        $ref_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users where token = '$token'"));

    }


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
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }else{
                $errors['password'] = 'Password Must contain Uppercase,lowercase & Numerics!';
            }
        }else {
            $errors['password'] = 'Field Cannot be empty!';
        }

        $token = getUniqueToken();


        if(array_filter($errors)) {
            // echo "INVALID FORM";
        }else {

            $email = stripcslashes($_POST['email']);
            $email = mysqli_real_escape_string($conn,$_POST['email']);

            if(!empty($ref_user)){
                $rf_user_id = $ref_user['id'];
                $insert_query = "INSERT INTO users(ref_id,email,password,token) VALUES ('$rf_user_id','$email','$password','$token')";
                $balance = $ref_user['balance'] + 5;
                mysqli_query($conn,"UPDATE users SET balance = '$balance' where id = '$rf_user_id'"); 
            }else{
                $insert_query = "INSERT INTO users(email,password,token) VALUES ('$email','$password','$token')";    
            }

            
            if(mysqli_query($conn,$insert_query)) {
                header('Location: index.php?message=success');
            }else{
                echo '<p class= "remark alert">Query Error: ' . mysqli_error($conn);'</p>';
            }
        }




    } //main if ends here

   
    

?>

<form enctype="multipart/form-data" class="card cell-8 offset-2" action="" method="POST">
    <div class="form-group">
        <div class="card-header"> 
            <h1 class="title text-light offset-4 stylish-title pt-5 mr-10">Ref Demo</h1>
        </div>    
            <div class="card-content p-2 mr-10">
                <?php if(!empty($errors['main'])): ?>
                    <p class="remark alert"><?= $errors['main']; ?></p>
                <?php endif; ?>
                <?php if(!empty($ref_user)): ?>
                <div class="row mb-2">
                    <div class="cell-sm-6 offset-3">
                        <label><strong>Ref. User</strong></label>
                            <input value="<?= $ref_user['email'] ?>" name="email" class="rounded" type="email" disabled>
                    </div>  
                </div>
            <?php endif; ?>
                <div class="row mb-2">
                    <div class="cell-sm-6 offset-3">
                        <label><strong>Email</strong></label>
                            <input name="email" class="rounded" type="email">
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
                        <a href="index.php" class="tiny-text mt-2">Already have an account?</a>
                    </div>
                </div>
                <div class="row mb-10">
                    <button name="submit" type="submit" class="button primary rounded cell-sm-6 offset-3">Sign up</button>
                </div>
                <div class="card-footer">
                    <?php include "./include/footer.php"; ?>
                </div> 
    </div>     
</form>