<?php

namespace Stratus21\GraphCorsFix\Model\Query;

use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\Parser;
use GraphQL\Language\Source;

class QueryParser extends \Magento\Framework\GraphQl\Query\QueryParser
{
    /**
     * Parse query string into a `GraphQL\Language\AST\DocumentNode`.
     *
     * @param string $query
     * @return DocumentNode
     * @throws \GraphQL\Error\SyntaxError
     */
    public function parse(string $query): DocumentNode | string
    {
        $cacheKey = sha1($query);
        if (!isset($this->parsedQueries[$cacheKey])) {
            $this->parsedQueries[$cacheKey] = !empty($query) ? Parser::parse(new Source($query, 'GraphQL')) : '';
        }
        return $this->parsedQueries[$cacheKey];
    }
}
