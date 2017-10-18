<?php

namespace Tests\Integration\Classes;


use GameDisplay\RiotDisplay\API\Api;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
//todo: Test get Champions with fixture data if possible
class RiotApiTest extends \TestCase
{
    /**
     * @var \IntegrationTester
     */
    use DatabaseTransactions, DatabaseMigrations;

    protected $API;

    public function setUp()
    {
        parent::setUp();
        $this->API = new Api();
    }

    protected function resetDB()
    {
        // reset all the databases
//        $this->data;
        $x = [
            'server' => env('DB_HOST'),
            'user' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'database' => env('DB_DATABASE'),
            'dump' => 'database/dump/gigazone_wp.sql',
        ];
    }
    public function tearDown()
    {
        parent::tearDown();
    }

    // tests
    public function testCreatingObjectShouldHaveApiKeyNotNull()
    {
        $this->assertNotNull($this->API->getApiKey());
    }

    public function testRequestDDragonVersionMethodAndSeeThatTheVersionIsSetToMatchReg(){
        $this->API->requestDDragonVersion();
        $this->assertEquals(preg_match("[\d{1,3}\.\d{1,3}\.\d{1,3}]",$this->API->getDDragonVersion()),1);
    }

    public function testRequestSummonerIDAndIconIdMethodAndSeeThatSummonerIdAndIconIdIsSet(){
        $this->API->setSummoner("manklar");
        $this->API->requestSummonerIDAndIconId();
        $this->assertTrue(is_int($this->API->getSummonerId()));
        $this->assertTrue(is_int($this->API->getIconId()));
    }

    public function testInjectSummonerIntoApiAndReturnAnObjectWithSummonerSummonerIDAndIcon(){
        $this->API->injectSummoner("manklar", true);
        $this->assertEquals($this->API->getSummoner(),"manklar");
        codecept_debug($this->API->getSummonerId());
        $this->assertEquals($this->API->getSummonerId(),'75019874');
        $this->assertNotNull($this->API->getIconID());
        $this->assertNotNull($this->API->getSummonerIcon());
        $this->assertNotNull($this->API->getSoloRankedWinLoss());
        $this->assertNotNull($this->API->getSoloRank());
        $this->assertNotNull($this->API->getFLEXRankedWinLoss());
        $this->assertNotNull($this->API->getFLEXRank());
        codecept_debug((array)$this->API->getLeagueV3Json());
        codecept_debug((array)Cache::get($this->API->getSummonerId().'LeagueV3Data'));
        $this->assertSame((array)$this->API->getLeagueV3Json(), (array)Cache::get($this->API->getSummonerId().'LeagueV3Data'));
    }

    public function testInjectBadSummonerNameAndGetBackAnException(){
        try{
            $this->API->injectSummoner("djasfhjfghdfsafdafas", true);
        }catch (\Exception $e){
            $this->assertEquals("Summoner 'djasfhjfghdfsafdafas' is not a valid name in North America",$e->getMessage());
        }
    }

    public function testCallingGetChampionsBeforeCheckingStatusThrowException(){
        $this->API->injectSummoner("manklar", false);
        $testPass = True;
        try{
            $this->API->getChampion();
            $testPass = false;
        }catch (\Exception $e){
            $this->assertEquals("Call checkCurrentGameStatus before calling this method. manklar is not in game",$e->getMessage());
        }
        $this->assertTrue($testPass);
    }

    public function testSetChampionNameAndCacheIt(){
        $championID = '222';
        $this->API->setChampionName($championID);

//        $this->assertSame($this->API->getChampionName());
    }

    public function testRequestChampionMasterieDataShouldSetJsonAndCacheIt(){
        $this->API->setSummoner("manklar");
        $this->API->requestSummonerIDAndIconId();
        $this->API->requestChampionMasterData();
        $this->assertObjectHasAttribute('championId',$this->API->getChampionMasteries()[0]);
        $masterie = (array)Cache::get($this->API->getSummonerId().'MasterieData')[0];
        $this->assertSame((array)$this->API->getChampionMasteries()[0],$masterie);
        $this->assertTrue($masterie != []);
    }

    public function testGetTop3ChampionsShouldReturnArrayOfImages(){
        $this->API->setSummoner("oO Salsa Oo");
        $this->API->requestSummonerIDAndIconId();
        $this->API->requestChampionMasterData();
        $this->API->requestDDragonVersion();
        $this->assertSame(count($this->API->getTop3Champions()[0]), 3);
        $this->assertSame(count($this->API->getTop3Champions()[1]), 3);
        $this->assertSame(count($this->API->getTop3Champions()[2]), 3);
        $this->assertSame(count($this->API->getTop3Champions()[3]), 3);
        $this->assertSame((array)$this->API->getChampionMasteries()[0],(array)Cache::get($this->API->getSummonerId().'MasterieData')[0]);
    }
}