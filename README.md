```php
$select = new Qb\Select;
$select->from('foo f')
  ->innerJoin('bar b', 'b.foo_id = f.id')
  ->where('f.id IN(?)', [1, 2, 3]);
echo $select; // SELECT * FROM `foo` `f` INNER JOIN `bar` `b` ON b.foo_id = f.id WHERE f.id IN(1, 2, 3)
```

```php
$insert = new Qb\Insert;
$insert->into('foo')->value(['bar' => 1, 'baz' => 2]);
echo $insert; // INSERT `foo`(`bar`, `baz`) VALUES (1, 2)
```

```php
$update = new Qb\Update;
$update->from('foo')->value(['bar' => 1, 'baz' => 2])->where('id = ?', 1);
echo $update; // UPDATE `foo` SET `bar` = 1, `baz` = 2 WHERE id = 1
```

```php
$delete = new Qb\Delete;
$delete->from('foo')->where('id > ?', 2)->where('id < ?', 4)->limit(5);
echo $delete; // DELETE FROM `foo` WHERE id > 2 AND id < 4 LIMIT 5
```