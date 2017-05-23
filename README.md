php-rdk
=======
Integrating PHP and R

### Example

#### Use expression builder

```php
use Okvpn\R\Process\ProcessManager;
use Okvpn\R\Types\Type;

$rProcess = ProcessManager::create();
$exprBuilder = $rProcess->createExpressionBuilder();

$result = $exprBuilder
    ->select('t(combn(c(0::a), :b))', Type::MATRIX)
    ->setParameter('a', $a)
    ->setParameter('b', $b)
    ->execute()->getSingleResult();
```

License
-------
MIT License. See [LICENSE](LICENSE).
