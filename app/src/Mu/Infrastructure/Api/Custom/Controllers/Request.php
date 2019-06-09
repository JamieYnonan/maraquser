<?php

namespace Mu\Infrastructure\Api\Custom\Controllers;

trait Request
{
    public function content(
        \Symfony\Component\HttpFoundation\Request $request
    ): array {
        return json_decode($request->getContent(), true);
    }
}
