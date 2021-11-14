<?php

namespace LinkORB\Component\CustomElement\Twig;

use LinkORB\Component\CustomElement\CustomElementRenderer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CustomElementExtension extends AbstractExtension
{
    public function __construct(CustomElementRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('custom_element_render', [$this, 'render']),
        ];
    }

    public function render($body)
    {
        return $this->renderer->render($body);
    }
}
