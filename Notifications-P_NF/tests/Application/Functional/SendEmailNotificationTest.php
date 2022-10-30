<?php

namespace App\Tests\Application\Functional;

use App\Tests\EmailNotificationTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 */
class SendEmailNotificationTest extends EmailNotificationTestCase
{
    public function testSendEmailNotificaton()
    {
        $client = $this->createClient();
        $client->request(
            'POST',
            '/notification',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'CorrectAcceesTokenNotificationService',
            ],
            json_encode([
                'type' => 'email',
                'email' => 'my.email@test',
                'context' => 'Heello - Email send',
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $transport = $this->getTransport();
        $messages = $transport->getSent();

        $this->assertCount(1, $messages);

        $email = $this->getMailerMessage();
        $this->assertEmailHtmlBodyContains($email, 'Heello - Email send');
        $this->assertEmailTextBodyContains($email, 'Heello - Email send');
    }
}
