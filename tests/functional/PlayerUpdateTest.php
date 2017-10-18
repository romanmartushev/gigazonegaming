<?php
namespace Tests\Functional;

use App\Models\Auth\Users\User;
use App\Models\Championship\Game;
use App\Models\Championship\IndividualPlayer;
use App\Models\Championship\Player;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Pbc\Bandolier\Type\Numbers;

class PlayerUpdateTest extends \TestCase
{
    use DatabaseTransactions, DatabaseMigrations;

    /**
     *
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_returns_a_success_message_from_register_player_route_makes_sure_player_is_added_to_DB()
    {
        $faker = \Faker\Factory::create();
        $this->withoutMiddleware();
        $email = $faker->email;
        $username = $faker->userName;
        $parameters =['email'=>$email,
            'username'=>$username];
        $array = ['success'=>'Check Your Email!'];
        $this->call('POST', 'http://'.$_SERVER['SERVER_NAME'].'/player/register',$parameters);
        $this->assertRedirectedTo('http://'.$_SERVER['SERVER_NAME'].'/player/register',$array);

        // now check the db for this player
        $getIndividual = Player::where('email', '=', $parameters['email'])->first();
        $getUser = User::where('email', '=', $parameters['email'])->first();
        \Codeception\Util\Debug::debug($getIndividual);
        \Codeception\Util\Debug::debug($getUser);
        $this->assertInstanceOf(Player::class, $getIndividual);
        $this->assertInstanceOf(User::class, $getUser);
        $this->assertSame($getIndividual->email, $parameters['email']);
        $this->assertSame($getUser->email, $parameters['email']);
    }

    /**
     * @test
     */
    public function it_returns_a_redirect_to_the_player_update_page()
    {
        $faker = \Faker\Factory::create();
        $this->withoutMiddleware();
        $email = $faker->email;
        $password = $faker->password;
        $username = $faker->userName;
        $parameters =['email'=>$email,
            'password'=>$password];
        \Sentinel::register([
            'email'=>$email,
            'password'=>$password
        ]);
        $player = Player::create([
            'email'=>$email,
            'username'=>$username
        ]);
        $player->save();
        $this->call('POST', '/player/login',$parameters);
        $this->assertRedirectedTo('/player/playerUpdate');
    }

    /**
     * @test
     */
    public function it_returns_a_redirect_to_the_player_update_page_after_updating_players_information_and_checks_the_db_for_updated_info()
    {
        $faker = \Faker\Factory::create();
        $this->withoutMiddleware();
        $email = $faker->email;
        $username = $faker->userName;
        $name = $faker->name;
        $phone = $faker->phoneNumber;
        $password = $faker->password;
        $parameters =['email'=>$email,
            'name'=>$name,
            'username'=>$username,
            'phone'=>$phone];
        \Sentinel::register([
            'email'=>$email,
            'password'=>$password
        ]);
        $user = User::where('email', '=', $email)->first();
        $player = Player::create([
            'email'=>$email,
            'username'=>$username,
        ]);
        $player->save();
        \Sentinel::activate($user);
        \Sentinel::authenticate($user);
        $this->call('POST', '/player/playerUpdate',$parameters);
        $this->assertRedirectedTo('/player/playerUpdate');

        // now check the db for this players updated information
        $getIndividual = Player::where('email', '=', $parameters['email'])->first();
        \Codeception\Util\Debug::debug($getIndividual);
        $this->assertInstanceOf(Player::class, $getIndividual);
        $this->assertSame($getIndividual->email, $parameters['email']);
        $this->assertSame($getIndividual->name, $parameters['name']);
        $this->assertSame($getIndividual->phone, $parameters['phone']);
        $this->assertSame($getIndividual->username, $parameters['username']);
    }
}
