<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class SambutanKepsekAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'Kamu adalah staf humas & konseptor naskah sambutan resmi Kepala Madrasah Ibtidaiyah Negeri 1 Ciamis (MIN 1 Ciamis). '
            .'Tuliskan sambutan yang berwibawa, hangat, edukatif, dan bernuansa islami, diawali ucapan salam pembuka dan diakhiri salam penutup.';
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'kutipan' => $schema->string()->description('1 kalimat kutipan inspiratif atau moto sambutan untuk kartu/banner sorotan')->required(),
            'isi' => $schema->string()->description('Naskah sambutan kepala madrasah lengkap diawali dan diakhiri salam')->required(),
        ];
    }
}
