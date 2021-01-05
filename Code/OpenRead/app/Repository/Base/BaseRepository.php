<?php

namespace App\Repository\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Lookups\DatabaseIDPrefixes;

class BaseRepository implements IRepository
{
    protected $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function FindAll()
    {
        return $this->model::all();
    }

    public function FindAllWithDeleted()
    {
        return $this->model::withTrashed()->get();
    }

    public function Find($id)
    {
        return $this->model::find($id);
    }

    public function FindWithDeleted($id)
    {
        return $this->model::withTrashed()->find($id);
    }

    public function InsertUpdate(Model $model)
    {
        if ($model->save()){
            $id = $model->id();
            if (is_array($id)){
                $pkNames = $model->getKeyName();
                $query = $model::query();
                for ($i = 0; $i < count($pkNames); $i++) { 
                    $query = $query->where($pkNames[$i], '=', $id[$i]);
                }
                return $query->get();
            }
            return $model::find($id);
        }
        return null;
    }

    public function Delete($id)
    {
        $this->model = $this->model::find($id);
        if ($this->model == null){
            return null;
        }
        else if ($this->model->delete()){
            return $id;
        }
        return null;
    }
    
    public function RollbackDelete($id)
    {
        $this->model = $this->model::withTrashed()->find($id);
        if ($this->model == null){
            return null;
        }
        else if ($this->model->restore()){
            $this->model->refresh();
            return $this->model;
        }
        return null;
    }

    public function GetLastInsertID()
    {
        $tableName = $this->model->getTable();
        $prefix = DatabaseIDPrefixes::GetPrefixByTableName($tableName);
        $lastRecord = $this->model->orderBy($this->model->getKeyName(), 'desc')->first();
        if (is_null($lastRecord)){
            return $prefix.'00001';
        }
        $lastId = $lastRecord->id();
        $lastNumber = intval(ltrim($lastId, $prefix));
        return $prefix.sprintf('%05d', $lastNumber + 1);
    }
}
