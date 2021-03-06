<?php
/**
 * Created by PhpStorm.
 * User: sapphirehead
 * Date: 13.08.2016
 * Time: 22:32
 */

namespace application\testmodels;


use models\PHPMailerClass;

class PHPMailerClassTest extends \PHPUnit_Framework_TestCase
{
    private $mail_obj;
    private $stub;
    public function setUp()
    {
        $this->mail_obj = new PHPMailerClass();
        $this->stub = $this->createMock(PHPMailerClass::class);
    }
    public function tearDown()
    {
        unset($this->mail_obj);
    }
    /**
     *
     * @dataProvider provider
     */
    public function testMakeObject($host, $mail_from, $passw, $port, $overal_name, $mail_for,
                                          $recip, $header)
    {
        $res = $this->mail_obj->makeMailObj($host, $mail_from, $passw, $port, $overal_name,
            $mail_for, $recip, $header);
        $this->assertInstanceOf(PHPMailerClass::class, $res);
    }
    public function provider()
    {
        return [
            ['host', 'maifrom', 'passw', 1, 'a', 'b', 'e', 'f'],
            ['host', 'maifrom', 'passw', 1, 'a', 'b', 'e', 'f'],
            ['host', 'maifrom', 'passw', 1, 'a', 'b', 'e', 'f'],
            ['host', 'maifrom', 'passw', 1, 'a', 'b', 'e', 'f']
        ];
    }
}
