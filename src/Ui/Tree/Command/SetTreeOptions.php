<?php namespace Anomaly\Streams\Platform\Ui\Tree\Command;

use Anomaly\Streams\Platform\Support\Evaluator;
use Anomaly\Streams\Platform\Support\Resolver;
use Anomaly\Streams\Platform\Ui\Tree\TreeBuilder;
use Illuminate\Contracts\Bus\SelfHandling;

/**
 * Class SetTreeOptions
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\Streams\Platform\Ui\Tree\Command
 */
class SetTreeOptions implements SelfHandling
{

    /**
     * The tree builder.
     *
     * @var TreeBuilder
     */
    protected $builder;

    /**
     * Create a new SetTreeOptions instance.
     *
     * @param TreeBuilder $builder
     */
    public function __construct(TreeBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Handle the command.
     *
     * @param Resolver  $resolver
     * @param Evaluator $evaluator
     */
    public function handle(Resolver $resolver, Evaluator $evaluator)
    {
        $arguments = ['builder' => $this->builder];

        $tree = $this->builder->getTree();

        $options = $this->builder->getOptions();

        $options = $resolver->resolve($options, $arguments);
        $options = $evaluator->evaluate($options, $arguments);

        foreach ($options as $key => $value) {
            $tree->setOption($key, $value);
        }
    }
}
