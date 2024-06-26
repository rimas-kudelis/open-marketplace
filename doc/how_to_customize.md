# How to customize BitBag OpenMarketplace

BitBag Open Marketplace is created on the Sylius e-commerce platform.

[Sylius documentation](https://docs.sylius.com/en/latest/index.html#)

### Managing resources

In order to understand resource layer, see [Sylius resource layer](https://docs.sylius.com/en/latest/book/architecture/resource_layer.html)

### Customizing model

[Sylius customizing model guide](https://docs.sylius.com/en/latest/customization/model.html)

List of resources created by open marketplace app can be fetched using command

```php bin/console sylius:debug:resource | grep open_marketplace```

In order to display more details for give resource use command 

```php bin/console sylius:debug:resource open_marketplace.{resourceName}```

Example details view for vendor
```
+--------------------+---------------------------------------------------------------------+
| name               | vendor                                                              |
| application        | open_marketplace                                                    |
| driver             | doctrine/orm                                                        |
| classes.model      | BitBag\OpenMarketplace\Component\Vendor\Entity\Vendor               |
| classes.interface  | BitBag\OpenMarketplace\Component\Vendor\Entity\VendorInterface      |
| classes.controller | Sylius\Bundle\ResourceBundle\Controller\ResourceController          |
| classes.repository | BitBag\OpenMarketplace\Component\Vendor\Repository\VendorRepository |
| classes.form       | BitBag\OpenMarketplace\Component\Core\Admin\Form\Type\VendorType    |
| classes.factory    | Sylius\Component\Resource\Factory\Factory                           |
+--------------------+---------------------------------------------------------------------+
```


### Customizing form

[Sylius customizing form guide](https://docs.sylius.com/en/latest/customization/form.html)

List of forms customized by open marketplace app can be fetched using command

```php bin/console debug:container | grep open_marketplace.form```

### Customizing repositories

[Sylius customizing repositories guide](https://docs.sylius.com/en/latest/customization/repository.html)

List of repositories customized by open marketplace app can be fetched using command

```php bin/console debug:container | grep open_marketplace.repository```

### Customizing routes 

Example of Sylius route configuration
```
app_product_create:
    path: /my-stores/{store}/products/new 
    methods: [GET, POST]
    defaults:
        _controller: sylius.controller.product:createAction
        _sylius:
            form: app_user_product # Form type
            template: AppStoreBundle:Product:create.html.twig # Use a custom template.
            factory:
                method: createForStore # Use a custom factory method to create a product.
                arguments: [$store] # Pass the store name from the url.
            redirect:
                route: app_product_index # Redirect the user to his products.
                parameters: [$store]
```

A list of all routes used in application can be obtained with command 

```php bin/console debug:router```

Routes added to sylius by BitBag OpenMarketplace can be displayed by

```php bin/console debug:router | grep open_marketplace```

To obtain detail view of specific route type

```php bin/console debug:router {routeName}```


### Customizing state machine 

In order to customize state machine read [Sylius state machine guide](https://docs.sylius.com/en/latest/customization/state_machine.html)

List of all states machines can be obtained

```php bin/console debug:winzou:state-machine```


### Customizing templates 

Views added by BitBag OpenMarketplace located in templates directory can be overwritten directly,
to customize Sylius views see [Sylius customizing template guide](https://docs.sylius.com/en/latest/customization/template.html)

