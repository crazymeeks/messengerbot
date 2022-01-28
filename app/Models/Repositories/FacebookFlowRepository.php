<?php

/**
 * @todo:: Refactor all repositories.
 * Instead of creating repository every single module, we should
 * create just one repository with save() method. The parameter
 * of this method should be \App\Models\Api\DataInterface
 */

namespace App\Models\Repositories;

use App\Models\Repositories\BaseRepository;

class FacebookFlowRepository extends BaseRepository
{

}