<?php

namespace CobreFacil\Resources;

use CobreFacil\BaseTest;
use CobreFacil\CobreFacil;
use CobreFacil\Exceptions\InvalidCredentialsException;
use Throwable;

class AuthenticationTest extends BaseTest
{
    public function testAuthenticateWithValidCredentials()
    {
        $this->assertNotNull($this->cobrefacil->getValidToken());
    }

    public function testErrorOnAuthenticateWithInvalidCredentials()
    {
        try {
            $appId = 'invalid';
            $secret = 'invalid';
            (new CobreFacil($appId, $secret))
                ->setProduction(false)
                ->getValidToken();
        } catch (Throwable $e) {
            $this->assertInstanceOf(InvalidCredentialsException::class, $e);
        }
    }
}
