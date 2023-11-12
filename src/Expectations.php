<?php

use Illuminate\Support\Str;

expect()->extend('toHaveSelector', function (string $selector, string $value = null, mixed $attr = null, string $class = null) {

    if (isset($value)) {
        expect($this->value)->toHaveSelectorWithValue($selector, $value);
    } elseif(isset($attr)) {
        if (is_array($attr)) {
            expect($this->value)->toHaveSelectorWithAttributeContaining($selector, $attr[0], $attr[1]);
        } else {
            expect($this->value)->toHaveSelectorWithAttribute($selector, $attr);
        }
    } elseif(isset($class)) {
        expect($this->value)->toHaveSelectorWithClass($selector, $class);
    } else {
        expect($this->value)->toHaveSelectorMatch($selector);
    }

    return $this;
});

expect()->extend('toHaveSelectorMatch', function (string $selector) {

    $selectorContents = selectDOM($this->value, $selector);

    expect($selectorContents)->count()
        ->toBeGreaterThan(0, "The selector '{$selector}' was not present.");

    return $this;
});

expect()->extend('toHaveSelectorWithValue', function (string $selector, string $value) {

    $selectorContents = selectDOM($this->value, $selector);

    expect($selectorContents)
        ->count()
        ->toBeGreaterThan(0, "The selector ({$selector}) was not present.");

    $elements = $selectorContents
        ->pluck('textContent')
        ->map(fn ($value) => Str($value)->trim()->value())
        ->toArray();

    expect($elements)
        ->toBeArray()
        ->and($value)->toBeIn($elements, "The selector '{$selector}' with value '{$value}' was not present.");

    return $this;
});

expect()->extend('toHaveSelectorWithAttribute', function (string $selector, string $attribute) {

    $selectorContents = selectDOM($this->value, $selector);

    expect($selectorContents)
        ->count()
        ->toBeGreaterThan(0, "The selector ({$selector}) was not present.");

    $attributes = getAttributes($selectorContents, $attribute);

    expect($attributes)->count()->toBeGreaterThan(0);

    return $this;
});

expect()->extend('toHaveSelectorWithAttributeValue', function (string $selector, string $attribute, string $value) {

    $selectorContents = selectDOM($this->value, $selector);

    expect($selectorContents)
        ->count()
        ->toBeGreaterThan(0, "The selector ({$selector}) was not present.");

    $attributes = getAttributes($selectorContents, $attribute)
        ->reject(fn ($attributeValue) => $attributeValue !== $value);

    expect($attributes)->count()->toBeGreaterThan(0,
        "{$value} is not the value of any \"{$selector}\" selectors with the \"{$attribute}\" attribute.");

    return $this;

});

expect()->extend('toHaveSelectorWithAttributeContaining', function (string $selector, string $attribute, string $value) {

    $pattern = "/{$value}/";
    expect($this->value)->toHaveSelectorWithAttributeMatching($selector, $attribute, $pattern);

    return $this;
});

expect()->extend('toHaveSelectorWithAttributeMatching', function (string $selector, string $attribute, string $pattern) {

    $selectorContents = selectDOM($this->value, $selector);

    expect($selectorContents)
        ->count()
        ->toBeGreaterThan(0, "The selector ({$selector}) was not present.");

    $attributes = getAttributes($selectorContents, $attribute)
        ->reject(fn ($attributeValue) => ! preg_match($pattern, $attributeValue));

    expect($attributes)->count()->toBeGreaterThan(0,
        "{$pattern} does not match any of the \"{$selector}\" selectors with the \"{$attribute}\" attribute.");

    return $this;
});

expect()->extend('toHaveSelectorWithClass', function (string $selector, string $classString) {

    Str::of($classString)
        ->explode(' ')
        ->each(fn ($class) => expect($this->value)
            ->toHaveSelectorWithAttributeMatching($selector, 'class', "/{$class}/"));

});

expect()->extend('toHaveButton', function (string $value = null, mixed $attr = null, string $class = null) {

    expect($this->value)->toHaveSelector('button', value: $value, attr: $attr, class: $class);

});
