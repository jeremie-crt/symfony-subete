<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;

class CustomOpApiController
{
    /**
     * @Route("/article/{id}/authorinfo")
     * @param Article $data
     * @return void
     */
    public function __invoke(Article $data)
    {
        dd($data);
    }

}