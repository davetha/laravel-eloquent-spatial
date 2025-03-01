<?php

return (new Jubeki\LaravelCodeStyle\Config())
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
    )
    ->setRules([
        '@Laravel' => true,
        '@Laravel:risky' => true,
    ])
    ->setRiskyAllowed(true);
