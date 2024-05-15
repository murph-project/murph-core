<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use App\Core\BuilderBlock\BuilderBlock;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('builder_block.widget')]
class <?php echo $class_name; ?> extends BuilderBlock
{
    public function configure()
    {
        $this
            ->setName('<?php echo $class_name; ?>')
            ->setCategory('Custom')
            ->setTemplate('<?php echo $template; ?>')
            ->setLabel('<?php echo $label; ?>')
            // ->setOrder(1)
            // ->setIsContainer(false)
            // ->setIcon('<i class="fas fa-pencil-alt"></i>')
            // ->setClass('col-md-12')
            // ->addSetting(name: 'value1', label: 'Text', type: 'text', attributes: [], default: '')
            // ->addSetting(name: 'value2', label: 'Checkbox', type: 'checkbox', attributes: [], default: true)
            // ->addSetting(name: 'value2', label: 'Number', type: 'checkbox', attributes: [], default: true)
            // ->addSetting(name: 'value3', label: 'Textarea', type: 'textarea', attributes: [], default: '')
            // ->addSetting(name: 'value4', label: 'Choice', type: 'select', attributes: [], default: '', extraOptions: [
            //     'options' => [
            //         ['text' => 'Choice 1', 'value' => 'choice1'],
            //         ['text' => 'Choice 2', 'value' => 'choice2'],
            //     ],
            // ])
        ;
    }

    public function buildVars(array $data, array $context)
    {
    }
}
