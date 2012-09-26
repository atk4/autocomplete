To use this add-on:

 * place it inside atk4-addons
 * inside model type this:

```
$this->hasOne('User')->display(array('form'=>'autocomplete/basic'));
 // of course instead of User , use your model
```

This will replace standard drop-down field with an auto-complete field:

![Screenshot](https://raw.github.com/atk4/autocomplete/master/doc/screenshot.png)

