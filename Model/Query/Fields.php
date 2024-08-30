<?php

declare(strict_types=1);

namespace Stratus21\GraphCorsFix\Model\Query;

use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NodeKind;

class Fields extends \Magento\Framework\GraphQl\Query\Fields
{
    /**
     * Set Query for extracting list of fields.
     *
     * @param DocumentNode|string $query
     * @param array|null $variables
     *
     * @return void
     */
    public function setQuery(DocumentNode|string $query, array $variables = null)
    {
        $queryFields = [];
        try {
            if (is_string($query)) {
                $query = $this->queryParser->parse($query);
            }
            if (!empty($query)) {
                \GraphQL\Language\Visitor::visit(
                    $query,
                    [
                        'leave' => [
                            NodeKind::NAME => function (Node $node) use (&$queryFields) {
                                $queryFields[$node->value] = $node->value;
                            }
                        ]
                    ]
                );
            }
            if (isset($variables)) {
                $this->extractVariables($queryFields, $variables);
            }
            // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock
        } catch (\Exception $e) {
            // If a syntax error is encountered do not collect fields
        }
        if (isset($queryFields['IntrospectionQuery']) || (isset($queryFields['__schema'])) ||
            (isset($queryFields['__type']))) {
            // It must be possible to query any fields during introspection query
            $queryFields = [];
        }
        $this->fieldsUsedInQuery = $queryFields;
    }
}
