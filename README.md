# jwt_store

This plugin is based upon another plugin (jwt), and adds store capabilities.

## Requiers

PHP version X.Y (or newer) and a mysql database, with table 'TOKEN_STORE' specified in the 'jwt_model.php' file.

## Usage

The basic usage requires you to instance the class;

> ```$jwt_store = new TokenStore();```

<br>

To then create a new token do something like this;

> ```$to_encode = (object) ['foo' => 'bar'];```<br>
> ```$token_string = jwt_store->create(437, $to_encode);```

This will result in a new token string that can be verified at a later point.

<br>

To verify a token string use the verify function;

> ```$valid = $jwt_store->verify($token_string[, $new_object]);```

This will result in the token being re-encoded from the previous data, or new data, if valid.

<br>

To just decode the token, without re-encoding or updating the database, use the decode function;

> ```$data = $jwt_store->decode($token_string);```

<br>

When the token is no longer valid use the destroy function to delete it from the store;

> ```$removed = $jwt_store->destroy($token_string);```