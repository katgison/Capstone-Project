<?php

include "conn.php";
session_start();


//this code is for registration

if(isset($_POST['create'])){
     
   $email = $_POST['email'];
   $password = $_POST['password'];
   $ln = $_POST['ln'];
   $fn = $_POST['fn'];
   $contact = $_POST['cn'];
   $id_no = $_POST['id_no'];
   $department = $_POST['department'];
   $user_type = $_POST['user_type'];



   //validate email / users
   $validate_email = mysqli_query($conn, "SELECT * FROM accounts WHERE email='$email'");
   
   $validate_num = mysqli_num_rows($validate_email);
   if($validate_num <= 0 ){

      $insert = mysqli_query($conn, "INSERT INTO accounts
      VALUES('0', '$email', '$password','$id_no', '$ln', '$fn','$contact','$department','$user_type')");

          if($insert ==true){
             ?>
             <script>
              alert("Registration Successfully Accepted!");
              window.location.href="index.html";
             </script>
            <?php
          }else{
            ?>
            <script>
              alert("Error in Registration1");
              window.location.href="index.html";
            </script>
            <?php
          }
        }else{
              ?>
              <script>
                alert("Account already Registered!");
                window.location.href="index.html";
              </script>
              <?php
        }
   }




//--- This code is for Admin

if(isset($_POST['admin_log'])){

   $admin_email = $_POST['email'];
   $admin_pass = $_POST['password'];


   $query = mysqli_query($conn, "SELECT * FROM admin WHERE email='$admin_email' AND password = '$admin_pass'");
   $validate = mysqli_num_rows($query);

   if($validate >= 1){
      $_SESSION['email'] = $admin_email;
      ?>
      <script>
         alert("Welcome Admin");
         window.location.href='admin/index.php';
      </script>
      <?php
   }else{
      
      ?>
         <script>
            alert("Error Email or Password");
            window.location.href='index.html';
         </script>
      <?php
  
   }
       
}

//--- This code is for Student

if(isset($_POST['login_student'])){

   $student_email = $_POST['email'];
   $student_pass = $_POST['pass'];


   $query1 = mysqli_query($conn, "SELECT * FROM accounts WHERE email='$student_email' AND password = '$student_pass' and user_type='Student'");
   $validate1 = mysqli_num_rows($query1);
   if($validate1 >= 1){
      $_SESSION['email'] = $student_email;
      ?>
      <script>
         alert("Login Success");
         window.location.href='student/index.php';
      </script>
      <?php
   }else{
      
      ?>
         <script>
            alert("Error Email or Password");
            window.location.href='index.html';
         </script>
      <?php
      
   }
}  

if(isset($_POST['teach_login'])){

   $teach_email = $_POST['email'];
   $teach_pass = $_POST['pass'];


   $query1 = mysqli_query($conn, "SELECT * FROM accounts WHERE email='$teach_email' AND password = '$teach_pass' AND user_type='Teacher'");
   $validate1 = mysqli_num_rows($query1);
   if($validate1 >= 1){
      $_SESSION['email'] = $teach_email;
      ?>
      <script>
         alert("Login Success");
         window.location.href='teacher/index.php';
      </script>
      <?php
   }else{
      
      ?>
         <script>
            alert("Error Email or Password");
            window.location.href='index.html';
         </script>
      <?php
      
   }
}     

if(isset($_POST['time'])){
   date_default_timezone_set("Asia/Manila");
   $date_now = date('d-m-Y');
   $time_in = date('h:i a');
   $time_type = $_POST['time_type'];

   $id_no = $_POST['id_no'];
   $check_students = mysqli_query($conn,"SELECT * FROM accounts WHERE id_number = '$id_no'");
   $student_num = mysqli_num_rows($check_students);
   if($student_num >= 1){
      switch($time_type){
         case "Time_In";
         $check_time = mysqli_query($conn,"SELECT * FROM time_records WHERE id_number ='$id_no' AND date='$date_now'");
         $time_num = mysqli_num_rows($check_time);
         if($time_num >= 1){
            ?>
               <script>
                  alert("You can only time in once");
                  location.href='index.html';
               </script>
            <?php
         }else{
            $insert_time  = mysqli_query($conn,"INSERT INTO time_records VALUE('0','$id_no','$date_now','$time_in','','','Time In')");
            if($insert_time == true){
               ?>
                  <script>
                     alert("Time in success!");
                     location.href='index.html';
                  </script>
               <?php
            }else{
               ?>
                  <script>
                     alert("Time in not inserted");
                     location.href='index.html';
                  </script>
               <?php
            }
         }
         break;
         case "Time_Out";
         $checkk_timee_out = mysqli_query($conn,"SELECT * FROM time_records WHERE date='$date_now' AND id_number='$id_no' AND status ='Time Out'");
         $time_out_num = mysqli_num_rows($checkk_timee_out);
         if($time_out_num >= 1){
            ?>
               <script>
                  alert("You can only time out once!");
                  location.href='index.html';
               </script>
            <?php
         }else{

     
         $update_time = mysqli_query($conn,"UPDATE time_records SET time_out='$time_in',status='Time Out' WHERE date='$date_now' AND id_number='$id_no'");
         if($update_time == true){
               $get_dtr = mysqli_query($conn,"SELECT * FROM time_records WHERE id_number='$id_no' AND date = '$date_now' AND status='Time Out'");
               while($dtr = mysqli_fetch_object($get_dtr)){
                  $time_in = $dtr->time_in;
                  $time_out = $dtr->time_out;
            
               $from = strtotime($time_in);
					$to = strtotime($time_out);

					$diff_time = round(abs($from - $to) / 60);

					
					$hours = floor($diff_time / 60); // in hours
					$min = $diff_time - ($hours * 60); // in minutes0002750695
					
					$min2 = $hours * 60;
					$total = $min + $min2;

             

               

               $update_total_min = mysqli_query($conn,"UPDATE time_records SET total_min = '$total' WHERE id_number='$id_no' AND date='$date_now' AND status='Time Out'");
            }
            ?>
            <script>
               alert("Time Out success!");
               location.href='index.html';
            </script>
         <?php
         
         
         
              
         }else{
            ?>
            <script>
               alert("Time Out error!");
               location.href='index.html';
            </script>
         <?php
         }
      }
      }
     

   }else{
      ?>
         <script>
            alert("This ID Number is not registred!");
            location.href='index.html';
         </script>
      <?php
   }
    

}




?>