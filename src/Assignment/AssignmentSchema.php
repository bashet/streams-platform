<?php namespace Anomaly\Streams\Platform\Assignment;

use Anomaly\Streams\Platform\Addon\FieldType\FieldType;
use Anomaly\Streams\Platform\Assignment\Contract\AssignmentInterface;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

/**
 * Class AssignmentSchema
 *
 * @link    http://anomaly.is/streams-platform
 * @author  AnomalyLabs, Inc. <hello@anomaly.is>
 * @author  Ryan Thompson <ryan@anomaly.is>
 * @package Anomaly\Streams\Platform\Assignment
 */
class AssignmentSchema
{

    /**
     * The schema builder object.
     *
     * @var Builder
     */
    protected $schema;

    /**
     * Create a new AssignmentSchema instance.
     */
    public function __construct()
    {
        $this->schema = app('db')->connection()->getSchemaBuilder();
    }

    /**
     * Add a column.
     *
     * @param                     $table
     * @param FieldType           $type
     * @param AssignmentInterface $assignment
     */
    public function addColumn($table, FieldType $type, AssignmentInterface $assignment)
    {
        $schema = $type->getSchema();

        $this->schema->table(
            $table,
            function (Blueprint $table) use ($schema, $assignment) {
                $schema->addColumn($table, $assignment);
            }
        );
    }

    /**
     * Update a column.
     *
     * @param                     $table
     * @param FieldType           $type
     * @param AssignmentInterface $assignment
     */
    public function updateColumn($table, FieldType $type, AssignmentInterface $assignment)
    {
        $schema = $type->getSchema();

        $this->schema->table(
            $table,
            function (Blueprint $table) use ($schema, $assignment) {
                $schema->updateColumn($table, $assignment);
            }
        );
    }

    /**
     * Rename a column.
     *
     * @param                     $table
     * @param FieldType           $type
     * @param AssignmentInterface $assignment
     */
    public function renameColumn($table, FieldType $type, AssignmentInterface $assignment)
    {
        $schema = $type->getSchema();
        $from   = $assignment->getFieldType(true);

        if ($from->getColumnName() === $type->getColumnName()) {
            return;
        }

        $this->schema->table(
            $table,
            function (Blueprint $table) use ($schema, $from) {
                $schema->renameColumn($table, $from);
            }
        );
    }

    /**
     * Drop a column.
     *
     * @param           $table
     * @param FieldType $type
     */
    public function dropColumn($table, FieldType $type)
    {
        $schema = $type->getSchema();

        if (!$this->schema->hasTable($table)) {
            return;
        }

        $this->schema->table(
            $table,
            function (Blueprint $table) use ($schema) {
                $schema->dropColumn($table);
            }
        );
    }
}
