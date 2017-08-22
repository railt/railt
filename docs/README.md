# GraphQL

**GraphQL** is a modern replacement of the almost obsolete **REST** approach to present API. 
It's been almost 16 years since the **REST** idea was found in 2000 by Roy Fielding. 
With all credit to everything we accomplished using REST it's time to change for 
something better. **GraphQL** advanced in many ways and has fundamental 
improvements over the old good **REST**:

- Self-checks embedded on the ground level of your backend architecture.
- Reusable **API** for different client versions and devices, i.e. no more need in maintaining `/v1`, `/v2` or `/v10002545.07E20`.
- A complete new level of distinguishing of the backend and frontend logic.
- Easily generated documentation and incredibly intuitive way to explore created **API**.
- Once your architecture is complete – most client-based changes does not require backend modifications.
- It could be hard to believe but give it a try and you'll be rewarded with much better architecture and so much easier to support code.

A **GraphQL** service is created by defining types and fields on those types, 
then providing functions for each field on each type. 

## Queries

Once a **GraphQL** service is running (typically at a URL on a web service), 
it can be sent **GraphQL** queries to validate and execute. 
A received query is first checked to ensure it only refers to 
the types and fields defined, then runs the provided functions to produce a result.

For example the query:

```php
{
    users {
        id
        name
    }
}
```

Could produce the **JSON** result:

```php
{
    "users": [
        {
            "id": 1,
            "name": "Vasya"
        },
        {
            "id": 2,
            "name": "Petya"
        }
    ]
}
```

## What is Railt? 

This is a pure async PHP realization of the **GraphQL** protocol based on the 
[youshido/graphql](https://github.com/Youshido/GraphQL) and/or 
[webonyx/graphql-php](https://github.com/webonyx/graphql-php#fields)
core drivers of the official GraphQL Specification 
located on [Facebook GitHub](http://facebook.github.io/graphql/).

