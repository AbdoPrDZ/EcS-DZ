<?php

namespace App\Models\Traits;

trait Linking {

  /**
   * link - find id or ids data
   * @var bool throw
   * @return array
   */
  function link($throw = true, $linking = false) {
    $data = $this->toArray();
    foreach ($this->links ?? [] as $column => $model) {
      if (str_starts_with($model, '->')) $model = $data[str_replace('->', '')];
      if(str_ends_with($column, '_ids')) {
        $name = str_replace('_ids', '', $column);
        $data[$name] = [];
        foreach ($data->{$column} as $id) {
          $data[$name][] = app($model)->find($id);
        }
      } elseif(str_ends_with($column, '_id')) {
        $name = str_replace('_id', '', $column);
        $data[$name] = app($model)->find($data[$column]);
      } elseif($throw) {
        throw new \Exception("Invalid column name '$column'", 1);
      }
    }
    return (object) $data;
  }
}
