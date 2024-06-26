<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FrontendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'fname' => $this->fname,
            'lname' => $this->lname,
            'mobile' => $this->mobile,
            'email' => $this->email,
        ];

        if ($request->has('include_id')) {
            $data['id'] = $this->id;
        }

        return $data;
    }
}
