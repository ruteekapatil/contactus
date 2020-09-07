<?php 
use PHPMailer\PHPMailer\PHPMailer;


//$response=array();
$name=$email=$company=$message=$contact="";
$flag=0;
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $response = array();
    $response['success'] = false;

// name_validation
if (empty($_POST["name"]))
{
    $response["nameErr"]= "Name is required";
    $flag=1;
    } else {
    $name = $_POST["name"];
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) 
    {

        $response["nameErr"] = "Only letters and white space allowed";
        $flag=1;
    }
}

// email_validation

if (empty($_POST["email"])) 
{
    $response["emailErr"] = "Email is required";
    $flag=1;
} else 
{
    $email = $_POST["email"];
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
        $response["emailErr"] = "Invalid email format";
        $flag=1;
    }
}

// company_name_validation


if (empty($_POST["company"])) 
{
    $response["CompanyErr"]= "Company name is Required";
    $flag=1;
} else 
{
    $company = $_POST["company"];
    
}
 
// conatct_name_validation

if (empty($_POST["contact"])) 
{
    $response["contactErr"] = "contact  is Required";
    $flag=1;
} 
else 
{
    $contact = $_POST["contact"];
    if(!preg_match('/^\d{10}$/',$contact)) // phone number is valid
    {
              
        $response["contactErr"]='Phone number invalid !';
        $flag=1;
            // your other code here
    }
    
}

//message_validation

if (empty($_POST["message"]))
{
    $response["messageErr"] = "This feild is Required";
    $flag=1;
} else 
{
    $message = $_POST["message"];
}

   
if($flag==0)
    {

        require_once "PHPMailer/PHPMailer.php";
        require_once "PHPMailer/SMTP.php";
        require_once "PHPMailer/Exception.php";


        $mail = new PHPMailer();
        $body = "

            <p> <b>Name : </b> ".$name." </p>
            <p> <b>Mail : </b> ".$email." </p>
            <p> <b>Company Name : </b> ".$company." </p>
            <p> <b>Contact Number </b> ".$contact." </p>
            <p> <b>Where did they hear about us? </b><br> ".$message." </p>
            
    
        ";


        //smtp settings
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "krazypandaadm@gmail.com";
        $mail->Password = 'Krazypanda20#';
        $mail->Port = 465;
        $mail->SMTPSecure = "ssl";
        

        //email settings
        $mail->isHTML(true);
        $mail->setFrom($email, $name);
        $mail->addAddress("krazypandaadm@gmail.com");
        $mail->Subject = "The Brand wants to contact you";
        $mail->Body = $body;

 

        if($mail->send()){
            $status = "success";
            $response = "Email is sent!";
        }
        else
        {
            $status = "failed";
            $response = "Something is wrong: <br>" . $mail->ErrorInfo;
        }

        exit(json_encode(array("status" => $status, "response" => $response)));
    }


// echo all the errors in json form
    $responseJSON = json_encode($response);
    echo $responseJSON;
}
    
?>
