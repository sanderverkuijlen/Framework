<?php
    //Models
    $classMap['BaseModel']              = 'classes/lib/base/base_model.php';
    $classMap['User']                   = 'classes/models/user_model.php';

    //Views
    $classMap['BaseView']               = 'classes/lib/base/base_view.php';
    $classMap['UserView']               = 'classes/views/user_view.php';

    //Controllers
    $classMap['BaseController']         = 'classes/lib/base/base_controller.php';
    $classMap['UserController']         = 'classes/controllers/user_controller.php';

    //Mappers
    $classMap['BaseMapper']             = 'classes/lib/base/base_mapper.php';
    $classMap['UserMapper']             = 'classes/mappers/user_mapper.php';

    //Other
    $classMap['Router']                 = 'classes/lib/router.php';
    $classMap['DbCon']                  = 'classes/lib/db/dbcon.php';
    $classMap['dbPDO']                  = 'classes/lib/db/pdo.php';
    $classMap['dbPDOStatement']         = 'classes/lib/db/pdo.php';
    $classMap['ValidationException']    = 'classes/lib/exceptions/validation_exception.php';
    $classMap['SqlException']           = 'classes/lib/exceptions/sql_exception.php';
    $classMap['NotImplementedException']= 'classes/lib/exceptions/notimplemented_exception.php';

    //Traits
    $classMap['Singleton']              = 'classes/lib/traits/singleton.php';
    $classMap['Mapped']                 = 'classes/lib/traits/mapped.php';
    $classMap['Db']                     = 'classes/lib/traits/db.php';
?>