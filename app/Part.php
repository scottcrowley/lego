<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    /**
     * no timestamps needed
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['category_label'];

    /**
     * A part belongs to one part category
     *
     * @return belongsTo
     */
    public function category()
    {
        return $this->belongsTo(PartCategory::class, 'part_category_id');
    }

    /**
     * A part belongs to a storage location
     *
     * @return hasOneDeepFromRelations
     */
    public function storageLocation()
    {
        return $this->hasOneDeepFromRelations($this->category(), (new PartCategory)->storageLocation());
    }

    /**
     * Custom getter for category name. DO NOT USE THE category RELATIONSHIP
     *
     * @return string
     */
    public function getCategoryLabelAttribute()
    {
        return PartCategory::find($this->part_category_id)->name;
    }
}
