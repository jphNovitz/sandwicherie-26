<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LogoutController
{
    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function __invoke(): Response
    {
        throw new \LogicException('This method should never be reached. Logout is handled by the firewall.');
    }
}
