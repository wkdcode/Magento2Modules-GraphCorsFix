<?php

namespace Stratus21\GraphCorsFix\Plugin;

use Magento\Framework\GraphQl\Query\QueryParser as Subject;
use GraphQL\Language\Parser;
use GraphQL\Language\Source;
use GraphQL\Language\AST\DocumentNode;

class QueryParserPlugin
{
    /**
     * Around plugin for the parse method.
     *
     * @param Subject $subject
     * @param callable $proceed
     * @param string $query
     * @return DocumentNode|string
     */
    public function aroundParse(Subject $subject, callable $proceed, string $query)
    {
        $cacheKey = sha1($query);

        // Check if the query is already parsed and cached
        if (!isset($subject->parsedQueries[$cacheKey])) {
            if (!empty($query)) {
                // Parse the query and cache the result
                $subject->parsedQueries[$cacheKey] = Parser::parse(new Source($query, 'GraphQL'));
            } else {
                // Handle empty query
                $subject->parsedQueries[$cacheKey] = '';
            }
        }

        // Return the cached result
        return $subject->parsedQueries[$cacheKey];
    }
}
