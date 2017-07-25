<?php
namespace App\Models\Auth;
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 7/18/17
 * Time: 10:29 AM
 */
use App\Http\Requests\Auth\PlayerRegisterRequest;
use App\Models\Auth\PlayerUpdate;

class RegisterUser
{
    protected $request;

    public function __construct(PlayerRegisterRequest $request)
    {
        $this->request = $request;
    }

    public function register()
    {
        $this->validateRequest($this->request);
        $message = $this->createUser($this->request);
        /*gets the inbox_id from mail trap*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://mailtrap.io/api/v1/inboxes?api_token=122ed35b015da58276e95c8d8cb81fee");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        $inbox = json_decode($response);
        $inbox_id = $inbox[0]->id;
        /*gets the password from the message sent*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://mailtrap.io/api/v1/inboxes/".$inbox_id."/messages?api_token=122ed35b015da58276e95c8d8cb81fee");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        $inboxMessage = json_decode($response);
        $messageID = $inboxMessage[0]->id;
        $messagePassword = trim($inboxMessage[0]->text_body);
        /*deletes the message sent*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://mailtrap.io/api/v1/inboxes/".$inbox_id."/messages/".$messageID);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $response = curl_exec($ch);
        curl_close($ch);

        return $message;

    }

    protected function validateRequest($request){

        return $request;
    }
    protected function createUser($request){

        return PlayerUpdate::generateUser($request);
    }

}