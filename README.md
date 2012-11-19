```php
$select = new Qb\Select;
$select->from('foo f')
  ->innerJoin('bar b', 'b.foo_id = f.id')
  ->where('f.id IN(?)', [1, 2, 3]);
echo $select;
// SELECT * FROM `foo` `f` INNER JOIN `bar` `b` ON b.foo_id = f.id WHERE f.id IN(1, 2, 3)
```