<?php

    $firstname = $lastname = $email = $phone = $message = "";
    $firstnameError = $lastnameError = $emailError = $phoneError = $messageError = "";
    $isSuccess = false;
    $emailTo = "contact@dev-web.fr"; // destinataire
    // apres le post du formulaire
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $firstname = verifyInput($_POST['firstname']);
        $lastname = verifyInput($_POST['lastname']);
        $email = verifyInput($_POST['email']);
        $phone = verifyInput($_POST['phone']);
        $message = verifyInput($_POST['message']);
        $isSuccess = true;
        $emailText = "";

        if(empty($firstname)){
            $firstnameError = "Je veux conaitre ton prénom !";
            $isSuccess = false;
        }else{
            $emailText .= "Firstname: $firstname\n";
        }
        if(empty($lastname)){
            $lastnameError = "Oui je veux savoir même ton nom !";
            $isSuccess = false;
        }else{
            $emailText .= "Lastname: $lastname\n";
        }
        if(!isEmail($email)){
            $emailError = "Ton email n'a pas la bonne forme nom@domaine.com";
            $isSuccess = false;
        }else{
            $emailText .= "Email: $email\n";
        }
        if(!isPhone($phone)){
            $phoneError = "Ton téléphone n'est pas bon !";
            $isSuccess = false;
        }else{
            $emailText .= "Phone: $phone\n";
        }
        if(empty($message)){
            $messageError = "Qu'est ce que tu veux me dire ?";
            $isSuccess = false;
        }else{
            $emailText .= "Message: $message\n";
        }
        if($isSuccess){
            $headers = "From: $firstname $lastname <$email>\r\nReply-To: $email"; // headers pour la gestion de reponse du mail
            mail($emailTo,'Subjet Email', $emailText, $headers);
            $firstname = $lastname = $email = $phone = $message = "";
        }
    }

    // regex des telephones
    function isPhone($var){
        return preg_match('/^[0-9 ]*$/', $var);
    }

    // regex de l'email
    function isEmail($var){
        return filter_var($var, FILTER_VALIDATE_EMAIL);
    }

    // function faille XSS
    function verifyInput($var){
        $var = trim($var);  // nettoyage caracteres indesirables
        $var = stripslashes($var); // nettoyage des / \
        $var = htmlspecialchars($var); // verification de l'url
        return $var;
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-moi !</title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato" type="text/css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="heading">
            <h2>Contactez-moi</h2>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <form id="contact-form" method="post" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="firstname" class="form-label">Prénom <span class="blue">*</span> </label> 
                            <input id="firstname" name="firstname" type="text" class="form-control" placeholder="Votre prénom" value= "<?php echo $firstname; ?>" >
                            <p class="comments"> <?php echo $firstnameError ?> </p>
                        </div>

                        <div class="col-lg-6">
                            <label for="lastname" class="form-label">Nom <span class="blue">*</span> </label> 
                            <input id="lastname" name="lastname" type="text" class="form-control" placeholder="Votre nom" value= "<?php echo $lastname; ?>" >
                            <p class="comments"> <?php echo $lastnameError ?> </p>
                        </div>

                        <div class="col-lg-6">
                            <label for="email" class="form-label">Email <span class="blue">*</span> </label> 
                            <input id="email" name="email" type="email" class="form-control" placeholder="Votre Email" value= "<?php echo $email; ?>" >
                            <p class="comments"> <?php echo $emailError ?> </p>
                        </div>

                        <div class="col-lg-6">
                            <label for="phone" class="form-label">Téléphone </label> 
                            <input id="phone" name="phone" type="tel" class="form-control" placeholder="Votre téléphone" value= "<?php echo $phone; ?>" >
                            <p class="comments"> <?php echo $phoneError ?> </p>
                        </div>
                        
                        <div class="col-lg-12">
                            <label for="message" class="form-label">Message <span class="blue">*</span> </label> 
                            <textarea id="message" name="message" rows="4" class="form-control" placeholder="Votre message"><?php echo $message; ?></textarea>
                            <p class="comments"> <?php echo $messageError ?> </p>
                        </div>

                        <div class="col-lg-12">
                            <p class="blue"><strong>* Ces informations sont requises</strong></p>
                        </div>

                        <div class="col-lg-12 text-center">
                            <input type="submit" class="button1" value="Envoyer">
                        </div>
                    </div>

                    <p class="thank-you" style="display: <?php if($isSuccess){echo 'block';}else{echo 'none';}  ?> " >Votre message a bien été envoyé. Merci de m'avoir contacté.</p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>