<?php

namespace Stratus21\GraphCorsFix\Plugin;

use GraphQL\Language\AST\DocumentNode;
use Magento\Framework\GraphQl\Query\QueryParser;

class QueryParserPluginString
{
    /**
     * After plugin to modify the result of the parse method
     *
     * @param QueryParser $subject
     * @param DocumentNode $result
     * @param string $query
     * @return DocumentNode|string
     */
    public function afterParse(QueryParser $subject, DocumentNode $result, string $query)
    {
        // Check if the DocumentNode's definitions are empty
        if (empty($result->definitions)) {
            // Return an empty string if definitions are empty
            return '';
        }

        // Otherwise, return the DocumentNode as is
        return $result;
    }
}
