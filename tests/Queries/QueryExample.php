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
    case ProductSearchString = <<<GRAPHQL
query test1(\$search:String!) {
    products(searchString:\$search) {
        id
        author {
           roles
        }
    }
}
GRAPHQL;
    case WrongProductFields = <<<GRAPHQL
query test2 {
    products {
        ide
        author {
           roles
        }
    }
}
GRAPHQL;
    case WrongArguments = <<<GRAPHQL
query test2 {
    products(searchStrings:"best shoes") {
        id
        author {
           roles
        }
    }
}
GRAPHQL;
    case ProductWithVariants = <<<GRAPHQL
query test1(\$available:Boolean) {
    product: productById(id:"99999_222") {
        id
        variants(available:\$available) {
           id
        }
    }
}
GRAPHQL;
    case ProductWithVariantsWrongReturnValue = <<<GRAPHQL
query test1(\$available:Boolean) {
    product: productById(id:"99999_222") {
        id
        wrongVariantReturnValue(available:\$available) {
           id
        }
    }
}
GRAPHQL;
}
