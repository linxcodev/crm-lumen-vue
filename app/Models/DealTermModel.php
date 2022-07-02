<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DealTermModel extends Model
{
    use SoftDeletes;

    protected $table = 'deal_term';
    protected $dates = ['deleted_at'];

    public function storeDealTerms(array $validatedData)
    {
        $model = new DealTermModel();

        $model->body = $validatedData['body'];
        $model->id_deal = $validatedData['id_deal'];

        return $model->save();
    }

    public function getDealTerms(int $dealId)
    {
        return $this->where('id_deal', $dealId)->get();
    }

    public function getTermsBody(int $termId) : string
    {
        return $this->where('id', $termId)->get()->last()->body;
    }

    public function deleteTerm(int $termId) : bool
    {
        return $this->find($termId)->delete();
    }

    public function countAssignedDealTerms(int $dealId)
    {
        return $this->where('id_deal', $dealId)->count();
    }
}
