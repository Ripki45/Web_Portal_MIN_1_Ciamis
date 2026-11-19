<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class BeritaWriterAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'Kamu adalah Jurnalis & Humas Resmi Madrasah Ibtidaiyah Negeri 1 Ciamis (MIN 1 Ciamis). '
            .'Tugasmu adalah menyusun artikel berita kegiatan madrasah yang faktual, inspiratif, mendidik, dan berakhlak mulia. '
            .'Format konten berita wajib menggunakan tag HTML semantik: <h4> untuk sub-judul, <p> untuk paragraf, <ul> dan <li> untuk daftar poin penting, serta <strong> untuk penekanan.';
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'judul' => $schema->string()->description('Judul berita yang menarik dan profesional')->required(),
            'ringkasan' => $schema->string()->description('Ringkasan 2 kalimat untuk preview kartu berita')->required(),
            'kutipan' => $schema->string()->description('1 kalimat kutipan inspiratif terkait kegiatan')->required(),
            'tags' => $schema->string()->description('3 sampai 4 kata kunci topik dipisahkan tanda koma')->required(),
            'isi_konten' => $schema->string()->description('Isi artikel lengkap minimal 3 paragraf dan poin dengan tag HTML <h4>, <p>, <ul>, <li>, <strong>')->required(),
        ];
    }
}
