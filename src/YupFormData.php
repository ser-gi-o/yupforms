<?php

namespace YupForms;

use Illuminate\Database\Eloquent\Model;

class YupFormData extends Model
{
    protected $table = 'yup_form_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'form_id', 'flagged', 'data', 'server', 'note'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that are stored as json
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'server' => 'array',
    ];

    /**
     * Form entries
     */
    public function yupForm()
    {
        return $this->belongsTo('\YupForms\YupForm', 'form_id', 'id');
    }

    /**
     * Scope limiting result to form id
     *
     * @param $query
     * @param $formId
     * @return mixed
     */
    public function scopeFormId($query, $formId)
    {
        return $query->where('form_id', $formId);
    }

    /**
     * Toggles the status flag
     *
     */
    public function toggleFlag()
    {
        $this->flagged = $this->flagged ? 0 : 1;
        $this->save();
    }

    /**
     * Handles removing all formdata data
     *
     * @throws \Exception
     */
    public function remove()
    {
        $this->delete();
    }
}
