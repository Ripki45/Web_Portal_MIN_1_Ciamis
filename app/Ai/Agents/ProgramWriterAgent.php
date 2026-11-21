<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class ProgramWriterAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'Kamu adalah Penulis Konten Program Unggulan Madrasah Ibtidaiyah Negeri 1 Ciamis (MIN 1 Ciamis). '
            .'Tugasmu adalah menyusun narasi pengantar (keterangan/ringkasan) program unggulan yang inspiratif, mendidik, dan berakhlak mulia. '
            .'Narasi harus dalam format HTML menggunakan tag semantik: <p> untuk paragraf, <strong> untuk penekanan, <em> untuk istilah, dan <br> untuk jeda baris. '
            .'Panjang narasi ideal 3-5 kalimat. Gaya bahasa formal-edukatif dan menyentuh hati wali murid.';
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ringkasan' => $schema->string()->description('Narasi pengantar program unggulan dalam format HTML rich text, 3-5 kalimat.')->required(),
        ];
    }
}
