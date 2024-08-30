<?php

namespace Stratus21\GraphCorsFix\Plugin;

use Magento\Framework\GraphQl\Query\Fields;
use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\Parser;
use GraphQL\Language\Visitor;
use GraphQL\Language\NodeKind;
use GraphQL\Language\AST\Node;

class FieldsPlugin
{
    /**
     * Around plugin for the setQuery method.
     *
     * @param Fields $subject
     * @param callable $proceed
     * @param DocumentNode|string $query
     * @param array|null $variables
     * @return void
     */
    public function aroundSetQuery(Fields $subject, callable $proceed, DocumentNode|string $query, array $variables = null)
    {
        $queryFields = [];

        try {
            // Parse the query if it's a string
            if (is_string($query)) {
                $query = $subject->queryParser->parse($query);
            }

            // If the query is not empty, extract the fields
            if (!empty($query)) {
                Visitor::visit(
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

            // If variables are set, extract them
            if (isset($variables)) {
                $subject->extractVariables($queryFields, $variables);
            }
        } catch (\Exception $e) {
            // Ignore any exceptions (syntax errors, etc.)
        }

        // If this is an introspection query, clear the fields
        if (isset($queryFields['IntrospectionQuery']) || isset($queryFields['__schema']) || isset($queryFields['__type'])) {
            $queryFields = [];
        }

        // Set the fields used in the query
        $subject->fieldsUsedInQuery = $queryFields;
    }
}
