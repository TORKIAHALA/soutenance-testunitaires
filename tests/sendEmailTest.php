<?php 
namespace App\tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class sendEmailTest extends webTestCase {

    public function test_email_are_send_correctly(){

        // setup
        $client = static::createClient();
        
        // perform an action
        $client-> request('GET', '/email');
        
        //make Assertions 
        $sentMail = $this->getMailerMessage(0);
        
        //1 email sent 
        $this->assertEmailCount(1);
        
        // sent to the correct personn
        $this->assertEmailHeaderSame($sentMail, 'To', 'jeremdh.dev@outlook.com');

        //Has correct body content
        $this->assertEmailTextBodyContains($sentMail, 'Hello voici un email test');

        // Has an attachement 
        $this->assertEmailAttachmentCount(1);
        }