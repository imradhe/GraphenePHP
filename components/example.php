<!-- 
 You can use this component with this line
```php
$example = ['key1' => 'value1', 'key2' => 'value2'];
component('example', ['example' => $example]);
``` 
-->
<table>
    <thead>
        <tr>
            <th>Key</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($example as $key => $value): ?>
        <tr>
            <td><?= $key ?></td>
            <td><?= $value ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>