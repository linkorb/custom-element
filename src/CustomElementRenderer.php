<?php

namespace LinkORB\Component\CustomElement;

use Twig\Environment;
use RuntimeException;

class CustomElementRenderer
{
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(?string $body): string
    {
        if (!$body) {
            return '';
        }

        // Wrap HTML to ensure a root element exists
        $html = '<body>'.$body.'</body>';

        // Load HTML into a DOMDocument
        try {
            $doc = new \DOMDocument();
            // loadXml fails on `<br>` (i.e. no closing tag in memos)
            $doc->loadXml($html);
            // loadHtml fails on non-html elements such as `root` or `MyCoolComponent`
            // $doc->loadHtml($html);

        } catch (\Exception $e) {
            // echo $e->getMessage();
            // echo $html;exit();
            return '<h1>Error: ' . $e->getMessage() . '</h1><pre>' . htmlspecialchars($html) . '</pre>';
        }

        // Create array of all custom element names used in the doc
        $elementNames = [];
        foreach($doc->getElementsByTagName('*') as $element ){ // Find all elements
            $name = $element->nodeName;
            // Only include element names starting with uppercase character
            if (ctype_upper($name[0])) {
                $elementNames[$name] = $name;
            }
        }
        // print_r($elementNames);exit();

        foreach ($elementNames as $elementName) {
            $elements = $doc->getElementsByTagName($elementName);
            while (count($elements) > 0) {
                foreach ($elements as $element) {
                    $data = [
                        'ElementName' => $element->nodeName,
                    ];
                    foreach ($element->attributes as $a) {
                        $data[$a->nodeName] = $a->nodeValue;
                    }

                    $templateName = 'elements/' . $elementName . '.html.twig';
                    try {
                        $html = $this->twig->render($templateName, $data);
                    } catch (\Exception $e) {
                        $html = '<div class="alert alert-danger">Failed to render custom element: <b>' . $elementName . '</b>.<br />' . $e->getMessage() . '</div>';
                    }

                    $d = new \DOMDocument();
                    try {
                        $d->loadHTML('<?xml version="1.0" encoding="utf-8"?>' . $html);
                    } catch (\Exception $e) {
                        throw new RuntimeException($e->getMessage());
                    }

                    $new = $doc->importNode($d->documentElement, true);

                    $element->parentNode->replaceChild($new, $element);
                }
                $elements = $doc->getElementsByTagName($elementName);
            }
        }
        $html = $doc->saveHTML();
        $html = str_replace('<body>', '', $html);
        $html = str_replace('</body>', '', $html);
        return $html;
    }
}
