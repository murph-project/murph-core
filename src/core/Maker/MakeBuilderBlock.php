<?php

namespace App\Core\Maker;

use Doctrine\Common\Annotations\Annotation;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;

class MakeBuilderBlock extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:builder-block';
    }

    public static function getCommandDescription(): string
    {
        return 'Creates a new builder block class';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->addArgument(
                'builder-block-class',
                InputArgument::OPTIONAL,
                'Choose a name for your block class (e.g. <fg=yellow>ExampleBlock</>)'
            )
            ->setHelp('')
        ;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $blockClassNameDetails = $generator->createClassNameDetails(
            $input->getArgument('builder-block-class'),
            'BuilderBlock\\',
            'Block'
        );

        $templatePath = sprintf(
            'builder_block/%s.html.twig',
            Str::asSnakeCase(preg_replace('/Block$/', '', $blockClassNameDetails->getShortName()))
        );

        $options = [
            'entity' => $blockClassNameDetails->getFullName(),
            'template' => $templatePath,
            'label' => Str::asHumanWords($blockClassNameDetails->getShortName())
        ];

        $blockPath = $generator->generateController(
            $blockClassNameDetails->getFullName(),
            __DIR__.'/../Resources/maker/builder/Block.tpl.php',
            $options
        );

        $generator->writeChanges();
        $realTemplatePath = 'templates/'.$templatePath;

        $filesystem = new Filesystem();

        if (!$filesystem->exists($templatePath)) {
            $filesystem->mkdir(dirname($realTemplatePath));
            $filesystem->dumpFile($realTemplatePath, $this->getTemplate());

            $io->comment(sprintf('<fg=blue>created</>: %s', $realTemplatePath));
        }

        $this->writeSuccessMessage($io);
    }

    protected function getTemplate(): string
    {
        return <<< EOF
<div id="{{ id }}">
    {% for item in children %}
        {{ item|block_to_html(context) }}
    {% endfor %}
</div>

EOF;
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
    }
}
