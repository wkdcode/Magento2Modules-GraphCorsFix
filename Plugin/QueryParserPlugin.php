<?php

namespace Stratus21\GraphCorsFix\Plugin;

use Magento\Framework\GraphQl\Query\QueryParser;
use GraphQL\Language\Parser;
use GraphQL\Language\Source;
use GraphQL\Language\AST\DocumentNode;

class QueryParserPlugin
{
    /**
     * Around plugin for the parse method.
     *
     * @param QueryParser $subject
     * @param callable $proceed
     * @param string $query
     * @return DocumentNode|string
     */
    public function aroundParse(QueryParser $subject, callable $proceed, string $query)
    {
        // Generate the cache key
        $cacheKey = sha1($query);

        // Check if the query has already been parsed and cached
        if (!isset($subject->parsedQueries[$cacheKey])) {
            // If the query is empty, store and return an empty string
            if (!empty($query)) {
                $subject->parsedQueries[$cacheKey] = Parser::parse(new Source($query, 'GraphQL'));
            } else {
                $subject->parsedQueries[$cacheKey] = '';
            }
        }

        // Return the cached parsed query
        return $subject->parsedQueries[$cacheKey];
    }
}
