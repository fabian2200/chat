<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailController extends Controller
{

  
    public function enviaeEmailRecuperarContrasena($password, $email, $nombre) {
        $this->enviarCorreo($password, $email, $nombre);
    }

    public function enviarCorreo($password, $email, $nombre) {
        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ing_fabian_quintero@ingeer.co'; 
            $mail->Password = 'ezqu pwzu mloo feqb'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            $mail->setFrom('ing_fabian_quintero@ingeer.co', 'Departamento de Sistemas');
            $mail->addAddress($email);
            $mail->addCC('e_suarez@ingeer.co');
            $mail->SMTPKeepAlive = true;  
            $mail->Mailer = "smtp"; 
            $mail->isHTML(true);

            $subject = 'Recuperar contraseña ' . $nombre . ' - INGEER';
            $encoded_subject = mb_encode_mimeheader($subject, 'UTF-8');
            $mail->Subject = $encoded_subject;
            $mail->Body = self::mapearPlantilla($nombre, $password);
        
            if ($mail->send()) {
                \Log::info('Mensaje enviado correctamente');
            } else {
                \Log::error('Error al enviar: ' . $mail->ErrorInfo);
            }
        } catch (Exception $e) {
            \Log::error('Error al enviar: ' . $e->getMessage());
        }
    }

    public function mapearPlantilla($nombre, $password ){
       return  "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
            <html xmlns='http://www.w3.org/1999/xhtml'>
            <head>
                <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                <meta name='viewport' content='width=device-width, initial-scale=1' />
                <title>Narrative Invitation Email</title>
                <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
                <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
                <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js'></script>
                <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'></script>
                <style type='text/css'>

                /* Take care of image borders and formatting */

                img {
                max-width: 600px;
                outline: none;
                text-decoration: none;
                -ms-interpolation-mode: bicubic;
                }

                a {
                border: 0;
                outline: none;
                }

                a img {
                border: none;
                }

                /* General styling */

                td, h1, h2, h3  {
                font-family: Helvetica, Arial, sans-serif;
                font-weight: 400;
                }

                body {
                -webkit-font-smoothing:antialiased;
                -webkit-text-size-adjust:none;
                width: 100%;
                height: 100%;
                color: #37302d;
                background: #ffffff;
                }

                table {
                border-collapse: collapse !important;
                }


                h1, h2, h3, h4 {
                padding: 0;
                margin: 0;
                color: #444444;
                font-weight: 400;
                line-height: 110%;
                }

                h1 {
                font-size: 35px;
                }

                h2 {
                font-size: 30px;
                }

                h3 {
                font-size: 24px;
                }

                h4 {
                font-size: 18px;
                font-weight: normal;
                }

                .important-font {
                color: #21BEB4;
                font-weight: bold;
                }

                .hide {
                display: none !important;
                }

                .force-full-width {
                width: 100% !important;
                }

                </style>

                <style type='text/css' media='screen'>
                    @media screen {
                    @import url(http://fonts.googleapis.com/css?family=Open+Sans:400);

                    /* Thanks Outlook 2013! */
                    td, h1, h2, h3 {
                        font-family: 'Open Sans', 'Helvetica Neue', Arial, sans-serif !important;
                    }
                    }
                </style>
            </head>
            <body class='body' style='padding:0; margin:0; display:block; background:#ffffff; -webkit-text-size-adjust:none' bgcolor='#ffffff'>
                <div class='row' style='padding-top: 20px'>
                    <div class='col-lg-9' style= 'padding-left: 30%; padding-right: 30%;border-right: 3px solid gray;border-left: 3px solid gray;'>
                        <h3><b>Recuperar contraseña</b></h3>
                        <br>
                        <h4>Cordial saludo $nombre:</h4>
                        <br>
                        <h4>Este correo fue enviado por el equipo de INGEER en respuesta a tu solicitud recuperación de contraseña.</h4>
                        <h4>De acuerdo a los datos proporcionados, tu nueva contraseña es: <strong style='color: #d10808; font-weight: bold'>$password</strong></h4>
                        <br>
                        <h4>Por favor, no respondas a este correo electrónico. Si tienes alguna pregunta, por favor contáctanos a través de nuestro sitio web o correo electrónico de soporte.</h4>
                        <br>
                        <hr>
                        <h4 style='font-weight: bold;color: #3F51B5; font-size: 1.5rem;margin-bottom: 10px;'>Atentamente</h4>
                        <h4>Departamento de Sistemas INGEER S.A.S</h4>
                        <h4><strong>Soporte:</strong> soporte@ingeer.co</h4>
                        <h4>Mas que ingeniería</h4>                
                    </div>
                </div>
            </body>
        </html>";
    }
}