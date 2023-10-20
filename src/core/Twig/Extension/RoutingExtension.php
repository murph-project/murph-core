<?php

namespace App\Core\Twig\Extension;

use App\Core\Entity\Site\Node;
use App\Core\Site\SiteRequest;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Node as TwigNode;
use Twig\TwigFunction;

class RoutingExtension extends AbstractExtension
{
    public function __construct(
        protected UrlGeneratorInterface $generator,
        protected SiteRequest $siteRequest
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('node_url', [$this, 'getNodeUrl'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new TwigFunction('node_path', [$this, 'getNodePath'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new TwigFunction('safe_node_url', [$this, 'getSafeNodeUrl'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new TwigFunction('safe_node_path', [$this, 'getSafeNodePath'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new TwigFunction('safe_url', [$this, 'getSafeUrl'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new TwigFunction('safe_path', [$this, 'getSafePath'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new TwigFunction('code_url', [$this, 'getCodeUrl'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new TwigFunction('code_path', [$this, 'getCodePath'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new TwigFunction('safe_code_url', [$this, 'getSafeCodeUrl'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new TwigFunction('safe_code_path', [$this, 'getSafeCodePath'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
        ];
    }

    public function getSafePath(string $route, array $parameters = [], bool $relative = false): ?string
    {
        try {
            return $this->generator->generate(
                $route,
                $parameters,
                $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH
            );
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getSafeUrl(string $route, array $parameters = [], bool $schemeRelative = false): ?string
    {
        try {
            return $this->generator->generate(
                $route,
                $parameters,
                $schemeRelative ? UrlGeneratorInterface::NETWORK_PATH : UrlGeneratorInterface::ABSOLUTE_URL
            );
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getNodePath(Node $node, array $parameters = [], bool $relative = false): ?string
    {
        if ($node->hasExternalUrl() || $node->hasAppUrl()) {
            return $node->getUrl();
        }

        return $this->generator->generate(
            $node->getRouteName(),
            $parameters,
            $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH
        );
    }

    public function getNodeUrl(Node $node, array $parameters = [], bool $schemeRelative = false): ?string
    {
        if ($node->hasExternalUrl() || $node->hasAppUrl()) {
            return $node->getUrl();
        }

        return $this->generator->generate(
            $node->getRouteName(),
            $parameters,
            $schemeRelative ? UrlGeneratorInterface::NETWORK_PATH : UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    public function getSafeNodePath(Node $node, array $parameters = [], bool $relative = false): ?string
    {
        try {
            return $this->getNodePath($node, $parameters, $relative);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getSafeNodeUrl(Node $node, array $parameters = [], bool $schemeRelative = false): ?string
    {
        try {
            return $this->getNodeUrl($node, $parameters, $schemeRelative);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getCodePath(string $menuCode, string $nodeCode, array $parameters = [], bool $relative = false): string
    {
        $route = $this->generateRouteWithCode($menuCode, $nodeCode);
        $path = $this->getSafePath($route, $parameters, $relative);

        if ($path) {
            return $path;
        }

        throw new RouteNotFoundException(sprintf('Unable to generate a URL for the named route "%s" as such route does not exist.', $route));
    }

    public function getCodeUrl(string $menuCode, string $nodeCode, array $parameters = [], bool $schemeRelative = false): string
    {
        $route = $this->generateRouteWithCode($menuCode, $nodeCode);
        $url = $this->getSafeUrl($route, $parameters, $schemeRelative);

        if ($url) {
            return $url;
        }

        throw new RouteNotFoundException(sprintf('Unable to generate a URL for the named route "%s" as such route does not exist.', $url));
    }

    public function getSafeCodePath(string $menuCode, string $nodeCode, array $parameters = [], bool $relative = false): ?string
    {
        try {
            return $this->getCodePath($menuCode, $nodeCode, $parameters, $relative);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getSafeCodeUrl(string $menuCode, string $nodeCode, array $parameters = [], bool $schemeRelative = false): ?string
    {
        try {
            return $this->getCodeUrl($menuCode, $nodeCode, $parameters, $schemeRelative);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @see Symfony\Bridge\Twig\Extension\RoutingExtension::isUrlGenerationSafe
     */
    public function isUrlGenerationSafe(TwigNode $argsNode): array
    {
        // support named arguments
        $paramsNode = $argsNode->hasNode('parameters') ? $argsNode->getNode('parameters') : (
            $argsNode->hasNode(1) ? $argsNode->getNode(1) : null
        );

        if (null === $paramsNode || $paramsNode instanceof ArrayExpression && \count($paramsNode) <= 2
            && (!$paramsNode->hasNode(1) || $paramsNode->getNode(1) instanceof ConstantExpression)
        ) {
            return ['html'];
        }

        return [];
    }

    protected function generateRouteWithCode(string $menuCode, string $nodeCode)
    {
        return implode('_', [
            $this->siteRequest->getNavigation()->getCode(),
            $menuCode,
            $nodeCode,
        ]);
    }
}
