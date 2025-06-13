<?php

namespace App\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class ErrorDTO
{
    #[SerializedName('code')]
    public int $code;

    #[SerializedName('description')]
    public string $description;

    public function __construct(int $code, string $description)
    {
        $this->code = $code;
        $this->description = $description;
    }
}
