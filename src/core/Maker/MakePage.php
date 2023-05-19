<?php

namespace App\Core\Maker;

use Doctrine\Common\Annotations\Annotation;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Filesystem\Filesystem;

class MakePage extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:page';
    }

    public static function getCommandDescription(): string
    {
        return 'Creates a new page class';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->addArgument(
                'page-class',
                InputArgument::OPTIONAL,
                'Choose a name for your page class (e.g. <fg=yellow>ExamplePage</>)'
            )
            ->setHelp('')
        ;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $pageClassNameDetails = $generator->createClassNameDetails(
            $input->getArgument('page-class'),
            'Entity\\Page\\',
            'Page'
        );

        $blocks = [];

        $isFirstField = true;
        $blocks = [];

        while (true) {
            $newBlock = $this->askForNextBlock($io, $blocks, $isFirstField);
            $isFirstField = false;

            if (null === $newBlock) {
                break;
            }

            $blocks[$newBlock['name']] = $newBlock;
        }

        $options = [
            'entity' => $pageClassNameDetails->getFullName(),
            'blocks' => $blocks,
        ];

        $controllerPath = $generator->generateController(
            $pageClassNameDetails->getFullName(),
            __DIR__.'/../Resources/maker/page/PageEntity.tpl.php',
            $options
        );

        $generator->writeChanges();

        $templatePath = sprintf(
            'page/%s/default.html.twig',
            Str::asSnakeCase(preg_replace('/Page$/', '', $pageClassNameDetails->getShortName()))
        );

        $realTemplatePath = 'templates/'.$templatePath;

        $filesystem = new Filesystem();

        if (!$filesystem->exists($templatePath)) {
            $filesystem->mkdir(dirname($realTemplatePath));
            $filesystem->dumpFile($realTemplatePath, "{% extends 'base.html.twig' %}\n\n{% block page %}\n\n{% endblock %}\n");
        }

        $this->writeSuccessMessage($io);
        $io->text('Register the page in <comment>config/packages/app.yaml</comment>: ');
        $io->text(<<< EOF

core:
    site:
        pages:
            {$pageClassNameDetails->getFullName()}:
                name: {$pageClassNameDetails->getShortName()}
                templates:
                    - {name: "Default", file: "${templatePath}"}

EOF
);
    }

    private function askForNextBlock(ConsoleStyle $io, array $blocks, bool $isFirstField)
    {
        $io->writeln('');

        if ($isFirstField) {
            $questionText = 'New property name (press <return> to stop adding fields)';
        } else {
            $questionText = 'Add another property? Enter the property name (or press <return> to stop adding fields)';
        }

        $blockName = $io->ask($questionText, null, function ($name) use ($blocks) {
            if (!$name) {
                return $name;
            }

            if (isset($blocks[$name])) {
                throw new \InvalidArgumentException(sprintf('The "%s" block already exists.', $name));
            }

            return $name;
        });

        if (!$blockName) {
            return null;
        }

        $type = null;
        $defaultType = 'text';
        $snakeCasedField = Str::asSnakeCase($blockName);
        $types = [
            'text' => null,
            'textarea' => null,
            'choice' => 'BlockEntity\\ChoiceBlock::class',
            'collection' => 'BlockEntity\\CollectionBlock::class',
            'editor_js_textarea' => null,
            'file' => 'BlockEntity\\FileBlock::class',
            'file_picker' => null,
            'grapes_js' => null,
            'image' => 'BlockEntity\\FileBlock::class',
            'tinymce_textarea' => null,
        ];

        while (null === $type) {
            $question = new Question('Field type (enter <comment>?</comment> to see all types)', $defaultType);
            $question->setAutocompleterValues(array_keys($types));
            $type = $io->askQuestion($question);

            if ('?' === $type) {
                $this->printAvailableTypes($io, array_keys($types));
                $io->writeln('');

                $type = null;
            } elseif (!\in_array($type, array_keys($types))) {
                $this->printAvailableTypes($io, array_keys($types));
                $io->error(sprintf('Invalid type "%s".', $type));
                $io->writeln('');

                $type = null;
            }
        }

        return [
            'name' => $blockName,
            'type' => 'Block\\'.Str::asCamelCase($type).'BlockType::class',
            'class' => $types[$type],
            'camelCase' => Str::asCamelCase($blockName),
        ];
    }

    private function printAvailableTypes(ConsoleStyle $io, array $types)
    {
        $io->writeln('<info>Types</info>');

        foreach ($types as $type) {
            $io->writeln(sprintf('  * <comment>%s</comment>', $type));
        }
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
        $dependencies->addClassDependency(
            Annotation::class,
            'doctrine/annotations'
        );
    }
}
