<?php

/**
 * @package   Email controller
 * @author    Ntabethemba Ntshoza
 * @date      16-02-2024
 * @copyright Copyright © 2024 VMP By Maneza
 */

namespace Controller;

use Model\Person;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class EmailSenderController
{
    /**
     * Send Email
     *
     * @param integer $userId
     * @return void
     */
    public function sendEmail(int $userId): void
    {
        // Create an instance of Person to retrieve user details
        $person = new Person();
        $personData = $person->getUserById($userId);

        $mail = new PHPMailer(true);
        $person = new Person();

        $logger = new Logger('SEND-EMAIL');
        // Now add some handlers
        $logger->pushHandler(new StreamHandler('var/System.log', Logger::DEBUG));

        $config = include __DIR__ . '/../config.php';

        $host = $config["mail"]["host"];
        $username = $config["mail"]["username"];
        $password = $config["mail"]["password"];
        $port = $config["mail"]["port"];

        try {
            //Server settings
            $mail->Debugoutput = 'error_log';
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->Username = $username;
            $mail->Password = $password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = $port;

            //Recipients
            $mail->setFrom('notifications@ental.co.za', 'ProPay SA');

            if ($personData !== null && is_array($personData)) {
                $mail->addAddress($personData['email_address'], $personData['name']);
            } else {
                $mail->addAddress($person->getEmailAddress(), $person->getName());
            }

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'You have been added to ProPay';
            $mail->Body = "<html>
                                <body>
                                    <p>Heeey <b>{$personData['name']}</b></p>
                                    <p>Welcome to ProPay!</p>
                                    <p>Congratulations on joining our community. We are thrilled to have you on board.</p>
                                    <p>At Propay, we believe in making your experience seamless and secure. You are now in trusted hands,
                                        and we are here to assist you every step of the way.</p>
                                    <p>What you can expect:</p>
                                    <ul>
                                        <li>Secure transactions and payments</li>
                                        <li>Personalized support to meet your needs</li>
                                        <li>Regular updates on new features and offerings</li>
                                    </ul>
                                    <p>We will be in touch soon with more information tailored just for you!</p>
                                    <p>If you have any questions or need assistance, feel free to reach out to our support team.</p>
                                    <p>Once again, welcome to ProPay!</p>
                                    <p>Best regards,<br><b>Your ProPay Team</b></p>
                                </body>
                                </html>";

            $mail->AltBody = 'Welcome to Propay!\n\nCongratulations on joining our community. We are thrilled to have you on board.\n\nAt Propay, we believe in making your experience seamless and secure. You are now in trusted hands, and we are here to assist you every step of the way.\n\nHere\'s what you can expect:\n- Secure transactions and payments\n- Personalized support to meet your needs\n- Regular updates on new features and offerings\n\nWe will be in touch soon with more information tailored just for you!\n\nIf you have any questions or need assistance, feel free to reach out to our support team.\n\nOnce again, welcome to Propay!\n\nBest regards,\nYour Propay Team';

            if ($mail->send()) {
                $logger->info("Email has been sent to: {$personData['name']}");
            }
        } catch (Exception $e) {
            $logger->error("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }
}
