<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Queries;

enum QueryExample:string
{
   case BasicProducts = <<<GRAPHQL
query test1 {
    products(searchString:"best shoes") {
        id
        author {
           roles
        }
    }
}
GRAPHQL;
}
