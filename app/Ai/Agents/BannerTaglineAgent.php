<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class BannerTaglineAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'Kamu adalah desainer copywriter profesional untuk media publikasi dan spanduk web Madrasah Ibtidaiyah Negeri 1 Ciamis (MIN 1 Ciamis). '
            .'Tugasmu adalah menciptakan teks banner carousel hero landing page yang memikat, elegan, bernuansa islami, dan membangkitkan rasa bangga madrasah.';
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'aksara_kecil' => $schema->string()->description('Teks sorotan atas huruf kapital 2-4 kata (contoh: PENDIDIKAN DASAR ISLAMI UNGGUL)')->required(),
            'aksara_besar' => $schema->string()->description('Judul utama hero yang tegas dan berbobot (contoh: Membentuk Generasi Qur\'ani yang Cerdas dan Berprestasi)')->required(),
            'teks_domain' => $schema->string()->description('Sub-judul renggang dengan pemisah peluru • (contoh: BERPRESTASI • BERAKHLAK • MANDIRI)')->required(),
            'deskripsi' => $schema->string()->description('Deskripsi penjelasan 1-2 kalimat pendukung')->required(),
        ];
    }
}
