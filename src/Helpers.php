<?php

use Illuminate\Support\Collection;
use Symfony\Component\CssSelector\CssSelectorConverter;

function decodeHTML($string): string
{
    return mb_encode_numericentity(
        htmlspecialchars_decode(
            htmlentities($string, ENT_NOQUOTES, 'UTF-8', false), ENT_NOQUOTES
        ), [0x80, 0x10FFFF, 0, ~0],
        'UTF-8'
    );
}

/**
 * @throws Exception
 */
function domify(string $html): DOMXPath
{
    if (empty($html)) {
        throw new Exception('You must pass a string.');
    }

    $dom = new DOMDocument();

    $dom->loadHTML(
        decodeHTML($html),
        LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR
    );

    return new DOMXPath($dom);
}

/**
 * @throws Exception
 */
function selectDOM(mixed $input, $selector): Collection
{
    if (empty($input)) {
        throw new Exception('You must pass some content (an HTML string or DOMXPath object).');
    }

    if (! is_string($input) && ! $input instanceof DOMXPath) {
        throw new Exception('You can only pass an HTML string or DOMXPath object.');
    }

    if (empty($selector)) {
        throw new Exception('You must pass a selector string');
    }

    $xpath = $input instanceof DOMXPath ? $input : domify($input);

    $converter = new CssSelectorConverter();

    $xpathSelector = $converter->toXPath($selector);

    return collect($xpath->query($xpathSelector));
}

function getAttributes(Collection $selectorContents, string $attribute): Collection
{
    return $selectorContents
        ->map(function (DOMElement $element) use ($attribute) {
            return $element->hasAttribute($attribute) ? $element->getAttribute($attribute) : null;
        })
        ->reject(fn ($attributeValue) => is_null($attributeValue));
}
