<?php

//Models
$classMap['BaseModel']              = 'classes/lib/base/base_model.php';
$classMap['Customer']               = 'classes/models/customer_model.php';
$classMap['Order']                  = 'classes/models/order_model.php';
$classMap['Product']                = 'classes/models/product_model.php';

//Views
$classMap['BaseView']               = 'classes/lib/base/base_view.php';
$classMap['CustomerView']           = 'classes/views/customer_view.php';
$classMap['OrderView']              = 'classes/views/order_view.php';
$classMap['ProductView']            = 'classes/views/product_view.php';

//Controllers
$classMap['BaseController']         = 'classes/lib/base/base_controller.php';
$classMap['CustomerController']     = 'classes/controllers/customer_controller.php';
$classMap['OrderController']        = 'classes/controllers/order_controller.php';
$classMap['ProductController']      = 'classes/controllers/product_controller.php';

//Mappers
$classMap['BaseMapper']             = 'classes/lib/base/base_mapper.php';
$classMap['CustomerMapper']         = 'classes/mappers/customer_mapper.php';
$classMap['OrderMapper']            = 'classes/mappers/order_mapper.php';
$classMap['ProductMapper']          = 'classes/mappers/product_mapper.php';

//Resources
$classMap['Resource']               = 'classes/lib/resources/resource.php';
$classMap['MysqlResource']          = 'classes/lib/resources/mysql_resource.php';
$classMap['DbCon']                  = 'classes/lib/resources/dbcon.php';
$classMap['dbPDO']                  = 'classes/lib/resources/pdo.php';
$classMap['dbPDOStatement']         = 'classes/lib/resources/pdo.php';

//Exceptions
$classMap['ValidationException']    = 'classes/lib/exceptions/validation_exception.php';
$classMap['QueryException']         = 'classes/lib/exceptions/query_exception.php';
$classMap['NotImplementedException']= 'classes/lib/exceptions/notimplemented_exception.php';

//Traits
$classMap['Singleton']              = 'classes/lib/traits/singleton.php';
$classMap['Mapped']                 = 'classes/lib/traits/mapped.php';

//Other
$classMap['Router']                 = 'classes/lib/router.php';
$classMap['Config']                 = 'classes/lib/config.php';
$classMap['TypeEnum']               = 'classes/lib/base/base_mapper.php';