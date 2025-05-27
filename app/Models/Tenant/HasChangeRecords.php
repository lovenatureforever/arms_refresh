<?php

namespace App\Models\Tenant;

use Illuminate\Support\Str;
use BadMethodCallException;
use Carbon\Carbon;

trait HasChangeRecords
{
    protected function handleChangeRecordMethods($name, $arguments)
    {

        if ($name == 'detailAtStart' || $name == 'detailAtLast' || $name == 'detailChangesCurrentYear')
        {
            $relation = 'detailChanges';
        }
        elseif ($name == 'businessAtStart' || $name == 'businessAtLast' || $name == 'businessChangesCurrentYear')
        {
            $relation = 'businessChanges';
        }
        elseif ($name == 'addressAtStart' || $name == 'addressAtLast' || $name == 'addressChangesCurrentYear')
        {
            $relation = 'addressChanges';
        }
        elseif ($name == 'bizAddressAtStart' || $name == 'bizAddressAtLast' || $name == 'bizAddressChangesCurrentYear')
        {
            $relation = 'bizAddressChanges';
        }
        elseif ($name == 'sharecapitalAtStart' || $name == 'sharecapitalChangesCurrentYear')
        {
            $relation = 'sharecapitalChanges';
        }
        elseif ($name == 'directorChangesCurrentYear')
        {
            $relation = 'directorChanges';
        }
        elseif ($name == 'shareholderChangesCurrentYear')
        {
            $relation = 'shareholderChanges';
        }
        elseif ($name == 'secretaryChangesCurrentYear')
        {
            $relation = 'secretaryChanges';
        }
        // elseif ($name == 'chargeChangesCurrentYear')
        // {
        //     $relation = 'chargeChanges';
        // }
        else
        {
            throw new BadMethodCallException("Method {$name} does not exist.");
        }

        if (method_exists($this, $relation))
        {
            if (Str::endsWith($name, 'AtStart'))
            {
                return $this->getLatestBefore($relation, $this->current_year_from);
            }
            elseif (Str::endsWith($name, 'AtLast'))
            {
                $date = $arguments[0] ?? null;
                $date = $date ? Carbon::parse($date) : $this->current_year_to;
                return $this->getLatestBefore($relation, $date);
            }
            elseif (Str::endsWith($name, 'ChangesCurrentYear'))
            {
                return $this->getChangesCurrentYear($relation);
            }
        }

        throw new BadMethodCallException("Method {$name} does not exist.");
    }

    public function __call($name, $arguments)
    {
        if (Str::startsWith($name, ['detail', 'business', 'address']))
        {
            return $this->handleChangeRecordMethods($name, $arguments);
        }

        return parent::__call($name, $arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        return parent::__callStatic($name, $arguments);
    }

    protected function getLatestBefore($relation, $date)
    {
        if (!$date) return null;

        return $this->$relation()
            ->where('effective_date', '<=', $date)
            ->latest('effective_date')
            ->first();
    }

    protected function getChangesCurrentYear($relation)
    {
        if (!$this->current_year_from || !$this->current_year_to)
        {
            return null;
        }

        return $this->$relation()
            ->whereBetween('effective_date', [$this->current_year_from, $this->current_year_to])
            ->orderBy('effective_date')
            ->get();
    }
}
