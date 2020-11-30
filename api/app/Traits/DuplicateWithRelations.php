<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

/**
 * Trait DuplicateWithRelations
 * @package App\Traits
 */
trait DuplicateWithRelations
{
    /**
     * Duplicate a model and its hasMany relations.
     *
     * @param string[] $relations
     * @param array $fill
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function duplicateHasMany($relations, $fill = [])
    {
        DB::beginTransaction();

        // Reset relations
        $this->relations = [];

        // Duplicate the model
        $duplicate = $this->replicate()->fill($fill);
        $duplicate->save();

        // Load relations
        $this->load($relations);

        foreach ($this->getRelations() as $relationName => $models) {
            foreach ($models as $model) {
                $newRelationship = $model->replicate();
                $duplicate->{$relationName}()->create($newRelationship->toArray());
            }
        }

        DB::commit();

        return $duplicate;
    }
}
