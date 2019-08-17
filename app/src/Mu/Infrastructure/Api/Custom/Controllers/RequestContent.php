<?php

namespace Mu\Infrastructure\Api\Custom\Controllers;

use Symfony\Component\HttpFoundation\Request;

trait RequestContent
{
    public function content(Request $request): array
    {
        return json_decode($request->getContent(), true);
    }
}
