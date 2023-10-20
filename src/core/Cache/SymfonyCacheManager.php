<?php

namespace App\Core\Cache;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * class SymfonyCacheManager.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class SymfonyCacheManager
{
    public function __construct(
        protected KernelInterface $kernel,
        protected HttpClientInterface $httpClient,
        protected UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function cleanRouting()
    {
        $finder = new Finder();
        $finder
            ->in($this->kernel->getCacheDir())
            ->depth('== 0')
            ->name('url_*.php*')
        ;

        $pingUrl = $this->urlGenerator->generate('_ping', [], UrlGeneratorInterface::ABSOLUTE_URL);

        foreach ($finder as $file) {
            unlink((string) $file->getPathname());
        }

        try {
            // Hack: used to regenerate cache of url generator
            $this->httpClient->request('POST', $pingUrl);
        } catch (ClientException $e) {
        } catch (TransportException $e) {
        }
    }

    public function cleanAll(OutputInterface $output = null)
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        if (null === $output) {
            $output = new BufferedOutput();
        }

        $input = new ArrayInput([
            'command' => 'cache:warmup',
            '-e' => $this->kernel->getEnvironment(),
        ]);

        $application->run($input, $output);
    }
}
