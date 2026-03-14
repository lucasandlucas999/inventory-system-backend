<?php

namespace App\Http\Actions;

use Illuminate\Http\Request;

class TestingPostAction
{
    public function execute(Request $request): string
    {
        $queryParams = $request->query();
        $response = 'Post works, congrats!';
        if (!empty($queryParams)) {
            $response .= ' ' . $this->convertQueryParamsToString($queryParams);
        }
        return $response;
    }

    private function convertQueryParamsToString(array $queryParams): string
    {
        return collect($queryParams)->map(fn($value, $key) => "$key $value")->implode(' ');
    }
}
