<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;


use PHPMailer\PHPMailer\PHPMailer;


class MailService
{

    public function send(string $to_email, string $subject, string $message, string $from_email = null) {

        if (env('SMTP_DISABLE')) return true;


            $driver = env('MAIL_DRIVER');

            $mailer = new PHPMailer(env('SEND_MAIL_DEBUG'));

            if ($driver == "SMTP") {
                $mailer->isSMTP();
                $mailer->SMTPAuth = true;
                $mailer->SMTPDebug = 0;

                $mailer->Timeout       =   5;
                $mailer->Host = env('SMTP_HOST');
                $mailer->Port = env('SMTP_PORT');
                $mailer->Username = env('SMTP_USER');
                $mailer->Password = env('SMTP_PASSWORD');
                $mailer->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ];
            }

            $mailer->From = $from_email;
            $mailer->FromName = env('FEEDBACK_FROM_NAME');
            $mailer->addAddress($to_email);

            $mailer->Subject = $subject;
            $mailer->CharSet = 'utf-8';
            $mailer->Body = $message;
            $mailer->IsHTML(true);
            if (!$mailer->send()) {
                $errors = new MessageBag;
                session()->flash('errors', $errors->add('email', 'Mailer error:'.$mailer->ErrorInfo));
                return false;
            } else {
                return true;
            }
    }

}

?>