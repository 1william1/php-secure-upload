<?php
   class config {
     static $allowedFiles = ["zip","7zip","rar", "txt"];
     static $uploadDir = "./user uploads/";
     static $maxFileSize = 20 * 1000000; // 20mb
   }

   function uploadFile() {

     $target_file = config::$uploadDir.basename($_FILES["file"]["name"]);
     $extention = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

     if(file_exists($target_file)) {
         return [
           "success" => false,
           "reason" => "File already exists."
         ];
     }

     if($_FILES["file"]["size"] > config::$maxFileSize) {
         return [
           "success" => false,
           "reason" => "File size too large."
         ];
     }

     if(in_array($extention, config::$allowedFiles) == false) {
         return [
           "success" => false,
           "reason" => "File type not allowed."
         ];
     }

     if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
         return [
           "success" => true,
           "reason" => "File successfully uploaded."
         ];
     } else {
       echo $_FILES["file"]["tmp_name"]."END<br>";
       echo $target_file;
         return [
           "success" => false,
           "reason" => "A unexpected error occurred."
         ];
     }

   }

   $status = "";

   if(isset($_FILES["file"])) {
     $success = uploadFile();
     if($success["success"] == true) {
       $status = '<div class="alert alert-success" role="alert">'.htmlspecialchars($success["reason"]).'</div>';
     } else {
       $status = '<div class="alert alert-danger" role="alert">'.htmlspecialchars($success["reason"]).'</div>';
     }
   }

   $fileTypes = "";

   foreach (config::$allowedFiles as $key => $value) {
     $fileTypes .= '<span class="badge badge-primary">.'.htmlentities($value)."</span> ";
   }
   ?>
<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
      <title>Upload</title>
   </head>
   <body>
      <div class="container">
         <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/">Secure Upload</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
               <ul class="navbar-nav mr-auto">
                  <li class="nav-item">
                     <a class="nav-link" href="/">Home</a>
                  </li>
                  <li class="nav-item active">
                     <a class="nav-link" href="#">Upload <span class="sr-only">(current)</span></a>
                  </li>
               </ul>
            </div>
         </nav>
         <br>
           <div class="jumbotron jumbotron-fluid">
              <div class="container">
                 <h1 class="display-4">Secure Upload<h1>
                 <p class="lead">Upload a <?php echo $fileTypes; ?> file and add it to the site. (Max file size <?php echo htmlspecialchars(config::$maxFileSize / 1000000 ."mb"); ?>)</p>
              </div>
           </div>
           <?php echo $status; ?>
         <form method="post" enctype="multipart/form-data">
           <div class="form-group">
            <label for="file">Upload File</label>
            <input type="file" class="form-control-file" name="file" id="file">
          </div>
            <button style="margin-top: 20px;" type="submit" class="btn btn-primary">Upload</button>
         </form>
      </div>
      <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
   </body>
</html>
