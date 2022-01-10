<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class MainCategoryScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereNull('categories.parent_id');
    }
}