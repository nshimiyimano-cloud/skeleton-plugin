<script language=JavaScript>
function reload(form)
{
let val=form.formselect.options[form.formselect.options.selectedIndex].value;

self.location='/backend/plugin/skeleton?formselect=' + val;
}


</script>


<?php

include_once "nocrsf.php";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlentities($data, ENT_QUOTES, 'utf-8');
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');  
    $data = strip_tags($data);
            // $data = mysql_real_escape_string($data);
           // $data = strtoupper($data);
    return $data;
 }

?>

<html>
<head>
    <title> send data in database</title>

    <style>
       fieldset{
        margin-bottom:100px;
        padding:20px;
       }

       .form-container{
        width:100%;
        position: relative;
       }

        .form-group{
            position: relative;
            padding:7px;
            margin-bottom:23px;
            width:98%;
            border-top:1px solid #ccc;
        }

        .label{
            position:relative;
            left:13%;   
            }

        .part{            
            position:absolute;
            right:10%;
            width:60%;
            padding:2px;
        }
        .select{
            border:1px solid #888;
            background:white;
        }
        
        input[type='submit']{
            padding:5px 14px;
            font-size:18px;
        }
        
        #newsNotSelected,#jobsNotSelected{
            display:none;
        }
        

       @media only screen and (max-width: 1200px) {
            .label{
            left:-18px;
        }
        .part{          
            right:1%;        
        }

        </style>
</head>
<body>
    
<?php
/* Security measure */
if (!defined('IN_CMS')) { exit(); }
?>

<h1><?php echo('Forms'); ?></h1>
<p>

<?php
//slug validation
function slugify($slug)
{ 
    $sluggedtext= iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
    return strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $sluggedtext));
}


    $errors=array();
 $jotitle=$jocategory=$joslug=$location=$branch=$validfrom=$closingdate="";


    if(($_SERVER["REQUEST_METHOD"] == "POST")&& isset($_POST['save'])){


$chosenform = $_GET['formselect'];

    try{
        //Run CSRF check, on POST data, in exception mode, for 10minutes, in one-time mode.
       NoCSRF::check( 'csrf_token', $_POST, true, 60*10, false );   



    if($chosenform == "newsform"){ 
        
      
        if(empty($_POST['n_title'])){
        array_push($errors,"Please firstly enter  Title is required");        
        }
        else{
            $ntitle=test_input($_POST['n_title']);        
        }

       
        if(empty($_POST['n_category'])){
            array_push($errors,"Please firstly enter  Category is required");  
         }
         else{
             $ncategory=test_input($_POST['n_category']);        
         }

           
         if(empty(slugify($_POST['n_slug']))){
            array_push($errors,"Please,firstly enter Slug is required");        
             }
            else{
             $nslug=slugify(test_input($_POST['n_slug']));  
            }
        
                    
        if(empty($_POST['picture'])){
            array_push($errors,"Please firstly enter  Picture is required");   
            }
            else{
                $picture=test_input($_POST['picture']);            
            }

        if(empty($_POST['n_createdat'])){
            array_push($errors,"Please firstly enter  Created At is required");
            }
            else{
                $ncreatedat=test_input($_POST['n_createdat']);
            }
        if(empty($_POST['description'])){
            array_push($errors,"Please firstly enter  Description is required");
            }
            else{
                $description=test_input($_POST['description']);        
            }

        try {
            $query="SELECT * FROM `prime_news` where slug= '$nslug'";
           $db = Record::getConnection();
           $statement = $db->prepare($query);
           $statement->execute();
           $no_rows = $statement->rowCount();
           }               
       catch (PDOException $e) {
         echo $e->getMessage();
         echo $stmt->errorCode();
       }

        if($no_rows == 0){

         try {
            $insertnews="INSERT INTO prime_news(title,description,slug,picture,createdAt,category)  VALUES('$ntitle','$description','$nslug','$picture','$ncreatedat','$ncategory')";

            $db = Record::getConnection();
            $stmt = $db->prepare($insertnews);
            $isSuccess=$stmt->execute();                   
            }           
            
         catch (PDOException $e) {
          echo $e->getMessage();
          echo $stmt->errorCode();
         }

          if($isSuccesss == true){
           array_push($errors,"News Data Successfully have been saved!");  
           }
      
        }
        else{
            array_push($errors,"Please Page Slug Already exist"); 
        }
           
    }  

    elseif($chosenform =="jobsform"){

        if(empty($_POST['jo_title'])){
            array_push($errors,"Please firstly enter  Job Title is required"); 
            }
    
            else{
                $jotitle=test_input($_POST['jo_title']);        
            }
    
            if(empty($_POST['jo_category'])){
                array_push($errors,"Please firstly enter Job Category is required");        
             }
             else{
                 $jocategory=test_input($_POST['jo_category']);       
             }
    
               
             if(empty($_POST['business_unit'])){
                array_push($errors,"Please,please firstly enter Business Unit is required");                    
                 }
                else{
                 $businessunit=test_input($_POST['business_unit']);                    
                }
            
                        
            if(empty($_POST['branch'])){
                array_push($errors,"Please firstly enter  Branch is required");            
                }
                else{
                    $branch=test_input($_POST['branch']);            
            }
    
            if(empty($_POST['location'])){
                array_push($errors,"Please firstly enter  Location is required");                
                }
                else{
                    $location=test_input($_POST['location']);                
            }
    
    
            if(empty($_POST['valid_from'])){
                array_push($errors,"Please firstly enter  Valid From isrequired");                
                }
                else{
                    $validfrom=test_input($_POST['valid_from']);                
            }


            if(empty($_POST['closing_date'])){
                array_push($errors,"Please firstly enter  Closing Date is required");                
                }
                else{
                    $closingdate=test_input($_POST['closing_date']);                
                } 


            if(empty(slugify($_POST['jo_slug']))){
                array_push($errors,"Please firstly enter Job Slug is required");
                }
                else{
                    $joslug=slugify(test_input($_POST['jo_slug']));                   
            } 

            if(count($errors) == 0){ 
                try {
                    $select="SELECT * FROM `prime_jobs` where page_slug= '$joslug'";
                   $db = Record::getConnection();
                   $statement = $db->prepare($select);
                   $statement->execute();
                   $num_rows = $statement->rowCount();
                   }               
               catch (PDOException $e) {
                 echo $e->getMessage();
                 echo $stmt->errorCode();
               }

                if($num_rows == 0){
                  $insertjob="INSERT INTO prime_jobs(position_title,category,business_unit,jo_location,branch,valid_from,closing_date,page_slug)  VALUES('$jotitle','$jocategory','$businessunit','$location','$branch','$validfrom','$closingdate','$joslug')";

                 try {
                    $db = Record::getConnection();
                    $statement = $db->prepare($insertjob);
                    $isExcuted=$statement->execute();                   
                    }           
                    
                 catch (PDOException $e) {
                  echo $e->getMessage();
                  echo $stmt->errorCode();
                 }

                  if($isExcuted == true){
                   array_push($errors,"<h4>Job Data Successfully have been saved!</h4>");
                   }
                }
                else{
                    array_push($errors,"Please Page Slug Already exist"); 
                }                   
            }     
         }
         else{       
            Flash::set('error',('Please Firstly Select form before submit!'));
        }
        
    }catch (Exception $e) {
            // CSRF attack detected    
             //$sysErr = $e->getMessage() .' Request was not submitted, try again.';   
             array_push($errors,$e->getMessage() .' Request was not submitted, try again.');     
           }    
  }

  $token = NoCSRF::generate( 'csrf_token' );    


 



?>


<div class="container">

    <?php
     
     
   if(count($errors) > 0){
    //Flash::set('error',('Invalid form you need to Fill all Field below in error Details'));
       ?>

   <fieldset class="fieldset"> 
    <legend> Query FeedBack</legend>
       <?php
       foreach ($errors as $key => $err) {
              echo '<p class="error-block">'.$err.'</p>';      
       }
       ?>
       </fieldset>       
       <?php
   }
   ?>
<form action="" autocomplete="off" method="post">
   <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
   <fieldset>    
    <legend> Select form To use </legend>    

        <div class="form-container">

        <div class="form-group">
            <label class="part label">Select Form</label>
            
              <?php  echo "<select  name='formselect' class=\"part selec\"  onchange=\"reload(this.form)\">";  ?>
            <option value="">

            <?php
            if(!empty( $_GET['formselect'])){
                echo  $_GET['formselect'];
            }
            else{                
           echo  Select;
            }
            ?>
        </option>
                <option value="jobsform">Jobs Form </option>
                <option value="newsform">News Form </option>
            </select>          
        
        </div>
  </div>

</fieldset>


<fieldset class="news"   id="<?php echo ($_GET['formselect'] == "newsform") ? "newsform" : "newsNotSelected";  ?>">  

    <legend> News Form </legend>  

       <div class="form-container  news">        
       
       <div class="form-group">
            <label class="part label">News Title</label>
            <input class="part select"  name="n_title" type="text"/>
            </input>        
        </div>

        <div class="form-group">
            <label class="part label">News Category</label>
            <input class="part select"  name="n_category" type="text" />
            </input>        
        </div>

        <div class="form-group">
            <label class="part label"> News  Slug</label>
            <input class="part select"  name="n_slug" type="text"  placeholder="Ex: copy title and paste  here will be converted in slug before save" />
            </input>        
        </div>

        <div class="form-group" style="margin-bottom:70px;">
            <label class="part label">Description</label>
            <textarea  name="description" rows="4" style="width:60.5%;" class="part select" />
        </textarea>               
        </div>

        <div class="form-group">
            <label class="part label">Picture</label>
            <input class="part  select""  name="picture" type="text" />        
        </div>

        <div class="form-group">
            <label class="part label">Created At </label>
            <input class="part select" name="n_createdat" type="text" placeholder="Ex:2021-05-29" />                    
        </div>
   </div>

</fieldset>
</fieldset>




<fieldset class="jobs" id="<?php echo ($_GET['formselect'] == "jobsform") ? "jobsform" : "jobsNotSelected";  ?>" >

    <legend> Jobs Form </legend>  
       <div class="form-container  jobs">       
       
       <div class="form-group">
            <label class="part label">Job Title</label>
            <input class="part select"  name="jo_title" type="text" >
            </input>        
        </div>


        <div class="form-group">
            <label class="part label">Job Category</label>
            <input class="part select"  name="jo_category" type="text" >
            </input>        
        </div>

        <div class="form-group">
            <label class="part label">Business Unit</label>
            <input class="part select"  name="business_unit" type="text" >
            </input>        
        </div>      

        <div class="form-group">
            <label class="part label">Branch</label>
            <input class="part  select"  name="branch" type="text" />                   
        </div>

        <div class="form-group">
            <label class="part label">Location </label>
            <input class="part select" name="location" type="text" />                    
        </div>

        <div class="form-group">
            <label class="part label">Valid From </label>
            <input class="part select" name="valid_from" type="text" placeholder="Ex: 2021-09-30 01:00:00" />                    
        </div>

        <div class="form-group">
            <label class="part label">Closing Date </label>
            <input class="part select" name="closing_date" type="text"  placeholder="Ex: 2021-10-30 01:00:00" />                    
        </div>


        <div class="form-group">
            <label class="part label">Jobs Page Slug</label>
            <input class="part select"  name="jo_slug" type="text" placeholder="Ex: copy title and paste  here will be converted in slug before save">
            </input>        
        </div>
        
   </div>

   </fieldset>
   </fieldset>
   
 
    <div class="form-group">
    <input type="submit" name="save" value="Save">
</div>
  </form>
 </div>
 
 
<script>
let news=document.getElementById("newsForm");
    let jobs=document.getElementById("jobsForm");
    let jobsnotSelected=document.getElementById("jobsNotSelected");
    let newsnotSelected=document.getElementById("newsNotSelected");
   
    
    
   
    
    
    if((jobsnotSelected =="jobsNotSelected")  && (newsnotSelected == "newsNotSelected")){
    jobsnotSelected.style.display="none";
    newsnotSelected.style.display="none";
    }
    else{
    if(jobs.style.display== "block"){
    news.style.display="none";
    }
    else if(news.style.display=="block"){
    jobs.style.display="none";
    }
    
    }






</script>



</body>
</html>