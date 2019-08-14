<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StorageLocation extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relationships to always eager load
     *
     * @var array
     */
    protected $with = ['type'];

    /**
     * Add a part category to the storage location
     *
     * @param PartCategory $partCategory
     * @return void
     */
    public function addPartCategory(PartCategory $partCategory)
    {
        $this->partCategories()->attach($partCategory);
    }

    /**
     * Removes a part category to the storage location
     *
     * @param PartCategory $partCategory
     * @return void
     */
    public function removePartCategory(PartCategory $partCategory)
    {
        $this->partCategories()->detach($partCategory);
    }

    /**
     * A location belongs to one storage type
     *
     * @return belongsTo
     */
    public function type()
    {
        return $this->belongsTo(StorageType::class, 'storage_type_id');
    }

    /**
     * A storage location belongs to many part categories
     *
     * @return belongsToMany
     */
    public function partCategories()
    {
        return $this->belongsToMany(PartCategory::class);
    }

    /**
     * A storage location has many parts
     *
     * @return hasManyDeep
     */
    public function parts()
    {
        return $this->hasManyDeep(Part::class, ['part_category_storage_location', PartCategory::class]);
    }
}
