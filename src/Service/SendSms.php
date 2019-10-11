<?php


namespace App\Service;
use Twilio\Rest\Client;

class SendSms
{

    public function send($twilio_sid, $twilio_token, $phone, $code)
    {

        $twilio = new Client($twilio_sid, $twilio_token);
        $send =  false;

        try {
            $message = $twilio->messages
                ->create(
                    $phone,
                    // "+212658744775",
                    array(
                        "from" => "+13302949018",
                        "body" => 'Utilisez le code ' . $code . ' pour accéder au résultat de votre simulation !'
                    )
                );
            $send = true;
        }
        catch (Exception $e) {

        }

        return $send;

    }


    public function sendTest($twilio_sid, $twilio_token, $phone, $code)
    {

        $twilio = new Client($twilio_sid, $twilio_token);
        $send = false;

        try {
            $message = $twilio->messages
                ->create(
                    $phone,
                    // "+212658744775",
                    array(
                        "from" => "+13302949018",
                        "body" => 'Utilisez le code ' . $code . ' pour accéder au résultat de votre simulation !'
                    )
                );
            $send= true;
        }
        catch (Exception $e) {

        }

        return $send;

    }

}

