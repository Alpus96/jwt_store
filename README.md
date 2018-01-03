# jwt_store

This plugin encodes and decodes JSON web tokens and verifies them by comparing to a database store.

## Requiers

PHP version 5.3 (or newer) and a MySQL database, with tables specified in the 'jwt_model.php' file.

## Usage

The basic usage requires you to instance the class;

> ```$jwt_store = new TokenStore();```

<br>

To create a new token do like this;

> ```$id_for_token_in_store = 437;```<br>
> ```$obj_to_encode = (object) ['foo' => 'bar'];```<br>
> ```$token_string = jwt_store->create($id_for_token_in_store, $obj_to_encode); // String```

This will result in a new token string that has been saved in the store and can be verified at a later point.

<br>

To verify a token string, and optionaly update it, use the verify function;

> ```$valid = $jwt_store->verify($token_string[, $new_object]); // Boolean```

This will result in the token being re-encoded from the previous data, or the new data, if the token string is valid.

<br>

To just decode the token, without re-encoding or updating the database, use the decode function;

> ```$data = $jwt_store->decode($token_string); // Object```

<br>

When the token is no longer valid or in use remove it from the store with the destroy function;

> ```$removed = $jwt_store->destroy($token_string); // Boolean```