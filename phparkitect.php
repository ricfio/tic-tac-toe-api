<?php

declare(strict_types=1);

use Arkitect\ClassSet;
use Arkitect\CLI\Config;
use Arkitect\Expression\ForClasses\HaveNameMatching;
use Arkitect\Expression\ForClasses\ResideInOneOfTheseNamespaces;
use Arkitect\RuleBuilders\Architecture\Architecture;
use Arkitect\Rules\Rule;

return static function (Config $config): void {
    $allClasses = ClassSet::fromDir(__DIR__.'/src');

    $exagonalRules = Architecture::withComponents()
        ->component('Domain')->definedBy('App\Domain\*')
        ->component('Application')->definedBy('App\Application\*')
        ->component('Infrastructure')->definedBy('App\Infrastructure\*')
        ->where('Domain')->shouldNotDependOnAnyComponent()
        ->where('Application')->mayDependOnComponents('Domain')
        ->where('Infrastructure')->mayDependOnComponents('Domain', 'Application')
        ->rules();

    $layeredRules = Architecture::withComponents()
        ->component('Entity')->definedBy('App\Entity\*')
        ->component('Repository')->definedBy('App\Repository\*')
        ->component('Service')->definedBy('App\Service\*')
        ->component('Controller')->definedBy('App\Controller\*')
        ->where('Entity')->shouldNotDependOnAnyComponent()
        ->where('Repository')->mayDependOnComponents('Entity')
        ->where('Service')->mayDependOnComponents('Entity', 'Repository')
        ->where('Controller')->mayDependOnComponents('Entity', 'Service')
        ->rules();

    $namingRules = [];

    $namingRules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Controller'))
        ->should(new HaveNameMatching('*Controller'))
        ->because('we want uniform naming');

    $config
        ->add($allClasses, ...$exagonalRules)
        ->add($allClasses, ...$layeredRules)
        ->add($allClasses, ...$namingRules);
};
