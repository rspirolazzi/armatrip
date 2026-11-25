<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Rspirolazzi\MultiCompany\Models\Company as MultipleCompany;

/**
 * Class Company
 * @package App\Models
 * @version March 3, 2019, 6:27 pm -03
 *
 */
class Company extends MultipleCompany
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
