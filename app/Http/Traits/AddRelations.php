<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait AddRelations
{
    private function addRelations(Builder|Model|HasMany $for, array $allowedRelations) {

        $relations = explode(",", request()->query('include', ''));

        foreach($relations as $relation) {
            $relation = strtolower(trim($relation)); //Format relation

            //Only load the relation if it's an allowed relation
            if(isset($allowedRelations[$relation])) {
                if($for instanceof Model) $for->load($allowedRelations[$relation]);
                else $for->with($allowedRelations[$relation]);
            }
        }
    }
}
