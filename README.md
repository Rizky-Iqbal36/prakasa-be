# Laravel Excercise

## Deployment

-   client-side: https://ikbal-prakasa-fe.vercel.app
-   server-side: https://ikbal-prakasa-be.vercel.app

## Format response

Success:

```json
{
    "success": true,
    "message": "Operation successful",
    "data": {...}

}
```

Error:

```json
{
    "status_code": 400,
    "success": false,
    "message": "Payload doesn't pass validation",
    "details": {...}
}
```

## Authentication

Using laravel passport <br />
Role permission: admin, basic

## API Documentation

See APIs documentation on [stoplight](https://iqbaldev-api-doc.stoplight.io/docs/Graphql-test-API-spec/d0f25de183e50-prakasa-excercise)
