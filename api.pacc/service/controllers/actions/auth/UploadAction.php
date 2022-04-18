<?php

namespace service\controllers\actions\image;

use core\actions\ApiAction;
use core\components\helpers\UserHelper;

class UploadAction extends ApiAction
{
    public function run()
    {
        $organizationId = UserHelper::getOrganizationId();
        
        $uploaded = $_FILES['image'];
   }
}
