<?php

namespace App\Managers\ImportManager;

use App\Models\Lead;
use Illuminate\Support\Facades\Validator;

class ImportManager
{

    public function importLeads(array $leadsArray, int $userID): array
    {
        $leads = $this->parseArrayOfObjectsToCorrectFormat($leadsArray);
        $leads = $this->addUserIDToLeads($leads, $userID);
        $leads = $this->validateLeads($leads);
        return $leads;
    }

    protected function parseArrayOfObjectsToCorrectFormat(array $leadsArray): array
    {
        return  array_map(function ($object) {
            return (array) $object;
        }, $leadsArray);
    }

    protected function addUserIDToLeads(array $leads, int $userID): array
    {
        return array_map(function ($lead) use ($userID) {
            $lead['userID'] = $userID;
            return $lead;
        }, $leads);
    }

    protected function validateLeads(array $leads): array
    {
        return $leads = array_filter($leads, function ($lead) {
            return $this->validateLead($lead);
        });
    }

    protected function validateLead(array $lead): bool
    {
        $validationRules = Lead::getValidationRules([]);
        $validator = Validator::make($lead, $validationRules);
        if ($validator->fails()) {
            return false;
        }
        return true;
    }
}
