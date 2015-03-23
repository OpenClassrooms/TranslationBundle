<?php

namespace OpenClassrooms\Bundle\TranslationBundle;

use OpenClassrooms\Bundle\TranslationBundle\DependencyInjection\TranslationExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Bastien Rambure <bastien.rambure@openclassrooms.com>
 */
class OpenClassroomsTranslationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->registerExtension(new TranslationExtension());
    }

}
