<?php

it('can accept string input', function () {
    expect('<div>')->toHaveSelector('div');
});

it('can accept DOMXpath input', function () {
    $dom = domify('<div>');
    expect($dom)->toHaveSelector('div');
});

// EXCEPTIONS

it('throws an exception if the input is empty', function () {
    expect('')->toHaveSelector('span');
})->throws('You must pass some content');

it('throws an exception if the input is not string or DOMXPath', function () {
    expect(['test'])->toHaveSelector('span');
})->throws('You can only pass an HTML string or DOMXPath object');

it('throws an exception if the selector is empty', function () {
    expect('<div>')->toHaveSelector('');
})->throws('You must pass a selector string');

// MATCH AND NOT-MATCH TESTS

it('matches an input with a selector', function ($input, $selector) {
    expect($input)->toHaveSelector($selector);
})->with([
    'unclosed' => ['<span>', 'span'],
    'self-closed' => ['<span />', 'span'],
    'closed' => ['<span>Test</span>', 'span'],
    'with-attributes' => ['<span class="mt-3">Test</span>', 'span'],
]);

it('fails when the input does not have the selector', function ($input, $selector) {
    expect($input)->not->toHaveSelector($selector);
})->with([
    'tag' => ['<div>', 'span'],
    'text' => ['<div>span</div>', 'span'],
    'attribute' => ['<div class="span "/>', 'span'],
]);
