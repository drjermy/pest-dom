<?php

describe('button', function () {
    it('can test for a button', function($html) {
        expect($html)->toHaveButton();
    })->with([
        'single' => '<button>',
        'self-closed' => '<button />',
        'full' => '<button>test</button>',
    ]);

    it('does not match button text', function () {
        $html = '<div>button</div>';

        expect($html)->not->toHaveButton();
    });
});

describe('value', function () {
    it('can test for a button with value', function($value) {
        $html = "<button>{$value}</button>";

        expect($html)->toHaveButton($value);
    })->with([
        'single-word' => 'create',
        'multi-word' => 'New App',
    ]);

    it('can test for a button with value using named argument', function() {
        $html = "<button>Create</button>";

        expect($html)->toHaveButton(value: 'Create');
    });

    it('does not match button with incorrect value', function () {
        $html = '<button>create</button>';

        expect($html)->not->toHaveButton('Create');
    });
});

describe('attributes', function () {
    it('can test for a button with an empty attribute', function() {
        $html = '<button disabled>Create</button>';

        expect($html)->toHaveButton(attr: 'disabled');
    });

    it('does not match an empty selector with a missing argument', function() {
        $html = '<button disabled>Create</button>';

        expect($html)->not->toHaveButton(attr: 'dis');
    });

    it('can test for a button with an argument', function() {
        $html = '<button class="mb-2">Create</button>';

        expect($html)->toHaveButton(attr: 'class');
    });

    it('does not match a selector with a missing argument', function() {
        $html = '<button class="mb-2">Create</button>';

        expect($html)->not->toHaveButton(attr: 'name');
    });

    it('can test for a button with an attribute with value', function() {
        $html = '<button class="mt-2">Create</button>';

        expect($html)->toHaveButton(attr: ['class', 'mt-2']);
    });

    it('does not match a selector attribute that is non-matching', function() {
        $html = '<button class="mt-2">Create</button>';

        expect($html)->not->toHaveButton(attr: ['class', 'mt-3']);
    });
});

describe('classes', function() {
    it('can test for a button with classes', function() {
        $html = '<button class="mt-2">Create</button>';

        expect($html)->toHaveButton(class: 'mt-2');
    });
});
