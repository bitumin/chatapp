<?php

namespace ChatApp\Controller;

use Symfony\Component\HttpFoundation\Response;

class ChatController
{
    /**
     * @param $max
     * @return Response
     */
    public function index($max = 123)
    {
        $number = mt_rand(0, $max);

        return new Response(
            '<html><body>Lucky number: ' . $number . '</body></html>'
        );
    }
}
