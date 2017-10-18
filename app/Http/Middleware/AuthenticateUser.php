<?php
namespace App\Http\Middleware;
use App\Http\Requests\Auth\PlayerAuthRequest;
use App\Models\Auth\Users\User;

/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 7/13/17
 * Time: 10:25 AM
 */
class AuthenticateUser
{
    protected $request;

    public function __construct(PlayerAuthRequest $request)
    {
        $this->request = $request;
    }
    /** This validates a user
     * Checks to see if the user exists
     * completes activation
     * authenticates user
     * then sends them to their player profile
     */
    public function login()
    {
        $this->validateRequest($this->request);
        if($user = User::where('email',$this->request->email)->first()) {
            if (!\Activation::completed($user)) {
                \Sentinel::activate($user);
                if(\Sentinel::authenticate(['email' => $this->request->email, 'password' => $this->request->password])){
                    return redirect("/player/playerUpdate");
                }
                return redirect("/player/login")->withErrors("Invalid Password");
            }
            elseif(\Sentinel::authenticate(['email' => $this->request->email, 'password' => $this->request->password])){
                return redirect("/player/playerUpdate");
            }
            return redirect("/player/login")->withErrors("Invalid Password");
        }
        return redirect("/player/login")->withErrors("Invalid Email");
    }

    protected function validateRequest($request){

        return $request;
    }

}