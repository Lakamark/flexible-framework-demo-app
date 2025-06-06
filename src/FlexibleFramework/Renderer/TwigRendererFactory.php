<?php

namespace FlexibleFramework\Renderer;

use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class TwigRendererFactory
{
    public function __invoke(ContainerInterface $container): TwigRenderer
    {
        $viewPath = $container->get('templates.path');
        $loader = new FilesystemLoader($viewPath);
        $twig = new Environment($loader, ['debug' => true]);

        // Disable the debug context and this extension on production environment
        $twig->addExtension(new DebugExtension());

        // Load twig extension from the container
        if ($container->has('twig.extensions')) {
            foreach ($container->get('twig.extensions') as $extension) {
                $twig->addExtension($extension);
            }
        }

        return new TwigRenderer($twig);
    }
}
