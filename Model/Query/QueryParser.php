<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Stratus21\GraphCorsFix\Model\Query;

use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\Parser;
use GraphQL\Language\Source;

/**
 * Wrapper for GraphQl query parser. It parses query string into a `GraphQL\Language\AST\DocumentNode`
 */
class QueryParser extends \Magento\Framework\GraphQl\Query\QueryParser
{

    /**
     * Parse query string into a `GraphQL\Language\AST\DocumentNode`.
     *
     * @param string $query
     * @return DocumentNode
     * @throws \GraphQL\Error\SyntaxError
     */
    public function parse(string $query): DocumentNode
    {
        $cacheKey = sha1($query);
        if (!isset($this->parsedQueries[$cacheKey])) {
            if (!empty($query)) {
                $this->parsedQueries[$cacheKey] = Parser::parse(new Source($query, 'GraphQL'));
            } else {
                // Use a default or placeholder DocumentNode if needed
                // You might need to create a valid but empty document or handle this differently
                $this->parsedQueries[$cacheKey] = new DocumentNode([
                    'definitions' => [] // or handle with other attributes if required
                ]);
            }
        }
        return $this->parsedQueries[$cacheKey];
    } 
}