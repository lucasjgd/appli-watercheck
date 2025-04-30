<?php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('MapPrelevement')]
class MapPrelevement
{
    public array $prelevements = [];
}
