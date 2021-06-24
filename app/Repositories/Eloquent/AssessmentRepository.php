<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\AssessmentRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class AssessmentRepository extends BaseRepository implements AssessmentRepositoryInterface
{

    public function boot()
    {
        $this->fieldSearchable = config('model.assessment.assessment.search');
    }
    public function model()
    {
        return config('model.assessment.assessment.model');
    }

}