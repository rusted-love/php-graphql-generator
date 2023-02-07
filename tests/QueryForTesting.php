<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests;

enum QueryForTesting:string
{
   case BasicProducts = <<<GRAPHQL
fragment ProductData on Product {
    id
    author {
       roles
    }
}
query test1 {
    products(searchString:"best shoes") {
        ...ProductData
       
    }
}
GRAPHQL;
    case ProductSearchString = <<<GRAPHQL
query test1(\$search:String!) {
    productsSearchResult:products(searchString:\$search) {
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
    case ExternalType = <<<GRAPHQL
query test1 {
    order: orderById(id:"amazing_order") {
        id
        description
        totalItemPrice{
            currency
            amount
        }
        products{
            id
             author {
               roles
            }
        }
    }
}
GRAPHQL;
    case TestFieldAccess = <<<GRAPHQL
mutation test1 {
    typeAccessTest{forDev,forAdmin}
}
GRAPHQL;
}
